<?php
/**
 * MenúVital — Motor híbrido de menús
 *
 * 1. Calcula qué tan bien encaja cada receta con el mercado del usuario.
 * 2. Si hay IA (Groq) disponible, le pide elegir entre las mejores candidatas
 *    y dar un consejo de coach — pero SIEMPRE valida que el id elegido sea
 *    uno de los candidatos reales (nunca se inventan platos ni datos).
 * 3. Si la IA no responde, el algoritmo de coincidencia decide solo:
 *    la app nunca se queda sin menú.
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/ingredients.php';
require_once __DIR__ . '/groq.php';
require_once __DIR__ . '/profile.php';

const MEAL_TYPES = ['desayuno', 'almuerzo', 'cena', 'snack'];

/** Todas las recetas de un tipo, con ingredientes/tags decodificados. Cache por request. */
function get_recipes_by_type(string $type): array {
    static $cache = [];
    if (isset($cache[$type])) {
        return $cache[$type];
    }
    $stmt = db()->prepare('SELECT * FROM recipes WHERE meal_type = ?');
    $stmt->execute([$type]);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $r['ingredients'] = json_decode($r['ingredients'], true) ?: [];
        $r['steps'] = json_decode($r['steps'], true) ?: [];
        $r['tags'] = json_decode($r['tags'], true) ?: [];
    }
    return $cache[$type] = $rows;
}

function recipe_by_id(int $id): ?array {
    static $cache = [];
    if (array_key_exists($id, $cache)) {
        return $cache[$id];
    }
    $stmt = db()->prepare('SELECT * FROM recipes WHERE id = ?');
    $stmt->execute([$id]);
    $r = $stmt->fetch();
    if ($r) {
        $r['ingredients'] = json_decode($r['ingredients'], true) ?: [];
        $r['steps'] = json_decode($r['steps'], true) ?: [];
        $r['tags'] = json_decode($r['tags'], true) ?: [];
    }
    return $cache[$id] = ($r ?: null);
}

/** Nombre del ingrediente sin la cantidad ("pollo|150 g" -> "pollo"). */
function ingredient_name(string $entry): string {
    return trim(explode('|', $entry)[0]);
}

/** ¿La receta contiene algo de la lista de alergias/no-me-gusta? */
function recipe_is_blocked(array $recipe, array $blockedItems): bool {
    if (empty($blockedItems)) {
        return false;
    }
    foreach ($recipe['ingredients'] as $entry) {
        $name = ingredient_name($entry);
        foreach ($blockedItems as $blocked) {
            if ($blocked !== '' && ingredients_match($name, $blocked)) {
                return true;
            }
        }
    }
    return false;
}

/** % de ingredientes (sin contar básicos) que ya están en la despensa. */
function recipe_match_score(array $recipe, array $pantryItems): float {
    $relevant = array_filter($recipe['ingredients'], fn($e) => !is_staple(ingredient_name($e)));
    if (empty($relevant)) {
        return 1.0;
    }
    $have = 0;
    foreach ($relevant as $entry) {
        if (in_pantry(ingredient_name($entry), $pantryItems)) {
            $have++;
        }
    }
    return $have / count($relevant);
}

/** Bono de afinidad según el objetivo del perfil (desempate suave, no filtro duro). */
function goal_bonus(array $recipe, string $goal): float {
    $tags = $recipe['tags'];
    return match ($goal) {
        'bajar_peso'     => (in_array('ligero', $tags, true) ? 0.15 : 0) + ($recipe['kcal'] <= 420 ? 0.1 : 0),
        // Prioriza proteína (motor de la ganancia muscular) y evita platos "ligero"
        // (pensados para déficit) — aquí sí conviene comida más contundente.
        'ganar_musculo'  => (in_array('alto en proteína', $tags, true) ? 0.3 : 0)
            + ((int)$recipe['protein'] >= 25 ? 0.15 : 0)
            + ($recipe['kcal'] >= 400 ? 0.1 : 0),
        'energia'        => in_array('alto en proteína', $tags, true) ? 0.15 : 0,
        'familia'        => in_array('tradicional', $tags, true) ? 0.1 : 0,
        default          => 0,
    };
}

