<?php
/**
 * MenúVital — API de administración
 * Acciones: ?action=generate_codes | list_codes | list_users | stats
 *          | toggle_code | delete_code | toggle_user_block
 * Todas requieren sesión de administradora.
 */

require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
require_admin_api();

$action = $_GET['action'] ?? '';

if ($action === 'stats' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $pdo = db();
    $users = (int)$pdo->query('SELECT COUNT(*) AS c FROM users WHERE is_admin = 0')->fetch()['c'];
    $total = (int)$pdo->query('SELECT COUNT(*) AS c FROM activation_codes')->fetch()['c'];
    $used = (int)$pdo->query('SELECT COUNT(*) AS c FROM activation_codes WHERE used_by IS NOT NULL')->fetch()['c'];
    $available = (int)$pdo->query('SELECT COUNT(*) AS c FROM activation_codes WHERE used_by IS NULL AND is_active = 1')->fetch()['c'];
    json_response(['ok' => true, 'stats' => [
        'users' => $users, 'codes_total' => $total, 'codes_available' => $available, 'codes_used' => $used,
    ]]);
}

if ($action === 'list_codes' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = db()->query('SELECT ac.id, ac.batch_label, ac.created_at, ac.used_at, ac.is_active,
                                 u.id AS used_by_id, u.name AS used_by_name, u.email AS used_by_email,
                                 u.is_blocked AS used_by_blocked
                          FROM activation_codes ac
                          LEFT JOIN users u ON u.id = ac.used_by
                          ORDER BY ac.id DESC LIMIT 300');
    json_response(['ok' => true, 'codes' => $stmt->fetchAll()]);
}

if ($action === 'list_users' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = db()->query('SELECT id, name, email, is_admin, is_blocked, created_at FROM users ORDER BY id DESC LIMIT 300');
    json_response(['ok' => true, 'users' => $stmt->fetchAll()]);
}

if ($action === 'toggle_user_block' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $id = (int)($in['id'] ?? 0);
    $pdo = db();
    $stmt = $pdo->prepare('SELECT is_admin, is_blocked FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        json_error('Esa usuaria no existe.', 404);
    }
    if ((int)$row['is_admin'] === 1) {
        json_error('No puedes bloquear una cuenta de administradora.');
    }
    $newState = (int)$row['is_blocked'] === 1 ? 0 : 1;
    $pdo->prepare('UPDATE users SET is_blocked = ? WHERE id = ?')->execute([$newState, $id]);
    json_response(['ok' => true, 'is_blocked' => $newState]);
}

if ($action === 'toggle_code' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $id = (int)($in['id'] ?? 0);
    $pdo = db();
    $stmt = $pdo->prepare('SELECT used_by, is_active FROM activation_codes WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        json_error('Ese código no existe.', 404);
    }
    if ($row['used_by'] !== null) {
        json_error('Ese código ya fue usado y no se puede desactivar.');
    }
    $newState = (int)$row['is_active'] === 1 ? 0 : 1;
    $pdo->prepare('UPDATE activation_codes SET is_active = ? WHERE id = ?')->execute([$newState, $id]);
    json_response(['ok' => true, 'is_active' => $newState]);
}

if ($action === 'delete_code' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $id = (int)($in['id'] ?? 0);
    $pdo = db();
    $stmt = $pdo->prepare('SELECT used_by FROM activation_codes WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        json_error('Ese código no existe.', 404);
    }
    if ($row['used_by'] !== null) {
        json_error('Ese código ya fue usado y no se puede eliminar.');
    }
    $pdo->prepare('DELETE FROM activation_codes WHERE id = ?')->execute([$id]);
    json_response(['ok' => true]);
}

if ($action === 'generate_codes' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    $in = json_input();
    $count = (int)($in['count'] ?? 1);
    $label = clean_text($in['label'] ?? '', 100);

    if ($count < 1 || $count > 200) {
        json_error('Genera entre 1 y 200 códigos a la vez.');
    }

    $pdo = db();
    $stmt = $pdo->prepare('INSERT INTO activation_codes (code_hash, batch_label, created_at) VALUES (?, ?, ?)');
    $generated = [];
    $pdo->beginTransaction();
    for ($i = 0; $i < $count; $i++) {
        // Reintenta si por azar colisiona un hash (extremadamente improbable)
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $code = generate_activation_code();
            try {
                $stmt->execute([activation_code_hash($code), $label, db_now()]);
                $generated[] = $code;
                break;
            } catch (PDOException $e) {
                continue;
            }
        }
    }
    $pdo->commit();

    json_response(['ok' => true, 'codes' => $generated]);
}

json_error('Acción no reconocida.', 404);
