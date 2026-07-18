<?php
/**
 * MenúVital — API de perfil
 * ?action=get (GET) | update (POST)
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
secure_session_start();
send_security_headers();
$user = require_login_api();

$action = $_GET['action'] ?? 'get';

if ($action === 'get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $profile = load_profile((int)$user['id']);
    json_response(['ok' => true, 'profile' => [
        'allergies' => $profile['allergies'],
        'dislikes' => $profile['dislikes'],
        'favorites' => $profile['favorites'],
        'goal' => $profile['goal'],
        'people' => (int)$profile['people'],
        'meals_per_day' => (int)$profile['meals_per_day'],
        'height_cm' => $profile['height_cm'] !== null ? (int)$profile['height_cm'] : null,
        'starting_weight' => $profile['starting_weight'] !== null ? (float)$profile['starting_weight'] : null,
        'sex' => $profile['sex'],
        'age' => $profile['age'] !== null ? (int)$profile['age'] : null,
        'kcal_target' => $profile['kcal_target'],
        'protein_target' => $profile['protein_target'],
        'water_target' => daily_water_target($profile),
    ]]);
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $goal = in_array($in['goal'] ?? '', GOALS, true) ? $in['goal'] : 'balance';
    $people = max(1, min(12, (int)($in['people'] ?? 1)));
    $mealsRaw = (int)($in['meals_per_day'] ?? 3);
    $meals = in_array($mealsRaw, [3, 4], true) ? $mealsRaw : 3;
    $allergies = clean_text($in['allergies'] ?? '', 300);
    $dislikes = clean_text($in['dislikes'] ?? '', 300);
    $favorites = clean_text($in['favorites'] ?? '', 500);

    $height = $in['height_cm'] ?? null;
    $height = ($height === null || $height === '') ? null : max(100, min(230, (int)$height));
    $startWeight = $in['starting_weight'] ?? null;
    $startWeight = ($startWeight === null || $startWeight === '') ? null : max(20, min(300, (float)$startWeight));
    $sex = in_array($in['sex'] ?? '', ['f', 'm'], true) ? $in['sex'] : null;
    $age = $in['age'] ?? null;
    $age = ($age === null || $age === '') ? null : max(12, min(100, (int)$age));

    save_profile((int)$user['id'], [
        'allergies' => $allergies,
        'dislikes' => $dislikes,
        'favorites' => $favorites,
        'goal' => $goal,
        'people' => $people,
        'meals_per_day' => $meals,
        'height_cm' => $height,
        'starting_weight' => $startWeight,
        'sex' => $sex,
        'age' => $age,
    ]);
    json_response(['ok' => true, 'kcal_target' => daily_kcal_target([
        'sex' => $sex, 'age' => $age, 'height_cm' => $height, 'starting_weight' => $startWeight, 'goal' => $goal,
    ])]);
}

json_error('Acción no reconocida.', 404);
