<?php
/**
 * MenúVital — Conexión a base de datos (PDO)
 * Soporta MySQL (producción en Hostinger) y SQLite (desarrollo local).
 */

require_once __DIR__ . '/config.php';

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
