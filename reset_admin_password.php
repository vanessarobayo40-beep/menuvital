<?php
/**
 * MenúVital — Restablece la contraseña de la cuenta administradora
 * Uso: https://tudominio.com/reset_admin_password.php?key=TU_INSTALL_KEY
 *
 * La app no tiene ningún flujo para cambiar la contraseña desde adentro
 * (la contraseña inicial solo se lee una vez, al crear la cuenta en
 * install.php). Este script la actualiza directamente en la base de datos,
 * protegido con la misma INSTALL_KEY de siempre. La contraseña nueva se
 * manda por POST (no por la URL) para que no quede en el historial del
 * navegador ni en los logs del servidor.
 *
 * Por seguridad, bórralo del servidor después de usarlo (igual que se
 * recomienda con install.php una vez que ya no lo necesites a diario).
 */

if (!file_exists(__DIR__ . '/includes/config.php')) {
    http_response_code(500);
    exit('Falta includes/config.php.');
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$key = $_GET['key'] ?? $_POST['key'] ?? '';
if (!defined('INSTALL_KEY') || INSTALL_KEY === '' || !is_string($key) || !hash_equals(INSTALL_KEY, $key)) {
    http_response_code(403);
    exit('Acceso denegado. Usa reset_admin_password.php?key=TU_INSTALL_KEY');
}

header('Content-Type: text/html; charset=utf-8');

$pdo = db();
$stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE email = ? AND is_admin = 1');
$stmt->execute([ADMIN_EMAIL]);
$admin = $stmt->fetch();

if (!$admin) {
    exit('No se encontró ninguna cuenta administradora con el correo ' . htmlspecialchars(ADMIN_EMAIL) . '.');
}

$done = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['password2'] ?? '');
    if (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $confirm) {
        $error = 'Las dos contraseñas no coinciden.';
    } else {
        $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $admin['id']]);
        $done = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Restablecer contraseña — MenúVital</title>
<style>
  body{font-family:system-ui,sans-serif;max-width:420px;margin:40px auto;padding:0 20px;color:#1f2937}
  h1{color:#059669;font-size:20px}
  input{width:100%;padding:10px;margin:6px 0 14px;border:1px solid #d1d5db;border-radius:8px;font-size:15px;box-sizing:border-box}
  button{background:#059669;color:#fff;border:none;padding:12px 20px;border-radius:8px;font-size:15px;cursor:pointer;width:100%}
  .error{background:#fef2f2;color:#b91c1c;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:14px}
  .ok{background:#f0fdf4;color:#166534;padding:14px;border-radius:8px}
</style></head>
<body>
<h1>Restablecer contraseña de administradora</h1>
<p>Cuenta: <strong><?= htmlspecialchars($admin['email']) ?></strong></p>

<?php if ($done): ?>
  <div class="ok">✅ Contraseña actualizada. Ya puedes entrar en <a href="/login.php">/login.php</a> con la contraseña nueva.</div>
  <p style="margin-top:20px;color:#6b7280;font-size:13px;">Por seguridad, borra este archivo (<code>reset_admin_password.php</code>) del servidor ahora que ya lo usaste.</p>
<?php else: ?>
  <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post">
    <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
    <label>Contraseña nueva</label>
    <input type="password" name="password" minlength="8" required autofocus>
    <label>Repite la contraseña nueva</label>
    <input type="password" name="password2" minlength="8" required>
    <button type="submit">Guardar contraseña</button>
  </form>
<?php endif; ?>
</body>
</html>
