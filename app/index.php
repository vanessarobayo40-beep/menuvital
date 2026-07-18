<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Hoy';
$ACTIVE_NAV = 'hoy';
require __DIR__ . '/../includes/layout_top.php';
?>

<h2 style="margin-bottom:2px;">Hola, <?= e(explode(' ', $user['name'])[0]) ?> 👋</h2>
<p class="muted" id="today-date" style="margin-top:0;font-size:14px;"></p>

<div id="install-banner" class="card-soft" style="display:none;margin-bottom:14px;">
  <div style="display:flex;align-items:center;gap:10px;">
    <span style="font-size:20px;">📲</span>
    <p style="margin:0;flex:1;font-size:13px;">Descarga la app en tu celular</p>
    <button id="btn-install-mini" class="btn btn-outline btn-sm">Instalar</button>
    <button id="btn-install-dismiss" class="btn-icon-plain" aria-label="Cerrar" style="background:none;border:none;color:var(--t3);font-size:18px;padding:0 4px;">×</button>
  </div>
  <p id="install-mini-steps" class="muted" style="display:none;margin:10px 0 0;font-size:12px;"></p>
</div>

<div id="coach-tip" class="card-soft" style="display:none;margin-bottom:18px;">
  <div style="display:flex;gap:10px;align-items:flex-start;">
    <span style="font-size:20px;">💬</span>
    <p style="margin:0;font-size:14px;color:var(--t1);" id="coach-tip-text"></p>
  </div>
</div>

<div id="pantry-empty" class="card text-center" style="display:none;margin-bottom:18px;">
  <p style="margin:0 0 12px;font-size:14px;">Aún no has ingresado tu mercado. Agrega lo que tienes disponible y te armamos el menú de hoy.</p>
  <a href="/app/mercado.php" class="btn btn-primary btn-sm">Ingresar mi mercado</a>
</div>

<div id="meals-container"></div>

<div id="loading" class="fade-in">
  <div class="skeleton" style="height:160px;margin-bottom:14px;"></div>
  <div class="skeleton" style="height:160px;margin-bottom:14px;"></div>
  <div class="skeleton" style="height:160px;"></div>
</div>

<button id="btn-regen" class="btn btn-outline btn-block" style="margin-top:8px;display:none;">
  🔄 Quiero otro menú para hoy
</button>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
const MEAL_LABELS = { desayuno: 'Desayuno', almuerzo: 'Almuerzo', cena: 'Cena', snack: 'Snack' };
const MEAL_ORDER = ['desayuno', 'almuerzo', 'cena', 'snack'];

document.getElementById('today-date').textContent = new Date().toLocaleDateString('es-CO', {
  weekday: 'long', day: 'numeric', month: 'long',
});

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s ?? '';
  return d.innerHTML;
}

const todayKey = new Date().toISOString().slice(0, 10);
let consumedIds = new Set(MV.loadLocal('consumed_' + todayKey, []));

function saveConsumed() {
  MV.saveLocal('consumed_' + todayKey, Array.from(consumedIds));
}

function renderMeal(type, meal) {
  const missingHtml = meal.missing.length
    ? `<div style="margin-top:10px;"><span class="badge badge-warn">Te falta comprar</span>
        <p style="font-size:13px;color:var(--t2);margin:6px 0 0;">${meal.missing.map(m => escapeHtml(m.item)).join(', ')}</p></div>`
    : `<div style="margin-top:10px;"><span class="badge badge-green">Ya tienes todo</span></div>`;
  const done = consumedIds.has(meal.id);

  return `
    <div class="meal-card">
      <span class="meal-tag">${MEAL_LABELS[type] || type}</span>
      <h3>${escapeHtml(meal.name)}</h3>
      <div class="meal-meta">
        <span>⏱ ${meal.time_min} min</span>
        <span>🔥 ${meal.kcal_porcion} kcal</span>
        <span>💪 ${meal.protein_porcion} g prot.</span>
      </div>
      <div style="display:flex;gap:8px;margin:0 14px 12px;">
        <button class="toggle-btn" data-target="body-${type}" style="flex:1;background:var(--surface);border:none;border-radius:var(--radius-sm);padding:9px;font-size:13px;font-weight:600;color:var(--green-dark);">Ver receta completa ▾</button>
        <button class="btn-cooked" data-recipe-id="${meal.id}" style="flex:1;border:none;border-radius:var(--radius-sm);padding:9px;font-size:13px;font-weight:600;${done ? 'background:var(--green-light);color:var(--green-dark);' : 'background:var(--surface-2);color:var(--t2);'}" ${done ? 'disabled' : ''}>${done ? '✅ Hecha' : '🍳 Ya la hice'}</button>
      </div>
      <div class="meal-body" id="body-${type}" style="display:none;">
        ${missingHtml}
        <h4>Ingredientes</h4>
        <ul>${meal.ingredients.map(i => `<li>${escapeHtml(i.item)}${i.qty ? ' — ' + escapeHtml(i.qty) : ''}</li>`).join('')}</ul>
        <h4>Preparación</h4>
        <ol>${meal.steps.map(s => `<li>${escapeHtml(s)}</li>`).join('')}</ol>
      </div>
    </div>`;
}

