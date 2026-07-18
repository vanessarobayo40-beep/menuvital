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

let currentWeek = null;

function dayKcal(day) {
  return Object.values(day.meals).reduce((sum, m) => sum + (m.kcal_porcion || 0), 0);
}

function renderDay(day, idx) {
  const order = MEAL_ORDER.filter(t => day.meals[t]);
  return `
    <div class="card" style="margin-bottom:14px;">
      <div style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;" data-day-toggle="${idx}">
        <h3 style="margin:0;font-size:16px;">${escapeHtml(day.day)}</h3>
        <span class="badge badge-purple">≈ ${dayKcal(day)} kcal</span>
      </div>
      <div id="day-body-${idx}" style="display:none;margin-top:14px;">
        ${order.map(t => {
          const meal = day.meals[t];
          const done = !!meal.done;
          return `
          <div style="display:flex;gap:10px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px dashed var(--border);">
            <img src="${escapeHtml(meal.image_url)}" alt="${escapeHtml(meal.name)}" loading="lazy"
                 style="width:56px;height:56px;border-radius:10px;object-fit:cover;flex-shrink:0;background:var(--grad-soft);"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2256%22 height=%2256%22%3E%3Crect width=%2256%22 height=%2256%22 rx=%2210%22 fill=%22%23EFF6F3%22/%3E%3C/svg%3E';">
            <div style="flex:1;min-width:0;">
              <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:6px;">
                <span class="badge badge-green">${MEAL_LABELS[t]}</span>
                <button class="btn-swap-week" data-day="${idx}" data-meal-type="${t}" style="background:none;border:none;color:var(--t3);font-size:11px;font-weight:600;padding:2px;flex-shrink:0;">🔄 Cambiar</button>
              </div>
              <h4 style="margin:6px 0 4px;font-size:15px;">${escapeHtml(meal.name)}</h4>
              <p class="muted" style="font-size:11px;margin:0 0 6px;">⏱ ${meal.time_min} min · 🔥 ${meal.kcal_porcion} kcal · 💪 ${meal.protein_porcion}g prot · 🌾 ${meal.carbs_porcion}g carbos · 🧈 ${meal.fat_porcion}g grasa</p>
              <p style="font-size:13px;margin:0 0 8px;"><strong>Ingredientes:</strong> ${meal.ingredients.map(i => escapeHtml(i.item)).join(', ')}</p>
              <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <button class="btn-cook-mode-week" data-day="${idx}" data-meal-type="${t}" style="border:none;border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;background:var(--grad-soft);color:var(--purple-dark);">👩‍🍳 Modo Cocina</button>
                <button class="btn-cooked-week" data-recipe-id="${meal.id}" data-day="${idx}" data-meal-type="${t}" style="border:none;border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;${done ? 'background:var(--green-light);color:var(--green-dark);' : 'background:var(--surface);color:var(--t2);'}" ${done ? 'disabled' : ''}>${done ? '✅ Hecha' : '🍳 Ya la hice'}</button>
              </div>
            </div>
          </div>`;
        }).join('')}
        ${day.consejo_coach ? `<p style="font-size:13px;color:var(--t2);margin:0;">💬 ${escapeHtml(day.consejo_coach)}</p>` : ''}
      </div>
    </div>`;
}

async function markCookedWeek(btn, recipeId, dayIdx, type) {
  btn.disabled = true;
  try {
    const res = await MV.api('/api/pantry.php?action=consume_recipe', { method: 'POST', body: { recipe_id: recipeId } });
    if (currentWeek.days[dayIdx] && currentWeek.days[dayIdx].meals[type]) {
      currentWeek.days[dayIdx].meals[type].done = true;
    }
    renderWeek(currentWeek);
    MV.saveLocal('week_plan', currentWeek);
    MV.toast(res.consumed.length ? `Descontamos de tu despensa: ${res.consumed.join(', ')}` : '¡Buen provecho! 🍽️');
  } catch (err) {
    btn.disabled = false;
    MV.toast(err.message, true);
  }
}

async function swapWeekMeal(btn, dayIdx, type) {
  btn.disabled = true;
  btn.textContent = '...';
  try {
    const res = await MV.api('/api/planner.php?action=swap_week_meal', { method: 'POST', body: { day_index: dayIdx, meal_type: type } });
    currentWeek.days[dayIdx].meals[type] = res.meal;
    renderWeek(currentWeek);
    document.getElementById('day-body-' + dayIdx).style.display = 'block';
    MV.saveLocal('week_plan', currentWeek);
    MV.toast('¡Listo! Cambiamos ese plato.');
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
    btn.textContent = '🔄 Cambiar';
  }
}

function renderWeek(plan) {
  currentWeek = plan;
  const openDays = new Set(
    Array.from(document.querySelectorAll('[id^="day-body-"]'))
      .filter(el => el.style.display !== 'none')
      .map(el => el.id.replace('day-body-', ''))
  );
  const container = document.getElementById('week-container');
  container.innerHTML = plan.days.map((d, i) => renderDay(d, i)).join('');
  container.querySelectorAll('[data-day-toggle]').forEach(el => {
    const idx = el.dataset.dayToggle;
    const body = document.getElementById('day-body-' + idx);
    if (openDays.has(idx)) body.style.display = 'block';
    el.addEventListener('click', () => {
      body.style.display = body.style.display === 'none' ? 'block' : 'none';
    });
  });
  container.querySelectorAll('.btn-cooked-week').forEach(btn => {
    btn.addEventListener('click', () => markCookedWeek(btn, parseInt(btn.dataset.recipeId, 10), parseInt(btn.dataset.day, 10), btn.dataset.mealType));
  });
  container.querySelectorAll('.btn-swap-week').forEach(btn => {
    btn.addEventListener('click', () => swapWeekMeal(btn, parseInt(btn.dataset.day, 10), btn.dataset.mealType));
  });
  container.querySelectorAll('.btn-cook-mode-week').forEach(btn => {
    const dayIdx = parseInt(btn.dataset.day, 10);
    btn.addEventListener('click', () => MV.cookMode(plan.days[dayIdx].meals[btn.dataset.mealType]));
  });
}

document.addEventListener('mv-meal-cooked', (e) => {
  if (!currentWeek) return;
  let changed = false;
  currentWeek.days.forEach(day => {
    Object.values(day.meals).forEach(meal => {
      if (meal.id === e.detail.recipeId) { meal.done = true; changed = true; }
    });
  });
  if (changed) { renderWeek(currentWeek); MV.saveLocal('week_plan', currentWeek); }
});

async function loadWeek() {
  document.getElementById('loading').style.display = 'block';
  try {
    const res = await MV.api('/api/planner.php?action=week');
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

loadWeek();
</script>
