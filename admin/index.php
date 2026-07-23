<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/layout.php';
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
<meta name="theme-color" content="#0B0F14">
<link rel="manifest" href="/assets/manifest-admin.json">
<link rel="apple-touch-icon" href="/assets/img/admin-icon-192.png">
<link rel="icon" href="/assets/img/admin-icon-192.png">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #0B0F14; --surface: #131922; --surface-2: #1A212C; --border: #232B38;
    --t1: #E6EAF0; --t2: #97A2B3; --t3: #5C6B80;
    --green: #22C55E; --green-light: #16301F;
    --orange: #F0A020; --orange-light: #322611;
    --blue: #3B82F6; --blue-light: #16233F;
    --red: #EF4444; --red-light: #341717;
    --teal: #14B8A6; --teal-light: #0F2C29;
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
  .btn-green { background:var(--green); color:#fff; }
  .btn-ghost { background:var(--surface-2); color:var(--t1); border:1px solid var(--border); }
  .btn-danger { background:var(--red-light); color:var(--red); }
  .btn-icon { background:var(--surface-2); border:1px solid var(--border); color:var(--t2); width:30px; height:30px; border-radius:8px; padding:0; justify-content:center; }
  .btn-icon:hover { color:var(--t1); }

  .wrap { max-width:1100px; margin:0 auto; padding:24px; }

  .stats-row { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px; }
  .stat-card {
    background:var(--surface); border:1px solid var(--border); border-left:3px solid var(--accent, var(--border));
    border-radius:var(--radius); padding:16px; display:flex; align-items:center; gap:12px;
  }
  .stat-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:17px; flex-shrink:0; }
  .stat-card .value { font-size:22px; font-weight:800; line-height:1.1; }
  .stat-card .label { font-size:11px; color:var(--t2); text-transform:uppercase; letter-spacing:.3px; margin-top:2px; }

  .tabs { display:flex; gap:22px; border-bottom:1px solid var(--border); margin-bottom:18px; }
  .tabs button {
    background:none; border:none; color:var(--t2); font-size:14px; font-weight:600;
    padding:0 0 12px; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px;
  }
  .tabs button.active { color:var(--green); border-bottom-color:var(--green); }

  .toolbar { display:flex; gap:10px; margin-bottom:16px; }
  .toolbar input {
    flex:1; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-sm);
    padding:10px 14px; color:var(--t1); font-size:13px;
  }
  .toolbar input::placeholder { color:var(--t3); }
  .toolbar input:focus { outline:none; border-color:var(--green); }

  .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; }
  .table-wrap { overflow-x:auto; }
  table { width:100%; border-collapse:collapse; font-size:13px; }
  th, td { text-align:left; padding:12px 16px; border-bottom:1px solid var(--border); white-space:nowrap; }
  th { color:var(--t3); font-weight:600; font-size:11px; text-transform:uppercase; letter-spacing:.3px; }
  tr:last-child td { border-bottom:none; }
  td.muted, .muted { color:var(--t3); }

  .badge { display:inline-block; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; }
  .badge-green { background:var(--green-light); color:var(--green); }
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
  .field input:focus { outline:none; border-color:var(--green); }
  .modal-actions { display:flex; gap:10px; margin-top:18px; }
  .modal-actions .btn { flex:1; justify-content:center; }

  .codes-result { margin-top:16px; }
  .code-pill {
    display:flex; align-items:center; justify-content:space-between; gap:8px;
    background:var(--surface-2); border:1px solid var(--border); border-radius:var(--radius-sm);
    padding:9px 12px; font-family:monospace; font-size:13px; margin-bottom:6px; color:var(--green);
  }
  .code-pill button { background:none; border:none; color:var(--t2); cursor:pointer; font-size:12px; font-weight:600; }
  .code-pill button:hover { color:var(--t1); }

  .empty-row td { text-align:center; color:var(--t3); padding:28px; }
  .code-plain { font-family:monospace; font-weight:700; color:var(--orange); letter-spacing:0.5px; }
  .btn-copy { background:none; border:none; color:var(--t3); cursor:pointer; font-size:13px; padding:2px; margin-left:4px; }
  .btn-copy:hover { color:var(--t1); }
</style>
</head>
<body>

<div class="header">
  <h1>MenúVital <span>· Panel de administración</span></h1>
  <div style="display:flex;gap:8px;">
    <button class="btn btn-ghost" id="btn-install-admin" style="display:none;">📲 Instalar</button>
    <button class="btn btn-ghost" id="btn-logout">Salir</button>
  </div>
</div>

