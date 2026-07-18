<?php
/**
 * MenúVital — Pie HTML compartido (bottom nav + cierre de body/html).
 * Variable esperada: $ACTIVE_NAV
 */
$navItems = [
    'hoy'      => ['href' => '/app/index.php',     'label' => 'Hoy'],
    'plan'     => ['href' => '/app/plan.php',      'label' => 'Semana'],
    'mercado'  => ['href' => '/app/mercado.php',   'label' => 'Mercado'],
    'coach'    => ['href' => '/app/coach.php',     'label' => 'Coach'],
    'progreso' => ['href' => '/app/progreso.php',  'label' => 'Progreso'],
];
?>
</main>
<nav class="bottom-nav">
  <?php foreach ($navItems as $key => $item): ?>
  <a href="<?= e($item['href']) ?>" class="<?= ($ACTIVE_NAV ?? '') === $key ? 'active' : '' ?>">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= nav_icon($key) ?></svg>
    <span><?= e($item['label']) ?></span>
  </a>
  <?php endforeach; ?>
</nav>
<script src="/assets/js/app.js?v=<?= ASSET_VER ?>"></script>
<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
}
MV.lockBackButton();
</script>
</body>
</html>
