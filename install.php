<?php
/**
 * MenúVital — Instalador (ejecutar UNA sola vez)
 * Uso: https://tudominio.com/install.php?key=TU_INSTALL_KEY
 * Crea las tablas, carga las recetas y crea la cuenta de administradora.
 * Es seguro volver a ejecutarlo: no borra ni duplica nada.
 */

if (!file_exists(__DIR__ . '/includes/config.php')) {
    http_response_code(500);
    exit('Falta includes/config.php — copia config.sample.php como config.php y llena tus datos.');
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$key = $_GET['key'] ?? '';
if (!defined('INSTALL_KEY') || INSTALL_KEY === '' || !is_string($key) || !hash_equals(INSTALL_KEY, $key)) {
    http_response_code(403);
    exit('Acceso denegado. Usa install.php?key=TU_INSTALL_KEY (la clave que pusiste en config.php).');
}

$pdo = db();
$isMysql = (DB_DRIVER !== 'sqlite');
$ai   = $isMysql ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
$tail = $isMysql ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci' : '';
$log  = [];

$tables = [
    'users' => "CREATE TABLE IF NOT EXISTS users (
        id $ai,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(190) NULL UNIQUE,
        password_hash VARCHAR(255) NULL,
        is_admin TINYINT NOT NULL DEFAULT 0,
        is_blocked TINYINT NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL
    )$tail",
    'activation_codes' => "CREATE TABLE IF NOT EXISTS activation_codes (
        id $ai,
        code_hash CHAR(64) NOT NULL UNIQUE,
        batch_label VARCHAR(100) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL,
        used_by INT NULL,
        used_at DATETIME NULL,
        is_active TINYINT NOT NULL DEFAULT 1
    )$tail",
    'profiles' => "CREATE TABLE IF NOT EXISTS profiles (
        user_id INT PRIMARY KEY,
        allergies TEXT,
        dislikes TEXT,
        favorites TEXT,
        goal VARCHAR(50) NOT NULL DEFAULT 'balance',
        people INT NOT NULL DEFAULT 1,
        meals_per_day INT NOT NULL DEFAULT 3,
        height_cm INT NULL,
        starting_weight DECIMAL(5,2) NULL,
        sex VARCHAR(1) NULL,
        age INT NULL,
        updated_at DATETIME NOT NULL
    )$tail",
    'recipes' => "CREATE TABLE IF NOT EXISTS recipes (
        id $ai,
        name VARCHAR(150) NOT NULL,
        meal_type VARCHAR(20) NOT NULL,
        ingredients TEXT NOT NULL,
        steps TEXT NOT NULL,
        tags TEXT NOT NULL,
        kcal INT NOT NULL DEFAULT 0,
        protein INT NOT NULL DEFAULT 0,
        time_min INT NOT NULL DEFAULT 0,
        carbs INT NULL,
        fat INT NULL,
        sugar INT NULL,
        fiber INT NULL,
        image_url TEXT NULL,
        user_id INT NULL
    )$tail",
    'pantry_items' => "CREATE TABLE IF NOT EXISTS pantry_items (
        id $ai,
        user_id INT NOT NULL,
        item VARCHAR(100) NOT NULL,
        quantity VARCHAR(60) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL,
        CONSTRAINT uq_pantry UNIQUE (user_id, item)
    )$tail",
    'meal_plans' => "CREATE TABLE IF NOT EXISTS meal_plans (
        id $ai,
        user_id INT NOT NULL,
        plan_type VARCHAR(10) NOT NULL,
        start_date VARCHAR(10) NOT NULL,
        plan_json TEXT NOT NULL,
        created_at DATETIME NOT NULL
    )$tail",
    'progress_logs' => "CREATE TABLE IF NOT EXISTS progress_logs (
        id $ai,
        user_id INT NOT NULL,
        log_date VARCHAR(10) NOT NULL,
        weight DECIMAL(5,2) NULL,
        water INT NOT NULL DEFAULT 0,
        habits TEXT,
        note VARCHAR(300) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL,
        CONSTRAINT uq_progress UNIQUE (user_id, log_date)
    )$tail",
    'chat_messages' => "CREATE TABLE IF NOT EXISTS chat_messages (
        id $ai,
        user_id INT NOT NULL,
        role VARCHAR(10) NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME NOT NULL
    )$tail",
    'rate_limits' => "CREATE TABLE IF NOT EXISTS rate_limits (
        bucket VARCHAR(140) PRIMARY KEY,
        window_start DATETIME NOT NULL,
        hits INT NOT NULL DEFAULT 1
    )$tail",
    'push_subscriptions' => "CREATE TABLE IF NOT EXISTS push_subscriptions (
        id $ai,
        user_id INT NOT NULL,
        endpoint_hash CHAR(64) NOT NULL,
        endpoint TEXT NOT NULL,
        p256dh VARCHAR(255) NOT NULL,
        auth VARCHAR(255) NOT NULL,
        created_at DATETIME NOT NULL,
        last_sent_at DATETIME NULL,
        CONSTRAINT uq_push_endpoint UNIQUE (user_id, endpoint_hash)
    )$tail",
    'favorite_recipes' => "CREATE TABLE IF NOT EXISTS favorite_recipes (
        id $ai,
        user_id INT NOT NULL,
        recipe_id INT NOT NULL,
        created_at DATETIME NOT NULL,
        CONSTRAINT uq_favorite UNIQUE (user_id, recipe_id)
    )$tail",
    'menu_entries' => "CREATE TABLE IF NOT EXISTS menu_entries (
        id $ai,
        user_id INT NOT NULL,
        entry_date VARCHAR(10) NOT NULL,
        meal_type VARCHAR(20) NOT NULL,
        recipe_id INT NOT NULL,
        servings INT NOT NULL DEFAULT 1,
        done TINYINT NOT NULL DEFAULT 0,
        done_at DATETIME NULL,
        source VARCHAR(10) NOT NULL DEFAULT 'user',
        created_at DATETIME NOT NULL,
        CONSTRAINT uq_menu_slot UNIQUE (user_id, entry_date, meal_type)
    )$tail",
    'code_devices' => "CREATE TABLE IF NOT EXISTS code_devices (
        id $ai,
        code_id INT NOT NULL,
        user_id INT NOT NULL,
        device_token_hash CHAR(64) NOT NULL UNIQUE,
        device_label VARCHAR(120) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL,
        last_seen_at DATETIME NULL
    )$tail",
];