<div class="wrap">

  <div class="stats-row">
    <div class="stat-card" style="--accent:var(--blue);">
      <div class="stat-icon" style="background:var(--blue-light);">👤</div>
      <div><div class="value" id="stat-users">–</div><div class="label">Usuarias</div></div>
    </div>
    <div class="stat-card" style="--accent:var(--orange);">
      <div class="stat-icon" style="background:var(--orange-light);">🔑</div>
      <div><div class="value" id="stat-total">–</div><div class="label">Códigos total</div></div>
    </div>
    <div class="stat-card" style="--accent:var(--green);">
      <div class="stat-icon" style="background:var(--green-light);">✅</div>
      <div><div class="value" id="stat-active">–</div><div class="label">Códigos activos</div></div>
    </div>
    <div class="stat-card" style="--accent:var(--teal);">
      <div class="stat-icon" style="background:var(--teal-light);">📬</div>
      <div><div class="value" id="stat-used">–</div><div class="label">Códigos usados</div></div>
    </div>
  </div>

  <div class="tabs">
    <button class="active" data-tab="tab-codes">Códigos de acceso</button>
    <button data-tab="tab-users">Usuarias</button>
  </div>

  <div id="tab-codes">
    <div class="toolbar">
      <input type="text" id="search-codes" placeholder="Buscar por etiqueta o correo...">
      <button class="btn btn-green" id="btn-open-modal">+ Nuevo código</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table id="table-codes">
          <thead><tr><th>Código</th><th>Nombre / Email</th><th>Estado</th><th>Uso</th><th>Dispositivo</th><th>Creado</th><th>Acciones</th></tr></thead>
          <tbody><tr class="empty-row"><td colspan="7">Cargando...</td></tr></tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="tab-users" style="display:none;">
    <div class="card">
      <div class="table-wrap">
        <table id="table-users">
          <thead><tr><th>Nombre</th><th>Correo</th><th>Estado</th><th>Registrada</th><th>Acción</th></tr></thead>
          <tbody><tr class="empty-row"><td colspan="5">Cargando...</td></tr></tbody>
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
        <button type="submit" class="btn btn-green">Generar</button>
      </div>
    </form>
    <div class="codes-result" id="codes-result" style="display:none;"></div>
  </div>
</div>

<script src="/assets/js/app.js?v=<?= ASSET_VER ?>"></script>
<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
}

// ---------- Instalar el panel de admin como app aparte (ícono distinto) ----------
try {
  if (!MV.install.isStandalone()) {
    const btnInstallAdmin = document.getElementById('btn-install-admin');
    document.addEventListener('mv-installable', () => { btnInstallAdmin.style.display = 'inline-flex'; });
    if (MV.install.hasPrompt()) btnInstallAdmin.style.display = 'inline-flex';
    btnInstallAdmin.addEventListener('click', async () => {
      const result = await MV.install.trigger();
      if (result === 'installed') {
        btnInstallAdmin.style.display = 'none';
        MV.toast('¡Listo! El panel quedó instalado en tu celular 🎉');
      } else if (result === 'manual') {
        MV.toast('Desde el menú de tu navegador, busca "Agregar a pantalla de inicio" o "Instalar app".');
      }
    });
  }
} catch (e) { /* instalar es secundario: nunca debe romper el panel */ }

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

function estadoBadge(c) {
  return c.is_active ? '<span class="badge badge-green">Activo</span>' : '<span class="badge badge-red">Desactivado</span>';
}
function usoBadge(c) {
  return c.used_at ? '<span class="badge badge-gray">Usado</span>' : '<span class="badge badge-green">Disponible</span>';
}

