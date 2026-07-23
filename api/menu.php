<?php
/**
 * MenúVital — API del menú manejado por la usuaria
 * ?action=list | add | remove | swap | suggest | done
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/menu.php';
secure_session_start();
send_security_headers();
$user = require_login_api();
$userId = (int)$user['id'];

$action = $_GET['action'] ?? '';

/** Valida "YYYY-MM-DD" y devuelve true/false. */
function valid_date_str(string $s): bool {
    $d = DateTime::createFromFormat('Y-m-d', $s);
    return $d && $d->format('Y-m-d') === $s;
}

/** Receta visible para esta usuaria (oficial o propia), o error 404. */
function menu_visible_recipe_or_404(int $id, int $userId): array {
    $recipe = recipe_by_id($id);
    if (!$recipe || ($recipe['user_id'] !== null && (int)$recipe['user_id'] !== $userId)) {
        json_error('Esa receta no existe.', 404);
    }
    return $recipe;
}

if ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $from = (string)($_GET['from'] ?? '');
    $to = (string)($_GET['to'] ?? '');
    if (!valid_date_str($from) || !valid_date_str($to) || $from > $to) {
        json_error('Rango de fechas no válido.');
    }
    if ((new DateTime($from))->diff(new DateTime($to))->days > 45) {
        json_error('El rango no puede ser mayor a 45 días.');
    }

    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $entries = get_menu_entries($userId, $from, $to);

    $grouped = [];
    foreach ($entries as $e) {
        $presented = present_menu_entry($e, $pantry);
        if ($presented) {
            $grouped[$e['entry_date']][$e['meal_type']] = $presented;
        }
    }

    json_response([
        'ok' => true,
        'entries' => $grouped,
        'meal_types' => meal_types_for_profile($profile),
        'people' => (int)$profile['people'],
        'consejo_coach' => default_coach_tip($profile),
    ]);
}

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $recipeId = (int)($in['recipe_id'] ?? 0);
    $mealType = (string)($in['meal_type'] ?? '');
    $servings = max(1, min(12, (int)($in['servings'] ?? 1)));
    $overwrite = !empty($in['overwrite']);
    $dates = is_array($in['dates'] ?? null) ? array_values(array_unique($in['dates'])) : [];

    if (!in_array($mealType, MEAL_TYPES, true)) {
        json_error('Tipo de comida no válido.');
    }
    if (empty($dates) || count($dates) > 31) {
        json_error('Elige entre 1 y 31 fechas.');
    }
    $today = new DateTime('today');
    $minDate = (clone $today)->modify('-1 day');
    $maxDate = (clone $today)->modify('+45 day');
    foreach ($dates as $d) {
        if (!is_string($d) || !valid_date_str($d)) {
            json_error('Alguna fecha no es válida.');
        }
        $dt = new DateTime($d);
        if ($dt < $minDate || $dt > $maxDate) {
            json_error('Solo puedes planear entre hoy y los próximos 45 días.');
        }
    }

    menu_visible_recipe_or_404($recipeId, $userId);

    if (!$overwrite) {
        $conflicts = [];
        foreach ($dates as $d) {
            $slot = get_menu_slot($userId, $d, $mealType);
            if ($slot) {
                $conflicts[] = ['date' => $d, 'current_name' => $slot['name']];
            }
        }
        if ($conflicts) {
            json_response(['ok' => true, 'conflicts' => $conflicts]);
        }
    }

    foreach ($dates as $d) {
        set_menu_entry($userId, $d, $mealType, $recipeId, $servings, 'user');
    }
    json_response(['ok' => true, 'added' => count($dates)]);
}

if ($action === 'remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $entryId = (int)($in['entry_id'] ?? 0);
    if (!remove_menu_entry($userId, $entryId)) {
        json_error('Ese plato no existe.', 404);
    }
    json_response(['ok' => true]);
}

