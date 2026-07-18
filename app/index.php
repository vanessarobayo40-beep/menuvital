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

<div id="nutrition-summary" class="card" style="display:none;margin-bottom:14px;">
  <div style="display:flex;align-items:center;gap:16px;">
    <svg width="68" height="68" viewBox="0 0 72 72" style="flex-shrink:0;">
      <circle cx="36" cy="36" r="30" fill="none" stroke="var(--surface-2)" stroke-width="8"/>
      <circle id="kcal-ring" cx="36" cy="36" r="30" fill="none" stroke="var(--green)" stroke-width="8"
        stroke-linecap="round" transform="rotate(-90 36 36)" stroke-dasharray="188.5" stroke-dashoffset="188.5"
        style="transition:stroke-dashoffset 0.4s ease, stroke 0.4s ease;"/>
    </svg>
    <div style="flex:1;">
      <p style="margin:0;font-size:12px;color:var(--t2);">Lo que llevas comido hoy</p>
      <p style="margin:2px 0 0;font-size:19px;font-weight:700;" id="kcal-summary-text">–</p>
      <p style="margin:2px 0 0;font-size:12px;color:var(--t3);" id="protein-summary-text"></p>
    </div>
  </div>
</div>

<div id="nutrition-nudge" class="card-soft" style="display:none;margin-bottom:14px;">
  <p style="margin:0;font-size:13px;">💡 <a href="/app/perfil.php" style="color:var(--green-dark);font-weight:600;">Completa tu perfil</a> (sexo, edad, estatura y peso) para ver tu meta calórica del día.</p>
</div>

<div class="card-soft" style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
  <span style="font-size:22px;">💧</span>
  <div style="flex:1;">
    <p style="margin:0;font-size:13px;font-weight:600;">Agua de hoy</p>
    <p class="muted" style="margin:1px 0 0;font-size:12px;" id="water-quick-label">– / – vasos</p>
  </div>
  <button id="btn-water-quick" class="btn btn-primary btn-sm">+ 1 vaso</button>
</div>

<div id="coach-tip" class="card-soft" style="display:none;margin-bottom:18px;">
  <div style="display:flex;gap:10px;align-items:flex-start;">
    <span style="font-size:20px;">💬</span>
    <p style="margin:0;font-size:14px;color:var(--t1);" id="coach-tip-text"></p>
  </div>
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

const MEAL_EMOJI = { desayuno: '🍳', almuerzo: '🍲', cena: '🥗', snack: '🍎' };

