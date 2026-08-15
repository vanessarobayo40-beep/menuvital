<?php
/**
 * MenúVital — Conexión a base de datos (PDO)
 * Soporta MySQL (producción en Hostinger) y SQLite (desarrollo local).
 */

require_once __DIR__ . '/config.php';

// ---------- Manejo de errores en producción ----------
// Sin esto, un error de PHP o una excepción sin capturar (p. ej. si la base
// de datos no responde) puede imprimir el DSN, el usuario de MySQL o rutas
// del servidor directamente en lo que ve la usuaria — o un atacante.
if (defined('APP_ENV') && APP_ENV === 'production') {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED);
    if (!defined('MV_EXCEPTION_HANDLER_SET')) {
        define('MV_EXCEPTION_HANDLER_SET', true);
        set_exception_handler(function (Throwable $e) {
            error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            if (headers_sent()) {
                exit;
            }
            http_response_code(500);
            $isApi = isset($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], '/api/');
            if ($isApi) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['ok' => false, 'error' => 'Ocurrió un error interno. Intenta de nuevo en un momento.']);
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo '<!doctype html><meta charset="utf-8"><body style="font-family:system-ui,sans-serif;'
                    . 'text-align:center;padding:60px 20px;color:#333"><h1>Algo salió mal</h1>'
                    . '<p>Intenta de nuevo en un momento.</p></body>';
            }
            exit;
        });
    }
}

function db(): PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }
    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    if (DB_DRIVER === 'sqlite') {
        $pdo = new PDO('sqlite:' . DB_SQLITE_PATH, null, null, $opts);
        $pdo->exec('PRAGMA foreign_keys = ON');
        $pdo->exec('PRAGMA journal_mode = WAL');
    } else {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
    }
    return $pdo;
}

/** Fecha/hora actual en UTC para guardar en BD. */
function db_now(): string {
    return gmdate('Y-m-d H:i:s');
}
