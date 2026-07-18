<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Recetas';
$ACTIVE_NAV = 'recetas';
require __DIR__ . '/../includes/layout_top.php';
?>

<h2 style="margin-bottom:4px;">Recetario</h2>
<p class="muted" style="margin-top:0;font-size:14px;">Explora las 105 recetas, marca tus favoritas, agrega las tuyas y cocínalas cuando quieras.</p>

<button type="button" id="btn-open-create" class="btn btn-primary btn-block" style="margin-bottom:14px;">+ Agregar mi receta</button>

<div class="field" style="margin-bottom:10px;">
  <input type="text" id="recipe-search" placeholder="Buscar receta..." autocomplete="off">
</div>

<div class="chips" id="recipe-filters" style="margin-bottom:16px;">
  <button type="button" class="chip tag active" data-filter="todas">Todas</button>
  <button type="button" class="chip tag" data-filter="desayuno">Desayuno</button>
  <button type="button" class="chip tag" data-filter="almuerzo">Almuerzo</button>
  <button type="button" class="chip tag" data-filter="cena">Cena</button>
  <button type="button" class="chip tag" data-filter="snack">Snack</button>
  <button type="button" class="chip tag" data-filter="rapidas">⏱ Rápidas</button>
  <button type="button" class="chip tag" data-filter="favoritas">❤ Favoritas</button>
  <button type="button" class="chip tag" data-filter="mias">👩‍🍳 Mías</button>
</div>

<div id="recipe-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;"></div>

<div id="recipe-empty" class="empty-state" style="display:none;">
  <p>No encontramos recetas con ese filtro.</p>
</div>

<div id="loading-recipes">
  <div class="skeleton" style="height:180px;"></div>
</div>

<!-- ---------- Detalle de receta (hoja inferior) ---------- -->
<div id="detail-backdrop" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:70;align-items:flex-end;justify-content:center;">
  <div style="background:var(--card-bg);border-radius:20px 20px 0 0;width:100%;max-width:520px;max-height:88vh;overflow-y:auto;">
    <div style="position:relative;">
      <img id="detail-image" src="" alt="" style="width:100%;height:200px;object-fit:cover;background:var(--grad-soft);">
      <button type="button" id="detail-close" aria-label="Cerrar" style="position:absolute;top:12px;right:12px;background:rgba(16,32,27,0.55);color:#fff;border:none;width:32px;height:32px;border-radius:50%;font-size:16px;">×</button>
      <button type="button" id="detail-fav" aria-label="Favorita" style="position:absolute;top:12px;left:12px;background:rgba(16,32,27,0.55);color:#fff;border:none;width:36px;height:36px;border-radius:50%;font-size:17px;">🤍</button>
    </div>
    <div style="padding:18px;">
      <span class="meal-tag" id="detail-tag" style="margin:0 0 8px;"></span>
      <h2 id="detail-name" style="margin:4px 0 6px;font-size:19px;"></h2>
      <p class="muted" id="detail-meta" style="margin:0 0 14px;font-size:13px;"></p>

      <div class="stat-row" style="margin-bottom:16px;">
        <div class="stat-tile"><div class="value" id="detail-kcal">–</div><div class="label">kcal</div></div>
        <div class="stat-tile"><div class="value" id="detail-protein">–</div><div class="label">proteína g</div></div>
        <div class="stat-tile"><div class="value" id="detail-carbs">–</div><div class="label">carbos g</div></div>
        <div class="stat-tile"><div class="value" id="detail-fat">–</div><div class="label">grasa g</div></div>
      </div>

      <button type="button" id="detail-cook-mode" class="btn btn-primary btn-block" style="margin-bottom:10px;">👩‍🍳 Modo Cocina</button>
      <button type="button" id="detail-delete" class="btn btn-danger btn-block" style="display:none;margin-bottom:18px;">🗑 Eliminar mi receta</button>

      <p class="section-title" style="margin-top:0;">Ingredientes</p>
      <ul id="detail-ingredients" style="margin:0 0 16px;padding-left:20px;font-size:14px;"></ul>

      <p class="section-title">Preparación</p>
      <ol id="detail-steps" style="margin:0;padding-left:20px;font-size:14px;"></ol>
    </div>
  </div>
</div>