/** Bono si la receta coincide con los platos favoritos que la usuaria escribió. */
function favorites_bonus(array $recipe, array $favorites): float {
    if (empty($favorites)) {
        return 0;
    }
    $recipeName = normalize_ingredient($recipe['name']);
    foreach ($favorites as $fav) {
        $favNorm = normalize_ingredient($fav);
        if ($favNorm === '') continue;
        // Coincidencia por nombre del plato...
        if (str_contains($recipeName, $favNorm) || str_contains($favNorm, $recipeName)) {
            return 0.35;
        }
        // ...o por ingrediente protagonista (ej: favorita "salmón" sube recetas con salmón)
        foreach ($recipe['ingredients'] as $entry) {
            if (ingredients_match(ingredient_name($entry), $fav)) {
                return 0.2;
            }
        }
    }
    return 0;
}

/**
 * Candidatas ordenadas por afinidad para un tipo de comida.
 * $exclude: ids ya usados (para variar en la semana).
 */
function candidate_recipes(string $type, array $pantryItems, array $profile, array $exclude = [], int $limit = 8): array {
    $blocked = array_merge($profile['allergies_list'] ?? [], $profile['dislikes_list'] ?? []);
    $favoriteIds = $profile['favorite_recipe_ids'] ?? [];
    $recipes = get_recipes_by_type($type);
    $scored = [];
    foreach ($recipes as $r) {
        if (in_array((int)$r['id'], $exclude, true)) {
            continue;
        }
        if (recipe_is_blocked($r, $blocked)) {
            continue;
        }
        $score = recipe_match_score($r, $pantryItems)
            + goal_bonus($r, $profile['goal'] ?? 'balance')
            + favorites_bonus($r, $profile['favorites_list'] ?? [])
            + (in_array((int)$r['id'], $favoriteIds, true) ? 0.3 : 0);
        $scored[] = ['recipe' => $r, 'score' => $score];
    }
    usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
    $top = array_slice($scored, 0, $limit);
    return array_map(fn($s) => $s['recipe'], $top);
}

/** Tipos de comida a generar según cuántas veces al día come el usuario. */
function meal_types_for_profile(array $profile): array {
    $n = (int)($profile['meals_per_day'] ?? 3);
    return $n >= 4 ? MEAL_TYPES : ['desayuno', 'almuerzo', 'cena'];
}

/** Convierte "pollo|150 g" a ["item"=>"pollo","qty"=>"150 g"] y escala por número de personas. */
function scale_ingredient(string $entry, int $people): array {
    $parts = explode('|', $entry, 2);
    $item = trim($parts[0]);
    $qty = trim($parts[1] ?? '');
    if ($people > 1) {
        // Si la cantidad empieza en número, se multiplica; si no, se deja la nota "x N".
        if (preg_match('/^([\d\.\/]+)\s*(.*)$/', $qty, $m) && is_numeric(str_replace('/', '.', $m[1]))) {
            $num = strpos($m[1], '/') !== false
                ? array_reduce(explode('/', $m[1]), fn($c, $v) => $c === null ? (float)$v : $c / (float)$v, null)
                : (float)$m[1];
            $scaled = $num * $people;
            $qty = (floor($scaled) == $scaled ? (string)(int)$scaled : rtrim(rtrim(number_format($scaled, 1), '0'), '.')) . ' ' . $m[2];
        } else {
            $qty .= " (x{$people})";
        }
    }
    return ['item' => $item, 'qty' => trim($qty)];
}

/** Ingredientes que faltan por comprar para una receta dada la despensa actual. */
function missing_ingredients(array $recipe, array $pantryItems, int $people): array {
    $missing = [];
    foreach ($recipe['ingredients'] as $entry) {
        $name = ingredient_name($entry);
        if (is_staple($name) || in_pantry($name, $pantryItems)) {
            continue;
        }
        $missing[] = scale_ingredient($entry, $people);
    }
    return $missing;
}

/**
 * URL de foto real del plato (banco de fotos Pexels, buscada y guardada una sola vez
 * por receta — carga instantánea). Si por algún motivo una receta no tiene foto
 * guardada, cae a una generada por IA como respaldo para que nunca falte imagen.
 */
function recipe_image_url(array $recipe): string {
    if (!empty($recipe['image_url'])) {
        return $recipe['image_url'];
    }
    $prompt = 'fotografía profesional de comida, plato de ' . $recipe['name']
        . ', apetitoso, alta calidad, luz natural, vista superior, fondo simple';
    return 'https://image.pollinations.ai/prompt/' . rawurlencode($prompt)
        . '?width=480&height=320&nologo=true&seed=' . (int)($recipe['id'] ?? 0);
}

