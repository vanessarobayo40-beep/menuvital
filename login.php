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
<meta name="theme-color" content="#0E6B45">
<?= theme_init_script() ?>
<link rel="icon" href="/assets/img/icon-192-v3.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=<?= ASSET_VER ?>">
<style>
  body { background: var(--grad-soft); min-height: 100vh; }
  .auth-wrap { min-height: 100vh; display: flex; align-items: center; padding: 32px 0; }
  .auth-card { width: 100%; }
  .logo-big { text-align: center; margin-bottom: 28px; }
  .logo-big .circle {
    width: 64px; height: 64px; border-radius: 20px; margin: 0 auto 14px;
    box-shadow: var(--shadow-md); overflow: hidden;
  }
  .logo-big .circle img { width: 100%; height: 100%; display: block; }
  .logo-big h1 { font-size: 22px; margin: 0; }
  .logo-big p { color: var(--t2); font-size: 14px; margin: 4px 0 0; }
  .form-panel { display: none; }
  .form-panel.active { display: block; }
  #code-input { text-transform: uppercase; text-align: center; font-size: 18px; font-weight: 700; letter-spacing: 1px; }
  .toggle-email { text-align: center; margin-top: 18px; }
  .toggle-email a { font-size: 13px; color: var(--t2); font-weight: 600; }

  .install-premium {
    display: none;
    align-items: center; gap: 12px;
    background: var(--card-bg);
    border: 1.5px solid transparent;
    background-image: linear-gradient(var(--card-bg), var(--card-bg)), var(--grad-v);
    background-origin: border-box; background-clip: padding-box, border-box;
    border-radius: var(--radius); padding: 14px 16px; margin-bottom: 20px;
    box-shadow: var(--shadow-md); cursor: pointer; transition: transform var(--ease);
  }
  .install-premium:active { transform: scale(0.98); }
  .install-premium .ip-icon {
    width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
    background: var(--grad-v); display: flex; align-items: center; justify-content: center; font-size: 20px;
  }
  .install-premium .ip-text { flex: 1; text-align: left; }
  .install-premium .ip-title { font-size: 14px; font-weight: 700; color: var(--t1); margin: 0; }
  .install-premium .ip-sub { font-size: 12px; color: var(--t2); margin: 2px 0 0; }
  .install-premium .ip-arrow { color: var(--green-dark); font-size: 18px; flex-shrink: 0; }
</style>
</head>
<body>
<div class="container auth-wrap">
  <div class="auth-card">
    <div class="logo-big">
      <div class="circle">
        <img src="/assets/img/icon-192-v3.png" alt="MenúVital">
      </div>
      <h1>MenúVital</h1>
      <p>Tu coach de nutrición y menú diario</p>
    </div>

    <div class="install-premium" id="install-premium">
      <div class="ip-icon">📲</div>
      <div class="ip-text">
        <p class="ip-title">Descargar la app</p>
        <p class="ip-sub">Instálala en tu celular y ábrela con un toque</p>
      </div>
      <span class="ip-arrow">›</span>
    </div>
    <p id="install-steps-login" class="muted" style="display:none;margin:-10px 0 18px;font-size:12px;text-align:center;"></p>

    <form id="form-code" class="card form-panel active">
      <div class="field mb-0">
        <label for="code-input">Tu código de acceso</label>
        <input type="text" id="code-input" required placeholder="MV-XXX-XXX" autocomplete="off" autocapitalize="characters">
        <p class="hint">Te lo enviamos por WhatsApp cuando confirmamos tu pago. Puedes usarlo hasta en 2 dispositivos.</p>
      </div>
      <p id="code-error" class="field error-text" style="display:none;"></p>
      <button type="submit" class="btn btn-primary btn-block" id="btn-code">Entrar</button>
    </form>

    <form id="form-login" class="card form-panel">
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

    <div class="toggle-email">
      <a href="#" id="link-toggle-email">Entrar con correo y contraseña</a>
    </div>

    <p class="text-center muted" style="font-size:12px;margin-top:20px;">
      ¿Aún no tienes tu código? Escríbenos por WhatsApp para obtenerlo.
    </p>
  </div>
</div>

<script src="/assets/js/app.js?v=<?= ASSET_VER ?>"></script>
<script>
// ---------- Descargar app (botón premium) ----------
try {
  if (!MV.install.isStandalone()) {
    const installBtn = document.getElementById('install-premium');
    const showButton = () => { installBtn.style.display = 'flex'; };
    document.addEventListener('mv-installable', showButton);
    if (MV.install.hasPrompt() || MV.install.platform() === 'ios') showButton();
    installBtn.addEventListener('click', async () => {
      const result = await MV.install.trigger();
      if (result === 'installed') {
        installBtn.style.display = 'none';
        MV.toast('¡Listo! MenúVital quedó instalada en tu celular 🎉');
      } else if (result === 'manual') {
        const stepsEl = document.getElementById('install-steps-login');
        stepsEl.innerHTML = MV.install.manualSteps();
        stepsEl.style.display = stepsEl.style.display === 'none' ? 'block' : 'none';
      }
    });
  }
} catch (e) { /* instalar es secundario: nunca debe romper el login */ }

document.getElementById('link-toggle-email').addEventListener('click', (e) => {
  e.preventDefault();
  const usingCode = document.getElementById('form-code').classList.contains('active');
  document.getElementById('form-code').classList.toggle('active', !usingCode);
  document.getElementById('form-login').classList.toggle('active', usingCode);
  e.target.textContent = usingCode ? 'Entrar con correo y contraseña' : 'Entrar con mi código';
});

document.getElementById('form-code').addEventListener('submit', async (e) => {
  e.preventDefault();
  const errEl = document.getElementById('code-error');
  errEl.style.display = 'none';
  const btn = document.getElementById('btn-code');
  btn.disabled = true;
  try {
    const res = await MV.api('/api/auth.php?action=code_login', {
      method: 'POST',
      body: { code: document.getElementById('code-input').value.trim() },
    });
    window.location.href = res.first_time ? '/app/onboarding.php' : '/app/index.php';
  } catch (err) {
    errEl.textContent = err.message;
    errEl.style.display = 'block';
  } finally {
    btn.disabled = false;
  }
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
</script>
</body>
</html>
