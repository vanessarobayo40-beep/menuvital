<?php
/**
 * MenúVital — API del recetario navegable
 * ?action=list (GET) | detail (GET, &id=) | toggle_favorite (POST, {recipe_id})
 *          | create (POST, receta propia) | delete (POST, {recipe_id})
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/planner.php';
require_once __DIR__ . '/../includes/groq.php';
secure_session_start();
send_security_headers();
$user = require_login_api();
$userId = (int)$user['id'];

const CREATE_RECIPE_IMAGE_FALLBACK = [
    'desayuno' => 'healthy breakfast plate table',
    'almuerzo' => 'latin lunch plate healthy food',
    'cena' => 'healthy dinner plate food',
    'snack' => 'healthy snack food',
];

/** Trae una receta y valida que sea visible para esta usuaria (oficial o suya). 404 si es de otra. */
function visible_recipe_or_404(int $id, int $userId): array {
    $recipe = recipe_by_id($id);
    if (!$recipe || ($recipe['user_id'] !== null && (int)$recipe['user_id'] !== $userId)) {
        json_error('Esa receta no existe.', 404);
    }
    return $recipe;
}

$action = $_GET['action'] ?? 'list';

if ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = db()->prepare('SELECT id, name, meal_type, tags, kcal, protein, time_min, image_url, user_id
                            FROM recipes WHERE user_id IS NULL OR user_id = ? ORDER BY name ASC');
    $stmt->execute([$userId]);
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
            'image_url' => recipe_image_url(['id' => $r['id'], 'name' => $r['name'], 'image_url' => $r['image_url']]),
            'is_favorite' => in_array((int)$r['id'], $favIds, true),
            'is_own' => $r['user_id'] !== null,
        ];
    }, $stmt->fetchAll());
    json_response(['ok' => true, 'recipes' => $recipes]);
}

if ($action === 'detail' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int)($_GET['id'] ?? 0);
    $recipe = visible_recipe_or_404($id, $userId);
    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $presented = present_recipe($recipe, $pantry, max(1, (int)$profile['people']));
    $presented['is_favorite'] = in_array($id, load_favorite_recipe_ids($userId), true);
    $presented['is_own'] = $recipe['user_id'] !== null;
    json_response(['ok' => true, 'recipe' => $presented]);
}

if ($action === 'toggle_favorite' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $id = (int)($in['recipe_id'] ?? 0);
    visible_recipe_or_404($id, $userId);
    $isFavorite = toggle_favorite_recipe($userId, $id);
    json_response(['ok' => true, 'is_favorite' => $isFavorite]);
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!groq_available()) {
        json_error('Esta función necesita la IA activada para calcular la nutrición de tu receta.');
    }
    if (!rate_limit_check('create_recipe:' . $userId, 15, 86400)) {
        json_error('Ya agregaste varias recetas hoy. Intenta de nuevo más tarde.', 429);
    }

    $in = json_input();
    $name = clean_text($in['name'] ?? '', 150);
    $mealType = (string)($in['meal_type'] ?? '');
    $timeMin = max(1, min(240, (int)($in['time_min'] ?? 20)));

    if ($name === '') {
        json_error('Ponle un nombre a tu receta.');
    }
    if (!in_array($mealType, MEAL_TYPES, true)) {
        json_error('Elige un tipo de comida válido.');
    }

    $ingredientLines = [];
    $ingredientNames = [];
    foreach ((is_array($in['ingredients'] ?? null) ? $in['ingredients'] : []) as $ing) {
        $item = clean_text((string)($ing['item'] ?? ''), 60);
        $qty = clean_text((string)($ing['quantity'] ?? ''), 40);
        if ($item === '') continue;
        $ingredientLines[] = $qty !== '' ? "$item|$qty" : $item;
        $ingredientNames[] = $item;
    }
    if (empty($ingredientLines)) {
        json_error('Agrega al menos un ingrediente.');
    }

    $stepLines = [];
    foreach ((is_array($in['steps'] ?? null) ? $in['steps'] : []) as $s) {
        $s = clean_text((string)$s, 300);
        if ($s !== '') $stepLines[] = $s;
    }
    if (empty($stepLines)) {
        json_error('Agrega al menos un paso de preparación.');
    }

    $details = groq_generate_recipe_details($name, $mealType, $ingredientNames);
    if (!$details) {
        json_error('No pudimos calcular la información nutricional. Intenta de nuevo en un momento.');
    }

    $imageUrl = pexels_search_photo($details['search_query'])
        ?? pexels_search_photo(CREATE_RECIPE_IMAGE_FALLBACK[$mealType]);

    $recipeId = create_custom_recipe($userId, [
        'name' => $name,
        'meal_type' => $mealType,
        'time_min' => $timeMin,
        'ingredients' => $ingredientLines,
        'steps' => $stepLines,
        'tags' => $details['tags'],
        'kcal' => $details['kcal'],
        'protein' => $details['protein'],
        'carbs' => $details['carbs'],
        'fat' => $details['fat'],
        'sugar' => $details['sugar'],
        'fiber' => $details['fiber'],
        'image_url' => $imageUrl,
    ]);

    $recipe = recipe_by_id($recipeId);
    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $presented = present_recipe($recipe, $pantry, max(1, (int)$profile['people']));
    $presented['is_favorite'] = false;
    $presented['is_own'] = true;
    json_response(['ok' => true, 'recipe' => $presented]);
}

if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $id = (int)($in['recipe_id'] ?? 0);
    if (!delete_custom_recipe($userId, $id)) {
        json_error('No encontramos esa receta entre las tuyas.', 404);
    }
    json_response(['ok' => true]);
}

json_error('Acción no reconocida.', 404);
