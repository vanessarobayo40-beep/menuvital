<?php
/**
 * MenúVital — API de despensa/mercado
 * ?action=list (GET) | add (POST) | remove (POST) | search (GET, autocompletado)
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/ingredients.php';
secure_session_start();
send_security_headers();
$user = require_login_api();
$userId = (int)$user['id'];

$action = $_GET['action'] ?? 'list';

if ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    json_response(['ok' => true, 'items' => load_pantry($userId)]);
}

if ($action === 'search' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $q = normalize_ingredient((string)($_GET['q'] ?? ''));
    if ($q === '') {
        json_response(['ok' => true, 'results' => []]);
    }
    $matches = [];
    foreach (array_keys(ingredient_catalog()) as $name) {
        if (str_contains(normalize_ingredient($name), $q)) {
            $matches[] = $name;
        }
        if (count($matches) >= 8) break;
    }
    json_response(['ok' => true, 'results' => $matches]);
}

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $items = is_array($in['items'] ?? null) ? $in['items'] : [(string)($in['item'] ?? '')];
    $added = 0;
    foreach ($items as $item) {
        if (!is_string($item)) continue;
        $item = clean_text($item, 60);
        if ($item === '') continue;
        add_pantry_item($userId, $item);
        $added++;
    }
    if ($added === 0) {
        json_error('Escribe al menos un ingrediente.');
    }
    json_response(['ok' => true, 'items' => load_pantry($userId)]);
}

if ($action === 'remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $item = clean_text($in['item'] ?? '', 60);
    if ($item === '') {
        json_error('Ingrediente no válido.');
    }
    remove_pantry_item($userId, $item);
    json_response(['ok' => true, 'items' => load_pantry($userId)]);
}

if ($action === 'clear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    clear_pantry($userId);
    json_response(['ok' => true, 'items' => []]);
}

json_error('Acción no reconocida.', 404);
