<?php
/**
 * MenúVital — Shell visual de la app (header + bottom nav)
 * Uso:
 *   $PAGE_TITLE = 'Hoy';
 *   $ACTIVE_NAV = 'hoy';
 *   require __DIR__ . '/../includes/layout_top.php';
 *   ... contenido ...
 *   require __DIR__ . '/../includes/layout_bottom.php';
 */

/**
 * Versión de los estáticos (app.js, style.css). Súbela cada vez que cambien
 * para forzar que el navegador descargue la versión nueva de inmediato,
 * en vez de esperar los 7 días de caché configurados en .htaccess.
 */
const ASSET_VER = '20260718b';

function nav_icon(string $name): string {
    $icons = [
        'hoy' => '<path d="M12 3v3M5.6 5.6l2.1 2.1M3 12h3M5.6 18.4l2.1-2.1M12 18v3M16.3 16.3l2.1 2.1M18 12h3M16.3 7.7l2.1-2.1"/><circle cx="12" cy="12" r="4"/>',
        'plan' => '<rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>',
        'mercado' => '<path d="M6 6h15l-1.5 8.5a2 2 0 0 1-2 1.5H8.5a2 2 0 0 1-2-1.5L4.5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/>',
        'recetas' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M9 7h7M9 11h5"/>',
        'coach' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>',
        'progreso' => '<path d="M3 3v18h18M7 15l4-4 3 3 5-6"/>',
        'perfil' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>',
    ];
    return $icons[$name] ?? '';
}
?>