<!-- ---------- Crear receta propia (hoja inferior) ---------- -->
<div id="create-backdrop" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:75;align-items:flex-end;justify-content:center;">
  <div style="background:var(--card-bg);border-radius:20px 20px 0 0;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;padding:20px;">
    <h3 style="margin:0 0 4px;font-size:17px;">Agrega tu receta</h3>
    <p class="muted" style="margin:0 0 16px;font-size:13px;">Escribe el nombre e ingredientes — la IA calcula la nutrición y le busca una foto real, igual que las demás recetas.</p>

    <div class="field">
      <label>Nombre del plato</label>
      <input type="text" id="cr-name" maxlength="150" placeholder="Ej: Sancocho de mi abuela">
    </div>
    <div style="display:flex;gap:10px;">
      <div class="field" style="flex:1;">
        <label>Tipo de comida</label>
        <select id="cr-meal-type">
          <option value="desayuno">Desayuno</option>
          <option value="almuerzo" selected>Almuerzo</option>
          <option value="cena">Cena</option>
          <option value="snack">Snack</option>
        </select>
      </div>
      <div class="field" style="flex:1;">
        <label>Tiempo (min)</label>
        <input type="number" id="cr-time" min="1" max="240" value="30">
      </div>
    </div>

    <label style="display:block;font-size:13px;font-weight:600;color:var(--t2);margin-bottom:6px;">Ingredientes</label>
    <div id="cr-ingredients"></div>
    <button type="button" id="cr-add-ingredient" class="btn btn-secondary btn-sm" style="margin-bottom:18px;">+ Agregar ingrediente</button>

    <label style="display:block;font-size:13px;font-weight:600;color:var(--t2);margin-bottom:6px;">Preparación</label>
    <div id="cr-steps"></div>
    <button type="button" id="cr-add-step" class="btn btn-secondary btn-sm" style="margin-bottom:18px;">+ Agregar paso</button>

    <p id="cr-error" class="field error-text" style="display:none;"></p>
    <div style="display:flex;gap:10px;">
      <button type="button" id="cr-cancel" class="btn btn-secondary" style="flex:1;">Cancelar</button>
      <button type="button" id="cr-submit" class="btn btn-primary" style="flex:2;">Crear receta</button>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
const MEAL_LABELS = { desayuno: 'Desayuno', almuerzo: 'Almuerzo', cena: 'Cena', snack: 'Snack' };
let allRecipes = [];
let currentFilter = 'todas';
let currentQuery = '';
let currentDetail = null;

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s ?? '';
  return d.innerHTML;
}

function matchesFilter(r) {
  if (currentFilter === 'todas') return true;
  if (currentFilter === 'rapidas') return r.time_min <= 25;
  if (currentFilter === 'favoritas') return r.is_favorite;
  if (currentFilter === 'mias') return r.is_own;
  return r.meal_type === currentFilter;
}

function renderGrid() {
  const q = currentQuery.trim().toLowerCase();
  const filtered = allRecipes.filter(r => matchesFilter(r) && (!q || r.name.toLowerCase().includes(q)));
  const grid = document.getElementById('recipe-grid');
  document.getElementById('recipe-empty').style.display = filtered.length ? 'none' : 'block';

  grid.innerHTML = filtered.map(r => `
    <div class="card" style="padding:0;overflow:hidden;cursor:pointer;" data-open-recipe="${r.id}">
      <div style="position:relative;">
        <img src="${escapeHtml(r.image_url)}" alt="${escapeHtml(r.name)}" loading="lazy"
             style="width:100%;height:110px;object-fit:cover;background:var(--grad-soft);"
             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22110%22%3E%3Crect width=%22200%22 height=%22110%22 fill=%22%23EFF6F3%22/%3E%3C/svg%3E';">
        <span style="position:absolute;top:6px;left:6px;background:rgba(255,255,255,0.92);color:var(--green-dark);font-size:10px;font-weight:700;padding:2px 8px;border-radius:999px;text-transform:uppercase;">${MEAL_LABELS[r.meal_type] || r.meal_type}</span>
        ${r.is_own ? '<span style="position:absolute;bottom:6px;left:6px;background:rgba(255,255,255,0.92);color:var(--purple-dark);font-size:10px;font-weight:700;padding:2px 8px;border-radius:999px;">👩‍🍳 Mía</span>' : ''}
        ${r.is_favorite ? '<span style="position:absolute;top:6px;right:6px;font-size:16px;">❤</span>' : ''}
      </div>
      <div style="padding:10px;">
        <h3 style="margin:0 0 4px;font-size:13px;line-height:1.3;">${escapeHtml(r.name)}</h3>
        <p class="muted" style="margin:0;font-size:11px;">⏱ ${r.time_min} min · 🔥 ${r.kcal} kcal</p>
      </div>
    </div>`).join('');

  grid.querySelectorAll('[data-open-recipe]').forEach(card => {
    card.addEventListener('click', () => openDetail(parseInt(card.dataset.openRecipe, 10)));
  });
}

