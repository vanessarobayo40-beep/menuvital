<?php
/**
 * MenúVital — Autenticación y sesiones de usuario
 */

require_once __DIR__ . '/security.php';

/** Usuario actual (o null si no hay sesión). */
function current_user(): ?array {
    static $user = false;
    if ($user !== false) {
        return $user;
    }
    secure_session_start();
    $uid = $_SESSION['uid'] ?? null;
    if (!$uid) {
        return $user = null;
    }
    $stmt = db()->prepare('SELECT id, name, email, is_admin, is_blocked, created_at FROM users WHERE id = ?');
    $stmt->execute([$uid]);
    $row = $stmt->fetch();
    if ($row && (int)$row['is_blocked'] === 1) {
        logout_user();
        return $user = null;
    }
    return $user = ($row ?: null);
}

/** Para PÁGINAS: redirige al login si no hay sesión. */
function require_login_page(): array {
    $user = current_user();
    if (!$user) {
        header('Location: /login.php');
        exit;
    }
    return $user;
}

/** Para APIs: responde 401 JSON si no hay sesión. */
function require_login_api(): array {
    $user = current_user();
    if (!$user) {
        json_error('Tu sesión expiró. Vuelve a entrar.', 401);
    }
    return $user;
}

/** Para APIs de administración. */
function require_admin_api(): array {
    $user = require_login_api();
    if ((int)$user['is_admin'] !== 1) {
        json_error('No tienes permisos para esto.', 403);
    }
    return $user;
}

/** Para la página de administración. */
function require_admin_page(): array {
    $user = require_login_page();
    if ((int)$user['is_admin'] !== 1) {
        header('Location: /app/index.php');
        exit;
    }
    return $user;
}

/** Inicia sesión para un usuario (regenera el ID de sesión). */
function login_user(int $userId): void {
    secure_session_start();
    session_regenerate_id(true);
    $_SESSION['uid'] = $userId;
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function logout_user(): void {
    secure_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/** Hash de un código de activación (se guardan hasheados, nunca en claro). */
function activation_code_hash(string $code): string {
    return hash('sha256', strtoupper(trim($code)));
}

/** Genera un código de activación legible: MV-XXXX-XXXX-XXXX (sin caracteres confusos). */
function generate_activation_code(): string {
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // sin 0/O ni 1/I
    $parts = [];
    for ($p = 0; $p < 3; $p++) {
        $chunk = '';
        for ($i = 0; $i < 4; $i++) {
            $chunk .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        $parts[] = $chunk;
    }
    return 'MV-' . implode('-', $parts);
}