function present_recipe(array $recipe, array $pantryItems, int $people): array {
    return [
        'id' => (int)$recipe['id'],
        'name' => $recipe['name'],
        'meal_type' => $recipe['meal_type'],
        'image_url' => recipe_image_url($recipe),
        'kcal_porcion' => (int)$recipe['kcal'],
        'kcal_total' => (int)$recipe['kcal'] * max(1, $people),
        'protein_porcion' => (int)$recipe['protein'],
        'carbs_porcion' => (int)($recipe['carbs'] ?? 0),
        'fat_porcion' => (int)($recipe['fat'] ?? 0),
        'sugar_porcion' => (int)($recipe['sugar'] ?? 0),
        'fiber_porcion' => (int)($recipe['fiber'] ?? 0),
        'time_min' => (int)$recipe['time_min'],
        'tags' => $recipe['tags'],
        'ingredients' => array_map(fn($e) => scale_ingredient($e, $people), $recipe['ingredients']),
        'steps' => $recipe['steps'],
        'missing' => missing_ingredients($recipe, $pantryItems, $people),
        'match_pct' => (int)round(recipe_match_score($recipe, $pantryItems) * 100),
        'done' => false,
    ];
}

// ---------- Integración con IA (opcional, con respaldo garantizado) ----------

function ai_pick_day(array $candidatesByType, array $profile, array $pantryItems): ?array {
    if (!groq_available()) {
        return null;
    }
    $brief = [];
    foreach ($candidatesByType as $type => $recipes) {
        $brief[$type] = array_map(fn($r) => [
            'id' => (int)$r['id'], 'nombre' => $r['name'], 'kcal' => (int)$r['kcal'],
            'proteina' => (int)$r['protein'], 'tags' => $r['tags'], 'tiempo_min' => (int)$r['time_min'],
        ], $recipes);
    }
    $system = 'Eres una nutricionista colombiana, cercana y motivadora. Trabajas para la app MenúVital. '
        . 'SOLO puedes elegir platos de la lista de candidatos que te dan (nunca inventes platos). '
        . 'Responde ÚNICAMENTE JSON válido con este formato exacto: '
        . '{"selecciones":{"desayuno":ID,"almuerzo":ID,"cena":ID,"snack":ID_O_NULL},"consejo_coach":"texto breve y cálido, máximo 220 caracteres"}';
    $user = json_encode([
        'objetivo' => $profile['goal'] ?? 'balance',
        'personas' => (int)($profile['people'] ?? 1),
        'mercado_disponible' => array_values($pantryItems),
        'platos_favoritos' => $profile['favorites_list'] ?? [],
        'candidatos' => $brief,
        'instruccion' => 'Elige la mejor opción de cada tipo de comida priorizando lo que ya hay en el mercado, los platos favoritos de la usuaria, variedad y el objetivo. Si no hay candidatos de snack, pon null.',
    ], JSON_UNESCAPED_UNICODE);

    $result = groq_chat_json([
        ['role' => 'system', 'content' => $system],
        ['role' => 'user', 'content' => $user],
    ], 1024);

    return $result;
}

function ai_pick_week(array $candidatesByDay, array $profile, array $pantryItems): ?array {
    if (!groq_available()) {
        return null;
    }
    $brief = [];
    foreach ($candidatesByDay as $type => $recipes) {
        $brief[$type] = array_map(fn($r) => [
            'id' => (int)$r['id'], 'nombre' => $r['name'], 'kcal' => (int)$r['kcal'], 'tags' => $r['tags'],
        ], $recipes);
    }
    $system = 'Eres una nutricionista colombiana, cercana y motivadora, de la app MenúVital. '
        . 'SOLO puedes elegir platos de la lista de candidatos (nunca inventes platos). '
        . 'Arma un plan de 7 días variado (evita repetir el mismo plato de un tipo dos días seguidos si hay opciones). '
        . 'Responde ÚNICAMENTE JSON con este formato: {"dias":[{"desayuno":ID,"almuerzo":ID,"cena":ID,"snack":ID_O_NULL,"consejo_coach":"texto breve"}, ...7 elementos...]}';
    $user = json_encode([
        'objetivo' => $profile['goal'] ?? 'balance',
        'personas' => (int)($profile['people'] ?? 1),
        'mercado_disponible' => array_values($pantryItems),
        'platos_favoritos' => $profile['favorites_list'] ?? [],
        'candidatos_por_tipo' => $brief,
    ], JSON_UNESCAPED_UNICODE);

    return groq_chat_json([
        ['role' => 'system', 'content' => $system],
        ['role' => 'user', 'content' => $user],
    ], 3072);
}

