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
<p class="muted" style="margin-top:0;font-size:14px;">Explora las 105 recetas, marca tus favoritas y cocínalas cuando quieras.</p>

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
  <div style="background:#fff;border-radius:20px 20px 0 0;width:100%;max-width:520px;max-height:88vh;overflow-y:auto;">
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

      <button type="button" id="detail-cook-mode" class="btn btn-primary btn-block" style="margin-bottom:18px;">👩‍🍳 Modo Cocina</button>

      <p class="section-title" style="margin-top:0;">Ingredientes</p>
      <ul id="detail-ingredients" style="margin:0 0 16px;padding-left:20px;font-size:14px;"></ul>

      <p class="section-title">Preparación</p>
      <ol id="detail-steps" style="margin:0;padding-left:20px;font-size:14px;"></ol>
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

loadRecipes();
</script>
