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
<p class="muted" style="margin-top:0;font-size:14px;">Elige qué vas a comer cada día, o deja que te sugiramos.</p>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
  <button id="btn-week-prev" class="btn btn-secondary btn-sm" aria-label="Semana anterior">‹</button>
  <span id="week-range-label" style="font-size:13px;font-weight:600;color:var(--t2);"></span>
  <button id="btn-week-next" class="btn btn-secondary btn-sm" aria-label="Semana siguiente">›</button>
</div>

<div id="loading">
  <div class="skeleton" style="height:100px;margin-bottom:12px;"></div>
  <div class="skeleton" style="height:100px;margin-bottom:12px;"></div>
  <div class="skeleton" style="height:100px;"></div>
</div>

<div id="week-container"></div>

<button id="btn-regen-week" class="btn btn-outline btn-block" style="margin-top:8px;display:none;">
  ✨ Sugerir toda la semana
</button>

<button id="btn-confirm-week" class="btn btn-primary btn-block" style="margin-top:10px;display:none;">
  ✅ Confirmar menú y ver lista de compras
</button>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
const MEAL_LABELS = { desayuno: 'Desayuno', almuerzo: 'Almuerzo', cena: 'Cena', snack: 'Snack' };
const MEAL_ORDER = ['desayuno', 'almuerzo', 'cena', 'snack'];
const DAY_NAMES = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s ?? '';
  return d.innerHTML;
}

function fmtLocal(d) {
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}

let weekOffset = 0;
let weekDates = [];
let currentEntries = {};
let mealTypesWeek = [];

function computeWeekDates(offset) {
  const now = new Date();
  const day = now.getDay(); // 0=domingo
  const diffToMonday = day === 0 ? -6 : 1 - day;
  const monday = new Date(now);
  monday.setDate(now.getDate() + diffToMonday + offset * 7);
  const dates = [];
  for (let i = 0; i < 7; i++) {
    const d = new Date(monday);
    d.setDate(monday.getDate() + i);
    dates.push(d);
  }
  return dates;
}

function dayKcal(date) {
  const dayEntries = currentEntries[fmtLocal(date)] || {};
  return Object.values(dayEntries).reduce((sum, m) => sum + (m.kcal_porcion || 0), 0);
}

function renderMealRow(type, meal, dateStr, idx) {
  if (!meal) {
    return `
      <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:10px;padding-bottom:10px;border-bottom:1px dashed var(--border);">
        <span class="badge" style="background:var(--surface-2);color:var(--t2);">${MEAL_LABELS[type]}</span>
        <div style="display:flex;gap:6px;">
          <a href="/app/recetas.php" style="font-size:11px;font-weight:600;color:var(--green-dark);text-decoration:none;">🍽 Elegir</a>
          <button class="btn-suggest-day" data-date="${dateStr}" style="background:none;border:none;color:var(--green-dark);font-size:11px;font-weight:600;padding:0;">✨ Sugerir</button>
        </div>
      </div>`;
  }
  const done = !!meal.done;
  return `
    <div style="display:flex;gap:10px;margin-bottom:14px;padding-bottom:14px;border-bottom:1px dashed var(--border);">
      <img src="${escapeHtml(meal.image_url)}" alt="${escapeHtml(meal.name)}" loading="lazy"
           style="width:56px;height:56px;border-radius:10px;object-fit:cover;flex-shrink:0;background:var(--grad-soft);"
           onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2256%22 height=%2256%22%3E%3Crect width=%2256%22 height=%2256%22 rx=%2210%22 fill=%22%23EFF6F3%22/%3E%3C/svg%3E';">
      <div style="flex:1;min-width:0;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:6px;">
          <span class="badge badge-green">${MEAL_LABELS[type]}${meal.servings > 1 ? ` · ${meal.servings}p` : ''}</span>
          <button class="btn-swap-week" data-entry-id="${meal.entry_id}" style="background:none;border:none;color:var(--t3);font-size:11px;font-weight:600;padding:2px;flex-shrink:0;">🔄 Cambiar</button>
        </div>
        <h4 style="margin:6px 0 4px;font-size:15px;">${escapeHtml(meal.name)}</h4>
        <p class="muted" style="font-size:11px;margin:0 0 6px;">⏱ ${meal.time_min} min · 🔥 ${meal.kcal_porcion} kcal · 💪 ${meal.protein_porcion}g prot · 🌾 ${meal.carbs_porcion}g carbos · 🧈 ${meal.fat_porcion}g grasa</p>
        <p style="font-size:13px;margin:0 0 8px;"><strong>Ingredientes:</strong> ${meal.ingredients.map(i => escapeHtml(i.item)).join(', ')}</p>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <button class="btn-cook-mode-week" data-entry-id="${meal.entry_id}" style="border:none;border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;background:var(--grad-soft);color:var(--green-dark);">👩‍🍳 Modo Cocina</button>
          <button class="btn-cooked-week" data-entry-id="${meal.entry_id}" style="border:none;border-radius:8px;padding:7px 12px;font-size:12px;font-weight:600;${done ? 'background:var(--green-light);color:var(--green-dark);' : 'background:var(--surface);color:var(--t2);'}" ${done ? 'disabled' : ''}>${done ? '✅ Hecha' : '🍳 Ya la hice'}</button>
        </div>
      </div>
    </div>`;
}