/** Valida que un id elegido por la IA esté realmente entre los candidatos; si no, usa el mejor por score. */
function safe_pick(?int $aiId, array $candidates): ?array {
    if ($aiId !== null) {
        foreach ($candidates as $r) {
            if ((int)$r['id'] === $aiId) {
                return $r;
            }
        }
    }
    return $candidates[0] ?? null;
}

/** Genera el plan de UN día. Devuelve ['meals'=>[...], 'consejo_coach'=>string]. */
function build_day_plan(array $profile, array $pantryItems, array $exclude = []): array {
    $types = meal_types_for_profile($profile);
    $candidatesByType = [];
    foreach (MEAL_TYPES as $type) {
        $candidatesByType[$type] = in_array($type, $types, true)
            ? candidate_recipes($type, $pantryItems, $profile, $exclude[$type] ?? [])
            : [];
    }

    $ai = ai_pick_day($candidatesByType, $profile, $pantryItems);
    $people = max(1, (int)($profile['people'] ?? 1));
    $meals = [];
    foreach ($types as $type) {
        $aiId = isset($ai['selecciones'][$type]) && $ai['selecciones'][$type] !== null
            ? (int)$ai['selecciones'][$type] : null;
        $chosen = safe_pick($aiId, $candidatesByType[$type]);
        if ($chosen) {
            $meals[$type] = present_recipe($chosen, $pantryItems, $people);
        }
    }

    $tip = is_string($ai['consejo_coach'] ?? null) && $ai['consejo_coach'] !== ''
        ? mb_substr($ai['consejo_coach'], 0, 260)
        : default_coach_tip($profile);

    return ['meals' => $meals, 'consejo_coach' => $tip];
}

function default_coach_tip(array $profile): string {
    $tips = [
        'balance' => '¡Buen trabajo planeando tus comidas! Recuerda tomar agua durante el día y comer despacio.',
        'bajar_peso' => 'Vas muy bien. Intenta servir las porciones en platos más pequeños y camina 20 minutos hoy.',
        'ganar_musculo' => 'Este menú te da la proteína que necesitas para crecer. Distribúyela en tus comidas del día y no olvides entrenar con carga y descansar bien — el músculo se construye descansando, no solo comiendo.',
        'energia' => 'Este menú te dará buena energía. No olvides dormir bien esta noche para rendir al máximo.',
        'familia' => 'Un menú pensado para toda la familia. Involucra a los niños en la cocina, ¡es un buen momento juntos!',
    ];
    return $tips[$profile['goal'] ?? 'balance'] ?? $tips['balance'];
}

/** Genera el plan de 7 días, evitando repetir receta del mismo tipo mientras haya opciones distintas. */
function build_week_plan(array $profile, array $pantryItems): array {
    $types = meal_types_for_profile($profile);
    $candidatesByType = [];
    foreach (MEAL_TYPES as $type) {
        $candidatesByType[$type] = in_array($type, $types, true)
            ? candidate_recipes($type, $pantryItems, $profile, [], 8)
            : [];
    }

    $ai = ai_pick_week($candidatesByType, $profile, $pantryItems);
    $people = max(1, (int)($profile['people'] ?? 1));
    $dayNames = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    $days = [];
    $usedThisWeek = []; // type => [ids ya usados esta semana]

    for ($i = 0; $i < 7; $i++) {
        $aiDay = $ai['dias'][$i] ?? null;
        $meals = [];
        foreach ($types as $type) {
            $all = $candidatesByType[$type];
            $used = $usedThisWeek[$type] ?? [];
            $pool = array_values(array_filter($all, fn($r) => !in_array((int)$r['id'], $used, true)));

            if (empty($pool)) {
                // Se agotaron las opciones distintas: reinicia, evitando al menos repetir el día anterior.
                $lastId = end($used) ?: null;
                $pool = array_values(array_filter($all, fn($r) => (int)$r['id'] !== $lastId)) ?: $all;
                $usedThisWeek[$type] = [];
            }

            $aiId = is_array($aiDay) && isset($aiDay[$type]) && $aiDay[$type] !== null ? (int)$aiDay[$type] : null;
            $chosen = safe_pick($aiId, $pool);
            if ($chosen) {
                $meals[$type] = present_recipe($chosen, $pantryItems, $people);
                $usedThisWeek[$type][] = (int)$chosen['id'];
            }
        }
        $tip = is_array($aiDay) && is_string($aiDay['consejo_coach'] ?? null) && $aiDay['consejo_coach'] !== ''
            ? mb_substr($aiDay['consejo_coach'], 0, 260)
            : default_coach_tip($profile);
        $days[] = ['day' => $dayNames[$i], 'meals' => $meals, 'consejo_coach' => $tip];
    }

    return ['days' => $days];
}

