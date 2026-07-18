<?php
/**
 * MenúVital — API del planificador de menús
 * ?action=today | today_new | week | week_new | shopping_list
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/planner.php';
secure_session_start();
send_security_headers();
$user = require_login_api();
$userId = (int)$user['id'];

function today_str(): string {
    return date('Y-m-d');
}

function get_stored_plan(int $userId, string $planType): ?array {
    $stmt = db()->prepare('SELECT start_date, plan_json FROM meal_plans WHERE user_id = ? AND plan_type = ?');
    $stmt->execute([$userId, $planType]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    return ['start_date' => $row['start_date'], 'data' => json_decode($row['plan_json'], true)];
}

function store_plan(int $userId, string $planType, string $startDate, array $data): void {
    $pdo = db();
    $pdo->prepare('DELETE FROM meal_plans WHERE user_id = ? AND plan_type = ?')->execute([$userId, $planType]);
    $pdo->prepare('INSERT INTO meal_plans (user_id, plan_type, start_date, plan_json, created_at) VALUES (?,?,?,?,?)')
        ->execute([$userId, $planType, $startDate, json_encode($data, JSON_UNESCAPED_UNICODE), db_now()]);
}

function exclude_from_day(array $dayData): array {
    $exclude = [];
    foreach ($dayData['meals'] ?? [] as $type => $meal) {
        $exclude[$type] = [(int)$meal['id']];
    }
    return $exclude;
}

$action = $_GET['action'] ?? 'today';

// ---------- HOY ----------
if ($action === 'today' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stored = get_stored_plan($userId, 'day');
    if ($stored && $stored['start_date'] === today_str()) {
        json_response(['ok' => true, 'date' => $stored['start_date'], 'plan' => $stored['data']]);
    }
    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $plan = build_day_plan($profile, $pantry);
    store_plan($userId, 'day', today_str(), $plan);
    json_response(['ok' => true, 'date' => today_str(), 'plan' => $plan]);
}

if ($action === 'today_new' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!rate_limit_check('plan_regen:' . $userId, 15, 86400)) {
        json_error('Ya generaste varios menús hoy. Intenta de nuevo más tarde.', 429);
    }
    $stored = get_stored_plan($userId, 'day');
    $exclude = ($stored && $stored['start_date'] === today_str()) ? exclude_from_day($stored['data']) : [];
    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $plan = build_day_plan($profile, $pantry, $exclude);
    store_plan($userId, 'day', today_str(), $plan);
    json_response(['ok' => true, 'date' => today_str(), 'plan' => $plan]);
}

// ---------- SEMANA ----------
if ($action === 'week' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stored = get_stored_plan($userId, 'week');
    $stillValid = $stored && (strtotime($stored['start_date']) + 7 * 86400) > time();
    if ($stillValid) {
        json_response(['ok' => true, 'start_date' => $stored['start_date'], 'plan' => $stored['data']]);
    }
    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $plan = build_week_plan($profile, $pantry);
    store_plan($userId, 'week', today_str(), $plan);
    json_response(['ok' => true, 'start_date' => today_str(), 'plan' => $plan]);
}

if ($action === 'week_new' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!rate_limit_check('plan_regen:' . $userId, 15, 86400)) {
        json_error('Ya generaste varios menús hoy. Intenta de nuevo más tarde.', 429);
    }
    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $plan = build_week_plan($profile, $pantry);
    store_plan($userId, 'week', today_str(), $plan);
    json_response(['ok' => true, 'start_date' => today_str(), 'plan' => $plan]);
}

// ---------- CAMBIAR UN SOLO PLATO ----------
if ($action === 'swap_meal' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!rate_limit_check('plan_regen:' . $userId, 15, 86400)) {
        json_error('Ya hiciste varios cambios hoy. Intenta de nuevo más tarde.', 429);
    }
    $in = json_input();
    $type = (string)($in['meal_type'] ?? '');
    if (!in_array($type, MEAL_TYPES, true)) {
        json_error('Tipo de comida no válido.');
    }
    $stored = get_stored_plan($userId, 'day');
    if (!$stored || $stored['start_date'] !== today_str() || !isset($stored['data']['meals'][$type])) {
        json_error('No encontramos el menú de hoy. Recarga la página.');
    }

    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $currentId = (int)$stored['data']['meals'][$type]['id'];
    $candidates = candidate_recipes($type, $pantry, $profile, [$currentId]);
    if (empty($candidates)) {
        json_error('No encontramos otra opción distinta para este tipo de comida.');
    }
    $newMeal = present_recipe($candidates[0], $pantry, max(1, (int)$profile['people']));

    $plan = $stored['data'];
    $plan['meals'][$type] = $newMeal;
    store_plan($userId, 'day', today_str(), $plan);
    json_response(['ok' => true, 'meal' => $newMeal]);
}

// ---------- CAMBIAR UN PLATO DENTRO DE LA SEMANA ----------
if ($action === 'swap_week_meal' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!rate_limit_check('plan_regen:' . $userId, 15, 86400)) {
        json_error('Ya hiciste varios cambios hoy. Intenta de nuevo más tarde.', 429);
    }
    $in = json_input();
    $dayIndex = (int)($in['day_index'] ?? -1);
    $type = (string)($in['meal_type'] ?? '');
    if (!in_array($type, MEAL_TYPES, true)) {
        json_error('Tipo de comida no válido.');
    }
    $stored = get_stored_plan($userId, 'week');
    $stillValid = $stored && (strtotime($stored['start_date']) + 7 * 86400) > time();
    if (!$stillValid || !isset($stored['data']['days'][$dayIndex]['meals'][$type])) {
        json_error('No encontramos ese día en tu plan semanal. Recarga la página.');
    }

    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    // Evita repetir cualquier plato de ese tipo que ya esté en otro día de la semana.
    $usedThisWeek = [];
    foreach ($stored['data']['days'] as $day) {
        if (isset($day['meals'][$type]['id'])) {
            $usedThisWeek[] = (int)$day['meals'][$type]['id'];
        }
    }
    $candidates = candidate_recipes($type, $pantry, $profile, $usedThisWeek);
    if (empty($candidates)) {
        // Sin opciones nuevas: al menos evita repetir la de ese mismo día.
        $currentId = (int)$stored['data']['days'][$dayIndex]['meals'][$type]['id'];
        $candidates = candidate_recipes($type, $pantry, $profile, [$currentId]);
    }
    if (empty($candidates)) {
        json_error('No encontramos otra opción distinta para este tipo de comida.');
    }
    $newMeal = present_recipe($candidates[0], $pantry, max(1, (int)$profile['people']));

    $plan = $stored['data'];
    $plan['days'][$dayIndex]['meals'][$type] = $newMeal;
    store_plan($userId, 'week', $stored['start_date'], $plan);
    json_response(['ok' => true, 'meal' => $newMeal]);
}

// ---------- LISTA DE MERCADO ----------
if ($action === 'shopping_list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $week = get_stored_plan($userId, 'week');
    $days = ($week && (strtotime($week['start_date']) + 7 * 86400) > time())
        ? $week['data']['days']
        : null;

    if ($days === null) {
        $today = get_stored_plan($userId, 'day');
        $days = ($today && $today['start_date'] === today_str()) ? [$today['data']] : [];
    }

    $list = build_shopping_list($days, $pantry, max(1, (int)$profile['people']));
    json_response(['ok' => true, 'list' => $list]);
}

json_error('Acción no reconocida.', 404);
