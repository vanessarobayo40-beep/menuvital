<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/profile.php';
require_once __DIR__ . '/../includes/layout.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Bienvenida — MenúVital</title>
<meta name="csrf-token" content="<?= e($csrf) ?>">
<meta name="theme-color" content="#0E6B45">
<?= theme_init_script() ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=<?= ASSET_VER ?>">
<style>
  body { background: var(--bg); min-height: 100vh; padding-bottom: 100px; }
  .ob-wrap { max-width: 460px; margin: 0 auto; padding: 24px 20px; }
  .ob-progress { display: flex; gap: 6px; margin-bottom: 28px; }
  .ob-progress span { flex: 1; height: 4px; border-radius: 999px; background: var(--surface-2); transition: background 0.25s; }
  .ob-progress span.done { background: var(--grad-v); }
  .ob-step { display: none; }
  .ob-step.active { display: block; }
  .ob-step h2 { font-size: 21px; margin: 0 0 6px; }
  .ob-step p.sub { color: var(--t2); font-size: 14px; margin: 0 0 24px; }
  .option-card {
    display: flex; align-items: center; gap: 12px; padding: 14px 16px; margin-bottom: 10px;
    border: 1.5px solid var(--border); border-radius: var(--radius); cursor: pointer; transition: all 0.15s;
  }
  .option-card.selected { border-color: var(--green); background: var(--green-light); }
  .option-card .icon { font-size: 22px; }
  .option-card .label { font-weight: 600; font-size: 14px; }
  .sex-row { display: flex; gap: 10px; margin-bottom: 16px; }
  .sex-row .option-card { flex: 1; margin-bottom: 0; justify-content: center; }
  .ob-bottom {
    position: fixed; bottom: 0; left: 0; right: 0; background: var(--card-bg); border-top: 1px solid var(--border);
    padding: 14px 20px calc(14px + env(safe-area-inset-bottom)); display: flex; gap: 10px; max-width: 460px; margin: 0 auto;
  }
  .ob-bottom .btn { flex: 1; }
  .skip-link { text-align: center; margin-top: 14px; }
  .skip-link a { font-size: 13px; color: var(--t3); font-weight: 600; }
</style>
</head>
<body>
<div class="ob-wrap">
  <div class="ob-progress">
    <span id="prog-1" class="done"></span><span id="prog-2"></span><span id="prog-3"></span><span id="prog-4"></span>
  </div>

  <!-- Paso 1: Objetivo -->
  <div class="ob-step active" data-step="1">
    <h2>¡Hola! 👋</h2>
    <p class="sub">Antes que nada, ¿cómo te llamas?</p>
    <div class="field">
      <input type="text" id="ob-name" maxlength="100" placeholder="Tu nombre" value="<?= $user['name'] === 'Usuaria' ? '' : e($user['name']) ?>">
    </div>
    <p class="sub" style="margin-top:20px;">¿Cuál es tu objetivo principal? Así ajustamos tu menú.</p>
    <div class="option-card" data-field="goal" data-value="balance"><span class="icon">⚖️</span><span class="label">Llevar una vida balanceada</span></div>
    <div class="option-card" data-field="goal" data-value="bajar_peso"><span class="icon">📉</span><span class="label">Bajar de peso</span></div>
    <div class="option-card" data-field="goal" data-value="ganar_musculo"><span class="icon">💪</span><span class="label">Aumentar masa muscular</span></div>
    <div class="option-card" data-field="goal" data-value="energia"><span class="icon">⚡</span><span class="label">Tener más energía</span></div>
    <div class="option-card" data-field="goal" data-value="familia"><span class="icon">👨‍👩‍👧</span><span class="label">Alimentar bien a mi familia</span></div>
  </div>

  <!-- Paso 2: Sobre ti -->
  <div class="ob-step" data-step="2">
    <h2>Sobre ti</h2>
    <p class="sub">Con esto calculamos tu meta calórica diaria — sin dietas extrañas, solo lo que tu cuerpo necesita.</p>
    <div class="sex-row">
      <div class="option-card" data-field="sex" data-value="f"><span class="icon">👩</span><span class="label">Mujer</span></div>
      <div class="option-card" data-field="sex" data-value="m"><span class="icon">👨</span><span class="label">Hombre</span></div>
    </div>
    <div class="field">
      <label>Edad</label>
      <input type="number" id="ob-age" min="12" max="100" placeholder="Ej: 32">
    </div>
    <div style="display:flex;gap:12px;">
      <div class="field" style="flex:1;"><label>Estatura (cm)</label><input type="number" id="ob-height" min="100" max="230" placeholder="Ej: 160"></div>
      <div class="field" style="flex:1;"><label>Peso actual (kg)</label><input type="number" id="ob-weight" step="0.1" min="20" max="300" placeholder="Ej: 65.5"></div>
    </div>
    <div class="field mb-0">
      <label>¿Qué tan activa eres?</label>
      <select id="ob-activity">
        <option value="sedentario">Sedentaria (poco o nada de ejercicio)</option>
        <option value="ligero">Ligera (ejercicio suave 1-3 días/semana)</option>
        <option value="moderado" selected>Moderada (ejercicio 3-5 días/semana)</option>
        <option value="activo">Activa (ejercicio intenso 6-7 días/semana)</option>
        <option value="muy_activo">Muy activa (2 veces al día o trabajo físico exigente)</option>
      </select>
    </div>
    <div class="skip-link"><a href="#" data-skip-to="3">Prefiero no decirlo, continuar →</a></div>
  </div>

  <!-- Paso 3: Tu hogar -->
  <div class="ob-step" data-step="3">
    <h2>Tu hogar</h2>
    <p class="sub">Ajustamos las porciones y cuántas comidas planear al día.</p>
    <div class="field">
      <label>¿Para cuántas personas cocinas?</label>
      <input type="number" id="ob-people" min="1" max="12" value="1">
    </div>
    <div class="field mb-0">
      <label>¿Cuántas comidas al día quieres planear?</label>
      <select id="ob-meals">
        <option value="3">3 (desayuno, almuerzo, cena)</option>
        <option value="4">4 (+ snack)</option>
      </select>
    </div>
  </div>

  <!-- Paso 4: Tus gustos -->
  <div class="ob-step" data-step="4">
    <h2>Tus gustos</h2>
    <p class="sub">Opcional, pero ayuda muchísimo a que el menú se sienta hecho para ti.</p>
    <div class="field">
      <label>¿Alguna alergia o algo que no puedas comer?</label>
      <input type="text" id="ob-allergies" placeholder="Ej: camarones, maní">
    </div>
    <div class="field">
      <label>¿Algo que no te guste?</label>
      <input type="text" id="ob-dislikes" placeholder="Ej: hígado, coliflor">
    </div>
    <div class="field mb-0">
      <label>❤️ Tus platos favoritos</label>
      <input type="text" id="ob-favorites" placeholder="Ej: ajiaco, pollo, arepa rellena">
    </div>
  </div>