function renderMeal(type, meal) {
  const missingHtml = meal.missing.length
    ? `<div style="margin-top:10px;"><span class="badge badge-warn">Te falta comprar</span>
        <p style="font-size:13px;color:var(--t2);margin:6px 0 0;">${meal.missing.map(m => escapeHtml(m.item)).join(', ')}</p></div>`
    : `<div style="margin-top:10px;"><span class="badge badge-green">Ya tienes todo</span></div>`;
  const done = !!meal.done;

  return `
    <div class="meal-card">
      <div class="meal-photo-wrap">
        <img src="${escapeHtml(meal.image_url)}" alt="${escapeHtml(meal.name)}" loading="lazy"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="meal-photo-fallback" style="display:none;">${MEAL_EMOJI[type] || '🍽️'}</div>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;margin:14px 14px 0;">
        <span class="meal-tag" style="margin:0;">${MEAL_LABELS[type] || type}</span>
        <button class="btn-swap" data-meal-type="${type}" style="background:none;border:none;color:var(--t3);font-size:12px;font-weight:600;padding:4px;">🔄 Cambiar plato</button>
      </div>
      <h3>${escapeHtml(meal.name)}</h3>
      <div class="meal-meta">
        <span>⏱ ${meal.time_min} min</span>
        <span>🔥 ${meal.kcal_porcion} kcal</span>
      </div>
      <div class="nutri-grid">
        <div class="nutri-item"><div class="n-val">${meal.protein_porcion}g</div><div class="n-lbl">Proteína</div></div>
        <div class="nutri-item"><div class="n-val">${meal.carbs_porcion}g</div><div class="n-lbl">Carbos</div></div>
        <div class="nutri-item"><div class="n-val">${meal.fat_porcion}g</div><div class="n-lbl">Grasa</div></div>
        <div class="nutri-item"><div class="n-val">${meal.sugar_porcion}g</div><div class="n-lbl">Azúcar</div></div>
        <div class="nutri-item"><div class="n-val">${meal.fiber_porcion}g</div><div class="n-lbl">Fibra</div></div>
        <div class="nutri-item"><div class="n-val">${meal.kcal_porcion}</div><div class="n-lbl">Kcal</div></div>
      </div>
      <p class="nutri-note">Valores estimados por porción.</p>
      <div style="display:flex;gap:8px;margin:0 14px 8px;">
        <button class="btn-cook-mode" data-meal-type="${type}" style="flex:1;background:var(--grad-soft);border:none;border-radius:var(--radius-sm);padding:9px;font-size:13px;font-weight:600;color:var(--purple-dark);">👩‍🍳 Modo Cocina</button>
        <button class="toggle-btn" data-target="body-${type}" style="flex:1;background:var(--surface);border:none;border-radius:var(--radius-sm);padding:9px;font-size:13px;font-weight:600;color:var(--green-dark);">Ver receta ▾</button>
      </div>
      <div style="display:flex;gap:8px;margin:0 14px 12px;">
        <button class="btn-cooked" data-recipe-id="${meal.id}" data-meal-type="${type}" style="flex:1;border:none;border-radius:var(--radius-sm);padding:9px;font-size:13px;font-weight:600;${done ? 'background:var(--green-light);color:var(--green-dark);' : 'background:var(--surface-2);color:var(--t2);'}" ${done ? 'disabled' : ''}>${done ? '✅ Hecha' : '🍳 Ya la hice'}</button>
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

async function markCooked(btn, recipeId, type) {
  btn.disabled = true;
  try {
    const res = await MV.api('/api/pantry.php?action=consume_recipe', { method: 'POST', body: { recipe_id: recipeId } });
    if (currentPlan.meals[type]) currentPlan.meals[type].done = true;
    renderPlan(currentPlan);
    MV.saveLocal('today_plan', currentPlan);
    MV.toast(res.consumed.length ? `Descontamos de tu despensa: ${res.consumed.join(', ')}` : '¡Buen provecho! 🍽️');
  } catch (err) {
    btn.disabled = false;
    MV.toast(err.message, true);
  }
}

let currentPlan = null;
let kcalTarget = null;

function renderPlan(plan) {
  currentPlan = plan;
  const container = document.getElementById('meals-container');
  const order = MEAL_ORDER.filter(t => plan.meals[t]);
  container.innerHTML = order.map(t => renderMeal(t, plan.meals[t])).join('');
  container.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const body = document.getElementById(btn.dataset.target);
      const open = body.style.display !== 'none';
      body.style.display = open ? 'none' : 'block';
      btn.textContent = open ? 'Ver receta ▾' : 'Ocultar receta ▴';
    });
  });
  container.querySelectorAll('.btn-cooked').forEach(btn => {
    btn.addEventListener('click', () => markCooked(btn, parseInt(btn.dataset.recipeId, 10), btn.dataset.mealType));
  });
  container.querySelectorAll('.btn-swap').forEach(btn => {
    btn.addEventListener('click', () => swapMeal(btn, btn.dataset.mealType));
  });
  container.querySelectorAll('.btn-cook-mode').forEach(btn => {
    btn.addEventListener('click', () => MV.cookMode(plan.meals[btn.dataset.mealType]));
  });

  if (plan.consejo_coach) {
    document.getElementById('coach-tip-text').textContent = plan.consejo_coach;
    document.getElementById('coach-tip').style.display = 'block';
  }
  renderNutritionSummary(plan);
}

function renderNutritionSummary(plan) {
  const meals = Object.values(plan.meals);
  const eaten = meals.filter(m => m.done);
  const eatenKcal = eaten.reduce((sum, m) => sum + (m.kcal_porcion || 0), 0);
  const eatenProtein = eaten.reduce((sum, m) => sum + (m.protein_porcion || 0), 0);
  const plannedKcal = meals.reduce((sum, m) => sum + (m.kcal_porcion || 0), 0);

  if (!kcalTarget) {
    document.getElementById('nutrition-summary').style.display = 'none';
    document.getElementById('nutrition-nudge').style.display = 'block';
    return;
  }
  document.getElementById('nutrition-nudge').style.display = 'none';
  document.getElementById('nutrition-summary').style.display = 'block';
  document.getElementById('kcal-summary-text').textContent = `${eatenKcal} / ${kcalTarget} kcal`;
  document.getElementById('protein-summary-text').textContent = eaten.length
    ? `💪 ${eatenProtein} g de proteína · plan del día: ${plannedKcal} kcal`
    : `Aún no marcas ninguna comida como hecha hoy · plan del día: ${plannedKcal} kcal`;

  const ratio = Math.min(1, eatenKcal / kcalTarget);
  const circumference = 188.5;
  const ring = document.getElementById('kcal-ring');
  ring.style.strokeDashoffset = String(circumference * (1 - ratio));
  ring.style.stroke = eatenKcal > kcalTarget * 1.1 ? 'var(--warn)' : 'var(--green)';
}

async function swapMeal(btn, type) {
  btn.disabled = true;
  btn.textContent = '...';
  try {
    const res = await MV.api('/api/planner.php?action=swap_meal', { method: 'POST', body: { meal_type: type } });
    currentPlan.meals[type] = res.meal;
    renderPlan(currentPlan);
    MV.saveLocal('today_plan', currentPlan);
    MV.toast('¡Listo! Cambiamos ese plato.');
  } catch (err) {
    btn.disabled = false;
    btn.textContent = '🔄 Cambiar plato';
    MV.toast(err.message, true);
  }
}

async function loadToday() {
  document.getElementById('loading').style.display = 'block';
  document.getElementById('btn-regen').style.display = 'none';
  try {
    const [res, profRes] = await Promise.all([
      MV.api('/api/planner.php?action=today'),
      MV.api('/api/profile.php?action=get').catch(() => ({ profile: {} })),
    ]);
    kcalTarget = profRes.profile.kcal_target || null;
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

// Si el Modo Cocina marca un plato como hecho, refleja el cambio en esta página
document.addEventListener('mv-meal-cooked', (e) => {
  if (!currentPlan) return;
  for (const type of MEAL_ORDER) {
    if (currentPlan.meals[type] && currentPlan.meals[type].id === e.detail.recipeId) {
      currentPlan.meals[type].done = true;
      renderPlan(currentPlan);
      MV.saveLocal('today_plan', currentPlan);
    }
  }
});

// ---------- Agua rápida ----------
let waterCount = 0;
let waterTarget = 8;
async function loadWaterQuick() {
  try {
    const [progress, prof] = await Promise.all([
      MV.api('/api/progress.php?action=list'),
      MV.api('/api/profile.php?action=get'),
    ]);
    waterCount = progress.today ? progress.today.water : 0;
    waterTarget = prof.profile.water_target || 8;
    document.getElementById('water-quick-label').textContent = `${waterCount} / ${waterTarget} vasos`;
  } catch (err) { /* no crítico */ }
}
document.getElementById('btn-water-quick').addEventListener('click', async (e) => {
  const btn = e.currentTarget;
  btn.disabled = true;
  try {
    const res = await MV.api('/api/progress.php?action=water', { method: 'POST', body: { delta: 1 } });
    waterCount = res.water;
    document.getElementById('water-quick-label').textContent = `${waterCount} / ${waterTarget} vasos`;
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
  }
});
loadWaterQuick();

// Lo esencial de la página va primero: si algo de abajo falla (ej. una
// función nueva en una versión vieja de app.js cacheada), el menú de
// hoy ya se está cargando y no se queda pegado en el esqueleto de carga.
loadToday();

// ---------- Banner de instalar app (recordatorio breve, descartable) ----------
try {
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
} catch (e) { /* el banner de instalación es secundario: nunca debe romper la página */ }
</script>