if ($action === 'swap' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!rate_limit_check('plan_regen:' . $userId, 60, 86400)) {
        json_error('Has cambiado muchos platos hoy. Intenta más tarde.', 429);
    }
    $in = json_input();
    $entryId = (int)($in['entry_id'] ?? 0);
    $entry = get_menu_entry_by_id($userId, $entryId);
    if (!$entry) {
        json_error('Ese plato no existe.', 404);
    }

    $profile = load_profile($userId);
    $pantry = load_pantry($userId);

    // Evita repetir un plato ya usado esa misma semana para el mismo tipo de comida.
    $entryDate = new DateTime($entry['entry_date']);
    $weekStart = (clone $entryDate)->modify('monday this week')->format('Y-m-d');
    $weekEnd = (clone $entryDate)->modify('sunday this week')->format('Y-m-d');
    $weekEntries = get_menu_entries($userId, $weekStart, $weekEnd);
    $exclude = [(int)$entry['recipe_id']];
    foreach ($weekEntries as $we) {
        if ($we['meal_type'] === $entry['meal_type']) {
            $exclude[] = (int)$we['recipe_id'];
        }
    }
    $exclude = array_values(array_unique($exclude));

    // Límite alto a propósito: "Cambiar plato" debe poder ofrecer cualquier receta
    // del recetario de ese tipo, no solo un puñado de las mejor puntuadas.
    $candidates = candidate_recipes($entry['meal_type'], $pantry, $profile, $exclude, 200);
    if (empty($candidates)) {
        $candidates = candidate_recipes($entry['meal_type'], $pantry, $profile, [(int)$entry['recipe_id']], 200);
    }
    if (empty($candidates)) {
        json_error('No encontramos otro plato para cambiar.');
    }

    $chosen = pick_candidate($candidates);
    set_menu_entry($userId, $entry['entry_date'], $entry['meal_type'], (int)$chosen['id'], (int)$entry['servings'], 'user');
    $newEntry = get_menu_slot($userId, $entry['entry_date'], $entry['meal_type']);
    $presented = present_menu_entry($newEntry, $pantry);
    json_response(['ok' => true, 'meal' => $presented]);
}

if ($action === 'suggest' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!rate_limit_check('plan_regen:' . $userId, 60, 86400)) {
        json_error('Has pedido muchas sugerencias hoy. Intenta más tarde.', 429);
    }
    $in = json_input();
    $from = (string)($in['from'] ?? '');
    $to = (string)($in['to'] ?? '');
    $replaceSuggested = !empty($in['replace_suggested']);
    if (!valid_date_str($from) || !valid_date_str($to) || $from > $to) {
        json_error('Rango de fechas no válido.');
    }
    if ((new DateTime($from))->diff(new DateTime($to))->days > 31) {
        json_error('El rango no puede ser mayor a 31 días.');
    }

    $profile = load_profile($userId);
    $pantry = load_pantry($userId);
    $filled = suggest_fill($userId, $profile, $pantry, $from, $to, $replaceSuggested);
    json_response(['ok' => true, 'filled' => $filled]);
}

if ($action === 'done' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $entryId = (int)($in['entry_id'] ?? 0);
    $entry = get_menu_entry_by_id($userId, $entryId);
    if (!$entry) {
        json_error('Ese plato no existe.', 404);
    }
    $portions = max(1, min(50, (int)($in['portions'] ?? $entry['servings'])));

    $consumed = consume_recipe_from_pantry($userId, (int)$entry['recipe_id'], $portions);
    mark_entry_done($userId, $entryId);

    json_response(['ok' => true, 'consumed' => $consumed]);
}

if ($action === 'shopping_list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $from = (string)($_GET['from'] ?? '');
    $period = (string)($_GET['period'] ?? 'semana');
    if (!valid_date_str($from)) {
        json_error('Fecha no válida.');
    }
    $daysByPeriod = ['semana' => 6, 'quincena' => 14, 'mes' => 29];
    $days = $daysByPeriod[$period] ?? 6;
    $to = (new DateTime($from))->modify("+{$days} day")->format('Y-m-d');

    $list = build_exact_shopping_list($userId, $from, $to);
    json_response(['ok' => true, 'list' => $list, 'from' => $from, 'to' => $to]);
}

json_error('Acción no reconocida.', 404);
