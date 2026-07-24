<?php
/**
 * MenúVital — Acceso al perfil y despensa del usuario
 */

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/ingredients.php';
require_once __DIR__ . '/quantities.php';

const GOALS = ['balance', 'bajar_peso', 'ganar_musculo', 'energia', 'familia'];

function goal_label(string $goal): string {
    return match ($goal) {
        'bajar_peso' => 'Bajar de peso',
        'ganar_musculo' => 'Aumentar masa muscular',
        'energia' => 'Más energía',
        'familia' => 'Alimentar a la familia',
        default => 'Vida balanceada',
    };
}

const ACTIVITY_LEVELS = ['sedentario', 'ligero', 'moderado', 'activo', 'muy_activo'];

function activity_level_label(string $level): string {
    return match ($level) {
        'sedentario' => 'Sedentario (poco o nada de ejercicio)',
        'ligero' => 'Ligero (ejercicio suave 1-3 días/semana)',
        'activo' => 'Activo (ejercicio intenso 6-7 días/semana)',
        'muy_activo' => 'Muy activo (entrenas 2 veces al día o trabajo físico exigente)',
        default => 'Moderado (ejercicio 3-5 días/semana)',
    };
}

/** Multiplicador de actividad de Mifflin-St Jeor sobre la tasa metabólica basal. */
function activity_multiplier(string $level): float {
    return match ($level) {
        'sedentario' => 1.2,
        'ligero' => 1.375,
        'activo' => 1.725,
        'muy_activo' => 1.9,
        default => 1.55,
    };
}

/** Perfil del usuario con listas separadas y listas para el planner. */
function load_profile(int $userId): array {
    $stmt = db()->prepare('SELECT * FROM profiles WHERE user_id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row) {
        $row = ['user_id' => $userId, 'allergies' => '', 'dislikes' => '', 'favorites' => '',
                'goal' => 'balance', 'people' => 1, 'meals_per_day' => 3,
                'height_cm' => null, 'starting_weight' => null, 'sex' => null, 'age' => null,
                'activity_level' => 'moderado'];
    }
    $row['favorites'] = $row['favorites'] ?? '';
    $row['activity_level'] = in_array($row['activity_level'] ?? null, ACTIVITY_LEVELS, true)
        ? $row['activity_level'] : 'moderado';
    $toList = fn($csv) => array_values(array_filter(array_map('trim', explode(',', (string)$csv))));
    $row['allergies_list'] = $toList($row['allergies']);
    $row['dislikes_list'] = $toList($row['dislikes']);
    $row['favorites_list'] = $toList($row['favorites']);
    $row['kcal_target'] = daily_kcal_target($row);
    $row['protein_target'] = daily_protein_target($row);
    $row['favorite_recipe_ids'] = load_favorite_recipe_ids($userId);
    return $row;
}

/** Ids de recetas marcadas con ❤ en el recetario (no confundir con "favorites" de texto libre). */
function load_favorite_recipe_ids(int $userId): array {
    $stmt = db()->prepare('SELECT recipe_id FROM favorite_recipes WHERE user_id = ?');
    $stmt->execute([$userId]);
    return array_map('intval', array_column($stmt->fetchAll(), 'recipe_id'));
}

function toggle_favorite_recipe(int $userId, int $recipeId): bool {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id FROM favorite_recipes WHERE user_id = ? AND recipe_id = ?');
    $stmt->execute([$userId, $recipeId]);
    if ($stmt->fetch()) {
        $pdo->prepare('DELETE FROM favorite_recipes WHERE user_id = ? AND recipe_id = ?')->execute([$userId, $recipeId]);
        return false;
    }
    $pdo->prepare('INSERT INTO favorite_recipes (user_id, recipe_id, created_at) VALUES (?, ?, ?)')
        ->execute([$userId, $recipeId, db_now()]);
    return true;
}

function save_profile(int $userId, array $data): void {
    $pdo = db();
    $exists = $pdo->prepare('SELECT 1 FROM profiles WHERE user_id = ?');
    $exists->execute([$userId]);
    if ($exists->fetch()) {
        $pdo->prepare('UPDATE profiles SET allergies=?, dislikes=?, favorites=?, goal=?, people=?, meals_per_day=?, height_cm=?, starting_weight=?, sex=?, age=?, activity_level=?, updated_at=? WHERE user_id=?')
            ->execute([$data['allergies'], $data['dislikes'], $data['favorites'], $data['goal'], $data['people'], $data['meals_per_day'], $data['height_cm'], $data['starting_weight'], $data['sex'], $data['age'], $data['activity_level'], db_now(), $userId]);
    } else {
        $pdo->prepare('INSERT INTO profiles (user_id, allergies, dislikes, favorites, goal, people, meals_per_day, height_cm, starting_weight, sex, age, activity_level, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)')
            ->execute([$userId, $data['allergies'], $data['dislikes'], $data['favorites'], $data['goal'], $data['people'], $data['meals_per_day'], $data['height_cm'], $data['starting_weight'], $data['sex'], $data['age'], $data['activity_level'], db_now()]);
    }
}

/**
 * Meta calórica diaria estimada (Mifflin-St Jeor), ajustada al objetivo.
 * Necesita sexo, edad, estatura y peso — si falta alguno, devuelve null
 * (la UI invita a completar el perfil en vez de mostrar un número inventado).
 */
