<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_login_page();
$PAGE_TITLE = 'Mercado';
$ACTIVE_NAV = 'mercado';
require __DIR__ . '/../includes/layout_top.php';
?>

<h2 style="margin-bottom:4px;">Mi mercado</h2>
<p class="muted" style="margin-top:0;font-size:14px;">Escribe lo que tienes disponible en casa. Con eso armamos tu menú.</p>

<div class="tabs">
  <button class="active" data-tab="tab-despensa">Mi despensa</button>
  <button data-tab="tab-compras">Lista de compras</button>
</div>

<div id="tab-despensa">
  <div class="autocomplete-wrap field">
    <input type="text" id="pantry-input" placeholder="Ej: pollo, arroz, tomate..." autocomplete="off">
    <div id="autocomplete-list" class="autocomplete-list" style="display:none;"></div>
  </div>

  <div class="chips" id="pantry-chips" style="margin-bottom:16px;"></div>

  <div id="pantry-empty" class="empty-state" style="display:none;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 6h15l-1.5 8.5a2 2 0 0 1-2 1.5H8.5a2 2 0 0 1-2-1.5L4.5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
    <p>Aún no has agregado ingredientes.</p>
  </div>

  <button id="btn-clear" class="btn btn-danger btn-sm" style="display:none;">Vaciar mi despensa</button>
</div>

<div id="tab-compras" style="display:none;">
  <p class="muted" style="font-size:13px;margin-top:0;">Esto es lo que necesitas comprar para completar tu plan de la semana (o del día, si aún no generas la semana).</p>
  <div id="shopping-list"></div>
  <div id="shopping-empty" class="empty-state" style="display:none;">
    <p>Genera tu menú del día o de la semana para ver tu lista de compras.</p>
    <a href="/app/plan.php" class="btn btn-primary btn-sm">Ver plan semanal</a>
  </div>
</div>

<?php require __DIR__ . '/../includes/layout_bottom.php'; ?>

<script>
const input = document.getElementById('pantry-input');
const listEl = document.getElementById('autocomplete-list');
const chipsEl = document.getElementById('pantry-chips');
let currentItems = [];
let activeIndex = -1;

function renderChips() {
  document.getElementById('pantry-empty').style.display = currentItems.length ? 'none' : 'block';
  document.getElementById('btn-clear').style.display = currentItems.length ? 'inline-flex' : 'none';
  chipsEl.innerHTML = currentItems.map(item => `
    <span class="chip">${escapeHtml(item)}<button type="button" data-item="${escapeHtml(item)}" aria-label="Quitar">×</button></span>
  `).join('');
  chipsEl.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => removeItem(btn.dataset.item));
  });
}

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s;
  return d.innerHTML;
}

async function loadPantry() {
  const res = await MV.api('/api/pantry.php?action=list');
  currentItems = res.items;
  renderChips();
}

async function addItem(item) {
  item = item.trim();
  if (!item) return;
  input.value = '';
  listEl.style.display = 'none';
  try {
    const res = await MV.api('/api/pantry.php?action=add', { method: 'POST', body: { item } });
    currentItems = res.items;
    renderChips();
  } catch (err) {
    MV.toast(err.message, true);
  }
}

async function removeItem(item) {
  try {
    const res = await MV.api('/api/pantry.php?action=remove', { method: 'POST', body: { item } });
    currentItems = res.items;
    renderChips();
  } catch (err) {
    MV.toast(err.message, true);
  }
}

const searchDebounced = MV.debounce(async (q) => {
  if (!q) { listEl.style.display = 'none'; return; }
  try {
    const res = await MV.api('/api/pantry.php?action=search&q=' + encodeURIComponent(q));
    if (!res.results.length) { listEl.style.display = 'none'; return; }
    activeIndex = -1;
    listEl.innerHTML = res.results.map(r => `<button type="button" data-name="${escapeHtml(r)}">${escapeHtml(r)}</button>`).join('');
    listEl.querySelectorAll('button').forEach(b => b.addEventListener('click', () => addItem(b.dataset.name)));
    listEl.style.display = 'block';
  } catch (err) { /* silencioso: autocompletado no es crítico */ }
}, 200);

input.addEventListener('input', () => searchDebounced(input.value));
input.addEventListener('keydown', (e) => {
  const options = listEl.querySelectorAll('button');
  if (e.key === 'ArrowDown' && options.length) {
    e.preventDefault();
    activeIndex = Math.min(activeIndex + 1, options.length - 1);
    options.forEach((o, i) => o.classList.toggle('active', i === activeIndex));
  } else if (e.key === 'ArrowUp' && options.length) {
    e.preventDefault();
    activeIndex = Math.max(activeIndex - 1, 0);
    options.forEach((o, i) => o.classList.toggle('active', i === activeIndex));
  } else if (e.key === 'Enter') {
    e.preventDefault();
    if (activeIndex >= 0 && options[activeIndex]) {
      addItem(options[activeIndex].dataset.name);
    } else {
      addItem(input.value);
    }
  } else if (e.key === 'Escape') {
    listEl.style.display = 'none';
  }
});
document.addEventListener('click', (e) => {
  if (!e.target.closest('.autocomplete-wrap')) listEl.style.display = 'none';
});

document.getElementById('btn-clear').addEventListener('click', async () => {
  if (!confirm('¿Vaciar toda tu despensa?')) return;
  const res = await MV.api('/api/pantry.php?action=clear', { method: 'POST' });
  currentItems = res.items;
  renderChips();
});

// ---------- Tabs ----------
document.querySelectorAll('.tabs button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tabs button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-despensa').style.display = btn.dataset.tab === 'tab-despensa' ? 'block' : 'none';
    document.getElementById('tab-compras').style.display = btn.dataset.tab === 'tab-compras' ? 'block' : 'none';
    if (btn.dataset.tab === 'tab-compras') loadShoppingList();
  });
});

async function loadShoppingList() {
  const el = document.getElementById('shopping-list');
  el.innerHTML = '<div class="skeleton" style="height:120px;"></div>';
  try {
    const res = await MV.api('/api/planner.php?action=shopping_list');
    const categories = Object.keys(res.list);
    if (!categories.length) {
      el.innerHTML = '';
      document.getElementById('shopping-empty').style.display = 'block';
      return;
    }
    document.getElementById('shopping-empty').style.display = 'none';
    el.innerHTML = categories.map(cat => `
      <p class="section-title">${escapeHtml(cat)}</p>
      <div class="card-soft" style="margin-bottom:14px;">
        ${res.list[cat].map(i => `
          <label class="checkbox-row" style="padding:6px 0;">
            <input type="checkbox">
            <span>${escapeHtml(i.item)}${i.qty ? ' <span class=\"muted\">— ' + escapeHtml(i.qty) + '</span>' : ''}</span>
          </label>`).join('')}
      </div>`).join('');
  } catch (err) {
    el.innerHTML = '';
    MV.toast(err.message, true);
  }
}

loadPantry();
</script>