document.getElementById('recipe-search').addEventListener('input', MV.debounce((e) => {
  currentQuery = e.target.value;
  renderGrid();
}, 150));

document.querySelectorAll('#recipe-filters button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('#recipe-filters button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentFilter = btn.dataset.filter;
    renderGrid();
  });
});

async function loadRecipes() {
  document.getElementById('loading-recipes').style.display = 'block';
  try {
    const res = await MV.api('/api/recipes.php?action=list');
    allRecipes = res.recipes;
    renderGrid();
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    document.getElementById('loading-recipes').style.display = 'none';
  }
}

async function openDetail(id) {
  try {
    const res = await MV.api('/api/recipes.php?action=detail&id=' + id);
    currentDetail = res.recipe;
    document.getElementById('detail-image').src = currentDetail.image_url;
    document.getElementById('detail-tag').textContent = MEAL_LABELS[currentDetail.meal_type] || currentDetail.meal_type;
    document.getElementById('detail-name').textContent = currentDetail.name;
    document.getElementById('detail-meta').textContent = `⏱ ${currentDetail.time_min} min de preparación`;
    document.getElementById('detail-kcal').textContent = currentDetail.kcal_porcion;
    document.getElementById('detail-protein').textContent = currentDetail.protein_porcion;
    document.getElementById('detail-carbs').textContent = currentDetail.carbs_porcion;
    document.getElementById('detail-fat').textContent = currentDetail.fat_porcion;
    document.getElementById('detail-fav').textContent = currentDetail.is_favorite ? '❤' : '🤍';
    document.getElementById('detail-ingredients').innerHTML = currentDetail.ingredients
      .map(i => `<li>${escapeHtml(i.item)}${i.qty ? ' — ' + escapeHtml(i.qty) : ''}</li>`).join('');
    document.getElementById('detail-steps').innerHTML = currentDetail.steps
      .map(s => `<li>${escapeHtml(s)}</li>`).join('');
    document.getElementById('detail-delete').style.display = currentDetail.is_own ? 'block' : 'none';
    document.getElementById('detail-backdrop').style.display = 'flex';
  } catch (err) {
    MV.toast(err.message, true);
  }
}

document.getElementById('detail-close').addEventListener('click', () => {
  document.getElementById('detail-backdrop').style.display = 'none';
});
document.getElementById('detail-backdrop').addEventListener('click', (e) => {
  if (e.target.id === 'detail-backdrop') e.currentTarget.style.display = 'none';
});

document.getElementById('detail-fav').addEventListener('click', async () => {
  if (!currentDetail) return;
  try {
    const res = await MV.api('/api/recipes.php?action=toggle_favorite', { method: 'POST', body: { recipe_id: currentDetail.id } });
    currentDetail.is_favorite = res.is_favorite;
    document.getElementById('detail-fav').textContent = res.is_favorite ? '❤' : '🤍';
    const item = allRecipes.find(r => r.id === currentDetail.id);
    if (item) item.is_favorite = res.is_favorite;
    renderGrid();
    MV.toast(res.is_favorite ? 'Agregada a tus favoritas ❤' : 'Quitada de favoritas');
  } catch (err) {
    MV.toast(err.message, true);
  }
});

document.getElementById('detail-cook-mode').addEventListener('click', () => {
  if (currentDetail) MV.cookMode(currentDetail);
});

document.getElementById('detail-delete').addEventListener('click', async () => {
  if (!currentDetail || !confirm(`¿Eliminar "${currentDetail.name}" de tu recetario? No se puede deshacer.`)) return;
  try {
    await MV.api('/api/recipes.php?action=delete', { method: 'POST', body: { recipe_id: currentDetail.id } });
    allRecipes = allRecipes.filter(r => r.id !== currentDetail.id);
    document.getElementById('detail-backdrop').style.display = 'none';
    renderGrid();
    MV.toast('Receta eliminada');
  } catch (err) {
    MV.toast(err.message, true);
  }
});

