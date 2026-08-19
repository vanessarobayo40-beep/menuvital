<?php
/**
 * MenúVital — Limpia recetas oficiales "huérfanas" (uso ocasional, no de una sola vez)
 * Uso:
 *   https://tudominio.com/cleanup_orphan_recipes.php?key=TU_INSTALL_KEY
 *     -> solo LISTA las huérfanas, no borra nada.
 *   https://tudominio.com/cleanup_orphan_recipes.php?key=TU_INSTALL_KEY&confirm=1
 *     -> borra las que aparecieron en el listado anterior.
 *
 * install.php solo AGREGA recetas nuevas y ACTUALIZA fotos que estaban vacías
 * — nunca borra. Cuando una receta oficial se renombra en database/recipes_data.php
 * (para corregir un typo, un nombre trunco, etc.), install.php inserta el
 * nombre nuevo como si fuera una receta más, pero la fila vieja con el nombre
 * anterior se queda huérfana en la base de datos para siempre — con su foto
 * y datos tal como estaban antes de cualquier corrección. Esas filas viejas
 * son las que de repente aparecen "sin foto" o con datos raros en la app.
 *
 * Esto compara por NOMBRE EXACTO contra el archivo actual: cualquier receta
 * oficial (user_id NULL) cuyo nombre no exista literalmente en
 * database/recipes_data.php se considera huérfana.
 */

if (!file_exists(__DIR__ . '/includes/config.php')) {
    http_response_code(500);
    exit('Falta includes/config.php.');
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$key = $_GET['key'] ?? '';
if (!defined('INSTALL_KEY') || INSTALL_KEY === '' || !is_string($key) || !hash_equals(INSTALL_KEY, $key)) {
    http_response_code(403);
    exit('Acceso denegado. Usa cleanup_orphan_recipes.php?key=TU_INSTALL_KEY');
}

header('Content-Type: text/html; charset=utf-8');

$seedRecipes = require __DIR__ . '/database/recipes_data.php';
$validNames = [];
foreach ($seedRecipes as $r) {
    $validNames[$r[0]] = true;
}

$pdo = db();
$official = $pdo->query('SELECT id, name, meal_type, image_url FROM recipes WHERE user_id IS NULL')->fetchAll();

$orphans = [];
foreach ($official as $r) {
    if (!isset($validNames[$r['name']])) {
        $orphans[] = $r;
    }
}

echo '<h1>Recetas huérfanas</h1>';
echo '<p>' . count($official) . ' recetas oficiales en la base de datos, ' . count($seedRecipes)
    . ' en el archivo actual, <strong>' . count($orphans) . ' huérfanas</strong> (en la base pero ya no en el archivo).</p>';

if (!$orphans) {
    echo '<p>✅ No hay ninguna. La base de datos ya coincide exactamente con el archivo actual.</p>';
    exit;
}

$confirm = ($_GET['confirm'] ?? '') === '1';

if (!$confirm) {
    echo '<ul>';
    foreach ($orphans as $r) {
        $img = $r['image_url'] ? htmlspecialchars($r['image_url']) : '<em>(sin foto)</em>';
        echo '<li>#' . $r['id'] . ' — ' . htmlspecialchars($r['name']) . ' [' . htmlspecialchars($r['meal_type']) . '] — ' . $img . '</li>';
    }
    echo '</ul>';
    echo '<p><strong>Nada se ha borrado todavía.</strong> Si esta lista se ve bien (nombres viejos/con typo, no recetas que quieras conservar), '
        . 'entra a <a href="?key=' . htmlspecialchars($key) . '&confirm=1">este mismo link con &amp;confirm=1</a> para borrarlas.</p>';
    exit;
}

$ids = array_column($orphans, 'id');
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$pdo->prepare("DELETE FROM menu_entries WHERE recipe_id IN ($placeholders)")->execute($ids);
$pdo->prepare("DELETE FROM favorite_recipes WHERE recipe_id IN ($placeholders)")->execute($ids);
$pdo->prepare("DELETE FROM recipes WHERE id IN ($placeholders)")->execute($ids);

echo '<p>✅ Borradas ' . count($ids) . ' recetas huérfanas (y sus referencias en menú/favoritos). '
    . 'Las recetas propias de cada usuaria nunca se tocan.</p>';