</div>

<div class="ob-bottom">
  <button type="button" id="btn-back" class="btn btn-secondary" style="display:none;">Atrás</button>
  <button type="button" id="btn-next" class="btn btn-primary">Continuar</button>
</div>

<script src="/assets/js/app.js?v=<?= ASSET_VER ?>"></script>
<script>
let step = 1;
const TOTAL_STEPS = 4;
const data = { name: '', goal: 'balance', sex: null, age: '', height_cm: '', starting_weight: '', activity_level: 'moderado', people: 1, meals_per_day: 3, allergies: '', dislikes: '', favorites: '' };

document.querySelectorAll('.option-card').forEach(card => {
  card.addEventListener('click', () => {
    const field = card.dataset.field;
    document.querySelectorAll(`.option-card[data-field="${field}"]`).forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    data[field] = card.dataset.value;
  });
});
document.querySelector('.option-card[data-field="goal"][data-value="balance"]').classList.add('selected');

function showStep(n) {
  document.querySelectorAll('.ob-step').forEach(s => s.classList.toggle('active', parseInt(s.dataset.step, 10) === n));
  for (let i = 1; i <= TOTAL_STEPS; i++) {
    document.getElementById('prog-' + i).classList.toggle('done', i <= n);
  }
  document.getElementById('btn-back').style.display = n > 1 ? 'inline-flex' : 'none';
  document.getElementById('btn-next').textContent = n === TOTAL_STEPS ? 'Terminar' : 'Continuar';
  step = n;
}

document.getElementById('btn-back').addEventListener('click', () => { if (step > 1) showStep(step - 1); });

document.querySelectorAll('[data-skip-to]').forEach(a => {
  a.addEventListener('click', (e) => { e.preventDefault(); showStep(parseInt(a.dataset.skipTo, 10)); });
});

async function finishOnboarding() {
  const btn = document.getElementById('btn-next');
  btn.disabled = true;
  btn.textContent = 'Guardando...';
  data.name = document.getElementById('ob-name').value.trim();
  data.age = document.getElementById('ob-age').value;
  data.height_cm = document.getElementById('ob-height').value;
  data.starting_weight = document.getElementById('ob-weight').value;
  data.activity_level = document.getElementById('ob-activity').value;
  data.people = document.getElementById('ob-people').value;
  data.meals_per_day = document.getElementById('ob-meals').value;
  data.allergies = document.getElementById('ob-allergies').value;
  data.dislikes = document.getElementById('ob-dislikes').value;
  data.favorites = document.getElementById('ob-favorites').value;

  try {
    await MV.api('/api/profile.php?action=update', { method: 'POST', body: data });
    window.location.href = '/app/mercado.php?bienvenida=1';
  } catch (err) {
    MV.toast(err.message, true);
    btn.disabled = false;
    btn.textContent = 'Terminar';
  }
}

document.getElementById('btn-next').addEventListener('click', () => {
  if (step < TOTAL_STEPS) {
    showStep(step + 1);
  } else {
    finishOnboarding();
  }
});

MV.lockBackButton();
</script>
</body>
</html>
