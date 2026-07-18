<?php
/**
 * MenúVital — API del recetario navegable
 * ?action=list (GET) | detail (GET, &id=) | toggle_favorite (POST, {recipe_id})
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/planner.php';
secure_session_start();
send_security_headers();
$user = require_login_api();
$userId = (int)$user['id'];

$action = $_GET['action'] ?? 'list';

if ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = db()->query('SELECT id, name, meal_type, tags, kcal, protein, time_min FROM recipes ORDER BY name ASC');
    $favIds = load_favorite_recipe_ids($userId);
    $recipes = array_map(function ($r) use ($favIds) {
        return [
            'id' => (int)$r['id'],
            'name' => $r['name'],
            'meal_type' => $r['meal_type'],
            'tags' => json_decode($r['tags'], true) ?: [],
            'kcal' => (int)$r['kcal'],
            'protein' => (int)$r['protein'],
            'time_min' => (int)$r['time_min'],
            'image_url' => recipe_image_url(['id' => $r['id'], 'name' => $r['name']]),
            'is_favorite' => in_array((int)$r['id'], $favIds, true),
        ];
    }, $stmt->fetchAll());
    json_response(['ok' => true, 'recipes' => $recipes]);
}

if ($action === 'detail' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int)($_GET['id'] ?? 0);
    $recipe = recipe_by_id($id);
    if (!$recipe) {
        json_error('Esa receta no existe.', 404);
    }
    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $presented = present_recipe($recipe, $pantry, max(1, (int)$profile['people']));
    $presented['is_favorite'] = in_array($id, load_favorite_recipe_ids($userId), true);
    json_response(['ok' => true, 'recipe' => $presented]);
}

if ($action === 'toggle_favorite' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $id = (int)($in['recipe_id'] ?? 0);
    if (!recipe_by_id($id)) {
        json_error('Esa receta no existe.', 404);
    }
    $isFavorite = toggle_favorite_recipe($userId, $id);
    json_response(['ok' => true, 'is_favorite' => $isFavorite]);
}

json_error('Acción no reconocida.', 404);
