<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/layout.php';
secure_session_start();
send_security_headers();

if (current_user()) {
    header('Location: /app/index.php');
    exit;
}

$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Entrar — MenúVital</title>
<meta name="csrf-token" content="<?= e($csrf) ?>">
<meta name="theme-color" content="#0F9D6B">
<link rel="icon" href="/assets/img/icon-192.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=<?= ASSET_VER ?>">
<style>
  body { background: var(--grad-soft); min-height: 100vh; }
  .auth-wrap { min-height: 100vh; display: flex; align-items: center; padding: 32px 0; }
  .auth-card { width: 100%; }
  .logo-big { text-align: center; margin-bottom: 28px; }
  .logo-big .circle {
    width: 64px; height: 64px; border-radius: 20px; background: var(--grad-v);
    display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;
    box-shadow: var(--shadow-md);
  }
  .logo-big h1 { font-size: 22px; margin: 0; }
  .logo-big p { color: var(--t2); font-size: 14px; margin: 4px 0 0; }
  .switcher { display: flex; gap: 4px; background: var(--surface); padding: 4px; border-radius: 999px; margin-bottom: 22px; }
  .switcher button { flex: 1; padding: 10px; border: none; background: none; border-radius: 999px; font-weight: 600; font-size: 14px; color: var(--t2); }
  .switcher button.active { background: #fff; color: var(--green-dark); box-shadow: var(--shadow); }
  .form-panel { display: none; }
  .form-panel.active { display: block; }
</style>
</head>
<body>
<div class="container auth-wrap">
  <div class="auth-card">
    <div class="logo-big">
      <div class="circle">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M12 3v3M5.6 5.6l2.1 2.1M3 12h3M5.6 18.4l2.1-2.1M12 18v3M16.3 16.3l2.1 2.1M18 12h3M16.3 7.7l2.1-2.1"/><circle cx="12" cy="12" r="4"/></svg>
      </div>
      <h1>MenúVital</h1>
      <p>Tu coach de nutrición y menú diario</p>
    </div>

    <div class="switcher">
      <button type="button" class="active" data-tab="login">Ya tengo cuenta</button>
      <button type="button" data-tab="register">Activar mi cuenta</button>
    </div>

    <form id="form-login" class="card form-panel active">
      <div class="field">
        <label for="li-email">Correo electrónico</label>
        <input type="email" id="li-email" autocomplete="email" required placeholder="tucorreo@ejemplo.com">
      </div>
      <div class="field mb-0">
        <label for="li-pass">Contraseña</label>
        <input type="password" id="li-pass" autocomplete="current-password" required placeholder="••••••••">
      </div>
      <p id="login-error" class="field error-text" style="display:none;"></p>
      <button type="submit" class="btn btn-primary btn-block" id="btn-login">Entrar</button>
    </form>

    <form id="form-register" class="card form-panel">
      <div class="field">
        <label for="rg-code">Código de activación</label>
        <input type="text" id="rg-code" required placeholder="MV-XXXX-XXXX-XXXX" style="text-transform:uppercase;">
        <p class="hint">Te lo enviamos por WhatsApp cuando confirmamos tu pago.</p>
      </div>
      <div class="field">
        <label for="rg-name">Tu nombre</label>
        <input type="text" id="rg-name" required placeholder="Ej: Vanessa">
      </div>
      <div class="field">
        <label for="rg-email">Correo electrónico</label>
        <input type="email" id="rg-email" autocomplete="email" required placeholder="tucorreo@ejemplo.com">
      </div>
      <div class="field mb-0">
        <label for="rg-pass">Crea una contraseña</label>
        <input type="password" id="rg-pass" autocomplete="new-password" required minlength="8" placeholder="Mínimo 8 caracteres">
      </div>
      <p id="register-error" class="field error-text" style="display:none;"></p>
      <button type="submit" class="btn btn-primary btn-block" id="btn-register">Activar mi cuenta</button>
    </form>

    <p class="text-center muted" style="font-size:12px;margin-top:20px;">
      ¿Aún no tienes tu código? Escríbenos por WhatsApp para obtenerlo.
    </p>
  </div>
</div>

<script src="/assets/js/app.js?v=<?= ASSET_VER ?>"></script>
<script>
document.querySelectorAll('.switcher button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.switcher button').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('form-' + btn.dataset.tab).classList.add('active');
  });
});

document.getElementById('form-login').addEventListener('submit', async (e) => {
  e.preventDefault();
  const errEl = document.getElementById('login-error');
  errEl.style.display = 'none';
  const btn = document.getElementById('btn-login');
  btn.disabled = true;
  try {
    const res = await MV.api('/api/auth.php?action=login', {
      method: 'POST',
      body: {
        email: document.getElementById('li-email').value.trim(),
        password: document.getElementById('li-pass').value,
      },
    });
    window.location.href = res.is_admin ? '/admin/index.php' : '/app/index.php';
  } catch (err) {
    errEl.textContent = err.message;
    errEl.style.display = 'block';
  } finally {
    btn.disabled = false;
  }
});

document.getElementById('form-register').addEventListener('submit', async (e) => {
  e.preventDefault();
  const errEl = document.getElementById('register-error');
  errEl.style.display = 'none';
  const btn = document.getElementById('btn-register');
  btn.disabled = true;
  try {
    await MV.api('/api/auth.php?action=register', {
      method: 'POST',
      body: {
        code: document.getElementById('rg-code').value.trim(),
        name: document.getElementById('rg-name').value.trim(),
        email: document.getElementById('rg-email').value.trim(),
        password: document.getElementById('rg-pass').value,
      },
    });
    window.location.href = '/app/onboarding.php';
  } catch (err) {
    errEl.textContent = err.message;
    errEl.style.display = 'block';
  } finally {
    btn.disabled = false;
  }
});
</script>
</body>
</html>
