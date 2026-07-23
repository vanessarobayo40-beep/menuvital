<?php
/**
 * MenúVital — Aritmética de cantidades ("200 g", "1/2 taza", "2 unidades"...)
 * Permite sumar/restar cantidades de verdad (no solo concatenar texto) para
 * la lista de compras exacta y el descuento de despensa por porciones.
 *
 * Una "bolsa" (`$bag`) es un array [unidad => cantidad numérica, ...] más una
 * clave especial '_unparsed' con los términos que no se pudieron interpretar
 * (ej. "al gusto"), que se preservan tal cual para no perder información.
 */

/** alias de unidad => [unidad canónica, factor de conversión a esa unidad] */
const QTY_UNITS = [
    'g' => ['g', 1], 'gr' => ['g', 1], 'grs' => ['g', 1], 'gramo' => ['g', 1], 'gramos' => ['g', 1],
    'kg' => ['g', 1000], 'kilo' => ['g', 1000], 'kilos' => ['g', 1000],
    'libra' => ['g', 500], 'libras' => ['g', 500], 'lb' => ['g', 500],
    'ml' => ['ml', 1], 'mililitro' => ['ml', 1], 'mililitros' => ['ml', 1],
    'litro' => ['ml', 1000], 'litros' => ['ml', 1000], 'l' => ['ml', 1000],
    'unidad' => ['unidad', 1], 'unidades' => ['unidad', 1], 'und' => ['unidad', 1],
    'taza' => ['taza', 1], 'tazas' => ['taza', 1],
    'cda' => ['cda', 1], 'cdas' => ['cda', 1], 'cucharada' => ['cda', 1], 'cucharadas' => ['cda', 1],
    'cdta' => ['cdta', 1], 'cdtas' => ['cdta', 1], 'cucharadita' => ['cdta', 1], 'cucharaditas' => ['cdta', 1],
    'diente' => ['diente', 1], 'dientes' => ['diente', 1],
    'rama' => ['rama', 1], 'ramas' => ['rama', 1],
    'hoja' => ['hoja', 1], 'hojas' => ['hoja', 1],
    'pizca' => ['pizca', 1], 'pizcas' => ['pizca', 1],
    'tajada' => ['tajada', 1], 'tajadas' => ['tajada', 1],
    'rebanada' => ['rebanada', 1], 'rebanadas' => ['rebanada', 1],
    'tallo' => ['tallo', 1], 'tallos' => ['tallo', 1],
    'lata' => ['lata', 1], 'latas' => ['lata', 1],
    'filete' => ['filete', 1], 'filetes' => ['filete', 1],
    'trozo' => ['trozo', 1], 'trozos' => ['trozo', 1],
    'paquete' => ['paquete', 1], 'paquetes' => ['paquete', 1],
    'scoop' => ['scoop', 1], 'scoops' => ['scoop', 1],
    'loncha' => ['loncha', 1], 'lonchas' => ['loncha', 1],
];

/** Etiqueta a mostrar por unidad canónica (singular => plural). */
const QTY_UNIT_LABELS = [
    'g' => ['g', 'g'], 'kg' => ['kg', 'kg'], 'ml' => ['ml', 'ml'], 'litro' => ['litro', 'litros'],
    'unidad' => ['unidad', 'unidades'], 'taza' => ['taza', 'tazas'], 'cda' => ['cda', 'cdas'],
    'cdta' => ['cdta', 'cdtas'], 'diente' => ['diente', 'dientes'], 'rama' => ['rama', 'ramas'],
    'hoja' => ['hoja', 'hojas'], 'pizca' => ['pizca', 'pizcas'], 'tajada' => ['tajada', 'tajadas'],
    'rebanada' => ['rebanada', 'rebanadas'], 'tallo' => ['tallo', 'tallos'], 'lata' => ['lata', 'latas'],
    'filete' => ['filete', 'filetes'], 'trozo' => ['trozo', 'trozos'], 'paquete' => ['paquete', 'paquetes'],
    'scoop' => ['scoop', 'scoops'], 'loncha' => ['loncha', 'lonchas'],
];

/**
 * Interpreta UN término de cantidad (ej. "200 g", "1/2 taza", "2 unidades").
 * Devuelve ['num'=>float, 'unit'=>string] o null si no se puede interpretar
 * (ej. "al gusto", cadena vacía).
 */
