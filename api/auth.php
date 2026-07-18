<?php
/**
 * MenúVital — API de autenticación
 * Acciones: ?action=login | register | logout
 */

require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Método no permitido.', 405);
}

// El registro y login son las únicas rutas sin sesión previa: no exigen CSRF
// de sesión autenticada, pero sí limitamos por IP para frenar fuerza bruta.

if ($action === 'login') {
    if (!rate_limit_check('login:' . client_ip(), 8, 900)) {
        json_error('Demasiados intentos. Espera unos minutos e intenta de nuevo.', 429);
    }

    $in = json_input();
    $email = clean_text($in['email'] ?? '', 190);
    $password = (string)($in['password'] ?? '');

    if (!valid_email($email) || $password === '') {
        json_error('Ingresa tu correo y contraseña.');
    }

    $stmt = db()->prepare('SELECT id, password_hash, is_admin, is_blocked FROM users WHERE email = ?');
    $stmt->execute([mb_strtolower($email)]);
    $row = $stmt->fetch();

    if (!$row || !password_verify($password, $row['password_hash'])) {
        json_error('Correo o contraseña incorrectos.', 401);
    }
    if ((int)$row['is_blocked'] === 1) {
        json_error('Tu cuenta fue desactivada. Contáctanos si crees que es un error.', 403);
    }

    login_user((int)$row['id']);
    json_response(['ok' => true, 'is_admin' => (int)$row['is_admin'] === 1]);
}

if ($action === 'register') {
    if (!rate_limit_check('register:' . client_ip(), 6, 3600)) {
        json_error('Demasiados intentos. Espera un momento e intenta de nuevo.', 429);
    }

    $in = json_input();
    $code = strtoupper(clean_text($in['code'] ?? '', 40));
    $name = clean_text($in['name'] ?? '', 100);
    $email = mb_strtolower(clean_text($in['email'] ?? '', 190));
    $password = (string)($in['password'] ?? '');

    if ($code === '') {
        json_error('Ingresa tu código de activación.');
    }
    if ($name === '' || mb_strlen($name) < 2) {
        json_error('Ingresa tu nombre.');
    }
    if (!valid_email($email)) {
        json_error('Ingresa un correo válido.');
    }
    if (mb_strlen($password) < 8) {
        json_error('La contraseña debe tener al menos 8 caracteres.');
    }

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $pdo->rollBack();
            json_error('Ese correo ya tiene una cuenta. Intenta iniciar sesión.');
        }

        $codeHash = activation_code_hash($code);
        $stmt = $pdo->prepare('SELECT id, used_by, is_active FROM activation_codes WHERE code_hash = ?');
        $stmt->execute([$codeHash]);
        $codeRow = $stmt->fetch();

        if (!$codeRow) {
            $pdo->rollBack();
            json_error('Ese código de activación no existe. Revísalo o contáctanos.');
        }
        if ($codeRow['used_by'] !== null) {
            $pdo->rollBack();
            json_error('Ese código ya fue usado. Si es un error, contáctanos.');
        }
        if ((int)$codeRow['is_active'] !== 1) {
            $pdo->rollBack();
            json_error('Ese código fue desactivado. Contáctanos para obtener uno nuevo.');
        }

        $pdo->prepare('INSERT INTO users (name, email, password_hash, is_admin, created_at) VALUES (?, ?, ?, 0, ?)')
            ->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), db_now()]);
        $userId = (int)$pdo->lastInsertId();

        $pdo->prepare('UPDATE activation_codes SET used_by = ?, used_at = ? WHERE id = ?')
            ->execute([$userId, db_now(), $codeRow['id']]);

        $pdo->prepare('INSERT INTO profiles (user_id, allergies, dislikes, goal, people, meals_per_day, updated_at)
                       VALUES (?, ?, ?, ?, ?, ?, ?)')
            ->execute([$userId, '', '', 'balance', 1, 3, db_now()]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        json_error('No pudimos crear tu cuenta. Intenta de nuevo.', 500);
    }

    login_user($userId);
    json_response(['ok' => true]);
}

if ($action === 'logout') {
    if (!csrf_verify()) {
        json_error('Token inválido.', 403);
    }
    logout_user();
    json_response(['ok' => true]);
}

json_error('Acción no reconocida.', 404);
