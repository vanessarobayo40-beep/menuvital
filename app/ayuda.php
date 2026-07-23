<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Ayuda';
require __DIR__ . '/../includes/layout_top.php';
$waMessage = rawurlencode('¡Hola! Tengo una pregunta sobre MenúVital 🙂');
$waLink = 'https://wa.me/' . preg_replace('/\D/', '', WHATSAPP_NUMBER) . '?text=' . $waMessage;
?>

<h2 style="margin-bottom:4px;">¿Cómo funciona MenúVital?</h2>
<p class="muted" style="margin-top:0;font-size:14px;">Una guía rápida de cada parte de la app.</p>

<div class="card" style="margin-bottom:14px;">
  <p style="margin:0;font-size:14px;">MenúVital te ayuda a decidir qué comer con lo que ya tienes en casa, arma tu menú, calcula la lista de compras exacta y descuenta tu despensa cuando cocinas — todo en español y pensado para la cocina colombiana.</p>
</div>

<p class="section-title" style="margin-top:4px;">Paso a paso</p>

<div class="card" style="margin-bottom:10px;">
  <div style="display:flex;gap:12px;align-items:flex-start;">
    <span style="font-size:22px;">🛒</span>
    <div>
      <p style="margin:0 0 4px;font-weight:600;font-size:14px;">1. Ingresa tu despensa</p>
      <p class="muted" style="margin:0;font-size:13px;">En <strong>Mercado</strong>, escribe lo que tienes disponible, dilo por voz 🎤 o toma una foto de tu factura 📷. La app reconoce los ingredientes automáticamente.</p>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:10px;">
  <div style="display:flex;gap:12px;align-items:flex-start;">
    <span style="font-size:22px;">📖</span>
    <div>
      <p style="margin:0 0 4px;font-weight:600;font-size:14px;">2. Elige tus recetas</p>
      <p class="muted" style="margin:0;font-size:13px;">En <strong>Recetas</strong>, explora el recetario, marca tus favoritas ❤ y toca <strong>"Agregar a mi menú"</strong> en cualquier receta: eliges el tipo de comida, las porciones y los días (hoy, mañana, la semana o hasta un mes completo).</p>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:10px;">
  <div style="display:flex;gap:12px;align-items:flex-start;">
    <span style="font-size:22px;">📅</span>
    <div>
      <p style="margin:0 0 4px;font-weight:600;font-size:14px;">3. Mira tu menú en Hoy y Semana</p>
      <p class="muted" style="margin:0;font-size:13px;">Lo que elegiste en Recetas aparece automáticamente en <strong>Hoy</strong> y <strong>Semana</strong>. Si dejas un espacio vacío, puedes tocar <strong>"✨ Sugerir"</strong> para que la app elija por ti según lo que tienes en la despensa.</p>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:10px;">
  <div style="display:flex;gap:12px;align-items:flex-start;">
    <span style="font-size:22px;">🧾</span>
    <div>
      <p style="margin:0 0 4px;font-weight:600;font-size:14px;">4. Compra exactamente lo que falta</p>
      <p class="muted" style="margin:0;font-size:13px;">En Mercado → <strong>Lista de compras</strong>, elige el periodo (semana, quincena o mes) y la app te dice la cantidad exacta a comprar, restando lo que ya tienes. Al marcar un ítem como comprado, se agrega solo a tu despensa.</p>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:10px;">
  <div style="display:flex;gap:12px;align-items:flex-start;">
    <span style="font-size:22px;">👩‍🍳</span>
    <div>
      <p style="margin:0 0 4px;font-weight:600;font-size:14px;">5. Cocina y marca "Ya la hice"</p>
      <p class="muted" style="margin:0;font-size:13px;">Toca <strong>"Modo Cocina"</strong> para ver los pasos en pantalla completa, uno a la vez. Cuando termines, marca <strong>"🍳 Ya la hice"</strong>, dinos cuántas porciones preparaste y descontamos justo esa cantidad de tu despensa.</p>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:10px;">
  <div style="display:flex;gap:12px;align-items:flex-start;">
    <span style="font-size:22px;">💬</span>
    <div>
      <p style="margin:0 0 4px;font-weight:600;font-size:14px;">6. Tu coach y tu progreso</p>
      <p class="muted" style="margin:0;font-size:13px;"><strong>Coach</strong> responde tus preguntas de nutrición. En <strong>Progreso</strong> registras tu peso, tu agua del día y ves tu meta calórica según tu perfil.</p>
    </div>
  </div>
</div>

<p class="section-title">Preguntas frecuentes</p>

<div class="card" style="margin-bottom:18px;">
  <?php
  $faqs = [
      ['¿Cómo entro a la app?', 'Con el código que te enviamos por WhatsApp al confirmar tu pago (formato MV-XXX-XXX). No necesitas correo ni contraseña — solo escribe tu código en la pantalla de entrada.'],
      ['¿En cuántos dispositivos puedo usar mi código?', 'Hasta en 2 dispositivos (por ejemplo, tu celular y el de tu pareja, o tu celular y una tablet). Si necesitas usarlo en un tercero, escríbenos por WhatsApp y te liberamos un cupo.'],
      ['¿Perdí mi código, qué hago?', 'Escríbenos por WhatsApp con el nombre o correo con el que lo activaste y te ayudamos a recuperarlo.'],
      ['¿Puedo cambiar entre modo claro y oscuro?', 'Sí, en Perfil → Apariencia puedes elegir Claro, Oscuro o Automático (según la configuración de tu celular).'],
      ['¿La app funciona sin internet?', 'Puedes ver el último menú que cargaste sin conexión, pero para generar sugerencias nuevas, agregar recetas o actualizar tu despensa necesitas internet.'],
      ['¿El pago es único o es una suscripción?', 'Es un pago único (' . e(APP_PRICE) . '). Tu código no vence ni se cobra de nuevo.'],
  ];
  foreach ($faqs as $i => [$q, $a]):
  ?>
  <div style="<?= $i > 0 ? 'border-top:1px solid var(--border);padding-top:12px;margin-top:12px;' : '' ?>">
    <button type="button" class="faq-toggle" data-target="faq-<?= $i ?>" style="width:100%;text-align:left;background:none;border:none;padding:0;display:flex;justify-content:space-between;align-items:center;font-size:14px;font-weight:600;color:var(--t1);cursor:pointer;">
      <span><?= e($q) ?></span>
      <span class="faq-arrow" style="color:var(--t3);">▾</span>
    </button>
    <p id="faq-<?= $i ?>" class="muted" style="display:none;margin:8px 0 0;font-size:13px;"><?= e($a) ?></p>
  </div>
  <?php endforeach; ?>
</div>

<div class="card-soft" style="text-align:center;margin-bottom:18px;">
  <p style="margin:0 0 12px;font-size:14px;">¿No encontraste lo que buscabas?</p>
  <a href="<?= e($waLink) ?>" target="_blank" rel="noopener" class="btn btn-primary btn-block">💬 Escríbenos por WhatsApp</a>
</div>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
document.querySelectorAll('.faq-toggle').forEach(btn => {
  btn.addEventListener('click', () => {
    const body = document.getElementById(btn.dataset.target);
    const arrow = btn.querySelector('.faq-arrow');
    const open = body.style.display !== 'none';
    body.style.display = open ? 'none' : 'block';
    arrow.textContent = open ? '▾' : '▴';
  });
});
</script>
