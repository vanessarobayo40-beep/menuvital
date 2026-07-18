<?php
/**
 * MenúVital — Cliente de IA (Groq, API compatible con OpenAI)
 * La API key vive SOLO en el servidor (config.php). Nunca llega al navegador.
 * Si no hay key o la API falla, las funciones devuelven null y la app
 * usa el motor de recetas como respaldo: nunca se queda muerta.
 */

require_once __DIR__ . '/config.php';

function groq_available(): bool {
    return defined('GROQ_API_KEY') && GROQ_API_KEY !== '';
}

/**
 * Llama al chat de Groq. $messages = [['role'=>'system','content'=>...], ...]
 * Devuelve el texto de la respuesta, o null si no hay key o hubo error.
 */
function groq_chat(array $messages, bool $jsonMode = false, int $maxTokens = 2048, float $temperature = 0.6): ?string {
    if (!groq_available()) {
        return null;
    }
    $payload = [
        'model'       => GROQ_MODEL,
        'messages'    => $messages,
        'max_tokens'  => $maxTokens,
        'temperature' => $temperature,
    ];
    if ($jsonMode) {
        $payload['response_format'] = ['type' => 'json_object'];
    }

    for ($attempt = 1; $attempt <= 2; $attempt++) {
        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . GROQ_API_KEY,
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT        => 45,
            CURLOPT_CONNECTTIMEOUT => 10,
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($body !== false && $status >= 200 && $status < 300) {
            $data = json_decode($body, true);
            $text = $data['choices'][0]['message']['content'] ?? null;
            if (is_string($text) && $text !== '') {
                return $text;
            }
        }
        // 429/5xx: pequeño respiro y un reintento
        if ($attempt === 1 && ($status === 429 || $status >= 500)) {
            usleep(800000);
            continue;
        }
        break;
    }
    return null;
}

/**
 * Igual que groq_chat pero espera JSON: decodifica y valida.
 * Devuelve el array decodificado o null.
 */
function groq_chat_json(array $messages, int $maxTokens = 4096): ?array {
    $text = groq_chat($messages, true, $maxTokens, 0.5);
    if ($text === null) {
        return null;
    }
    // Algunos modelos envuelven el JSON en ```...```
    $text = trim($text);
    $text = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $text);
    $data = json_decode($text, true);
    return is_array($data) ? $data : null;
}

/**
 * Transcribe un audio a texto (español) usando Whisper vía Groq.
 * Devuelve el texto transcrito, o null si no hay key o hubo error.
 */
function groq_transcribe_audio(string $tmpPath, string $filename, string $mimeType): ?string {
    if (!groq_available()) {
        return null;
    }
    $ch = curl_init('https://api.groq.com/openai/v1/audio/transcriptions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . GROQ_API_KEY],
        CURLOPT_POSTFIELDS     => [
            'file'            => new CURLFile($tmpPath, $mimeType, $filename),
            'model'           => 'whisper-large-v3-turbo',
            'language'        => 'es',
            'response_format' => 'json',
        ],
        CURLOPT_TIMEOUT        => 40,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($body === false || $status < 200 || $status >= 300) {
        return null;
    }
    $data = json_decode($body, true);
    $text = $data['text'] ?? null;
    return (is_string($text) && trim($text) !== '') ? trim($text) : null;
}

/** Extrae una lista de ingredientes de un texto libre (ej: transcripción de voz). */
function groq_extract_items_from_text(string $text): ?array {
    $result = groq_chat_json([
        ['role' => 'system', 'content' => 'Extraes nombres de alimentos/ingredientes de un texto en español dicho '
            . 'por voz para una app de menús. Responde SOLO JSON: {"items": ["item1", "item2", ...]}. '
            . 'Usa nombres simples y en singular (ej: "tomate" no "tomates", "huevo" no "huevos"). '
            . 'Ignora palabras que no sean alimentos o ingredientes de cocina.'],
        ['role' => 'user', 'content' => $text],
    ], 512);
    $items = $result['items'] ?? null;
    return is_array($items) ? array_values(array_filter(array_map('strval', $items))) : null;
}

/**
 * Extrae alimentos/ingredientes de una foto (ej: factura de mercado) con un modelo de visión.
 * $imageDataUri: "data:image/jpeg;base64,...."
 */
function groq_extract_items_from_image(string $imageDataUri): ?array {
    if (!groq_available()) {
        return null;
    }
    $payload = [
        'model' => 'qwen/qwen3.6-27b',
        'messages' => [
            ['role' => 'system', 'content' => 'Ves la foto de una factura o recibo de mercado colombiano. '
                . 'Identifica los alimentos e ingredientes comprados (ignora precios, totales, impuestos, el nombre '
                . 'del supermercado y productos que no sean comida). Responde SOLO JSON: '
                . '{"items": ["item1", "item2", ...]}. Usa nombres simples en español, singular y sin marcas '
                . '(ej: "arroz" no "Arroz Diana 500g").'],
            ['role' => 'user', 'content' => [
                ['type' => 'text', 'text' => 'Extrae los alimentos de esta factura.'],
                ['type' => 'image_url', 'image_url' => ['url' => $imageDataUri]],
            ]],
        ],
        'max_tokens' => 1024,
        'temperature' => 0.3,
        'response_format' => ['type' => 'json_object'],
    ];

    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Authorization: Bearer ' . GROQ_API_KEY],
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT        => 45,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($body === false || $status < 200 || $status >= 300) {
        return null;
    }
    $data = json_decode($body, true);
    $text = $data['choices'][0]['message']['content'] ?? null;
    if (!is_string($text)) {
        return null;
    }
    $text = preg_replace('/^```(?:json)?\s*|\s*```$/', '', trim($text));
    $parsed = json_decode($text, true);
    $items = $parsed['items'] ?? null;
    return is_array($items) ? array_values(array_filter(array_map('strval', $items))) : null;
}
