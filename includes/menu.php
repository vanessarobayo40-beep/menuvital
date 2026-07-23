<?php
/**
 * MenúVital — Menú manejado por la usuaria
 * La usuaria elige qué receta va en cada fecha y tipo de comida desde el
 * recetario; esas elecciones (menu_entries) son la fuente de verdad para
 * Hoy y la Semana, y alimentan la lista de compras exacta. "Sugerir" solo
 * rellena huecos, nunca pisa lo que la usuaria ya eligió.
 */

require_once __DIR__ . '/planner.php';
require_once __DIR__ . '/quantities.php';

/** Entradas del menú de una usuaria en un rango de fechas (incluye ambos extremos). */
function get_menu_entries(int $userId, string $from, string $to): array {
    $stmt = db()->prepare('SELECT * FROM menu_entries WHERE user_id = ? AND entry_date BETWEEN ? AND ? ORDER BY entry_date, meal_type');
    $stmt->execute([$userId, $from, $to]);
    return $stmt->fetchAll();
}

/** Presenta una entrada del menú lista para el frontend (misma forma que las tarjetas de siempre + metadatos). */
function present_menu_entry(array $entry, array $pantryItems): ?array {
    $recipe = recipe_by_id((int)$entry['recipe_id']);
    if (!$recipe) {
        return null;
    }
    $servings = max(1, (int)$entry['servings']);
    $presented = present_recipe($recipe, $pantryItems, $servings);
    $presented['entry_id'] = (int)$entry['id'];
    $presented['date'] = $entry['entry_date'];
    $presented['servings'] = $servings;
    $presented['source'] = $entry['source'];
    $presented['done'] = (bool)$entry['done'];
    return $presented;
}

