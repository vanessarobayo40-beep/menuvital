<?php
/**
 * MenúVital — Catálogo de ingredientes comunes en Colombia
 * Se usa para: autocompletado del mercado, agrupar la lista de compras
 * por secciones y hacer el matching entre despensa y recetas.
 */

/** Ingredientes que se asumen siempre disponibles (no cuentan en el matching). */
const PANTRY_STAPLES = ['sal', 'pimienta', 'aceite', 'agua', 'aceite vegetal'];

/** item => categoría (sección del supermercado) */
function ingredient_catalog(): array {
    static $cat = null;
    if ($cat !== null) {
        return $cat;
    }
    $cat = [
        // ---- Frutas ----
        'banano' => 'Frutas', 'manzana' => 'Frutas', 'pera' => 'Frutas', 'papaya' => 'Frutas',
        'piña' => 'Frutas', 'mango' => 'Frutas', 'fresa' => 'Frutas', 'mora' => 'Frutas',
        'lulo' => 'Frutas', 'maracuyá' => 'Frutas', 'guayaba' => 'Frutas', 'guanábana' => 'Frutas',
        'tomate de árbol' => 'Frutas', 'naranja' => 'Frutas', 'mandarina' => 'Frutas',
        'limón' => 'Frutas', 'sandía' => 'Frutas', 'melón' => 'Frutas', 'uvas' => 'Frutas',
        'coco' => 'Frutas', 'durazno' => 'Frutas', 'kiwi' => 'Frutas', 'granadilla' => 'Frutas',
        'curuba' => 'Frutas', 'arándanos' => 'Frutas',
        // ---- Verduras y hortalizas ----
        'tomate' => 'Verduras', 'tomate cherry' => 'Verduras', 'cebolla' => 'Verduras',
        'cebolla larga' => 'Verduras', 'cebolla roja' => 'Verduras', 'ajo' => 'Verduras',
        'zanahoria' => 'Verduras', 'habichuela' => 'Verduras', 'arveja' => 'Verduras',
        'brócoli' => 'Verduras', 'coliflor' => 'Verduras', 'espinaca' => 'Verduras',
        'acelga' => 'Verduras', 'lechuga' => 'Verduras', 'repollo' => 'Verduras',
        'pepino' => 'Verduras', 'pimentón' => 'Verduras', 'apio' => 'Verduras',
        'calabacín' => 'Verduras', 'ahuyama' => 'Verduras', 'champiñones' => 'Verduras',
        'mazorca' => 'Verduras', 'remolacha' => 'Verduras', 'rábano' => 'Verduras',
        'berenjena' => 'Verduras', 'rúgula' => 'Verduras', 'aguacate' => 'Verduras',
        'espárragos' => 'Verduras', 'puerro' => 'Verduras', 'pepinillo' => 'Verduras',
        // ---- Tubérculos y plátanos ----
        'papa' => 'Tubérculos y plátanos', 'papa criolla' => 'Tubérculos y plátanos',
        'yuca' => 'Tubérculos y plátanos', 'plátano verde' => 'Tubérculos y plátanos',
        'plátano maduro' => 'Tubérculos y plátanos', 'arracacha' => 'Tubérculos y plátanos',
        'batata' => 'Tubérculos y plátanos', 'ñame' => 'Tubérculos y plátanos',
        // ---- Proteínas ----
        'pechuga de pollo' => 'Proteínas', 'muslos de pollo' => 'Proteínas',
        'pollo entero' => 'Proteínas', 'carne de res' => 'Proteínas', 'carne molida' => 'Proteínas',
        'lomo de cerdo' => 'Proteínas', 'chuleta de cerdo' => 'Proteínas',
        'pescado blanco' => 'Proteínas', 'tilapia' => 'Proteínas', 'mojarra' => 'Proteínas',
        'trucha' => 'Proteínas', 'salmón' => 'Proteínas', 'atún en lata' => 'Proteínas',
        'sardinas' => 'Proteínas', 'camarones' => 'Proteínas', 'tofu' => 'Proteínas',
        'jamón de pavo' => 'Proteínas', 'pechuga de pavo' => 'Proteínas',
        'pavo molido' => 'Proteínas', 'merluza' => 'Proteínas',
        // ---- Granos y legumbres ----
        'arroz' => 'Granos y cereales', 'arroz integral' => 'Granos y cereales',
        'pasta' => 'Granos y cereales', 'pasta integral' => 'Granos y cereales',
        'avena' => 'Granos y cereales', 'avena en hojuelas' => 'Granos y cereales',
        'quinua' => 'Granos y cereales', 'cuscús' => 'Granos y cereales',
        'lentejas' => 'Granos y cereales', 'fríjol rojo' => 'Granos y cereales',
        'fríjol negro' => 'Granos y cereales', 'garbanzos' => 'Granos y cereales',
        'harina de maíz' => 'Granos y cereales', 'arepas' => 'Granos y cereales',
        'pan integral' => 'Granos y cereales', 'tostadas integrales' => 'Granos y cereales',
        'harina de avena' => 'Granos y cereales', 'harina de trigo' => 'Granos y cereales',
        'pan árabe integral' => 'Granos y cereales', 'granola' => 'Granos y cereales',
        // ---- Lácteos y huevos ----
        'huevos' => 'Lácteos y huevos', 'leche' => 'Lácteos y huevos',
        'leche deslactosada' => 'Lácteos y huevos', 'yogur natural' => 'Lácteos y huevos',
        'yogur griego' => 'Lácteos y huevos', 'queso campesino' => 'Lácteos y huevos',
        'queso mozarella' => 'Lácteos y huevos', 'queso costeño' => 'Lácteos y huevos',
        'queso parmesano' => 'Lácteos y huevos', 'cuajada' => 'Lácteos y huevos',
        'kumis' => 'Lácteos y huevos', 'mantequilla' => 'Lácteos y huevos',
        'crema de leche' => 'Lácteos y huevos', 'requesón' => 'Lácteos y huevos',
        'leche de almendras' => 'Lácteos y huevos',
        // ---- Despensa ----
        'aceite de oliva' => 'Despensa', 'aceite vegetal' => 'Despensa', 'sal' => 'Despensa',
        'azúcar' => 'Despensa', 'panela' => 'Despensa', 'miel' => 'Despensa',
        'café' => 'Despensa', 'cacao en polvo' => 'Despensa', 'chocolate de mesa' => 'Despensa',
        'vinagre' => 'Despensa', 'mostaza' => 'Despensa', 'salsa de soya' => 'Despensa',
        'pasta de tomate' => 'Despensa', 'leche de coco' => 'Despensa',
        'mantequilla de maní' => 'Despensa', 'maní' => 'Despensa', 'almendras' => 'Despensa',
        'nueces' => 'Despensa', 'semillas de chía' => 'Despensa', 'linaza' => 'Despensa',
        'uvas pasas' => 'Despensa', 'aceitunas' => 'Despensa', 'maicena' => 'Despensa',
        'vinagre balsámico' => 'Despensa',
        // ---- Hierbas y especias ----
        'cilantro' => 'Hierbas y especias', 'perejil' => 'Hierbas y especias',
        'albahaca' => 'Hierbas y especias', 'orégano' => 'Hierbas y especias',
        'tomillo' => 'Hierbas y especias', 'laurel' => 'Hierbas y especias',
        'comino' => 'Hierbas y especias', 'pimienta' => 'Hierbas y especias',
        'canela' => 'Hierbas y especias', 'jengibre' => 'Hierbas y especias',
        'cúrcuma' => 'Hierbas y especias', 'paprika' => 'Hierbas y especias',
        'hierbabuena' => 'Hierbas y especias', 'guascas' => 'Hierbas y especias',
        'vainilla' => 'Hierbas y especias',
    ];
    return $cat;
}

