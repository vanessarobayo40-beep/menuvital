<?php
require_once __DIR__ . '/../includes/auth.php';
secure_session_start();
send_security_headers();
$user = require_admin_page();
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Panel de administración — MenúVital</title>
<meta name="csrf-token" content="<?= e($csrf) ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #0B0F14; --surface: #131922; --surface-2: #1A212C; --border: #232B38;
    --t1: #E6EAF0; --t2: #97A2B3; --t3: #5C6B80;
    --purple: #7C5CE0; --purple-light: #2A2340;
    --green: #22C55E; --green-light: #16301F;
    --orange: #F0A020; --orange-light: #322611;
    --blue: #3B82F6; --blue-light: #16233F;
    --red: #EF4444; --red-light: #341717;
    --radius: 14px; --radius-sm: 9px;
  }
  * { box-sizing: border-box; }
  body { margin:0; font-family:'Inter',-apple-system,sans-serif; background:var(--bg); color:var(--t1); }
  a { color: inherit; }
  .header {
    display:flex; justify-content:space-between; align-items:center;
    padding:16px 24px; border-bottom:1px solid var(--border); background:var(--surface);
  }
  .header h1 { font-size:16px; margin:0; font-weight:700; }
  .header h1 span { color:var(--t2); font-weight:500; }
  .btn {
    border:none; border-radius:999px; padding:9px 16px; font-size:13px; font-weight:600;
    cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:opacity .15s;
  }
  .btn:hover { opacity:0.85; }
  .btn:disabled { opacity:0.4; cursor:not-allowed; }
  .btn-purple { background:var(--purple); color:#fff; }
  .btn-ghost { background:var(--surface-2); color:var(--t1); border:1px solid var(--border); }
  .btn-danger { background:var(--red-light); color:var(--red); }
  .btn-icon { background:var(--surface-2); border:1px solid var(--border); color:var(--t2); width:30px; height:30px; border-radius:8px; padding:0; justify-content:center; }
  .btn-icon:hover { color:var(--t1); }

  .wrap { max-width:1100px; margin:0 auto; padding:24px; }

  .stats-row { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px; }
  .stat-card {
    background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
    padding:16px; display:flex; align-items:center; gap:12px;
  }
  .stat-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:17px; flex-shrink:0; }
  .stat-card .value { font-size:22px; font-weight:800; line-height:1.1; }
  .stat-card .label { font-size:11px; color:var(--t2); text-transform:uppercase; letter-spacing:.3px; margin-top:2px; }

  .tabs { display:flex; gap:22px; border-bottom:1px solid var(--border); margin-bottom:18px; }
  .tabs button {
    background:none; border:none; color:var(--t2); font-size:14px; font-weight:600;
    padding:0 0 12px; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px;
  }
  .tabs button.active { color:var(--purple); border-bottom-color:var(--purple); }

  .toolbar { display:flex; gap:10px; margin-bottom:16px; }
  .toolbar input {
    flex:1; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-sm);
    padding:10px 14px; color:var(--t1); font-size:13px;
  }
  .toolbar input::placeholder { color:var(--t3); }
  .toolbar input:focus { outline:none; border-color:var(--purple); }

  .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; }
  .table-wrap { overflow-x:auto; }
  table { width:100%; border-collapse:collapse; font-size:13px; }
  th, td { text-align:left; padding:12px 16px; border-bottom:1px solid var(--border); white-space:nowrap; }
  th { color:var(--t3); font-weight:600; font-size:11px; text-transform:uppercase; letter-spacing:.3px; }
  tr:last-child td { border-bottom:none; }
  td.muted, .muted { color:var(--t3); }

  .badge { display:inline-block; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; }
  .badge-green { background:var(--green-light); color:var(--green); }
  .badge-purple { background:var(--purple-light); color:#B49CF0; }
  .badge-gray { background:var(--surface-2); color:var(--t2); }
  .badge-red { background:var(--red-light); color:var(--red); }

  .row-actions { display:flex; gap:6px; }

  /* Modal */
  .modal-backdrop {
    position:fixed; inset:0; background:rgba(0,0,0,.6); display:none;
    align-items:center; justify-content:center; z-index:50; padding:20px;
  }
  .modal-backdrop.open { display:flex; }
  .modal {
    background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
    padding:22px; width:100%; max-width:420px;
  }
  .modal h2 { margin:0 0 4px; font-size:16px; }
  .modal p.hint { color:var(--t2); font-size:12px; margin:0 0 18px; }
  .field { margin-bottom:14px; }
  .field label { display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px; }
  .field input {
    width:100%; background:var(--surface-2); border:1px solid var(--border); border-radius:var(--radius-sm);
    padding:10px 12px; color:var(--t1); font-size:14px;
  }
  .field input:focus { outline:none; border-color:var(--purple); }
  .modal-actions { display:flex; gap:10px; margin-top:18px; }
  .modal-actions .btn { flex:1; justify-content:center; }

  .codes-result { margin-top:16px; }
  .code-pill {
    display:flex; align-items:center; justify-content:space-between; gap:8px;
    background:var(--surface-2); border:1px solid var(--border); border-radius:var(--radius-sm);
    padding:9px 12px; font-family:monospace; font-size:13px; margin-bottom:6px; color:var(--purple);
  }
  .code-pill button { background:none; border:none; color:var(--t2); cursor:pointer; font-size:12px; font-weight:600; }
  .code-pill button:hover { color:var(--t1); }

  .empty-row td { text-align:center; color:var(--t3); padding:28px; }
</style>
</head>
<body>

<div class="header">
  <h1>MenúVital <span>· Panel de administración</span></h1>
  <button class="btn btn-ghost" id="btn-logout">Salir</button>
</div>

<div class="wrap">

  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-icon" style="background:var(--purple-light);">👤</div>
      <div><div class="value" id="stat-users">–</div><div class="label">Usuarias</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:var(--orange-light);">🔑</div>
      <div><div class="value" id="stat-total">–</div><div class="label">Códigos total</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:var(--green-light);">✅</div>
      <div><div class="value" id="stat-available">–</div><div class="label">Disponibles</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:var(--blue-light);">📬</div>
      <div><div class="value" id="stat-used">–</div><div class="label">Usados</div></div>
    </div>
  </div>

  <div class="tabs">
    <button class="active" data-tab="tab-codes">Códigos de acceso</button>
    <button data-tab="tab-users">Usuarias</button>
  </div>

  <div id="tab-codes">
    <div class="toolbar">
      <input type="text" id="search-codes" placeholder="Buscar por etiqueta o correo...">
      <button class="btn btn-purple" id="btn-open-modal">+ Nuevo código</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table id="table-codes">
          <thead><tr><th>Etiqueta</th><th>Estado</th><th>Usado por</th><th>Creado</th><th>Acciones</th></tr></thead>
          <tbody><tr class="empty-row"><td colspan="5">Cargando...</td></tr></tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="tab-users" style="display:none;">
    <div class="card">
      <div class="table-wrap">
        <table id="table-users">
          <thead><tr><th>Nombre</th><th>Correo</th><th>Registrada</th></tr></thead>
          <tbody><tr class="empty-row"><td colspan="3">Cargando...</td></tr></tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<div class="modal-backdrop" id="modal-backdrop">
  <div class="modal">
    <h2>Generar códigos de activación</h2>
    <p class="hint">Cada código vale por <?= e(APP_PRICE) ?> y se puede usar una sola vez.</p>
    <form id="form-generate">
      <div class="field">
        <label>Cantidad</label>
        <input type="number" id="gen-count" min="1" max="200" value="1">
      </div>
      <div class="field">
        <label>Etiqueta (ej: nombre de la clienta o campaña)</label>
        <input type="text" id="gen-label" placeholder="Ej: Sebastián Robayo">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" id="btn-close-modal">Cancelar</button>
        <button type="submit" class="btn btn-purple">Generar</button>
      </div>
    </form>
    <div class="codes-result" id="codes-result" style="display:none;"></div>
  </div>
</div>

<script src="/assets/js/app.js"></script>
<script>
let allCodes = [];

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s ?? '';
  return d.innerHTML;
}

