<?php
/**
 * MenúVital — Envío periódico de recordatorios de agua
 * Lo dispara un Cron Job de Hostinger cada 2 horas.
 * Uso: cron_push.php?key=TU_CRON_KEY
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/webpush.php';

header('Content-Type: text/plain; charset=utf-8');

$key = $_GET['key'] ?? '';
if (!defined('CRON_KEY') || CRON_KEY === '' || !is_string($key) || !hash_equals(CRON_KEY, $key)) {
    http_response_code(403);
    exit('Acceso denegado.');
}

// Solo entre 7am y 9pm — nadie quiere un recordatorio de agua a medianoche.
$hour = (int)date('G');
if ($hour < 7 || $hour >= 21) {
    exit("Fuera de horario ($hour:00), no se envían recordatorios.\n");
}

$pdo = db();
// No reenviar a quien ya recibió uno hace menos de 90 minutos (protege contra
// un cron mal configurado que dispare más seguido de lo previsto).
$cutoff = gmdate('Y-m-d H:i:s', time() - 90 * 60);
$stmt = $pdo->prepare('SELECT id, endpoint FROM push_subscriptions WHERE last_sent_at IS NULL OR last_sent_at < ?');
$stmt->execute([$cutoff]);
$subs = $stmt->fetchAll();

$sent = 0;
$expired = 0;
$errors = 0;

foreach ($subs as $sub) {
    $result = send_web_push($sub['endpoint']);
    if ($result === 'ok') {
        $sent++;
        $pdo->prepare('UPDATE push_subscriptions SET last_sent_at = ? WHERE id = ?')->execute([db_now(), $sub['id']]);
    } elseif ($result === 'expired') {
        $expired++;
        $pdo->prepare('DELETE FROM push_subscriptions WHERE id = ?')->execute([$sub['id']]);
    } else {
        $errors++;
    }
}

echo "Suscripciones revisadas: " . count($subs) . "\n";
echo "Enviados: $sent\n";
echo "Expirados (borrados): $expired\n";
echo "Errores: $errors\n";
