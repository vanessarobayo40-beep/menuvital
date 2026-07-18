<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
secure_session_start();
send_security_headers();

if (current_user()) {
    header('Location: /app/index.php');
    exit;
}

$waMessage = rawurlencode('¡Hola! Quiero comprar MenúVital (' . APP_PRICE . ') para dejar de pensar todos los días qué cocinar 🥗');
$waLink = 'https://wa.me/' . preg_replace('/\D/', '', WHATSAPP_NUMBER) . '?text=' . $waMessage;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MenúVital — Nunca más pienses qué cocinar hoy</title>
<meta name="description" content="La app que arma tu menú saludable con lo que ya tienes en el mercado, y te acompaña como tu coach de nutrición. Pago único de <?= e(APP_PRICE) ?>, sin mensualidades.">
<meta name="theme-color" content="#0F9D6B">
<link rel="manifest" href="/assets/manifest.json">
<link rel="apple-touch-icon" href="/assets/img/icon-192.png">
<link rel="icon" href="/assets/img/icon-192.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=<?= ASSET_VER ?>">
<style>
  body { background: #fff; }
  .hero {
    background: var(--grad-v);
    color: #fff;
    padding: 44px 20px 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .hero .badge-pill {
    display: inline-block; background: rgba(255,255,255,0.18); padding: 6px 16px;
    border-radius: 999px; font-size: 12px; font-weight: 700; margin-bottom: 16px; letter-spacing: 0.3px;
  }
  .hero h1 { font-size: 30px; line-height: 1.2; margin: 0 0 14px; font-weight: 800; letter-spacing: -0.5px; }
  .hero p.sub { font-size: 16px; opacity: 0.95; max-width: 420px; margin: 0 auto 26px; }
  .price-tag { font-size: 40px; font-weight: 800; margin: 0; }
  .price-tag span { font-size: 15px; font-weight: 500; opacity: 0.85; display: block; margin-top: 2px; }
  .cta-group { display: flex; flex-direction: column; gap: 10px; max-width: 340px; margin: 24px auto 0; }
  .btn-white { background: #fff; color: var(--green-dark); }
  .btn-ghost-white { background: rgba(255,255,255,0.15); color: #fff; border: 1.5px solid rgba(255,255,255,0.5); box-shadow: none; }

  section.section { padding: 46px 20px; max-width: 480px; margin: 0 auto; }
  .section h2 { font-size: 22px; text-align: center; margin: 0 0 8px; letter-spacing: -0.3px; }
  .section p.lead { text-align: center; color: var(--t2); font-size: 14px; margin: 0 0 30px; }

  .feature-card { display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px solid var(--border); }
  .feature-card:last-child { border-bottom: none; }
  .feature-card .icon {
    width: 44px; height: 44px; border-radius: 12px; background: var(--green-light);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;
  }
  .feature-card h3 { margin: 0 0 4px; font-size: 15px; }
  .feature-card p { margin: 0; font-size: 13px; color: var(--t2); }

  .steps { counter-reset: step; }
  .step { display: flex; gap: 14px; margin-bottom: 22px; }
  .step .num {
    counter-increment: step;
    width: 32px; height: 32px; border-radius: 50%; background: var(--grad-v); color: #fff;
    display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0;
  }
  .step h3 { margin: 2px 0 4px; font-size: 15px; }
  .step p { margin: 0; font-size: 13px; color: var(--t2); }

  .compare-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .compare-table th, .compare-table td { padding: 10px 8px; text-align: center; border-bottom: 1px solid var(--border); }
  .compare-table th { font-size: 12px; color: var(--t2); }
  .compare-table td:first-child, .compare-table th:first-child { text-align: left; }
  .compare-table .yes { color: var(--green-dark); font-weight: 700; }
  .compare-table .no { color: var(--t3); }
  .compare-table .highlight { background: var(--green-light); border-radius: 8px; }

  .faq-item { border-bottom: 1px solid var(--border); padding: 14px 0; }
  .faq-item summary { font-weight: 600; font-size: 14px; cursor: pointer; list-style: none; display: flex; justify-content: space-between; }
  .faq-item summary::-webkit-details-marker { display: none; }
  .faq-item p { font-size: 13px; color: var(--t2); margin: 10px 0 0; }

  .final-cta { background: var(--surface); padding: 46px 20px; text-align: center; }
  .final-cta h2 { font-size: 22px; margin: 0 0 10px; }
  .final-cta p { color: var(--t2); font-size: 14px; margin: 0 0 22px; }

  footer { text-align: center; padding: 24px 20px 40px; font-size: 12px; color: var(--t3); }

  .sticky-cta {
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 40;
    background: #fff; border-top: 1px solid var(--border);
    padding: 12px 16px calc(12px + env(safe-area-inset-bottom));
    display: flex; align-items: center; gap: 12px;
    box-shadow: 0 -4px 16px rgba(16,32,27,0.08);
  }
  .sticky-cta .price { font-weight: 800; font-size: 16px; color: var(--t1); white-space: nowrap; }
  .sticky-cta .btn { flex: 1; }
  body { padding-bottom: 74px; }
</style>
</head>
<body>

<section class="hero">
  <span class="badge-pill">🥗 Menú diario + coach de nutrición</span>
  <h1>Nunca más pienses<br>"¿qué hago hoy de comida?"</h1>
  <p class="sub">Ingresa lo que ya tienes en tu mercado y recibe un menú saludable, delicioso y sin complicaciones — hecho para el paladar colombiano.</p>
  <p class="price-tag"><?= e(APP_PRICE) ?> <span>pago único · sin mensualidades</span></p>
  <div class="cta-group">
    <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-white btn-block" id="cta-comprar">Quiero mi acceso por <?= e(APP_PRICE) ?></a>
    <a href="/login.php" class="btn btn-ghost-white btn-block">Ya tengo mi código de acceso</a>
  </div>
</section>

<section class="section">
  <h2>Todo lo que necesitas para comer rico y sano</h2>
  <p class="lead">Sin dietas extrañas. Sin listas imposibles. Solo comida real, con lo que ya compraste.</p>

  <div class="feature-card">
    <div class="icon">🛒</div>
    <div><h3>Parte de tu mercado real</h3><p>Escribes lo que ya compraste y armamos el menú con eso — no al revés.</p></div>
  </div>
  <div class="feature-card">
    <div class="icon">📅</div>
    <div><h3>Plan semanal completo</h3><p>Desayuno, almuerzo, cena y snack para los 7 días, en segundos.</p></div>
  </div>
  <div class="feature-card">
    <div class="icon">📝</div>
    <div><h3>Lista de compras inteligente</h3><p>Solo lo que te falta comprar, agrupado por secciones del supermercado.</p></div>
  </div>
  <div class="feature-card">
    <div class="icon">💬</div>
    <div><h3>Coach de nutrición 24/7</h3><p>Pregúntale lo que quieras: sustituciones, tips, motivación para seguir.</p></div>
  </div>
  <div class="feature-card">
    <div class="icon">👨‍👩‍👧</div>
    <div><h3>Se adapta a ti</h3><p>Alergias, gustos, objetivo y número de personas — el menú se ajusta solo.</p></div>
  </div>
  <div class="feature-card">
    <div class="icon">📈</div>
    <div><h3>Seguimiento de progreso</h3><p>Registra tu peso, agua y hábitos, y mira tu evolución con el tiempo.</p></div>
  </div>
</section>

<section class="section" style="background:var(--surface);border-radius:24px;max-width:440px;">
  <h2>Así de fácil</h2>
  <div class="steps" style="margin-top:24px;">
    <div class="step"><div class="num">1</div><div><h3>Compras tu acceso</h3><p>Pago único de <?= e(APP_PRICE) ?> por WhatsApp — sin tarjetas ni suscripciones.</p></div></div>
    <div class="step"><div class="num">2</div><div><h3>Activas tu cuenta</h3><p>Te enviamos tu código y creas tu perfil en 1 minuto.</p></div></div>
    <div class="step"><div class="num">3</div><div><h3>Recibes tu menú</h3><p>Ingresas tu mercado y listo: menú del día, semana y lista de compras.</p></div></div>
  </div>
</section>

<section class="section">
  <h2>¿Por qué MenúVital y no otra app?</h2>
  <p class="lead">Las apps de menús más conocidas cobran mensualidad y están pensadas para otro país.</p>
  <table class="compare-table">
    <thead><tr><th></th><th class="highlight">MenúVital</th><th>Otras apps</th></tr></thead>
    <tbody>
      <tr><td>Pago</td><td class="highlight yes">Único, <?= e(APP_PRICE) ?></td><td class="no">Mensual/anual</td></tr>
      <tr><td>Comida</td><td class="highlight yes">Colombiana/latina real</td><td class="no">Americanizada</td></tr>
      <tr><td>Parte de</td><td class="highlight yes">Lo que ya tienes</td><td class="no">Lista ideal genérica</td></tr>
      <tr><td>Coach IA</td><td class="highlight yes">Incluido</td><td class="no">Aparte o no incluido</td></tr>
      <tr><td>Idioma</td><td class="highlight yes">100% español</td><td class="no">Traducido</td></tr>
    </tbody>
  </table>
</section>

<section class="section">
  <h2>Preguntas frecuentes</h2>
  <details class="faq-item">
    <summary>¿Es una suscripción?</summary>
    <p>No. Pagas una sola vez <?= e(APP_PRICE) ?> y tu acceso queda activo, sin cobros mensuales.</p>
  </details>
  <details class="faq-item">
    <summary>¿Y si no me gusta algo del menú?</summary>
    <p>Puedes generar otro menú cuando quieras, y contarle a tu coach qué no te gusta para que no te lo vuelva a sugerir.</p>
  </details>
  <details class="faq-item">
    <summary>¿Sirve si cocino para toda la familia?</summary>
    <p>Sí. En tu perfil indicas para cuántas personas cocinas y las porciones se ajustan automáticamente.</p>
  </details>
  <details class="faq-item">
    <summary>¿Necesito internet todo el tiempo?</summary>
    <p>Para generar menús nuevos sí, pero tu último menú queda guardado en tu celular para verlo sin conexión.</p>
  </details>
  <details class="faq-item">
    <summary>¿Cómo recibo mi acceso después de pagar?</summary>
    <p>Te enviamos tu código de activación por WhatsApp apenas confirmamos el pago. Con ese código creas tu cuenta.</p>
  </details>
</section>

<div class="final-cta">
  <h2>Deja de pensar todos los días qué cocinar</h2>
  <p>Tu menú saludable te está esperando.</p>
  <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-primary">Quiero mi acceso por <?= e(APP_PRICE) ?></a>
</div>

<footer>
  MenúVital © <?= date('Y') ?> — Hecho con cariño para comer rico y sano.
</footer>

<div class="sticky-cta">
  <span class="price"><?= e(APP_PRICE) ?></span>
  <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-primary">Comprar ahora</a>
</div>

<?php if (defined('FB_PIXEL_ID') && FB_PIXEL_ID !== ''): ?>
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?= e(FB_PIXEL_ID) ?>');
fbq('track', 'PageView');
document.getElementById('cta-comprar').addEventListener('click', () => fbq('track', 'InitiateCheckout'));
</script>
<?php endif; ?>
</body>
</html>
