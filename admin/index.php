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
<link rel="stylesheet" href="/assets/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  body { background: var(--surface); }
  .admin-header { background: var(--grad-v); color: #fff; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; }
  .admin-header h1 { font-size: 17px; margin: 0; }
  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid var(--border); }
  th { color: var(--t2); font-weight: 600; font-size: 12px; text-transform: uppercase; }
  .codes-box { background: var(--surface); border-radius: var(--radius-sm); padding: 12px; font-family: monospace; font-size: 13px; max-height: 220px; overflow-y: auto; margin-top: 12px; white-space: pre-wrap; }
  .table-wrap { overflow-x: auto; }
</style>
</head>
<body>
<div class="admin-header">
  <h1>Panel de MenúVital — <?= e($user['name']) ?></h1>
  <button class="btn btn-sm btn-secondary" id="btn-logout" style="background:rgba(255,255,255,0.2);color:#fff;">Salir</button>
</div>

<div class="container-wide" style="padding:20px 20px 60px;">

  <div class="card" style="margin-bottom:20px;">
    <h2 style="margin-top:0;font-size:16px;">Generar códigos de activación</h2>
    <p class="muted" style="font-size:13px;">Genera los códigos que le envías a cada clienta por WhatsApp cuando confirmas su pago de <?= e(APP_PRICE ?? '$19.900') ?>.</p>
    <form id="form-generate" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
      <div class="field mb-0" style="flex:1;min-width:120px;">
        <label>Cantidad</label>
        <input type="number" id="gen-count" min="1" max="200" value="1">
      </div>
      <div class="field mb-0" style="flex:2;min-width:180px;">
        <label>Etiqueta (opcional, ej: "campaña julio")</label>
        <input type="text" id="gen-label" placeholder="campaña-julio">
      </div>
      <button type="submit" class="btn btn-primary">Generar</button>
    </form>
    <div id="codes-result" class="codes-box" style="display:none;"></div>
  </div>

  <div class="card" style="margin-bottom:20px;">
    <h2 style="margin-top:0;font-size:16px;">Códigos generados</h2>
    <div class="table-wrap">
      <table id="table-codes">
        <thead><tr><th>Etiqueta</th><th>Creado</th><th>Estado</th><th>Usado por</th></tr></thead>
        <tbody><tr><td colspan="4" class="muted">Cargando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <h2 style="margin-top:0;font-size:16px;">Usuarias registradas</h2>
    <div class="table-wrap">
      <table id="table-users">
        <thead><tr><th>Nombre</th><th>Correo</th><th>Registrada</th></tr></thead>
        <tbody><tr><td colspan="3" class="muted">Cargando...</td></tr></tbody>
      </table>
    </div>
  </div>

</div>

<script src="/assets/js/app.js"></script>
<script>
async function loadCodes() {
  const tbody = document.querySelector('#table-codes tbody');
  try {
    const res = await MV.api('/api/admin.php?action=list_codes');
    if (!res.codes.length) {
      tbody.innerHTML = '<tr><td colspan="4" class="muted">Todavía no has generado códigos.</td></tr>';
      return;
    }
    tbody.innerHTML = res.codes.map(c => `
      <tr>
        <td>${c.batch_label ? escapeHtml(c.batch_label) : '<span class="muted">—</span>'}</td>
        <td>${formatDate(c.created_at)}</td>
        <td>${c.used_at ? '<span class="badge badge-purple">Usado</span>' : '<span class="badge badge-green">Disponible</span>'}</td>
        <td>${c.used_by_name ? escapeHtml(c.used_by_name) + ' (' + escapeHtml(c.used_by_email) + ')' : '—'}</td>
      </tr>`).join('');
  } catch (err) {
    tbody.innerHTML = `<tr><td colspan="4" class="error-text">${escapeHtml(err.message)}</td></tr>`;
  }
}

async function loadUsers() {
  const tbody = document.querySelector('#table-users tbody');
  try {
    const res = await MV.api('/api/admin.php?action=list_users');
    if (!res.users.length) {
      tbody.innerHTML = '<tr><td colspan="3" class="muted">Aún no hay usuarias registradas.</td></tr>';
      return;
    }
    tbody.innerHTML = res.users.filter(u => !u.is_admin).map(u => `
      <tr><td>${escapeHtml(u.name)}</td><td>${escapeHtml(u.email)}</td><td>${formatDate(u.created_at)}</td></tr>
    `).join('');
  } catch (err) {
    tbody.innerHTML = `<tr><td colspan="3" class="error-text">${escapeHtml(err.message)}</td></tr>`;
  }
}

function escapeHtml(s) {
  const d = document.createElement('div');
  d.textContent = s;
  return d.innerHTML;
}

function formatDate(s) {
  if (!s) return '—';
  const d = new Date(s.replace(' ', 'T') + 'Z');
  return d.toLocaleDateString('es-CO', { day: '2-digit', month: 'short', year: 'numeric' });
}

document.getElementById('form-generate').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = e.target.querySelector('button');
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
    box.textContent = res.codes.join('\n');
    MV.toast(`${res.codes.length} código(s) generado(s)`);
    loadCodes();
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

loadCodes();
loadUsers();
</script>
</body>
</html>