/** Guarda (crea o reemplaza) lo que va en una fecha + tipo de comida. Devuelve el id de la entrada. */
function set_menu_entry(int $userId, string $date, string $mealType, int $recipeId, int $servings, string $source = 'user'): int {
    $pdo = db();
    $pdo->prepare('DELETE FROM menu_entries WHERE user_id = ? AND entry_date = ? AND meal_type = ?')
        ->execute([$userId, $date, $mealType]);
    $pdo->prepare('INSERT INTO menu_entries (user_id, entry_date, meal_type, recipe_id, servings, done, source, created_at)
                   VALUES (?, ?, ?, ?, ?, 0, ?, ?)')
        ->execute([$userId, $date, $mealType, $recipeId, max(1, $servings), $source, db_now()]);
    return (int)$pdo->lastInsertId();
}

/** El plato ya elegido para una fecha + tipo, si existe (para detectar choques al agregar). */
function get_menu_slot(int $userId, string $date, string $mealType): ?array {
    $stmt = db()->prepare('SELECT me.*, r.name FROM menu_entries me JOIN recipes r ON r.id = me.recipe_id
                            WHERE me.user_id = ? AND me.entry_date = ? AND me.meal_type = ?');
    $stmt->execute([$userId, $date, $mealType]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/** Trae una entrada por id, solo si es de esa usuaria (para validar antes de actuar sobre ella). */
function get_menu_entry_by_id(int $userId, int $entryId): ?array {
    $stmt = db()->prepare('SELECT * FROM menu_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$entryId, $userId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function remove_menu_entry(int $userId, int $entryId): bool {
    $stmt = db()->prepare('DELETE FROM menu_entries WHERE id = ? AND user_id = ?');
    $stmt->execute([$entryId, $userId]);
    return $stmt->rowCount() > 0;
}

/** Marca una entrada como hecha. El descuento real de despensa lo hace consume_recipe_from_pantry(). */
function mark_entry_done(int $userId, int $entryId): void {
    db()->prepare('UPDATE menu_entries SET done = 1, done_at = ? WHERE id = ? AND user_id = ?')
        ->execute([db_now(), $entryId, $userId]);
}

/**
 * Rellena los huecos del menú en un rango de fechas con sugerencias del motor
 * de coincidencia (nunca pisa lo que la usuaria ya eligió). Con
 * $replaceSuggested=true, también renueva sugerencias previas que no se
 * hayan marcado como hechas — para el botón "Sugerir de nuevo".
 * Devuelve cuántas fechas/tipo se llenaron.
 */
function suggest_fill(int $userId, array $profile, array $pantryItems, string $from, string $to, bool $replaceSuggested = false): int {
    $mealTypes = meal_types_for_profile($profile);
    $existing = get_menu_entries($userId, $from, $to);
    $byDateType = [];
    $usedByType = [];
    foreach ($existing as $e) {
        $byDateType[$e['entry_date']][$e['meal_type']] = $e;
        $usedByType[$e['meal_type']][] = (int)$e['recipe_id'];
    }

    $filled = 0;
    $start = new DateTime($from);
    $end = (new DateTime($to))->modify('+1 day');
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);
    $people = max(1, (int)($profile['people'] ?? 1));

    foreach ($period as $day) {
        $date = $day->format('Y-m-d');
        foreach ($mealTypes as $type) {
            $current = $byDateType[$date][$type] ?? null;
            if ($current !== null) {
                $replaceable = $replaceSuggested && $current['source'] === 'suggest' && !(int)$current['done'];
                if (!$replaceable) {
                    continue;
                }
            }
            $exclude = $usedByType[$type] ?? [];
            // Límite alto a propósito: queremos elegir de TODO el recetario disponible
            // de ese tipo (puede haber 70+ recetas), no solo de un puñado de las mejor puntuadas.
            $candidates = candidate_recipes($type, $pantryItems, $profile, $exclude, 200);
            if (empty($candidates)) {
                // Ya se usaron todas las candidatas obvias: se permite repetir antes que dejar el hueco vacío.
                $candidates = candidate_recipes($type, $pantryItems, $profile, [], 200);
            }
            if (empty($candidates)) {
                continue;
            }
            $chosen = pick_candidate($candidates);
            set_menu_entry($userId, $date, $type, (int)$chosen['id'], $people, 'suggest');
            $usedByType[$type][] = (int)$chosen['id'];
            $filled++;
        }
    }
    return $filled;
}

/**
 * Lista de compras EXACTA para un rango de fechas: suma de verdad las
 * cantidades de todas las recetas aún no marcadas como hechas (× porciones),
 * les resta lo que ya hay en la despensa, y agrupa el resultado por sección
 * del supermercado. Un ítem de despensa con cantidad vacía cuenta como que
 * "ya lo tienes" (no se pide, igual que el matching de antes por nombre).
 */
function build_exact_shopping_list(int $userId, string $from, string $to): array {
    $entries = get_menu_entries($userId, $from, $to);
    $need = []; // canonicalName => bag

    foreach ($entries as $entry) {
        if ((int)$entry['done'] === 1) {
            continue;
        }
        $recipe = recipe_by_id((int)$entry['recipe_id']);
        if (!$recipe) {
            continue;
        }
        $servings = max(1, (int)$entry['servings']);
        foreach ($recipe['ingredients'] as $ingEntry) {
            [$name, $qty] = array_pad(explode('|', $ingEntry, 2), 2, '');
            $name = trim($name);
            if ($name === '' || is_staple($name)) {
                continue;
            }
            $key = canonical_ingredient_name($name);
            if (!isset($need[$key])) {
                $need[$key] = [];
            }
            if (trim($qty) !== '') {
                qty_bag_add($need[$key], $qty, $servings);
            } else {
                $need[$key]['_unparsed'] = $need[$key]['_unparsed'] ?? [];
                $need[$key]['_unparsed'][] = 'al gusto';
            }
        }
    }

    $stmt = db()->prepare('SELECT item, quantity FROM pantry_items WHERE user_id = ?');
    $stmt->execute([$userId]);
    $pantryRows = $stmt->fetchAll();
    $pantryByKey = [];
    foreach ($pantryRows as $row) {
        $key = canonical_ingredient_name($row['item']);
        $pantryByKey[$key] = $row['quantity'];
    }

    $result = [];
    foreach ($need as $key => $bag) {
        if (array_key_exists($key, $pantryByKey)) {
            $haveQty = trim((string)$pantryByKey[$key]);
            if ($haveQty === '') {
                // Está en la despensa sin cantidad especificada: se asume que ya alcanza.
                continue;
            }
            $haveBag = [];
            qty_bag_add($haveBag, $haveQty, 1.0);
            $bag = qty_bag_subtract($bag, $haveBag);
        }
        $qtyStr = format_qty_bag($bag);
        if ($qtyStr === '') {
            continue;
        }
        $result[] = ['item' => $key, 'qty' => $qtyStr, 'category' => ingredient_category($key)];
    }

    $grouped = [];
    foreach ($result as $r) {
        $grouped[$r['category']][] = ['item' => $r['item'], 'qty' => $r['qty']];
    }
    ksort($grouped);
    return $grouped;
}
