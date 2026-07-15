<?php
/**
 * MenúVital — Capa de seguridad
 * Sesiones endurecidas, CSRF, rate limiting, headers y helpers.
 */

require_once __DIR__ . '/db.php';

// ---------- Sesión ----------

function is_https(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function secure_session_start(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_set_cookie_params([
        'lifetime' => 60 * 60 * 24 * 30, // 30 días
        'path'     => '/',
        'secure'   => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('mvsession');
    session_start();
}

// ---------- Headers de seguridad ----------
// .htaccess ya los envía en Apache; aquí se refuerzan para que también
// apliquen en el servidor local de pruebas (php -S no lee .htaccess).

function send_security_headers(): void {
    if (headers_sent()) {
        return;
    }
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// ---------- CSRF ----------

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_verify(): bool {
    $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf'] ?? '');
    return !empty($_SESSION['csrf']) && is_string($sent) && hash_equals($_SESSION['csrf'], $sent);
}

// ---------- Helpers ----------

/** Escape para HTML. Usar SIEMPRE al imprimir datos de usuario. */
function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function client_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

/** Lee el body JSON de la petición. */
function json_input(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/** Responde JSON y termina. */
function json_response(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error(string $message, int $status = 400): void {
    json_response(['ok' => false, 'error' => $message], $status);
}

// ---------- Rate limiting (ventana fija, en BD) ----------

/**
 * Devuelve true si la acción está permitida; false si superó el límite.
 * $bucket ej: "login:1.2.3.4", "code:1.2.3.4", "ai:user:5"
 */
function rate_limit_check(string $bucket, int $max, int $windowSec): bool {
    $pdo = db();
    $now = time();
    $stmt = $pdo->prepare('SELECT window_start, hits FROM rate_limits WHERE bucket = ?');
    $stmt->execute([$bucket]);
    $row = $stmt->fetch();

    if ($row) {
        $start = strtotime($row['window_start'] . ' UTC');
        if (($now - $start) < $windowSec) {
            if ((int)$row['hits'] >= $max) {
                return false;
            }
            $pdo->prepare('UPDATE rate_limits SET hits = hits + 1 WHERE bucket = ?')->execute([$bucket]);
            return true;
        }
        $pdo->prepare('UPDATE rate_limits SET window_start = ?, hits = 1 WHERE bucket = ?')
            ->execute([gmdate('Y-m-d H:i:s', $now), $bucket]);
        return true;
    }

    try {
        $pdo->prepare('INSERT INTO rate_limits (bucket, window_start, hits) VALUES (?, ?, 1)')
            ->execute([$bucket, gmdate('Y-m-d H:i:s', $now)]);
    } catch (PDOException $e) {
        // Carrera: otra petición lo insertó al mismo tiempo. No es grave.
    }
    return true;
}

// ---------- Validaciones comunes ----------

function valid_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false && mb_strlen($email) <= 190;
}

/** Limpia un texto corto de usuario: sin tags, espacios normalizados, largo máximo. */
function clean_text(?string $s, int $maxLen = 200): string {
    $s = strip_tags((string)$s);
    $s = preg_replace('/\s+/u', ' ', $s);
    return mb_substr(trim($s), 0, $maxLen);
}