function formatDate(s) {
  if (!s) return '—';
  const d = new Date(s.replace(' ', 'T') + 'Z');
  return d.toLocaleDateString('es-CO', { day: '2-digit', month: 'short', year: 'numeric' });
}

function codeStatusBadge(c) {
  if (c.used_at) return '<span class="badge badge-purple">Usado</span>';
  if (!c.is_active) return '<span class="badge badge-red">Desactivado</span>';
  return '<span class="badge badge-green">Disponible</span>';
}

function renderCodes() {
  const q = document.getElementById('search-codes').value.trim().toLowerCase();
  const filtered = !q ? allCodes : allCodes.filter(c =>
    (c.batch_label || '').toLowerCase().includes(q) ||
    (c.used_by_name || '').toLowerCase().includes(q) ||
    (c.used_by_email || '').toLowerCase().includes(q));

  const tbody = document.querySelector('#table-codes tbody');
  if (!filtered.length) {
    tbody.innerHTML = '<tr class="empty-row"><td colspan="5">No hay códigos que coincidan.</td></tr>';
    return;
  }
  tbody.innerHTML = filtered.map(c => {
    const canManage = !c.used_at;
    const actions = canManage ? `
      <div class="row-actions">
        <button class="btn btn-icon" data-toggle="${c.id}" title="${c.is_active ? 'Desactivar' : 'Activar'}">${c.is_active ? '⏸' : '▶'}</button>
        <button class="btn btn-icon" data-delete="${c.id}" title="Eliminar">🗑</button>
      </div>` : '<span class="muted">—</span>';
    return `
      <tr>
        <td>${c.batch_label ? escapeHtml(c.batch_label) : '<span class="muted">—</span>'}</td>
        <td>${codeStatusBadge(c)}</td>
        <td>${c.used_by_name ? escapeHtml(c.used_by_name) + ' <span class="muted">(' + escapeHtml(c.used_by_email) + ')</span>' : '<span class="muted">—</span>'}</td>
        <td class="muted">${formatDate(c.created_at)}</td>
        <td>${actions}</td>
      </tr>`;
  }).join('');

  tbody.querySelectorAll('[data-toggle]').forEach(btn => btn.addEventListener('click', () => toggleCode(btn.dataset.toggle)));
  tbody.querySelectorAll('[data-delete]').forEach(btn => btn.addEventListener('click', () => deleteCode(btn.dataset.delete)));
}