function renderDay(date, idx) {
  const dateStr = fmtLocal(date);
  const dayEntries = currentEntries[dateStr] || {};
  const isToday = dateStr === fmtLocal(new Date());
  return `
    <div class="card" style="margin-bottom:14px;${isToday ? 'border-color:var(--green);' : ''}">
      <div style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;" data-day-toggle="${idx}">
        <h3 style="margin:0;font-size:16px;">${DAY_NAMES[date.getDay()]} ${date.getDate()}${isToday ? ' · hoy' : ''}</h3>
        <span class="badge" style="background:var(--surface-2);color:var(--t2);">≈ ${dayKcal(date)} kcal</span>
      </div>
      <div id="day-body-${idx}" style="display:none;margin-top:14px;">
        ${mealTypesWeek.map(t => renderMealRow(t, dayEntries[t], dateStr, idx)).join('')}
      </div>
    </div>`;
}

async function markCookedWeek(btn, entryId) {
  const meal = findMealByEntryId(entryId);
  const portions = await MV.askPortions(meal ? meal.servings : 1);
  if (portions === null) return;
  btn.disabled = true;
  try {
    const res = await MV.api('/api/menu.php?action=done', { method: 'POST', body: { entry_id: entryId, portions } });
    if (meal) meal.done = true;
    renderWeek();
    MV.toast(res.consumed.length ? `Descontamos de tu despensa: ${res.consumed.join(', ')}` : '¡Buen provecho! 🍽️');
  } catch (err) {
    btn.disabled = false;
    MV.toast(err.message, true);
  }
}

function findMealByEntryId(entryId) {
  for (const date of Object.keys(currentEntries)) {
    for (const type of Object.keys(currentEntries[date])) {
      if (currentEntries[date][type].entry_id === entryId) return currentEntries[date][type];
    }
  }
  return null;
}

async function swapWeekMeal(btn, entryId) {
  btn.disabled = true;
  btn.textContent = '...';
  try {
    const res = await MV.api('/api/menu.php?action=swap', { method: 'POST', body: { entry_id: entryId } });
    const dateStr = res.meal.date;
    currentEntries[dateStr] = currentEntries[dateStr] || {};
    currentEntries[dateStr][res.meal.meal_type] = res.meal;
    renderWeek(true);
    MV.toast('¡Listo! Cambiamos ese plato.');
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
    btn.textContent = '🔄 Cambiar';
  }
}

async function suggestDay(btn) {
  btn.disabled = true;
  try {
    const date = btn.dataset.date;
    await MV.api('/api/menu.php?action=suggest', { method: 'POST', body: { from: date, to: date, replace_suggested: false } });
    await loadWeek(true);
  } catch (err) {
    btn.disabled = false;
    MV.toast(err.message, true);
  }
}

