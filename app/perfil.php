<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Perfil';
$ACTIVE_NAV = 'perfil';
$isWelcome = ($_GET['bienvenida'] ?? '') === '1';
require __DIR__ . '/../includes/layout_top.php';
?>

<?php if ($isWelcome): ?>
<div class="card-soft" style="margin-bottom:18px;">
  <p style="margin:0;font-size:14px;">🎉 <strong>¡Bienvenida a MenúVital, <?= e(explode(' ', $user['name'])[0]) ?>!</strong>
  Cuéntanos tus preferencias para armarte el menú perfecto.</p>
</div>
<?php endif; ?>

<div id="install-banner" class="card" style="display:none;margin-bottom:18px;background:var(--grad-soft);border:none;">
  <div style="display:flex;align-items:center;gap:12px;">
    <span style="font-size:26px;">📲</span>
    <div style="flex:1;">
      <p style="margin:0;font-size:14px;font-weight:600;">Lleva MenúVital en tu celular</p>
      <p style="margin:2px 0 0;font-size:12px;color:var(--t2);">Instálala como app y ábrela con un toque.</p>
    </div>
    <button id="btn-install" class="btn btn-primary btn-sm">Instalar</button>
  </div>
</div>

<div id="ios-install-help" class="card-soft" style="display:none;margin-bottom:18px;">
  <p style="margin:0;font-size:13px;">📲 <strong>Para instalar en iPhone:</strong> toca el botón de compartir
  <span style="font-size:15px;">⎋</span> en Safari y elige <strong>"Agregar a inicio"</strong>.</p>
</div>

<h2 style="margin-bottom:4px;">Mis preferencias</h2>
<p class="muted" style="margin-top:0;font-size:14px;">Esto ayuda a que tu menú y tu coach se ajusten a ti.</p>

<form id="form-profile" class="card">
  <div class="field">
    <label>¿Cuál es tu objetivo principal?</label>
    <select id="pf-goal">
      <option value="balance">Llevar una vida balanceada</option>
      <option value="bajar_peso">Bajar de peso</option>
      <option value="energia">Tener más energía</option>
      <option value="familia">Alimentar bien a mi familia</option>
    </select>
  </div>
  <div style="display:flex;gap:12px;">
    <div class="field" style="flex:1;">
      <label>¿Para cuántas personas cocinas?</label>
      <input type="number" id="pf-people" min="1" max="12" value="1">
    </div>
    <div class="field" style="flex:1;">
      <label>Comidas al día</label>
      <select id="pf-meals">
        <option value="3">3 comidas</option>
        <option value="4">4 (+ snack)</option>
      </select>
    </div>
  </div>

  <p class="section-title" style="margin-top:6px;">Tus datos para el progreso</p>
  <div style="display:flex;gap:12px;">
    <div class="field" style="flex:1;">
      <label>Estatura (cm)</label>
      <input type="number" id="pf-height" min="100" max="230" placeholder="Ej: 160">
    </div>
    <div class="field" style="flex:1;">
      <label>Peso inicial (kg)</label>
      <input type="number" id="pf-weight" step="0.1" min="20" max="300" placeholder="Ej: 65.5">
    </div>
  </div>
  <p class="hint" style="margin:-8px 0 16px;font-size:12px;color:var(--t3);">Con esto tu página de Progreso te muestra tu IMC y cuánto has avanzado desde que empezaste.</p>

  <p class="section-title" style="margin-top:6px;">Tus gustos</p>
  <div class="field">
    <label>¿Alguna alergia o algo que no puedas comer?</label>
    <input type="text" id="pf-allergies" placeholder="Ej: camarones, maní (sepáralos con comas)">
  </div>
  <div class="field">
    <label>¿Algo que no te guste, aunque no seas alérgica?</label>
    <input type="text" id="pf-dislikes" placeholder="Ej: hígado, coliflor (sepáralos con comas)">
  </div>
  <div class="field mb-0">
    <label>❤️ Tus platos o ingredientes favoritos</label>
    <input type="text" id="pf-favorites" placeholder="Ej: ajiaco, pollo, arepa rellena (sepáralos con comas)">
    <p class="hint">El menú les dará prioridad a tus favoritos cuando se pueda.</p>
  </div>
  <p id="profile-error" class="field error-text" style="display:none;"></p>
  <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px;">Guardar preferencias</button>
</form>

<p class="section-title">Cuenta</p>
<div class="card">
  <p style="margin:0 0 4px;font-size:14px;"><strong><?= e($user['name']) ?></strong></p>
  <p class="muted" style="margin:0 0 16px;font-size:13px;"><?= e($user['email']) ?></p>
  <button id="btn-logout" class="btn btn-secondary btn-block">Cerrar sesión</button>
</div>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
// ---------- Instalación de la app (PWA) ----------
let deferredPrompt = null;
const isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);
const isStandalone = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone;

window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;
  if (!isStandalone) document.getElementById('install-banner').style.display = 'block';
});

if (isIos && !isStandalone) {
  document.getElementById('ios-install-help').style.display = 'block';
}

document.getElementById('btn-install').addEventListener('click', async () => {
  if (!deferredPrompt) return;
  deferredPrompt.prompt();
  const choice = await deferredPrompt.userChoice;
  if (choice.outcome === 'accepted') {
    document.getElementById('install-banner').style.display = 'none';
    MV.toast('¡Listo! MenúVital quedó instalada en tu celular 🎉');
  }
  deferredPrompt = null;
});

// ---------- Perfil ----------
async function loadProfile() {
  try {
    const res = await MV.api('/api/profile.php?action=get');
    document.getElementById('pf-goal').value = res.profile.goal;
    document.getElementById('pf-people').value = res.profile.people;
    document.getElementById('pf-meals').value = res.profile.meals_per_day;
    document.getElementById('pf-allergies').value = res.profile.allergies;
    document.getElementById('pf-dislikes').value = res.profile.dislikes;
    document.getElementById('pf-favorites').value = res.profile.favorites || '';
    document.getElementById('pf-height').value = res.profile.height_cm ?? '';
    document.getElementById('pf-weight').value = res.profile.starting_weight ?? '';
  } catch (err) {
    MV.toast(err.message, true);
  }
}

async function saveProfile() {
  const errEl = document.getElementById('profile-error');
  errEl.style.display = 'none';
  try {
    await MV.api('/api/profile.php?action=update', {
      method: 'POST',
      body: {
        goal: document.getElementById('pf-goal').value,
        people: parseInt(document.getElementById('pf-people').value, 10) || 1,
        meals_per_day: parseInt(document.getElementById('pf-meals').value, 10) || 3,
        allergies: document.getElementById('pf-allergies').value,
        dislikes: document.getElementById('pf-dislikes').value,
        favorites: document.getElementById('pf-favorites').value,
        height_cm: document.getElementById('pf-height').value,
        starting_weight: document.getElementById('pf-weight').value,
      },
    });
    MV.toast('Preferencias guardadas ✅');
  } catch (err) {
    errEl.textContent = err.message;
    errEl.style.display = 'block';
  }
}

const autosave = MV.debounce(saveProfile, 1500);

document.getElementById('form-profile').addEventListener('submit', (e) => {
  e.preventDefault();
  saveProfile();
});
['pf-goal', 'pf-people', 'pf-meals'].forEach(id => {
  document.getElementById(id).addEventListener('change', autosave);
});

document.getElementById('btn-logout').addEventListener('click', async () => {
  try {
    await MV.api('/api/auth.php?action=logout', { method: 'POST' });
  } finally {
    window.location.href = '/login.php';
  }
});

loadProfile();
</script>