// ---------- Crear receta propia ----------
function addIngredientRow(item, qty) {
  const wrap = document.getElementById('cr-ingredients');
  const row = document.createElement('div');
  row.style.cssText = 'display:flex;gap:8px;margin-bottom:8px;';
  row.innerHTML = `
    <input type="text" placeholder="Ingrediente" class="cr-ing-item" style="flex:2;padding:11px 12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:14px;background:var(--card-bg);color:var(--t1);">
    <input type="text" placeholder="Cantidad" class="cr-ing-qty" style="flex:1;padding:11px 12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:14px;background:var(--card-bg);color:var(--t1);">
    <button type="button" class="cr-remove-row" aria-label="Quitar" style="background:var(--surface-2);border:none;width:38px;border-radius:var(--radius-sm);color:var(--t3);font-size:16px;">×</button>`;
  row.querySelector('.cr-ing-item').value = item || '';
  row.querySelector('.cr-ing-qty').value = qty || '';
  row.querySelector('.cr-remove-row').addEventListener('click', () => row.remove());
  wrap.appendChild(row);
}

function addStepRow(text) {
  const wrap = document.getElementById('cr-steps');
  const row = document.createElement('div');
  row.style.cssText = 'display:flex;gap:8px;margin-bottom:8px;';
  row.innerHTML = `
    <input type="text" placeholder="Ej: Sofríe la cebolla 3 minutos..." class="cr-step-text" style="flex:1;padding:11px 12px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:14px;background:var(--card-bg);color:var(--t1);">
    <button type="button" class="cr-remove-row" aria-label="Quitar" style="background:var(--surface-2);border:none;width:38px;border-radius:var(--radius-sm);color:var(--t3);font-size:16px;">×</button>`;
  row.querySelector('.cr-step-text').value = text || '';
  row.querySelector('.cr-remove-row').addEventListener('click', () => row.remove());
  wrap.appendChild(row);
}

function resetCreateForm() {
  document.getElementById('cr-name').value = '';
  document.getElementById('cr-meal-type').value = 'almuerzo';
  document.getElementById('cr-time').value = '30';
  document.getElementById('cr-ingredients').innerHTML = '';
  document.getElementById('cr-steps').innerHTML = '';
  document.getElementById('cr-error').style.display = 'none';
  addIngredientRow('', '');
  addIngredientRow('', '');
  addIngredientRow('', '');
  addStepRow('');
  addStepRow('');
}

document.getElementById('cr-add-ingredient').addEventListener('click', () => addIngredientRow('', ''));
document.getElementById('cr-add-step').addEventListener('click', () => addStepRow(''));

document.getElementById('btn-open-create').addEventListener('click', () => {
  resetCreateForm();
  document.getElementById('create-backdrop').style.display = 'flex';
});
document.getElementById('cr-cancel').addEventListener('click', () => {
  document.getElementById('create-backdrop').style.display = 'none';
});
document.getElementById('create-backdrop').addEventListener('click', (e) => {
  if (e.target.id === 'create-backdrop') e.currentTarget.style.display = 'none';
});

document.getElementById('cr-submit').addEventListener('click', async () => {
  const errEl = document.getElementById('cr-error');
  errEl.style.display = 'none';

  const name = document.getElementById('cr-name').value.trim();
  const mealType = document.getElementById('cr-meal-type').value;
  const timeMin = parseInt(document.getElementById('cr-time').value, 10) || 30;
  const ingredients = Array.from(document.querySelectorAll('#cr-ingredients > div')).map(row => ({
    item: row.querySelector('.cr-ing-item').value.trim(),
    quantity: row.querySelector('.cr-ing-qty').value.trim(),
  })).filter(i => i.item);
  const steps = Array.from(document.querySelectorAll('#cr-steps > div')).map(row => row.querySelector('.cr-step-text').value.trim()).filter(s => s);

  if (!name) { errEl.textContent = 'Ponle un nombre a tu receta.'; errEl.style.display = 'block'; return; }
  if (!ingredients.length) { errEl.textContent = 'Agrega al menos un ingrediente.'; errEl.style.display = 'block'; return; }
  if (!steps.length) { errEl.textContent = 'Agrega al menos un paso de preparación.'; errEl.style.display = 'block'; return; }

  const btn = document.getElementById('cr-submit');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner" style="display:inline-block;"></span> Creando (puede tardar unos segundos)...';
  try {
    const res = await MV.api('/api/recipes.php?action=create', {
      method: 'POST',
      body: { name, meal_type: mealType, time_min: timeMin, ingredients, steps },
    });
    allRecipes.unshift(res.recipe);
    document.getElementById('create-backdrop').style.display = 'none';
    renderGrid();
    MV.toast('¡Tu receta ya está en el recetario! 🎉');
  } catch (err) {
    errEl.textContent = err.message;
    errEl.style.display = 'block';
  } finally {
    btn.disabled = false;
    btn.textContent = 'Crear receta';
  }
});

loadRecipes();
</script>