/** Sección del supermercado para un ingrediente (con matching flexible). */
function ingredient_category(string $item): string {
    $catalog = ingredient_catalog();
    $norm = normalize_ingredient($item);
    foreach ($catalog as $name => $category) {
        if (normalize_ingredient($name) === $norm) {
            return $category;
        }
    }
    // Segundo intento: matching flexible (ej. "pollo" -> "pechuga de pollo")
    foreach ($catalog as $name => $category) {
        if (ingredients_match($item, $name)) {
            return $category;
        }
    }
    return 'Otros';
}

/** Normaliza para comparar: minúsculas, sin tildes, sin plural simple. */
function normalize_ingredient(string $s): string {
    $s = mb_strtolower(trim($s), 'UTF-8');
    $s = strtr($s, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
    $s = preg_replace('/[^a-z0-9 ]/', '', $s);
    $words = array_filter(explode(' ', $s), function ($w) {
        return $w !== '' && !in_array($w, ['de', 'en', 'la', 'el', 'con'], true);
    });
    $words = array_map(function ($w) {
        // plural simple: "huevos" -> "huevo", "papas" -> "papa"
        if (mb_strlen($w) > 4 && substr($w, -1) === 's' && substr($w, -2) !== 'es') {
            return substr($w, 0, -1);
        }
        if (mb_strlen($w) > 5 && substr($w, -2) === 'es') {
            return substr($w, 0, -2);
        }
        return $w;
    }, $words);
    return implode(' ', $words);
}

/**
 * ¿Dos nombres de ingrediente se refieren a lo mismo?
 * "pollo" coincide con "pechuga de pollo"; "papa" NO coincide con "papaya".
 */
function ingredients_match(string $a, string $b): bool {
    $na = normalize_ingredient($a);
    $nb = normalize_ingredient($b);
    if ($na === '' || $nb === '') {
        return false;
    }
    if ($na === $nb) {
        return true;
    }
    $wa = explode(' ', $na);
    $wb = explode(' ', $nb);
    $shorter = count($wa) <= count($wb) ? $wa : $wb;
    $longer  = count($wa) <= count($wb) ? $wb : $wa;
    // Todas las palabras del nombre corto deben estar en el largo (palabra completa)
    foreach ($shorter as $w) {
        if (!in_array($w, $longer, true)) {
            return false;
        }
    }
    return true;
}

/** ¿El ingrediente es un básico que siempre se asume disponible? */
function is_staple(string $item): bool {
    foreach (PANTRY_STAPLES as $staple) {
        if (ingredients_match($item, $staple)) {
            return true;
        }
    }
    return false;
}

/** ¿El ingrediente está en la despensa del usuario? */
function in_pantry(string $item, array $pantryItems): bool {
    if (is_staple($item)) {
        return true;
    }
    foreach ($pantryItems as $p) {
        if (ingredients_match($item, $p)) {
            return true;
        }
    }
    return false;
}