function parse_qty(string $qty): ?array {
    $qty = trim($qty);
    if ($qty === '') {
        return null;
    }
    if (!preg_match('/^([\d.,\/]+)\s*(.*)$/u', $qty, $m)) {
        return null;
    }
    $numRaw = $m[1];
    $unitRaw = trim($m[2]);

    if (strpos($numRaw, '/') !== false) {
        $parts = explode('/', $numRaw, 2);
        $num = (float)str_replace(',', '.', $parts[0]);
        $den = (float)str_replace(',', '.', $parts[1] ?? '1');
        if ($den == 0.0) {
            return null;
        }
        $num = $num / $den;
    } else {
        $num = (float)str_replace(',', '.', $numRaw);
    }
    if ($num <= 0) {
        return null;
    }

    if ($unitRaw === '') {
        $unit = 'unidad';
    } else {
        // Solo la primera palabra cuenta como unidad ("cdta rallado" -> "cdta");
        // el resto son notas de preparación que no afectan la suma.
        $firstWord = strtok($unitRaw, " \t");
        $unitKey = mb_strtolower($firstWord, 'UTF-8');
        $unitKey = strtr($unitKey, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n']);
        if (isset(QTY_UNITS[$unitKey])) {
            [$unit, $factor] = QTY_UNITS[$unitKey];
            $num *= $factor;
        } else {
            // Unidad desconocida (ej. "al gusto rallado"): se trata como su
            // propia unidad, sin convertir, para no mezclarla con otras.
            $unit = $unitKey;
        }
    }
    return ['num' => $num, 'unit' => $unit];
}

/**
 * Interpreta una cantidad que puede tener varios términos unidos con " + "
 * (formato usado por la lista de compras y la despensa cuando se fusionan
 * cantidades de distinta unidad). Devuelve una lista de resultados de
 * parse_qty(), y guarda en $unparsed (por referencia) los términos que no
 * se pudieron interpretar.
 */
function parse_qty_terms(string $qty, ?array &$unparsed = null): array {
    $unparsed = [];
    $out = [];
    foreach (explode('+', $qty) as $term) {
        $term = trim($term);
        if ($term === '') {
            continue;
        }
        $parsed = parse_qty($term);
        if ($parsed === null) {
            $unparsed[] = $term;
        } else {
            $out[] = $parsed;
        }
    }
    return $out;
}

/** Formatea una cantidad numérica + unidad canónica como texto legible. */
function format_qty(float $num, string $unit): string {
    if ($unit === 'g' && $num >= 1000) {
        $num /= 1000;
        $unit = 'kg';
    } elseif ($unit === 'ml' && $num >= 1000) {
        $num /= 1000;
        $unit = 'litro';
    }
    $rounded = round($num, 2);
    if (abs($rounded - round($rounded)) < 0.01) {
        $numStr = (string)(int)round($rounded);
    } else {
        $numStr = rtrim(rtrim(number_format($rounded, 1, '.', ''), '0'), '.');
    }
    $n = (float)$numStr;
    if (isset(QTY_UNIT_LABELS[$unit])) {
        [$sing, $plur] = QTY_UNIT_LABELS[$unit];
        $label = ($n <= 1) ? $sing : $plur;
    } else {
        $label = $unit;
    }
    return $numStr . ' ' . $label;
}

/** Agrega una cantidad (string) a una bolsa [$unit => $num, '_unparsed' => [...]], multiplicada por $multiplier. */
function qty_bag_add(array &$bag, string $qtyStr, float $multiplier = 1.0): void {
    $unparsed = [];
    $terms = parse_qty_terms($qtyStr, $unparsed);
    foreach ($terms as $t) {
        $bag[$t['unit']] = ($bag[$t['unit']] ?? 0.0) + $t['num'] * $multiplier;
    }
    if ($unparsed) {
        $bag['_unparsed'] = $bag['_unparsed'] ?? [];
        foreach ($unparsed as $u) {
            if (!in_array($u, $bag['_unparsed'], true)) {
                $bag['_unparsed'][] = $u;
            }
        }
    }
}

/**
 * Resta, unidad por unidad, lo que ya se tiene ($have) de lo que se necesita
 * ($need). No cruza unidades distintas (200 g no se resta de "2 unidades").
 * El resultado nunca es negativo. Los '_unparsed' de $need se conservan tal
 * cual (no se puede saber si $have los cubre).
 */
function qty_bag_subtract(array $need, array $have): array {
    $result = [];
    foreach ($need as $unit => $num) {
        if ($unit === '_unparsed') {
            $result['_unparsed'] = $num;
            continue;
        }
        $left = $num - (float)($have[$unit] ?? 0);
        if ($left > 0.001) {
            $result[$unit] = $left;
        }
    }
    return $result;
}

/** Convierte una bolsa en el texto final ("500 g + 2 unidades"). */
function format_qty_bag(array $bag): string {
    $parts = [];
    foreach ($bag as $unit => $num) {
        if ($unit === '_unparsed') {
            continue;
        }
        if ($num > 0.001) {
            $parts[] = format_qty((float)$num, (string)$unit);
        }
    }
    foreach ($bag['_unparsed'] ?? [] as $u) {
        $parts[] = $u;
    }
    return implode(' + ', $parts);
}

/** Suma dos cantidades en formato texto (ej. para fusionar despensa). */
function qty_string_add(string $a, string $b): string {
    $bag = [];
    if (trim($a) !== '') {
        qty_bag_add($bag, $a, 1.0);
    }
    if (trim($b) !== '') {
        qty_bag_add($bag, $b, 1.0);
    }
    return format_qty_bag($bag);
}
