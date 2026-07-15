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
        'goal' => $profile['goal'],
        'people' => (int)$profile['people'],
        'meals_per_day' => (int)$profile['meals_per_day'],
    ]]);
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $goal = in_array($in['goal'] ?? '', GOALS, true) ? $in['goal'] : 'balance';
    $people = max(1, min(12, (int)($in['people'] ?? 1)));
    $meals = in_array((int)($in['meals_per_day'] ?? 3), [3, 4], true) ? (int)$in['meals_per_day'] : 3;
    $allergies = clean_text($in['allergies'] ?? '', 300);
    $dislikes = clean_text($in['dislikes'] ?? '', 300);

    save_profile((int)$user['id'], [
        'allergies' => $allergies,
        'dislikes' => $dislikes,
        'goal' => $goal,
        'people' => $people,
        'meals_per_day' => $meals,
    ]);
    json_response(['ok' => true]);
}

json_error('Acción no reconocida.', 404);
