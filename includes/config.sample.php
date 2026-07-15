<?php
/**
 * MenúVital — Configuración
 *
 * INSTRUCCIONES:
 * 1. Copia este archivo como "config.php" en esta misma carpeta (includes/).
 * 2. Llena los datos reales (base de datos, API key, etc.).
 * 3. NUNCA subas config.php a GitHub (ya está en .gitignore).
 */

// ---------- Entorno ----------
// 'production' en Hostinger | 'local' para pruebas en tu computador
define('APP_ENV', 'production');

// URL pública de la app (sin / al final)
define('APP_URL', 'https://tudominio.com');

// ---------- Base de datos ----------
// En Hostinger usa 'mysql'. Para pruebas locales usa 'sqlite'.
define('DB_DRIVER', 'mysql');

// Datos de MySQL (hPanel → Bases de datos → MySQL)
define('DB_HOST', 'localhost');
define('DB_NAME', 'uXXXXXXXXX_menuvital');
define('DB_USER', 'uXXXXXXXXX_vanessa');
define('DB_PASS', 'TU_CONTRASEÑA_MYSQL');

// Ruta del archivo SQLite (solo para desarrollo local)
define('DB_SQLITE_PATH', __DIR__ . '/../database/menuvital.db');

// ---------- Inteligencia Artificial (Groq — gratis) ----------
// Crea tu cuenta gratis en https://console.groq.com y genera una API key.
// Si se deja vacío, la app funciona igual con el motor de recetas (sin chat IA).
define('GROQ_API_KEY', '');
define('GROQ_MODEL', 'llama-3.3-70b-versatile');

// ---------- Administradora ----------
// Con estos datos se crea tu cuenta de administradora al instalar.
// Desde /admin generas los códigos de activación para tus clientas.
define('ADMIN_NAME', 'Vanessa');
define('ADMIN_EMAIL', 'vanessarobayo40@gmail.com');
define('ADMIN_PASSWORD_INITIAL', 'CAMBIA-ESTA-CONTRASEÑA');

// ---------- Instalación ----------
// Clave secreta para poder ejecutar install.php (invéntate una larga).
define('INSTALL_KEY', 'cambia-esta-clave-secreta-larga');

// ---------- Ventas ----------
// Número de WhatsApp para el botón "Comprar" de la landing (con indicativo, sin +)
define('WHATSAPP_NUMBER', '573001234567');
// Precio mostrado en la landing
define('APP_PRICE', '$19.900');
// ID del Pixel de Facebook (Meta) para medir tus campañas. Vacío = desactivado.
define('FB_PIXEL_ID', '');

// ---------- Zona horaria ----------
date_default_timezone_set('America/Bogota');
