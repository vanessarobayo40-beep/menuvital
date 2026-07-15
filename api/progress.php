<?php
/**
 * MenúVital — API de registro de progreso
 * ?action=list (GET) | save (POST) | water (POST, +1 vaso rápido)
 */

require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_api();
$userId = (int)$user['id'];

const HABITS = ['verduras', 'ejercicio', 'dormir_bien', 'sin_gaseosa'];

function today_row(int $userId): ?array {
    $stmt = db()->prepare('SELECT * FROM progress_logs WHERE user_id = ? AND log_date = ?');
    $stmt->execute([$userId, date('Y-m-d')]);
    $row = $stmt->fetch();
    if ($row) {
        $row['habits'] = json_decode($row['habits'], true) ?: [];
    }
    return $row ?: null;
}

$action = $_GET['action'] ?? 'list';

if ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $since = date('Y-m-d', strtotime('-13 days'));
    $stmt = db()->prepare('SELECT log_date, weight, water, habits, note FROM progress_logs
                            WHERE user_id = ? AND log_date >= ? ORDER BY log_date ASC');
    $stmt->execute([$userId, $since]);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $r['habits'] = json_decode($r['habits'], true) ?: [];
        $r['weight'] = $r['weight'] !== null ? (float)$r['weight'] : null;
    }
    $today = today_row($userId);

    // Racha de días consecutivos con algún registro (hasta hoy)
    $streak = 0;
    $cursor = strtotime(date('Y-m-d'));
    $byDate = array_column($rows, null, 'log_date');
    while (isset($byDate[date('Y-m-d', $cursor)])) {
        $streak++;
        $cursor -= 86400;
    }

    json_response(['ok' => true, 'logs' => $rows, 'today' => $today, 'streak' => $streak, 'habit_keys' => HABITS]);
}

if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $weight = $in['weight'] ?? null;
    $weight = ($weight === null || $weight === '') ? null : max(20, min(300, (float)$weight));
    $water = max(0, min(20, (int)($in['water'] ?? 0)));
    $habits = array_values(array_intersect((array)($in['habits'] ?? []), HABITS));
    $note = clean_text($in['note'] ?? '', 300);

    $pdo = db();
    $today = date('Y-m-d');
    $existing = today_row($userId);
    if ($existing) {
        $pdo->prepare('UPDATE progress_logs SET weight=?, water=?, habits=?, note=? WHERE user_id=? AND log_date=?')
            ->execute([$weight, $water, json_encode($habits), $note, $userId, $today]);
    } else {
        $pdo->prepare('INSERT INTO progress_logs (user_id, log_date, weight, water, habits, note, created_at) VALUES (?,?,?,?,?,?,?)')
            ->execute([$userId, $today, $weight, $water, json_encode($habits), $note, db_now()]);
    }
    json_response(['ok' => true]);
}

if ($action === 'water' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $delta = (int)($in['delta'] ?? 1);
    $delta = max(-20, min(20, $delta));

    $pdo = db();
    $today = date('Y-m-d');
    $existing = today_row($userId);
    $newWater = max(0, min(20, ($existing['water'] ?? 0) + $delta));
    if ($existing) {
        $pdo->prepare('UPDATE progress_logs SET water=? WHERE user_id=? AND log_date=?')
            ->execute([$newWater, $userId, $today]);
    } else {
        $pdo->prepare('INSERT INTO progress_logs (user_id, log_date, water, habits, note, created_at) VALUES (?,?,?,?,?,?)')
            ->execute([$userId, $today, $newWater, json_encode([]), '', db_now()]);
    }
    json_response(['ok' => true, 'water' => $newWater]);
}

json_error('Acción no reconocida.', 404);
