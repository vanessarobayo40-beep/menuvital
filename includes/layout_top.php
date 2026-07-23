<?php
/**
 * MenúVital — Encabezado HTML compartido para páginas de la app autenticada.
 * Variables esperadas antes de incluir: $PAGE_TITLE, $ACTIVE_NAV, $user (array)
 */
require_once __DIR__ . '/layout.php';
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($PAGE_TITLE ?? 'MenúVital') ?> — MenúVital</title>
<meta name="csrf-token" content="<?= e($csrf) ?>">
<meta name="theme-color" content="#0E6B45">
<?= theme_init_script() ?>
<link rel="manifest" href="/assets/manifest.json">
<link rel="apple-touch-icon" href="/assets/img/icon-192-v3.png">
<link rel="icon" href="/assets/img/icon-192-v3.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=<?= ASSET_VER ?>">
</head>
<body>
<header class="app-header">
  <div class="brand">
    <span class="brand-mark">MV</span>
    MenúVital
  </div>
  <a href="/app/perfil.php" class="icon-btn" aria-label="Perfil">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
  </a>
</header>
<main class="container page-with-nav fade-in">
