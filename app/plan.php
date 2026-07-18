<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Semana';
$ACTIVE_NAV = 'plan';
require __DIR__ . '/../includes/layout_top.php';
?>

<h2 style="margin-bottom:4px;">Tu plan de la semana</h2>
<p class="muted" style="margin-top:0;font-size:14px;">7 días de menú listo, hecho con lo que tienes disponible.</p>

<div id="week-empty" class="card text-center" style="display:none;margin-bottom:18px;">
  <p style="margin:0 0 12px;font-size:14px;">Agrega primero tu mercado disponible para generar el plan semanal.</p>
  <a href="/app/mercado.php" class="btn btn-primary btn-sm">Ingresar mi mercado</a>
</div>

<div id="loading">
  <div class="skeleton" style="height:100px;margin-bottom:12px;"></div>
  <div class="skeleton" style="height:100px;margin-bottom:12px;"></div>
  <div class="skeleton" style="height:100px;"></div>
</div>

<div id="week-container"></div>

<button id="btn-regen-week" class="btn btn-outline btn-block" style="margin-top:8px;display:none;">
  🔄 Generar un nuevo plan semanal
</button>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
const MEAL_LABELS = { desayuno: 'Desayuno', almuerzo: 'Almuerzo', cena: 'Cena', snack: 'Snack' };
const MEAL_ORDER = ['desayuno', 'almuerzo', 'cena', 'snack'];

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s ?? '';
  return d.innerHTML;
}

let weekKey = 'consumed_week_default';
let consumedWeekIds = new Set(MV.loadLocal(weekKey, []));
function saveConsumedWeek() {
  MV.saveLocal(weekKey, Array.from(consumedWeekIds));
}

function renderDay(day, idx) {
  const order = MEAL_ORDER.filter(t => day.meals[t]);
  return `
    <div class="card" style="margin-bottom:14px;">
      <div style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;" data-day-toggle="${idx}">
        <h3 style="margin:0;font-size:16px;">${escapeHtml(day.day)}</h3>
        <span style="color:var(--t3);font-size:13px;">${order.map(t => MEAL_LABELS[t]).join(' · ')}</span>
      </div>
      <div id="day-body-${idx}" style="display:none;margin-top:14px;">
        ${order.map(t => {
          const meal = day.meals[t];
          const done = consumedWeekIds.has(meal.id);
          return `
          <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px dashed var(--border);">
            <span class="badge badge-green">${MEAL_LABELS[t]}</span>
            <h4 style="margin:8px 0 4px;font-size:15px;">${escapeHtml(meal.name)}</h4>
            <p class="muted" style="font-size:12px;margin:0 0 8px;">⏱ ${meal.time_min} min · 🔥 ${meal.kcal_porcion} kcal</p>
            <p style="font-size:13px;margin:0 0 8px;"><strong>Ingredientes:</strong> ${meal.ingredients.map(i => escapeHtml(i.item)).join(', ')}</p>
            <button class="btn-cooked-week" data-recipe-id="${meal.id}" style="border:none;border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;${done ? 'background:var(--green-light);color:var(--green-dark);' : 'background:var(--surface);color:var(--t2);'}" ${done ? 'disabled' : ''}>${done ? '✅ Hecha' : '🍳 Ya la hice'}</button>
          </div>`;
        }).join('')}
        ${day.consejo_coach ? `<p style="font-size:13px;color:var(--t2);margin:0;">💬 ${escapeHtml(day.consejo_coach)}</p>` : ''}
      </div>
    </div>`;
}

async function markCookedWeek(btn, recipeId) {
  btn.disabled = true;
  try {
    const res = await MV.api('/api/pantry.php?action=consume_recipe', { method: 'POST', body: { recipe_id: recipeId } });
    consumedWeekIds.add(recipeId);
    saveConsumedWeek();
    btn.textContent = '✅ Hecha';
    btn.style.background = 'var(--green-light)';
    btn.style.color = 'var(--green-dark)';
    MV.toast(res.consumed.length ? `Descontamos de tu despensa: ${res.consumed.join(', ')}` : '¡Buen provecho! 🍽️');
  } catch (err) {
    btn.disabled = false;
    MV.toast(err.message, true);
  }
}

function renderWeek(plan) {
  const container = document.getElementById('week-container');
  container.innerHTML = plan.days.map((d, i) => renderDay(d, i)).join('');
  container.querySelectorAll('[data-day-toggle]').forEach(el => {
    el.addEventListener('click', () => {
      const body = document.getElementById('day-body-' + el.dataset.dayToggle);
      body.style.display = body.style.display === 'none' ? 'block' : 'none';
    });
  });
  container.querySelectorAll('.btn-cooked-week').forEach(btn => {
    btn.addEventListener('click', () => markCookedWeek(btn, parseInt(btn.dataset.recipeId, 10)));
  });
}

async function loadWeek() {
  document.getElementById('loading').style.display = 'block';
  try {
    const res = await MV.api('/api/planner.php?action=week');
    weekKey = 'consumed_week_' + res.start_date;
    consumedWeekIds = new Set(MV.loadLocal(weekKey, []));
    renderWeek(res.plan);
    document.getElementById('btn-regen-week').style.display = 'block';
    MV.saveLocal('week_plan', res.plan);
  } catch (err) {
    const cached = MV.loadLocal('week_plan');
    if (cached) {
      renderWeek(cached);
      MV.toast('Mostrando tu último plan guardado (sin conexión).', true);
    } else {
      MV.toast(err.message, true);
    }
  } finally {
    document.getElementById('loading').style.display = 'none';
  }
}

document.getElementById('btn-regen-week').addEventListener('click', async () => {
  const btn = document.getElementById('btn-regen-week');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner dark" style="display:inline-block;"></span> Generando tu semana...';
  try {
    const res = await MV.api('/api/planner.php?action=week_new', { method: 'POST' });
    weekKey = 'consumed_week_' + res.start_date;
    consumedWeekIds = new Set(MV.loadLocal(weekKey, []));
    renderWeek(res.plan);
    MV.saveLocal('week_plan', res.plan);
    MV.toast('¡Nuevo plan semanal listo!');
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
    btn.innerHTML = '🔄 Generar un nuevo plan semanal';
  }
});

MV.api('/api/pantry.php?action=list').then(res => {
  if (!res.items.length) document.getElementById('week-empty').style.display = 'block';
}).catch(() => {});

loadWeek();
</script>
