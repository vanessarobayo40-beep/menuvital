<?php
/**
 * MenúVital — Backfill de fotos reales (ejecutar cuando haga falta, no es de una sola vez)
 * Uso: https://tudominio.com/backfill_photos.php?key=TU_INSTALL_KEY
 *
 * Busca las recetas oficiales sin foto guardada (image_url vacío) y les asigna
 * una foto real de Pexels: Groq sugiere un término de búsqueda en inglés y
 * Pexels devuelve la foto. Si Groq o Pexels no están configurados o fallan
 * para una receta puntual, esa receta se deja para el siguiente intento —
 * mientras tanto sigue mostrando la imagen generada por IA de respaldo
 * (recipe_image_url() en includes/planner.php), nunca queda sin foto.
 *
 * Procesa un lote por visita (para no agotar el tiempo de ejecución del
 * servidor) — si quedan pendientes, solo recarga la misma URL para seguir.
 */

if (!file_exists(__DIR__ . '/includes/config.php')) {
    http_response_code(500);
    exit('Falta includes/config.php.');
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/groq.php';

$key = $_GET['key'] ?? '';
if (!defined('INSTALL_KEY') || INSTALL_KEY === '' || !is_string($key) || !hash_equals(INSTALL_KEY, $key)) {
    http_response_code(403);
    exit('Acceso denegado. Usa backfill_photos.php?key=TU_INSTALL_KEY');
}

header('Content-Type: text/html; charset=utf-8');

if (!groq_available() || !pexels_available()) {
    echo '<p>Falta configurar GROQ_API_KEY y/o PEXELS_API_KEY en config.php.</p>';
    exit;
}

// ---------- Modo diagnóstico: ?debug=1 — una llamada cruda a cada API,
// mostrando código HTTP y respuesta completa, para ver el error real
// cuando el backfill normal da "sin resultado" en todo. No toca la BD. ----------
if (($_GET['debug'] ?? '') === '1') {
    echo '<h2>Diagnóstico Groq</h2>';
    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . GROQ_API_KEY],
        CURLOPT_POSTFIELDS => json_encode([
            'model' => GROQ_MODEL,
            'messages' => [['role' => 'user', 'content' => 'Di solo la palabra: prueba']],
            'max_tokens' => 10,
        ]),
        CURLOPT_TIMEOUT => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $body = curl_exec($ch);
    $err = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    echo '<p>Modelo configurado: <code>' . htmlspecialchars(GROQ_MODEL) . '</code></p>';
    echo "<p>HTTP status: <strong>$status</strong>" . ($err ? " — curl error: $err" : '') . '</p>';
    echo '<pre style="white-space:pre-wrap;background:#f3f4f6;padding:10px;border-radius:6px;">'
        . htmlspecialchars(substr((string)$body, 0, 1500)) . '</pre>';

    echo '<h2>Diagnóstico Pexels</h2>';
    $ch2 = curl_init('https://api.pexels.com/v1/search?' . http_build_query(['query' => 'breakfast plate', 'per_page' => 1]));
    curl_setopt_array($ch2, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: ' . PEXELS_API_KEY],
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
    ]);
    $body2 = curl_exec($ch2);
    $err2 = curl_error($ch2);
    $status2 = curl_getinfo($ch2, CURLINFO_RESPONSE_CODE);
    curl_close($ch2);
    echo "<p>HTTP status: <strong>$status2</strong>" . ($err2 ? " — curl error: $err2" : '') . '</p>';
    echo '<pre style="white-space:pre-wrap;background:#f3f4f6;padding:10px;border-radius:6px;">'
        . htmlspecialchars(substr((string)$body2, 0, 1500)) . '</pre>';
    exit;
}

$pdo = db();
$stmt = $pdo->query(
    "SELECT id, name, meal_type, tags FROM recipes
     WHERE user_id IS NULL AND (image_url IS NULL OR image_url = '')
     ORDER BY id"
);
$pending = $stmt->fetchAll();

$BATCH_LIMIT = 30;       // recetas por visita
$TIME_BUDGET = 45;       // segundos, deja margen frente al límite del servidor
$start = microtime(true);

$found = 0;
$notFound = 0;
$processed = 0;
$upd = $pdo->prepare('UPDATE recipes SET image_url = ? WHERE id = ?');

foreach ($pending as $r) {
    if ($processed >= $BATCH_LIMIT || (microtime(true) - $start) > $TIME_BUDGET) {
        break;
    }
    $processed++;

    // openai/gpt-oss-120b es un modelo "razonador": gasta tokens pensando
    // (campo "reasoning" de la respuesta) antes de escribir el "content".
    // Con max_tokens bajo, se le acaba el presupuesto pensando y el content
    // vuelve vacío (finish_reason "length") — por eso 200 en vez de 25.
    $tags = json_decode($r['tags'], true) ?: [];
    $searchTerm = groq_chat([
        ['role' => 'system', 'content' => 'Da SOLO un término de búsqueda de 3 a 6 palabras en inglés '
            . '(sin comillas, sin explicación) para encontrar en un banco de fotos de stock una foto '
            . 'real y apetitosa de este plato de comida. Sé genérico (tipo de plato, no el nombre propio). '
            . 'Responde directo, sin pensarlo mucho.'],
        ['role' => 'user', 'content' => "Plato: {$r['name']} ({$r['meal_type']}, " . implode(', ', $tags) . ')'],
    ], false, 200, 0.4);

    $searchTerm = $searchTerm ? trim(preg_replace('/["\r\n]/', '', $searchTerm)) : '';
    $photo = $searchTerm !== '' ? pexels_search_photo($searchTerm) : null;

    if ($photo) {
        $upd->execute([$photo, $r['id']]);
        $found++;
        echo "✅ {$r['name']} — <code>{$searchTerm}</code><br>\n";
        @ob_flush(); @flush();
    } else {
        $notFound++;
        echo "— {$r['name']}: sin resultado, se reintenta después<br>\n";
        @ob_flush(); @flush();
    }
    usleep(300000); // 0.3s entre llamadas, no saturar las APIs gratuitas
}

$remaining = count($pending) - $processed;
echo "<hr><p><strong>Con foto nueva: $found</strong> — sin resultado esta vez: $notFound — procesadas: $processed</p>";
if ($remaining > 0) {
    echo "<p>Quedan <strong>$remaining</strong> recetas por revisar. Recarga esta misma página para seguir.</p>";
} else {
    echo '<p>✅ No quedan recetas oficiales sin foto.</p>';
}