foreach ($tables as $name => $sql) {
    $pdo->exec($sql);
    $log[] = "Tabla lista: $name";
}

// ---------- Migración: agrega columnas nuevas a tablas ya existentes ----------
function column_exists(PDO $pdo, bool $isMysql, string $table, string $column): bool {
    if ($isMysql) {
        $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM information_schema.columns
                                WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?');
        $stmt->execute([$table, $column]);
    } else {
        $stmt = $pdo->query("PRAGMA table_info($table)");
        foreach ($stmt->fetchAll() as $col) {
            if ($col['name'] === $column) return true;
        }
        return false;
    }
    return (int)$stmt->fetch()['c'] > 0;
}

/** ¿La columna admite NULL? (para saber si ya se migró a acceso solo con código) */
function column_nullable(PDO $pdo, bool $isMysql, string $table, string $column): bool {
    if ($isMysql) {
        $stmt = $pdo->prepare('SELECT IS_NULLABLE FROM information_schema.columns
                                WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?');
        $stmt->execute([$table, $column]);
        $row = $stmt->fetch();
        return $row && $row['IS_NULLABLE'] === 'YES';
    }
    $stmt = $pdo->query("PRAGMA table_info($table)");
    foreach ($stmt->fetchAll() as $col) {
        if ($col['name'] === $column) {
            return (int)$col['notnull'] === 0;
        }
    }
    return false;
}

// Migración: permite acceso solo con código (sin correo/contraseña).
if (!column_nullable($pdo, $isMysql, 'users', 'email')) {
    if ($isMysql) {
        $pdo->exec('ALTER TABLE users MODIFY email VARCHAR(190) NULL');
        $pdo->exec('ALTER TABLE users MODIFY password_hash VARCHAR(255) NULL');
    } else {
        // SQLite no soporta MODIFY COLUMN: se reconstruye la tabla dentro de una transacción.
        $pdo->beginTransaction();
        $pdo->exec("CREATE TABLE users_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(190) NULL,
            password_hash VARCHAR(255) NULL,
            is_admin TINYINT NOT NULL DEFAULT 0,
            is_blocked TINYINT NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL
        )");
        $pdo->exec('INSERT INTO users_new SELECT id, name, email, password_hash, is_admin, is_blocked, created_at FROM users');
        $pdo->exec('DROP TABLE users');
        $pdo->exec('ALTER TABLE users_new RENAME TO users');
        $pdo->exec('CREATE UNIQUE INDEX IF NOT EXISTS uq_users_email ON users(email)');
        $pdo->commit();
    }
    $log[] = 'Migración: users.email y password_hash ahora admiten NULL (acceso solo con código)';
}

if (!column_exists($pdo, $isMysql, 'activation_codes', 'is_active')) {
    $pdo->exec('ALTER TABLE activation_codes ADD COLUMN is_active TINYINT NOT NULL DEFAULT 1');
    $log[] = 'Migración: columna is_active agregada a activation_codes';
}
if (!column_exists($pdo, $isMysql, 'activation_codes', 'code_plain')) {
    // Guarda el código en texto plano (además del hash) para poder verlo siempre
    // en el panel de administración. Los códigos generados ANTES de esta
    // columna no se pueden recuperar (el hash es de un solo sentido).
    $pdo->exec('ALTER TABLE activation_codes ADD COLUMN code_plain VARCHAR(20) NULL');
    $log[] = 'Migración: columna code_plain agregada a activation_codes';
}
foreach ([
    'favorites' => 'ALTER TABLE profiles ADD COLUMN favorites TEXT',
    'height_cm' => 'ALTER TABLE profiles ADD COLUMN height_cm INT NULL',
    'starting_weight' => 'ALTER TABLE profiles ADD COLUMN starting_weight DECIMAL(5,2) NULL',
    'sex' => 'ALTER TABLE profiles ADD COLUMN sex VARCHAR(1) NULL',
    'age' => 'ALTER TABLE profiles ADD COLUMN age INT NULL',
] as $col => $alterSql) {
    if (!column_exists($pdo, $isMysql, 'profiles', $col)) {
        $pdo->exec($alterSql);
        $log[] = "Migración: columna $col agregada a profiles";
    }
}
foreach ([
    'carbs' => 'ALTER TABLE recipes ADD COLUMN carbs INT NULL',
    'fat' => 'ALTER TABLE recipes ADD COLUMN fat INT NULL',
    'sugar' => 'ALTER TABLE recipes ADD COLUMN sugar INT NULL',
    'fiber' => 'ALTER TABLE recipes ADD COLUMN fiber INT NULL',
    'image_url' => 'ALTER TABLE recipes ADD COLUMN image_url TEXT NULL',
    'user_id' => 'ALTER TABLE recipes ADD COLUMN user_id INT NULL',
] as $col => $alterSql) {
    if (!column_exists($pdo, $isMysql, 'recipes', $col)) {
        $pdo->exec($alterSql);
        $log[] = "Migración: columna $col agregada a recipes";
    }
}
if (!column_exists($pdo, $isMysql, 'users', 'is_blocked')) {
    $pdo->exec('ALTER TABLE users ADD COLUMN is_blocked TINYINT NOT NULL DEFAULT 0');
    $log[] = 'Migración: columna is_blocked agregada a users';
}
if (!column_exists($pdo, $isMysql, 'pantry_items', 'quantity')) {
    $pdo->exec("ALTER TABLE pantry_items ADD COLUMN quantity VARCHAR(60) NOT NULL DEFAULT ''");
    $log[] = 'Migración: columna quantity agregada a pantry_items';
}

// ---------- Cargar recetas (solo si la tabla está vacía) ----------
$count = (int)$pdo->query('SELECT COUNT(*) AS c FROM recipes')->fetch()['c'];
if ($count === 0) {
    $recipes = require __DIR__ . '/database/recipes_data.php';
    $stmt = $pdo->prepare('INSERT INTO recipes (name, meal_type, ingredients, steps, tags, kcal, protein, time_min, carbs, fat, sugar, fiber, image_url)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $pdo->beginTransaction();
    foreach ($recipes as $r) {
        [$name, $type, $ingredients, $steps, $tags, $kcal, $protein, $time, $carbs, $fat, $sugar, $fiber, $image] = $r + [12 => null];
        $stmt->execute([
            $name, $type,
            json_encode($ingredients, JSON_UNESCAPED_UNICODE),
            json_encode($steps, JSON_UNESCAPED_UNICODE),
            json_encode($tags, JSON_UNESCAPED_UNICODE),
            $kcal, $protein, $time, $carbs, $fat, $sugar, $fiber, $image,
        ]);
    }
    $pdo->commit();
    $log[] = 'Recetas cargadas: ' . count($recipes);
} else {
    $log[] = "Recetas ya existentes: $count (no se duplican)";

    // Inserta recetas oficiales nuevas de database/recipes_data.php que aún no
    // existen en la tabla (comparando por nombre), sin tocar las que ya están.
    $recipes = require __DIR__ . '/database/recipes_data.php';
    $existingNames = $pdo->query('SELECT name FROM recipes WHERE user_id IS NULL')->fetchAll(PDO::FETCH_COLUMN);
    $existingNames = array_flip($existingNames);
    $insNew = $pdo->prepare('INSERT INTO recipes (name, meal_type, ingredients, steps, tags, kcal, protein, time_min, carbs, fat, sugar, fiber, image_url)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $newCount = 0;
    foreach ($recipes as $r) {
        [$name, $type, $ingredients, $steps, $tags, $kcal, $protein, $time, $carbs, $fat, $sugar, $fiber] = $r + [11 => null];
        $image = $r[12] ?? null;
        if (isset($existingNames[$name])) continue;
        $insNew->execute([
            $name, $type,
            json_encode($ingredients, JSON_UNESCAPED_UNICODE),
            json_encode($steps, JSON_UNESCAPED_UNICODE),
            json_encode($tags, JSON_UNESCAPED_UNICODE),
            $kcal, $protein, $time, $carbs, $fat, $sugar, $fiber, $image,
        ]);
        $newCount++;
    }
    if ($newCount > 0) {
        $log[] = "Recetas oficiales nuevas insertadas: $newCount";
    }

    // Backfill de nutrición (carbs/fat/sugar/fiber) para recetas que ya existían sin estos datos.
    $missing = (int)$pdo->query('SELECT COUNT(*) AS c FROM recipes WHERE carbs IS NULL')->fetch()['c'];
    if ($missing > 0) {
        $recipes = require __DIR__ . '/database/recipes_data.php';
        $upd = $pdo->prepare('UPDATE recipes SET carbs=?, fat=?, sugar=?, fiber=? WHERE name=? AND carbs IS NULL');
        $updated = 0;
        foreach ($recipes as $r) {
            [$name, , , , , , , , $carbs, $fat, $sugar, $fiber] = $r;
            $upd->execute([$carbs, $fat, $sugar, $fiber, $name]);
            $updated += $upd->rowCount();
        }
        $log[] = "Nutrición actualizada en $updated recetas existentes";
    }

    // Backfill de foto (image_url) para recetas que ya existían sin este dato.
    $missingImg = (int)$pdo->query('SELECT COUNT(*) AS c FROM recipes WHERE image_url IS NULL OR image_url = \'\'')->fetch()['c'];
    if ($missingImg > 0) {
        $recipes = require __DIR__ . '/database/recipes_data.php';
        $updImg = $pdo->prepare("UPDATE recipes SET image_url=? WHERE name=? AND (image_url IS NULL OR image_url = '')");
        $updatedImg = 0;
        foreach ($recipes as $r) {
            $name = $r[0];
            $image = $r[12] ?? null;
            if (!$image) continue;
            $updImg->execute([$image, $name]);
            $updatedImg += $updImg->rowCount();
        }
        $log[] = "Foto actualizada en $updatedImg recetas existentes";
    }

    // Resincroniza TODAS las fotos oficiales con database/recipes_data.php (incluye
    // las que ya tenían una foto, para aplicar correcciones). Solo si se pide con
    // ?resync_images=1, para no pisar datos sin querer en instalaciones normales.
    if (($_GET['resync_images'] ?? '') === '1') {
        $recipes = require __DIR__ . '/database/recipes_data.php';
        $updImg2 = $pdo->prepare('UPDATE recipes SET image_url=? WHERE name=? AND user_id IS NULL');
        $resynced = 0;
        foreach ($recipes as $r) {
            $name = $r[0];
            $image = $r[12] ?? null;
            if (!$image) continue;
            $updImg2->execute([$image, $name]);
            $resynced += $updImg2->rowCount();
        }
        $log[] = "Fotos resincronizadas (forzado): $resynced recetas";
    }
}

// ---------- Cuenta de administradora ----------
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([ADMIN_EMAIL]);
if (!$stmt->fetch()) {
    if (ADMIN_PASSWORD_INITIAL === 'CAMBIA-ESTA-CONTRASEÑA' || strlen(ADMIN_PASSWORD_INITIAL) < 8) {
        $log[] = 'ATENCIÓN: no se creó la cuenta admin — pon una ADMIN_PASSWORD_INITIAL segura (mínimo 8 caracteres) en config.php y vuelve a ejecutar.';
    } else {
        $pdo->prepare('INSERT INTO users (name, email, password_hash, is_admin, created_at) VALUES (?, ?, ?, 1, ?)')
            ->execute([ADMIN_NAME, ADMIN_EMAIL, password_hash(ADMIN_PASSWORD_INITIAL, PASSWORD_DEFAULT), db_now()]);
        $log[] = 'Cuenta de administradora creada: ' . ADMIN_EMAIL;
    }
} else {
    $log[] = 'Cuenta de administradora ya existe: ' . ADMIN_EMAIL;
}

// ---------- Resumen ----------
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>Instalación — MenúVital</title>
<style>body{font-family:system-ui,sans-serif;max-width:560px;margin:40px auto;padding:0 20px;color:#1f2937}
h1{color:#059669}li{margin:6px 0}code{background:#f3f4f6;padding:2px 6px;border-radius:4px}
.warn{background:#fef3c7;border-radius:8px;padding:12px 16px;margin-top:20px}</style></head>
<body>
<h1>✅ Instalación completada</h1>
<ul><?php foreach ($log as $line): ?><li><?= e($line) ?></li><?php endforeach; ?></ul>
<div class="warn"><strong>Importante:</strong> ya puedes entrar en <a href="/login.php">/login.php</a> con tu cuenta
de administradora y generar códigos en <a href="/admin/">/admin</a>. Cambia tu contraseña inicial y no compartas tu INSTALL_KEY.</div>
</body></html>
