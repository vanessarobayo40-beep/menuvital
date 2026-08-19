<?php
/**
 * MenúVital — Autenticación y sesiones de usuario
 */

require_once __DIR__ . '/security.php';

const DEVICE_COOKIE = 'mvdevice';
const MAX_DEVICES_PER_CODE = 2;
// Sin esto, un celular prestado/robado o un computador compartido conservan
// acceso por los 2 años completos de la cookie de dispositivo, aunque nadie
// vuelva a usarlo. A los 90 días sin actividad, el dispositivo se trata como
// vencido y hay que volver a entrar con el código.
const SESSION_IDLE_TIMEOUT = 60 * 60 * 24 * 30; // sesión PHP: 30 días sin actividad
const DEVICE_IDLE_TIMEOUT = 60 * 60 * 24 * 90;  // cookie de dispositivo: 90 días sin uso

/** Usuario actual (o null si no hay sesión). Si no hay sesión pero sí una
 *  cookie de dispositivo válida (acceso solo con código), reingresa sola. */
function current_user(): ?array {
    static $user = false;
    if ($user !== false) {
        return $user;
    }
    secure_session_start();
    $uid = $_SESSION['uid'] ?? null;
    if ($uid && !empty($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_IDLE_TIMEOUT) {
        logout_user();
        $uid = null;
    }
    if (!$uid) {
        $device = find_device_by_cookie();
        if ($device === null) {
            return $user = null;
        }
        $uid = (int)$device['user_id'];
        login_user($uid);
    }
    $_SESSION['last_activity'] = time();
    $stmt = db()->prepare('SELECT id, name, email, is_admin, is_blocked, created_at FROM users WHERE id = ?');
    $stmt->execute([$uid]);
    $row = $stmt->fetch();
    if ($row && (int)$row['is_blocked'] === 1) {
        logout_user();
        return $user = null;
    }
    // Si la cuenta es de una usuaria (no admin) y su código fue desactivado,
    // la sesión se cierra de inmediato aunque ya estuviera abierta: desactivar
    // un código ya usado es ahora la forma de revocar el acceso.
    if ($row && (int)$row['is_admin'] !== 1 && !user_has_active_code((int)$row['id'])) {
        logout_user();
        return $user = null;
    }
    return $user = ($row ?: null);
}

/** ¿La usuaria tiene al menos un código de activación propio y activo? */
function user_has_active_code(int $userId): bool {
    $stmt = db()->prepare('SELECT 1 FROM activation_codes WHERE used_by = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([$userId]);
    return (bool)$stmt->fetchColumn();
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
    // Invalida también el dispositivo actual: si no se hace esto, cerrar
    // sesión "no funciona" en la práctica, porque current_user() vuelve a
    // reingresar sola con la cookie de dispositivo (mvdevice) en la
    // siguiente carga de /login.php. Al cerrar sesión explícitamente, el
    // dispositivo libera su cupo (de máximo 2 por código); si vuelve a
    // entrar con el código, se registra de nuevo.
    $token = $_COOKIE[DEVICE_COOKIE] ?? '';
    if (is_string($token) && $token !== '') {
        db()->prepare('DELETE FROM code_devices WHERE device_token_hash = ?')->execute([device_token_hash($token)]);
        setcookie(DEVICE_COOKIE, '', [
            'expires' => time() - 42000, 'path' => '/',
            'secure' => is_https(), 'httponly' => true, 'samesite' => 'Lax',
        ]);
    }
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

// ---------- Cifrado del código en claro (para poder mostrarlo en el panel) ----------
// El hash de arriba sirve para VALIDAR un código que la usuaria escribe, pero
// no permite recuperar el texto original — así que para poder reenviárselo
// por WhatsApp más adelante se guarda también una copia CIFRADA (no en texto
// plano) con la clave de includes/config.php. Si esa clave no está puesta,
// simplemente no se guarda copia legible — nunca se cae de vuelta a texto plano.

/** ¿Hay clave de cifrado configurada y disponible la extensión sodium? */
function code_encryption_available(): bool {
    return function_exists('sodium_crypto_secretbox')
        && defined('CODE_ENCRYPTION_KEY') && CODE_ENCRYPTION_KEY !== '';
}

function code_encryption_key(): ?string {
    // Usa SODIUM_CRYPTO_SECRETBOX_KEYBYTES, que solo existe con la extensión
    // sodium cargada — sin este guard, en un hosting sin sodium esto tira un
    // error fatal de "constante indefinida" en vez de devolver null.
    if (!function_exists('sodium_crypto_secretbox') || !defined('CODE_ENCRYPTION_KEY') || CODE_ENCRYPTION_KEY === '') {
        return null;
    }
    $key = base64_decode(CODE_ENCRYPTION_KEY, true);
    return ($key !== false && strlen($key) === SODIUM_CRYPTO_SECRETBOX_KEYBYTES) ? $key : null;
}

/** Cifra un código de activación para guardarlo. Devuelve null si no se puede cifrar. */
function encrypt_activation_code(string $code): ?string {
    if (!code_encryption_available()) {
        return null;
    }
    $key = code_encryption_key();
    if ($key === null) {
        return null;
    }
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $cipher = sodium_crypto_secretbox($code, $nonce, $key);
    return base64_encode($nonce . $cipher);
}

/** Descifra un código guardado con encrypt_activation_code(). Devuelve null si falla. */
function decrypt_activation_code(?string $encoded): ?string {
    if ($encoded === null || $encoded === '' || !code_encryption_available()) {
        return null;
    }
    $key = code_encryption_key();
    if ($key === null) {
        return null;
    }
    $raw = base64_decode($encoded, true);
    if ($raw === false || strlen($raw) <= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
        return null;
    }
    $nonce = substr($raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $cipher = substr($raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $plain = sodium_crypto_secretbox_open($cipher, $nonce, $key);
    return $plain === false ? null : $plain;
}

/**
 * Genera un código de activación corto y legible: MV-XXX-XXX (sin caracteres
 * confusos). Con este alfabeto de 33 caracteres, 6 posiciones dan más de
 * 1.200 millones de combinaciones — de sobra para resistir fuerza bruta
 * junto con el límite de intentos por IP, y mucho más fácil de escribir
 * a mano en el celular que el formato largo de antes.
 */
function generate_activation_code(): string {
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // sin 0/O ni 1/I
    $parts = [];
    for ($p = 0; $p < 2; $p++) {
        $chunk = '';
        for ($i = 0; $i < 3; $i++) {
            $chunk .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        $parts[] = $chunk;
    }
    return 'MV-' . implode('-', $parts);
}

// ---------- Acceso solo con código: dispositivos ----------

/** Hash del token de dispositivo (se guarda hasheado, nunca en claro). */
function device_token_hash(string $token): string {
    return hash('sha256', $token);
}

/** Envía la cookie de dispositivo (persistente, ~2 años) con un token dado. */
function set_device_cookie(string $token): void {
    setcookie(DEVICE_COOKIE, $token, [
        'expires' => time() + 60 * 60 * 24 * 365 * 2,
        'path' => '/',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

/** Crea un token de dispositivo nuevo, lo envía como cookie y lo devuelve en claro. */
function issue_device_cookie(): string {
    $token = bin2hex(random_bytes(32));
    set_device_cookie($token);
    return $token;
}

/** Registra un dispositivo nuevo para un código ya usado. */
function register_device(int $codeId, int $userId, string $token): void {
    $label = substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 120);
    db()->prepare('INSERT INTO code_devices (code_id, user_id, device_token_hash, device_label, created_at) VALUES (?, ?, ?, ?, ?)')
        ->execute([$codeId, $userId, device_token_hash($token), $label, db_now()]);
}

/** Busca el dispositivo asociado a la cookie mvdevice actual (si existe y el código sigue activo). */
function find_device_by_cookie(): ?array {
    $token = $_COOKIE[DEVICE_COOKIE] ?? '';
    if (!is_string($token) || $token === '') {
        return null;
    }
    $stmt = db()->prepare('SELECT cd.*, ac.is_active AS code_active FROM code_devices cd
                            JOIN activation_codes ac ON ac.id = cd.code_id
                            WHERE cd.device_token_hash = ?');
    $stmt->execute([device_token_hash($token)]);
    $row = $stmt->fetch();
    if (!$row || (int)$row['code_active'] !== 1) {
        return null;
    }
    $lastSeen = strtotime(($row['last_seen_at'] ?: $row['created_at']) . ' UTC') ?: 0;
    if ((time() - $lastSeen) > DEVICE_IDLE_TIMEOUT) {
        // Dispositivo inactivo hace más de 90 días: se borra su registro y se
        // exige volver a entrar con el código, en vez de seguir confiando en
        // una cookie que pudo quedar en un celular prestado o robado.
        db()->prepare('DELETE FROM code_devices WHERE id = ?')->execute([$row['id']]);
        return null;
    }
    db()->prepare('UPDATE code_devices SET last_seen_at = ? WHERE id = ?')->execute([db_now(), $row['id']]);
    return $row;
}

/** Cuántos dispositivos tiene ya registrados un código. */
function count_devices(int $codeId): int {
    $stmt = db()->prepare('SELECT COUNT(*) FROM code_devices WHERE code_id = ?');
    $stmt->execute([$codeId]);
    return (int)$stmt->fetchColumn();
}
