<?php
/**
 * MenúVital — API del coach de nutrición (chat con IA)
 * ?action=history (GET) | send (POST)
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/groq.php';
secure_session_start();
send_security_headers();
$user = require_login_api();
$userId = (int)$user['id'];

const FALLBACK_REPLIES = [
    'Estoy teniendo un momentico de alta demanda 💚 Mientras tanto, un consejo que siempre sirve: toma un vaso de agua antes de cada comida y sirve porciones moderadas. Escríbeme de nuevo en unos minutos.',
    'Dame unos minuticos y vuelve a escribirme 🙏 Mientras tanto: intenta que la mitad de tu plato sean verduras y camina un poco después de comer.',
    'En este momento no puedo darte una respuesta completa, pero ya casi vuelvo 💪 Un tip rápido: dormir bien y comer despacio ayudan tanto como la comida misma.',
];

function coach_system_prompt(array $profile): string {
    $allergies = implode(', ', $profile['allergies_list']) ?: 'ninguna registrada';
    $dislikes = implode(', ', $profile['dislikes_list']) ?: 'ninguno registrado';
    $favorites = implode(', ', $profile['favorites_list'] ?? []) ?: 'ninguno registrado';
    $body = '';
    if (!empty($profile['height_cm'])) {
        $body .= 'Estatura: ' . (int)$profile['height_cm'] . ' cm. ';
    }
    if (!empty($profile['starting_weight'])) {
        $body .= 'Peso inicial registrado: ' . (float)$profile['starting_weight'] . ' kg. ';
    }
    if (!empty($profile['age'])) {
        $body .= 'Edad: ' . (int)$profile['age'] . ' años. ';
    }
    if (!empty($profile['kcal_target'])) {
        $body .= 'Su meta calórica diaria estimada es ' . (int)$profile['kcal_target'] . ' kcal — '
            . 'usa esto como referencia si te pregunta sobre porciones o cantidades, sin sonar obsesiva con el número. ';
    }
    return "Eres la coach de nutrición de la app MenúVital: colombiana, cálida, cercana y motivadora, "
        . "como una nutricionista de confianza que habla en español natural (no robótico). "
        . "Objetivo de la usuaria: " . goal_label($profile['goal']) . ". "
        . "Alergias: {$allergies}. No le gusta: {$dislikes}. Platos favoritos: {$favorites}. {$body}"
        . "Cocina para " . (int)$profile['people'] . " persona(s). "
        . "Da consejos prácticos, breves (máximo 4-5 líneas) y realistas con comida colombiana/latina de mercado, "
        . "nunca dietas extremas ni productos raros. "
        . "IMPORTANTE: nunca des diagnósticos médicos ni reemplaces a un profesional de la salud; "
        . "si la usuaria describe síntomas médicos o quiere perder mucho peso muy rápido, sugiere amablemente "
        . "consultar a un médico o nutricionista antes de continuar.";
}

$action = $_GET['action'] ?? 'history';

if ($action === 'history' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = db()->prepare('SELECT role, content, created_at FROM chat_messages WHERE user_id = ? ORDER BY id DESC LIMIT 40');
    $stmt->execute([$userId]);
    $rows = array_reverse($stmt->fetchAll());
    json_response(['ok' => true, 'messages' => $rows]);
}

if ($action === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        json_error('Token inválido. Recarga la página.', 403);
    }
    if (!rate_limit_check('coach:' . $userId, 40, 86400)) {
        json_error('Has hecho muchas preguntas hoy. Vuelve mañana para seguir charlando con tu coach.', 429);
    }

    $in = json_input();
    $message = clean_text($in['message'] ?? '', 600);
    if ($message === '') {
        json_error('Escribe tu pregunta primero.');
    }

    $pdo = db();
    $pdo->prepare('INSERT INTO chat_messages (user_id, role, content, created_at) VALUES (?, ?, ?, ?)')
        ->execute([$userId, 'user', $message, db_now()]);

    $profile = load_profile($userId);
    $stmt = $pdo->prepare('SELECT role, content FROM chat_messages WHERE user_id = ? ORDER BY id DESC LIMIT 10');
    $stmt->execute([$userId]);
    $recent = array_reverse($stmt->fetchAll());

    $messages = [['role' => 'system', 'content' => coach_system_prompt($profile)]];
    foreach ($recent as $m) {
        $messages[] = ['role' => $m['role'] === 'user' ? 'user' : 'assistant', 'content' => $m['content']];
    }

    $reply = groq_chat($messages, false, 400, 0.7);
    if ($reply === null) {
        $reply = FALLBACK_REPLIES[array_rand(FALLBACK_REPLIES)];
    }
    $reply = mb_substr(trim($reply), 0, 1000);

    $pdo->prepare('INSERT INTO chat_messages (user_id, role, content, created_at) VALUES (?, ?, ?, ?)')
        ->execute([$userId, 'assistant', $reply, db_now()]);

    json_response(['ok' => true, 'reply' => $reply]);
}

json_error('Acción no reconocida.', 404);
