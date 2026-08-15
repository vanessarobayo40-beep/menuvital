<?php
/**
 * MenúVital — Envío de notificaciones Web Push (VAPID, sin cifrar contenido)
 *
 * Enviamos push "vacíos" (sin payload): el Service Worker muestra un mensaje
 * fijo al recibirlos. Esto es válido en el estándar Web Push y evita la parte
 * más propensa a errores de una implementación casera (cifrado AES-GCM del
 * contenido) — el recordatorio llega igual, aunque no se pueda personalizar
 * el texto por notificación.
 */

require_once __DIR__ . '/config.php';

function b64url_encode(string $bin): string {
    return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
}

/**
 * ¿Es un endpoint de push REAL de un navegador conocido? Sin esto, cualquier
 * persona logueada podría registrar como "endpoint" una URL interna
 * (127.0.0.1, la red privada de Hostinger, etc.) y usar /api/push.php?action=test
 * como oráculo para escanear esa red desde el servidor (SSRF). Solo se
 * aceptan los hosts que los navegadores usan de verdad para Web Push.
 */
function is_valid_push_endpoint(string $endpoint): bool {
    if (mb_strlen($endpoint) > 2000) {
        return false;
    }
    $scheme = parse_url($endpoint, PHP_URL_SCHEME);
    $host = parse_url($endpoint, PHP_URL_HOST);
    if ($scheme !== 'https' || !$host) {
        return false;
    }
    $host = strtolower($host);
    $allowedExact = [
        'fcm.googleapis.com',
        'android.googleapis.com',
        'updates.push.services.mozilla.com',
        'web.push.apple.com',
    ];
    if (in_array($host, $allowedExact, true)) {
        return true;
    }
    // Windows/Edge usa subdominios variables: wns2-xx1p.notify.windows.com
    if (preg_match('/^[a-z0-9-]+\.notify\.windows\.com$/', $host)) {
        return true;
    }
    return false;
}

/** Convierte una firma ECDSA en formato DER (la que da openssl_sign) al formato raw R||S de 64 bytes que exige JWS. */
function der_to_raw_ecdsa(string $der): string {
    $offset = 1; // salta 0x30 (SEQUENCE)
    $len = ord($der[$offset]);
    $offset++;
    if ($len & 0x80) {
        $numBytes = $len & 0x7F;
        $len = 0;
        for ($i = 0; $i < $numBytes; $i++) {
            $len = ($len << 8) | ord($der[$offset]);
            $offset++;
        }
    }
    $offset++; // salta 0x02 (INTEGER) de R
    $rLen = ord($der[$offset]); $offset++;
    $r = substr($der, $offset, $rLen); $offset += $rLen;
    $offset++; // salta 0x02 (INTEGER) de S
    $sLen = ord($der[$offset]); $offset++;
    $s = substr($der, $offset, $sLen);

    $r = ltrim($r, "\x00");
    $s = ltrim($s, "\x00");
    $r = str_pad($r, 32, "\x00", STR_PAD_LEFT);
    $s = str_pad($s, 32, "\x00", STR_PAD_LEFT);
    return $r . $s;
}

/** Construye el JWT firmado con ES256 que exige VAPID para un endpoint de push dado. */
function vapid_jwt(string $audience): ?string {
    if (!defined('VAPID_PRIVATE_KEY_PEM') || VAPID_PRIVATE_KEY_PEM === '') {
        return null;
    }
    $privPem = base64_decode(VAPID_PRIVATE_KEY_PEM);
    $key = openssl_pkey_get_private($privPem);
    if (!$key) {
        return null;
    }

    $header = b64url_encode(json_encode(['typ' => 'JWT', 'alg' => 'ES256']));
    $claims = b64url_encode(json_encode([
        'aud' => $audience,
        'exp' => time() + 12 * 3600,
        'sub' => 'mailto:' . VAPID_SUBJECT_EMAIL,
    ]));
    $signingInput = $header . '.' . $claims;

    $ok = openssl_sign($signingInput, $derSignature, $key, OPENSSL_ALGO_SHA256);
    if (!$ok) {
        return null;
    }
    $rawSignature = der_to_raw_ecdsa($derSignature);
    return $signingInput . '.' . b64url_encode($rawSignature);
}

/**
 * Envía un push vacío (sin contenido cifrado) a una suscripción.
 * Devuelve 'ok', 'expired' (la suscripción ya no existe y debe borrarse) o 'error'.
 */
function send_web_push(string $endpoint): string {
    // Defensa en profundidad: aunque /api/push.php ya valida al guardar,
    // se revalida aquí por si algún endpoint viejo o de otra vía llega sin pasar
    // por esa validación — nunca se le hace una petición a una URL no confiable.
    if (!is_valid_push_endpoint($endpoint)) {
        return 'error';
    }
    $origin = parse_url($endpoint, PHP_URL_SCHEME) . '://' . parse_url($endpoint, PHP_URL_HOST);
    $jwt = vapid_jwt($origin);
    if ($jwt === null) {
        return 'error';
    }

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => '',
        CURLOPT_HTTPHEADER => [
            'Authorization: vapid t=' . $jwt . ', k=' . VAPID_PUBLIC_KEY,
            'TTL: 86400',
            'Content-Length: 0',
        ],
        CURLOPT_TIMEOUT => 15,
        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($status >= 200 && $status < 300) {
        return 'ok';
    }
    if ($status === 404 || $status === 410) {
        return 'expired';
    }
    return 'error';
}
