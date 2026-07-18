<?php
/**
 * MenúVital — Acceso al perfil y despensa del usuario
 */

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/ingredients.php';

const GOALS = ['balance', 'bajar_peso', 'energia', 'familia'];

function goal_label(string $goal): string {
    return match ($goal) {
        'bajar_peso' => 'Bajar de peso',
        'energia' => 'Más energía',
        'familia' => 'Alimentar a la familia',
        default => 'Vida balanceada',
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
                'height_cm' => null, 'starting_weight' => null, 'sex' => null, 'age' => null];
    }
    $row['favorites'] = $row['favorites'] ?? '';
    $toList = fn($csv) => array_values(array_filter(array_map('trim', explode(',', (string)$csv))));
    $row['allergies_list'] = $toList($row['allergies']);
    $row['dislikes_list'] = $toList($row['dislikes']);
    $row['favorites_list'] = $toList($row['favorites']);
    $row['kcal_target'] = daily_kcal_target($row);
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
        $pdo->prepare('UPDATE profiles SET allergies=?, dislikes=?, favorites=?, goal=?, people=?, meals_per_day=?, height_cm=?, starting_weight=?, sex=?, age=?, updated_at=? WHERE user_id=?')
            ->execute([$data['allergies'], $data['dislikes'], $data['favorites'], $data['goal'], $data['people'], $data['meals_per_day'], $data['height_cm'], $data['starting_weight'], $data['sex'], $data['age'], db_now(), $userId]);
    } else {
        $pdo->prepare('INSERT INTO profiles (user_id, allergies, dislikes, favorites, goal, people, meals_per_day, height_cm, starting_weight, sex, age, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)')
            ->execute([$userId, $data['allergies'], $data['dislikes'], $data['favorites'], $data['goal'], $data['people'], $data['meals_per_day'], $data['height_cm'], $data['starting_weight'], $data['sex'], $data['age'], db_now()]);
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
    $maintenance = $bmr * 1.35; // actividad ligera-moderada
    $target = match ($profile['goal'] ?? 'balance') {
        'bajar_peso' => max(1200, $maintenance - 400),
        'energia' => $maintenance + 150,
        default => $maintenance,
    };
    return (int)round($target / 10) * 10;
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

function add_pantry_item(int $userId, string $item, string $quantity = ''): void {
    $item = clean_text($item, 60);
    $quantity = clean_text($quantity, 60);
    if ($item === '') {
        return;
    }
    try {
        db()->prepare('INSERT INTO pantry_items (user_id, item, quantity, created_at) VALUES (?, ?, ?, ?)')
            ->execute([$userId, $item, $quantity, db_now()]);
    } catch (PDOException $e) {
        // Ya existe (UNIQUE user_id+item): actualiza la cantidad si se dio una nueva.
        if ($quantity !== '') {
            db()->prepare('UPDATE pantry_items SET quantity = ? WHERE user_id = ? AND item = ?')
                ->execute([$quantity, $userId, $item]);
        }
    }
}

function remove_pantry_item(int $userId, string $item): void {
    db()->prepare('DELETE FROM pantry_items WHERE user_id = ? AND item = ?')->execute([$userId, $item]);
}

function clear_pantry(int $userId): void {
    db()->prepare('DELETE FROM pantry_items WHERE user_id = ?')->execute([$userId]);
}
