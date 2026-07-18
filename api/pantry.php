<?php
/**
 * MenúVital — API de despensa/mercado
 * ?action=list (GET) | add (POST) | remove (POST) | search (GET, autocompletado)
 *          | voice (POST, audio -> ingredientes) | photo (POST, foto de factura -> ingredientes)
 *          | consume_recipe (POST, receta hecha -> descuenta ingredientes de la despensa)
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/ingredients.php';
require_once __DIR__ . '/../includes/groq.php';
require_once __DIR__ . '/../includes/planner.php';
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

// ---------- Ingresar mercado por voz ----------
if ($action === 'voice' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!groq_available()) {
        json_error('Esta función necesita la IA activada. Por ahora escribe los ingredientes.');
    }
    if (!rate_limit_check('voice:' . $userId, 20, 86400)) {
        json_error('Ya usaste el micrófono varias veces hoy. Intenta más tarde o escribe los ingredientes.', 429);
    }
    if (!isset($_FILES['audio']) || $_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
        json_error('No se pudo recibir el audio. Intenta de nuevo.');
    }
    $file = $_FILES['audio'];
    if ($file['size'] > 8 * 1024 * 1024) {
        json_error('El audio es muy largo. Intenta un mensaje más corto.');
    }

    $transcript = groq_transcribe_audio($file['tmp_name'], $file['name'] ?: 'audio.webm', $file['type'] ?: 'audio/webm');
    if ($transcript === null) {
        json_error('No pudimos entender el audio. Intenta de nuevo o escribe los ingredientes.');
    }
    $items = groq_extract_items_from_text($transcript);
    if (empty($items)) {
        json_error('No identificamos ingredientes en el audio. Intenta hablar más claro.');
    }
    $items = array_values(array_unique(array_map(fn($i) => clean_text($i, 60), $items)));
    json_response(['ok' => true, 'transcript' => $transcript, 'items' => array_slice($items, 0, 30)]);
}

// ---------- Ingresar mercado con foto de la factura ----------
if ($action === 'photo' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!groq_available()) {
        json_error('Esta función necesita la IA activada. Por ahora escribe los ingredientes.');
    }
    if (!rate_limit_check('photo:' . $userId, 20, 86400)) {
        json_error('Ya escaneaste varias facturas hoy. Intenta más tarde o escribe los ingredientes.', 429);
    }
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        json_error('No se pudo recibir la foto. Intenta de nuevo.');
    }
    $file = $_FILES['photo'];
    if ($file['size'] > 8 * 1024 * 1024) {
        json_error('La foto es muy pesada. Intenta con otra o con mejor luz.');
    }
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowedMimes, true)) {
        json_error('Formato de imagen no soportado. Usa JPG, PNG o WEBP.');
    }

    $bytes = file_get_contents($file['tmp_name']);
    $dataUri = 'data:' . $file['type'] . ';base64,' . base64_encode($bytes);
    $items = groq_extract_items_from_image($dataUri);
    if (empty($items)) {
        json_error('No pudimos leer la factura. Intenta con más luz o de más cerca.');
    }
    $items = array_values(array_unique(array_map(fn($i) => clean_text($i, 60), $items)));
    json_response(['ok' => true, 'items' => array_slice($items, 0, 40)]);
}

// ---------- Marcar receta como hecha (descuenta de la despensa) ----------
if ($action === 'consume_recipe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $recipeId = (int)($in['recipe_id'] ?? 0);
    if ($recipeId <= 0) {
        json_error('Receta no válida.');
    }
    $consumed = consume_recipe_from_pantry($userId, $recipeId);
    json_response(['ok' => true, 'consumed' => $consumed, 'items' => load_pantry($userId)]);
}

json_error('Acción no reconocida.', 404);
