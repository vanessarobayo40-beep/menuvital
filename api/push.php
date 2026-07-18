<?php
/**
 * MenúVital — API de notificaciones push
 * ?action=vapid_key (GET, pública) | subscribe (POST) | unsubscribe (POST)
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/webpush.php';
secure_session_start();
send_security_headers();

$action = $_GET['action'] ?? '';

if ($action === 'vapid_key' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    json_response(['ok' => true, 'key' => defined('VAPID_PUBLIC_KEY') ? VAPID_PUBLIC_KEY : '']);
}

$user = require_login_api();
$userId = (int)$user['id'];

if ($action === 'subscribe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $endpoint = (string)($in['endpoint'] ?? '');
    $p256dh = (string)($in['p256dh'] ?? '');
    $auth = (string)($in['auth'] ?? '');
    if ($endpoint === '' || $p256dh === '' || $auth === '' || mb_strlen($endpoint) > 2000) {
        json_error('Suscripción no válida.');
    }

    $pdo = db();
    $hash = hash('sha256', $endpoint);
    $stmt = $pdo->prepare('SELECT id FROM push_subscriptions WHERE user_id = ? AND endpoint_hash = ?');
    $stmt->execute([$userId, $hash]);
    if (!$stmt->fetch()) {
        $pdo->prepare('INSERT INTO push_subscriptions (user_id, endpoint_hash, endpoint, p256dh, auth, created_at) VALUES (?,?,?,?,?,?)')
            ->execute([$userId, $hash, $endpoint, $p256dh, $auth, db_now()]);
    }
    json_response(['ok' => true]);
}

if ($action === 'unsubscribe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $endpoint = (string)($in['endpoint'] ?? '');
    $hash = hash('sha256', $endpoint);
    db()->prepare('DELETE FROM push_subscriptions WHERE user_id = ? AND endpoint_hash = ?')->execute([$userId, $hash]);
    json_response(['ok' => true]);
}

if ($action === 'status' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = db()->prepare('SELECT COUNT(*) AS c FROM push_subscriptions WHERE user_id = ?');
    $stmt->execute([$userId]);
    json_response(['ok' => true, 'subscribed' => (int)$stmt->fetch()['c'] > 0]);
}

if ($action === 'test' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!rate_limit_check('push_test:' . $userId, 10, 3600)) {
        json_error('Ya probaste varias veces. Espera un momento.', 429);
    }
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, endpoint FROM push_subscriptions WHERE user_id = ?');
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();
    if (!$rows) {
        json_error('No tienes recordatorios activados todavía.');
    }
    $sent = 0;
    foreach ($rows as $row) {
        $result = send_web_push($row['endpoint']);
        if ($result === 'ok') {
            $sent++;
            $pdo->prepare('UPDATE push_subscriptions SET last_sent_at = ? WHERE id = ?')->execute([db_now(), $row['id']]);
        } elseif ($result === 'expired') {
            $pdo->prepare('DELETE FROM push_subscriptions WHERE id = ?')->execute([$row['id']]);
        }
    }
    if ($sent === 0) {
        json_error('No pudimos enviar la notificación de prueba. Intenta desactivar y activar de nuevo los recordatorios.');
    }
    json_response(['ok' => true, 'sent' => $sent]);
}

json_error('Acción no reconocida.', 404);