async function loadStats() {
  try {
    const res = await MV.api('/api/admin.php?action=stats');
    document.getElementById('stat-users').textContent = res.stats.users;
    document.getElementById('stat-total').textContent = res.stats.codes_total;
    document.getElementById('stat-available').textContent = res.stats.codes_available;
    document.getElementById('stat-used').textContent = res.stats.codes_used;
  } catch (err) { MV.toast(err.message, true); }
}

async function loadCodes() {
  try {
    const res = await MV.api('/api/admin.php?action=list_codes');
    allCodes = res.codes;
    renderCodes();
  } catch (err) {
    document.querySelector('#table-codes tbody').innerHTML = `<tr class="empty-row"><td colspan="5">${escapeHtml(err.message)}</td></tr>`;
  }
}

async function loadUsers() {
  const tbody = document.querySelector('#table-users tbody');
  try {
    const res = await MV.api('/api/admin.php?action=list_users');
    const rows = res.users.filter(u => !u.is_admin);
    if (!rows.length) {
      tbody.innerHTML = '<tr class="empty-row"><td colspan="3">Aún no hay usuarias registradas.</td></tr>';
      return;
    }
    tbody.innerHTML = rows.map(u => `
      <tr><td>${escapeHtml(u.name)}</td><td>${escapeHtml(u.email)}</td><td class="muted">${formatDate(u.created_at)}</td></tr>
    `).join('');
  } catch (err) {
    tbody.innerHTML = `<tr class="empty-row"><td colspan="3">${escapeHtml(err.message)}</td></tr>`;
  }
}

async function toggleCode(id) {
  try {
    await MV.api('/api/admin.php?action=toggle_code', { method: 'POST', body: { id: parseInt(id, 10) } });
    await loadCodes();
    await loadStats();
  } catch (err) { MV.toast(err.message, true); }
}

async function deleteCode(id) {
  if (!confirm('¿Eliminar este código? Esta acción no se puede deshacer.')) return;
  try {
    await MV.api('/api/admin.php?action=delete_code', { method: 'POST', body: { id: parseInt(id, 10) } });
    await loadCodes();
    await loadStats();
  } catch (err) { MV.toast(err.message, true); }
}

document.getElementById('search-codes').addEventListener('input', renderCodes);

document.querySelectorAll('.tabs button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tabs button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-codes').style.display = btn.dataset.tab === 'tab-codes' ? 'block' : 'none';
    document.getElementById('tab-users').style.display = btn.dataset.tab === 'tab-users' ? 'block' : 'none';
  });
});

const backdrop = document.getElementById('modal-backdrop');
document.getElementById('btn-open-modal').addEventListener('click', () => backdrop.classList.add('open'));
document.getElementById('btn-close-modal').addEventListener('click', () => backdrop.classList.remove('open'));
backdrop.addEventListener('click', (e) => { if (e.target === backdrop) backdrop.classList.remove('open'); });

document.getElementById('form-generate').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = e.target.querySelector('button[type=submit]');
  btn.disabled = true;
  try {
    const res = await MV.api('/api/admin.php?action=generate_codes', {
      method: 'POST',
      body: {
        count: parseInt(document.getElementById('gen-count').value, 10) || 1,
        label: document.getElementById('gen-label').value.trim(),
      },
    });
    const box = document.getElementById('codes-result');
    box.style.display = 'block';
    box.innerHTML = '<p class="hint" style="margin-bottom:8px;">Copia y envía cada código — no se volverá a mostrar.</p>' +
      res.codes.map(c => `<div class="code-pill"><span>${c}</span><button type="button" data-copy="${c}">Copiar</button></div>`).join('');
    box.querySelectorAll('[data-copy]').forEach(b => b.addEventListener('click', () => {
      navigator.clipboard.writeText(b.dataset.copy);
      MV.toast('Código copiado');
    }));
    document.getElementById('gen-label').value = '';
    loadCodes();
    loadStats();
  } catch (err) {
    MV.toast(err.message, true);
  } finally {
    btn.disabled = false;
  }
});

document.getElementById('btn-logout').addEventListener('click', async () => {
  await MV.api('/api/auth.php?action=logout', { method: 'POST' });
  window.location.href = '/login.php';
});

loadStats();
loadCodes();
loadUsers();
</script>
</body>
</html>