function renderCodes() {
  const q = document.getElementById('search-codes').value.trim().toLowerCase();
  const filtered = !q ? allCodes : allCodes.filter(c =>
    (c.batch_label || '').toLowerCase().includes(q) ||
    (c.code_plain || '').toLowerCase().includes(q) ||
    (c.used_by_name || '').toLowerCase().includes(q) ||
    (c.used_by_email || '').toLowerCase().includes(q));

  const tbody = document.querySelector('#table-codes tbody');
  if (!filtered.length) {
    tbody.innerHTML = '<tr class="empty-row"><td colspan="7">No hay códigos que coincidan.</td></tr>';
    return;
  }
  tbody.innerHTML = filtered.map(c => {
    let actions = `
        <div class="row-actions">
          <button class="btn btn-icon" data-toggle="${c.id}" title="${c.is_active ? 'Desactivar' : 'Activar'}">${c.is_active ? '⏸' : '▶'}</button>`;
    if (!c.used_at) {
      actions += `<button class="btn btn-icon" data-delete="${c.id}" title="Eliminar">🗑</button>`;
    } else {
      actions += `<button class="btn btn-sm ${c.used_by_blocked ? 'btn-green' : 'btn-danger'}" data-block-user="${c.used_by_id}">${c.used_by_blocked ? 'Desbloquear' : 'Bloquear'}</button>`;
      if (c.device_count > 0) {
        actions += `<button class="btn btn-icon" data-reset-devices="${c.id}" title="Liberar dispositivos">🔄</button>`;
      }
    }
    actions += `</div>`;

    const codeCell = c.code_plain
      ? `<span class="code-plain">${escapeHtml(c.code_plain)}</span><button type="button" class="btn-copy" data-copy="${escapeHtml(c.code_plain)}" title="Copiar">📋</button>`
      : '<span class="muted" title="Generado antes de poder verse siempre">—</span>';

    const nameCell = c.used_by_name
      ? `${escapeHtml(c.used_by_name)}<br><span class="muted" style="font-size:11px;">${escapeHtml(c.used_by_email || '')}</span>`
        + (c.used_by_blocked ? ' <span class="badge badge-red">Bloqueada</span>' : '')
      : (c.batch_label ? escapeHtml(c.batch_label) : '<span class="muted">—</span>');

    const deviceCell = c.used_at ? `${c.device_count}/2` : '<span class="muted">—</span>';

    return `
      <tr>
        <td>${codeCell}</td>
        <td>${nameCell}</td>
        <td>${estadoBadge(c)}</td>
        <td>${usoBadge(c)}</td>
        <td class="muted">${deviceCell}</td>
        <td class="muted">${formatDate(c.created_at)}</td>
        <td>${actions}</td>
      </tr>`;
  }).join('');

  tbody.querySelectorAll('[data-toggle]').forEach(btn => btn.addEventListener('click', () => toggleCode(btn.dataset.toggle)));
  tbody.querySelectorAll('[data-delete]').forEach(btn => btn.addEventListener('click', () => deleteCode(btn.dataset.delete)));
  tbody.querySelectorAll('[data-block-user]').forEach(btn => btn.addEventListener('click', () => toggleUserBlock(btn.dataset.blockUser, [loadCodes])));
  tbody.querySelectorAll('[data-reset-devices]').forEach(btn => btn.addEventListener('click', () => resetDevices(btn.dataset.resetDevices)));
  tbody.querySelectorAll('[data-copy]').forEach(btn => btn.addEventListener('click', () => {
    navigator.clipboard.writeText(btn.dataset.copy);
    MV.toast('Código copiado');
  }));
}

async function loadStats() {
  try {
    const res = await MV.api('/api/admin.php?action=stats');
    document.getElementById('stat-users').textContent = res.stats.users;
    document.getElementById('stat-total').textContent = res.stats.codes_total;
    document.getElementById('stat-active').textContent = res.stats.codes_active;
    document.getElementById('stat-used').textContent = res.stats.codes_used;
  } catch (err) { MV.toast(err.message, true); }
}

async function loadCodes() {
  try {
    const res = await MV.api('/api/admin.php?action=list_codes');
    allCodes = res.codes;
    renderCodes();
  } catch (err) {
    document.querySelector('#table-codes tbody').innerHTML = `<tr class="empty-row"><td colspan="7">${escapeHtml(err.message)}</td></tr>`;
  }
}

async function loadUsers() {
  const tbody = document.querySelector('#table-users tbody');
  try {
    const res = await MV.api('/api/admin.php?action=list_users');
    const rows = res.users.filter(u => !u.is_admin);
    if (!rows.length) {
      tbody.innerHTML = '<tr class="empty-row"><td colspan="5">Aún no hay usuarias registradas.</td></tr>';
      return;
    }
    tbody.innerHTML = rows.map(u => `
      <tr>
        <td>${escapeHtml(u.name)}</td>
        <td>${escapeHtml(u.email)}</td>
        <td>${u.is_blocked ? '<span class="badge badge-red">Bloqueada</span>' : '<span class="badge badge-green">Activa</span>'}</td>
        <td class="muted">${formatDate(u.created_at)}</td>
        <td><button class="btn btn-sm ${u.is_blocked ? 'btn-green' : 'btn-danger'}" data-block-user="${u.id}">${u.is_blocked ? 'Desbloquear' : 'Bloquear'}</button></td>
      </tr>
    `).join('');
    tbody.querySelectorAll('[data-block-user]').forEach(btn => btn.addEventListener('click', () => toggleUserBlock(btn.dataset.blockUser, [loadUsers])));
  } catch (err) {
    tbody.innerHTML = `<tr class="empty-row"><td colspan="5">${escapeHtml(err.message)}</td></tr>`;
  }
}

async function toggleUserBlock(id, refreshFns) {
  if (!confirm('¿Cambiar el acceso de esta cuenta?')) return;
  try {
    const res = await MV.api('/api/admin.php?action=toggle_user_block', { method: 'POST', body: { id: parseInt(id, 10) } });
    MV.toast(res.is_blocked ? 'Cuenta bloqueada' : 'Cuenta desbloqueada');
    for (const fn of refreshFns) await fn();
  } catch (err) { MV.toast(err.message, true); }
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

async function resetDevices(id) {
  if (!confirm('¿Liberar los dispositivos de este código? La usuaria podrá volver a entrar desde un dispositivo nuevo.')) return;
  try {
    await MV.api('/api/admin.php?action=reset_devices', { method: 'POST', body: { id: parseInt(id, 10) } });
    MV.toast('Dispositivos liberados');
    await loadCodes();
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