/**
 * Lista de compras agrupada por sección: ingredientes del plan que NO están en la despensa
 * ACTUAL (se recalcula en vivo contra $pantryItems, no contra una foto vieja del plan) —
 * así, si compras o cocinas algo, la lista se actualiza sola la próxima vez que la mires.
 */
function build_shopping_list(array $planDays, array $pantryItems, int $people): array {
    $needed = []; // normalizado => ['item'=>original, 'qty_notes'=>[]]
    foreach ($planDays as $day) {
        foreach ($day['meals'] as $meal) {
            $recipe = recipe_by_id((int)($meal['id'] ?? 0));
            $missing = $recipe ? missing_ingredients($recipe, $pantryItems, $people) : ($meal['missing'] ?? []);
            foreach ($missing as $ing) {
                $key = normalize_ingredient($ing['item']);
                if ($key === '') continue;
                if (!isset($needed[$key])) {
                    $needed[$key] = ['item' => $ing['item'], 'qtys' => []];
                }
                if ($ing['qty'] !== '') {
                    $needed[$key]['qtys'][] = $ing['qty'];
                }
            }
        }
    }

    $grouped = [];
    foreach ($needed as $entry) {
        $cat = ingredient_category($entry['item']);
        $grouped[$cat][] = [
            'item' => $entry['item'],
            'qty' => implode(' + ', array_unique($entry['qtys'])),
        ];
    }
    ksort($grouped);
    return $grouped;
}

/**
 * Marca una receta como "hecha": descuenta de la despensa los ingredientes que se usaron.
 * Como la lista de compras se recalcula en vivo, esos ingredientes vuelven a aparecer ahí
 * automáticamente la próxima vez que se necesiten en el plan.
 * Devuelve los ítems de la despensa que se quitaron.
 */
function consume_recipe_from_pantry(int $userId, int $recipeId): array {
    $recipe = recipe_by_id($recipeId);
    if (!$recipe) {
        return [];
    }
    $pantry = load_pantry($userId);
    $consumed = [];
    foreach ($recipe['ingredients'] as $entry) {
        $name = ingredient_name($entry);
        if (is_staple($name)) {
            continue;
        }
        foreach ($pantry as $p) {
            if (ingredients_match($name, $p)) {
                remove_pantry_item($userId, $p);
                $consumed[] = $p;
            }
        }
    }
    return array_values(array_unique($consumed));
}

/**
 * Marca un plato como "hecho" en los planes guardados (día y semana) donde aparezca,
 * para que el anillo de kcal de Hoy refleje lo realmente consumido, no solo lo planeado.
 */
function mark_meal_done(int $userId, int $recipeId): void {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, plan_type, plan_json FROM meal_plans WHERE user_id = ?');
    $stmt->execute([$userId]);
    foreach ($stmt->fetchAll() as $row) {
        $data = json_decode($row['plan_json'], true);
        if (!is_array($data)) {
            continue;
        }
        $changed = false;
        if ($row['plan_type'] === 'day' && isset($data['meals'])) {
            foreach ($data['meals'] as &$meal) {
                if ((int)($meal['id'] ?? 0) === $recipeId && empty($meal['done'])) {
                    $meal['done'] = true;
                    $changed = true;
                }
            }
            unset($meal);
        } elseif ($row['plan_type'] === 'week' && isset($data['days'])) {
            foreach ($data['days'] as &$day) {
                foreach ($day['meals'] as &$meal) {
                    if ((int)($meal['id'] ?? 0) === $recipeId && empty($meal['done'])) {
                        $meal['done'] = true;
                        $changed = true;
                    }
                }
                unset($meal);
            }
            unset($day);
        }
        if ($changed) {
            $pdo->prepare('UPDATE meal_plans SET plan_json = ? WHERE id = ?')
                ->execute([json_encode($data, JSON_UNESCAPED_UNICODE), $row['id']]);
        }
    }
}