function daily_kcal_target(array $profile): ?int {
    $sex = $profile['sex'] ?? null;
    $age = $profile['age'] ?? null;
    $height = $profile['height_cm'] ?? null;
    $weight = $profile['starting_weight'] ?? null;
    if (!$sex || !$age || !$height || !$weight) {
        return null;
    }
    $bmr = 10 * (float)$weight + 6.25 * (float)$height - 5 * (int)$age + ($sex === 'm' ? 5 : -161);
    $activityLevel = in_array($profile['activity_level'] ?? null, ACTIVITY_LEVELS, true)
        ? $profile['activity_level'] : 'moderado';
    $maintenance = $bmr * activity_multiplier($activityLevel);
    $target = match ($profile['goal'] ?? 'balance') {
        'bajar_peso' => max(1200, $maintenance - 400),
        'ganar_musculo' => $maintenance + 350, // superávit moderado: gana músculo sin acumular grasa de más
        'energia' => $maintenance + 150,
        default => $maintenance,
    };
    return (int)round($target / 10) * 10;
}

/**
 * Meta diaria de proteína (g), según peso y objetivo — solo necesita el peso,
 * a diferencia de la meta calórica. Rangos estándar de nutrición deportiva:
 * 2.0 g/kg para ganar músculo, 1.6 g/kg bajando de peso (para no perder
 * masa muscular en el déficit), 1.2 g/kg para el resto.
 */
function daily_protein_target(array $profile): ?int {
    $weight = $profile['starting_weight'] ?? null;
    if (!$weight) {
        return null;
    }
    $perKg = match ($profile['goal'] ?? 'balance') {
        'ganar_musculo' => 2.0,
        'bajar_peso' => 1.6,
        default => 1.2,
    };
    return (int)round((float)$weight * $perKg);
}

/**
 * Meta diaria de vasos de agua (vaso = 250 ml), según el peso (35 ml/kg,
 * regla clínica estándar). Sin peso registrado, usa la recomendación
 * general de 8 vasos (2 litros).
 */
function daily_water_target(array $profile): int {
    $weight = $profile['starting_weight'] ?? null;
    if (!$weight) {
        return 8;
    }
    $ml = (float)$weight * 35;
    return max(6, min(16, (int)round($ml / 250)));
}

/** Ítems de la despensa del usuario (solo nombres, para el matching del planner). */
function load_pantry(int $userId): array {
    $stmt = db()->prepare('SELECT item FROM pantry_items WHERE user_id = ? ORDER BY id DESC');
    $stmt->execute([$userId]);
    return array_column($stmt->fetchAll(), 'item');
}

/** Despensa con cantidad y categoría, agrupada por sección del supermercado, para la UI. */
function load_pantry_detailed(int $userId): array {
    $stmt = db()->prepare('SELECT item, quantity FROM pantry_items WHERE user_id = ? ORDER BY item ASC');
    $stmt->execute([$userId]);
    $grouped = [];
    foreach ($stmt->fetchAll() as $row) {
        $cat = ingredient_category($row['item']);
        $grouped[$cat][] = ['item' => $row['item'], 'quantity' => $row['quantity']];
    }
    ksort($grouped);
    return $grouped;
}

/**
 * Agrega un ítem a la despensa. El nombre se normaliza al del catálogo
 * (para que "pollo", "Pollo entero", etc. no queden como filas separadas).
 * Si el ítem ya existe y $merge es true (por defecto), la cantidad nueva se
 * SUMA a la que ya había en vez de reemplazarla; con $merge=false se
 * reemplaza (útil cuando la usuaria edita la cantidad a mano).
 */
function add_pantry_item(int $userId, string $item, string $quantity = '', bool $merge = true): void {
    $item = canonical_ingredient_name(clean_text($item, 60));
    $quantity = clean_text($quantity, 60);
    if ($quantity !== '') {
        $parsed = parse_qty($quantity);
        if ($parsed !== null) {
            $quantity = format_qty($parsed['num'], $parsed['unit']);
        }
    }
    if ($item === '') {
        return;
    }
    try {
        db()->prepare('INSERT INTO pantry_items (user_id, item, quantity, created_at) VALUES (?, ?, ?, ?)')
            ->execute([$userId, $item, $quantity, db_now()]);
    } catch (PDOException $e) {
        // Ya existe (UNIQUE user_id+item).
        if ($quantity === '') {
            return;
        }
        if ($merge) {
            $stmt = db()->prepare('SELECT quantity FROM pantry_items WHERE user_id = ? AND item = ?');
            $stmt->execute([$userId, $item]);
            $current = (string)($stmt->fetchColumn() ?: '');
            $quantity = ($current !== '') ? qty_string_add($current, $quantity) : $quantity;
        }
        db()->prepare('UPDATE pantry_items SET quantity = ? WHERE user_id = ? AND item = ?')
            ->execute([$quantity, $userId, $item]);
    }
}

function remove_pantry_item(int $userId, string $item): void {
    db()->prepare('DELETE FROM pantry_items WHERE user_id = ? AND item = ?')->execute([$userId, $item]);
}

function clear_pantry(int $userId): void {
    db()->prepare('DELETE FROM pantry_items WHERE user_id = ?')->execute([$userId]);
}