function renderWeek(keepOpen) {
  const openDays = keepOpen ? new Set(
    Array.from(document.querySelectorAll('[id^="day-body-"]'))
      .filter(el => el.style.display !== 'none')
      .map(el => el.id.replace('day-body-', ''))
  ) : new Set(['0']); // por defecto, hoy/lunes abierto

  const container = document.getElementById('week-container');
  container.innerHTML = weekDates.map((d, i) => renderDay(d, i)).join('');
  container.querySelectorAll('[data-day-toggle]').forEach(el => {
    const idx = el.dataset.dayToggle;
    const body = document.getElementById('day-body-' + idx);
    if (openDays.has(idx)) body.style.display = 'block';
    el.addEventListener('click', () => {
      body.style.display = body.style.display === 'none' ? 'block' : 'none';
    });
  });
  container.querySelectorAll('.btn-cooked-week').forEach(btn => {
    btn.addEventListener('click', () => markCookedWeek(btn, parseInt(btn.dataset.entryId, 10)));
  });
  container.querySelectorAll('.btn-swap-week').forEach(btn => {
    btn.addEventListener('click', () => swapWeekMeal(btn, parseInt(btn.dataset.entryId, 10)));
  });
  container.querySelectorAll('.btn-cook-mode-week').forEach(btn => {
    btn.addEventListener('click', () => MV.cookMode(findMealByEntryId(parseInt(btn.dataset.entryId, 10))));
  });
  container.querySelectorAll('.btn-suggest-day').forEach(btn => {
    btn.addEventListener('click', () => suggestDay(btn));
  });
}

document.addEventListener('mv-meal-cooked', (e) => {
  let changed = false;
  for (const date of Object.keys(currentEntries)) {
    for (const type of Object.keys(currentEntries[date])) {
      const m = currentEntries[date][type];
      if (m.entry_id === e.detail.entryId || m.id === e.detail.recipeId) { m.done = true; changed = true; }
    }
  }
  if (changed) renderWeek(true);
});

function updateWeekLabel() {
  const first = weekDates[0], last = weekDates[6];
  const fmt = (d) => `${d.getDate()} ${d.toLocaleDateString('es-CO', { month: 'short' })}`;
  document.getElementById('week-range-label').textContent = weekOffset === 0
    ? `Esta semana · ${fmt(first)}–${fmt(last)}`
    : `${fmt(first)}–${fmt(last)}`;
}

async function loadWeek(keepOpen) {
  weekDates = computeWeekDates(weekOffset);
  updateWeekLabel();
  if (!keepOpen) document.getElementById('loading').style.display = 'block';
  try {
    const from = fmtLocal(weekDates[0]);
    const to = fmtLocal(weekDates[6]);
    const res = await MV.api(`/api/menu.php?action=list&from=${from}&to=${to}`);
    currentEntries = res.entries;
    mealTypesWeek = res.meal_types;
    renderWeek(keepOpen);
    document.getElementById('btn-regen-week').style.display = 'block';
    const hasEntries = Object.values(currentEntries).some(day => Object.keys(day).length > 0);
    document.getElementById('btn-confirm-week').style.display = hasEntries ? 'block' : 'none';
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    document.getElementById('loading').style.display = 'none';
  }
}

document.getElementById('btn-week-prev').addEventListener('click', () => { weekOffset--; loadWeek(); });
document.getElementById('btn-week-next').addEventListener('click', () => { weekOffset++; loadWeek(); });

document.getElementById('btn-regen-week').addEventListener('click', async () => {
  const btn = document.getElementById('btn-regen-week');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner dark" style="display:inline-block;"></span> Generando tu semana...';
  try {
    const from = fmtLocal(weekDates[0]);
    const to = fmtLocal(weekDates[6]);
    await MV.api('/api/menu.php?action=suggest', { method: 'POST', body: { from, to, replace_suggested: true } });
    await loadWeek(true);
    MV.toast('¡Nuevo plan semanal listo!');
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
    btn.innerHTML = '✨ Sugerir toda la semana';
  }
});

document.getElementById('btn-confirm-week').addEventListener('click', () => {
  window.location.href = '/app/mercado.php?tab=compras';
});

loadWeek();
</script>
