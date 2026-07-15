<?php
/**
 * MenúVital — API de administración
 * Acciones: ?action=generate_codes | list_codes | list_users
 * Todas requieren sesión de administradora.
 */

require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
require_admin_api();

$action = $_GET['action'] ?? '';

if ($action === 'list_codes' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = db()->query('SELECT ac.id, ac.batch_label, ac.created_at, ac.used_at, u.name AS used_by_name, u.email AS used_by_email
                          FROM activation_codes ac
                          LEFT JOIN users u ON u.id = ac.used_by
                          ORDER BY ac.id DESC LIMIT 300');
    json_response(['ok' => true, 'codes' => $stmt->fetchAll()]);
}

if ($action === 'list_users' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = db()->query('SELECT id, name, email, is_admin, created_at FROM users ORDER BY id DESC LIMIT 300');
    json_response(['ok' => true, 'users' => $stmt->fetchAll()]);
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