async function markCooked(btn, recipeId) {
  btn.disabled = true;
  try {
    const res = await MV.api('/api/pantry.php?action=consume_recipe', { method: 'POST', body: { recipe_id: recipeId } });
    consumedIds.add(recipeId);
    saveConsumed();
    btn.textContent = '✅ Hecha';
    btn.style.background = 'var(--green-light)';
    btn.style.color = 'var(--green-dark)';
    if (res.consumed.length) {
      MV.toast(`Descontamos de tu despensa: ${res.consumed.join(', ')}`);
    } else {
      MV.toast('¡Buen provecho! 🍽️');
    }
  } catch (err) {
    btn.disabled = false;
    MV.toast(err.message, true);
  }
}

function renderPlan(plan) {
  const container = document.getElementById('meals-container');
  const order = MEAL_ORDER.filter(t => plan.meals[t]);
  container.innerHTML = order.map(t => renderMeal(t, plan.meals[t])).join('');
  container.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const body = document.getElementById(btn.dataset.target);
      const open = body.style.display !== 'none';
      body.style.display = open ? 'none' : 'block';
      btn.textContent = open ? 'Ver receta completa ▾' : 'Ocultar receta ▴';
    });
  });
  container.querySelectorAll('.btn-cooked').forEach(btn => {
    btn.addEventListener('click', () => markCooked(btn, parseInt(btn.dataset.recipeId, 10)));
  });

  if (plan.consejo_coach) {
    document.getElementById('coach-tip-text').textContent = plan.consejo_coach;
    document.getElementById('coach-tip').style.display = 'block';
  }
}

async function loadToday() {
  document.getElementById('loading').style.display = 'block';
  document.getElementById('btn-regen').style.display = 'none';
  try {
    const res = await MV.api('/api/planner.php?action=today');
    renderPlan(res.plan);
    document.getElementById('btn-regen').style.display = 'block';
    MV.saveLocal('today_plan', res.plan);
  } catch (err) {
    const cached = MV.loadLocal('today_plan');
    if (cached) {
      renderPlan(cached);
      MV.toast('Mostrando tu último menú guardado (sin conexión).', true);
    } else {
      MV.toast(err.message, true);
    }
  } finally {
    document.getElementById('loading').style.display = 'none';
  }
}

document.getElementById('btn-regen').addEventListener('click', async () => {
  const btn = document.getElementById('btn-regen');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner dark" style="display:inline-block;"></span> Generando...';
  try {
    const res = await MV.api('/api/planner.php?action=today_new', { method: 'POST' });
    renderPlan(res.plan);
    MV.saveLocal('today_plan', res.plan);
    MV.toast('¡Listo! Aquí tienes un nuevo menú.');
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
    btn.innerHTML = '🔄 Quiero otro menú para hoy';
  }
});

// Verifica si hay mercado ingresado
MV.api('/api/pantry.php?action=list').then(res => {
  if (!res.items.length) {
    document.getElementById('pantry-empty').style.display = 'block';
  }
}).catch(() => {});

// ---------- Banner de instalar app (recordatorio breve, descartable) ----------
if (!MV.install.isStandalone() && !MV.loadLocal('install_banner_dismissed')) {
  document.getElementById('install-banner').style.display = 'block';
}
document.getElementById('btn-install-mini').addEventListener('click', async () => {
  const result = await MV.install.trigger();
  if (result === 'installed') {
    document.getElementById('install-banner').style.display = 'none';
    MV.toast('¡Listo! MenúVital quedó instalada en tu celular 🎉');
  } else if (result === 'manual') {
    const el = document.getElementById('install-mini-steps');
    el.innerHTML = MV.install.manualSteps() + ' (También puedes verla siempre en tu <a href="/app/perfil.php" style="color:var(--green-dark);font-weight:600;">Perfil</a>.)';
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
  }
});
document.getElementById('btn-install-dismiss').addEventListener('click', () => {
  document.getElementById('install-banner').style.display = 'none';
  MV.saveLocal('install_banner_dismissed', true);
});

loadToday();
</script>
