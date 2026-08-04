# -*- coding: utf-8 -*-
"""
MenúVital — Parser de líneas de ingrediente crudas (texto de los .xlsx fuente)
hacia: (a) el formato "nombre|cantidad" que usa la app (planner.php::ingredient_name),
       (b) los gramos equivalentes para poder sumar macros con nutrition_data.py.

No pretende ser un parser NLP perfecto: los ingredientes con cantidad ambigua
("al gusto", "opcional", una pizca) contribuyen 0 g a los macros — igual que
hace cualquier receta real, la sal no cambia las calorías del plato. Cuando
un ingrediente SÍ es relevante nutricionalmente pero la fuente no dio cantidad
(frecuente en recetas tipo "bowl": "Pollo en cubos", "Arroz integral") se
aplica una porción típica según su perfil nutricional (igual que hace
cualquier calculadora de nutrición ante un ingrediente sin medida), y queda
marcado quantified=False para que build_recipes.py lo cuente en el informe
de confianza de la receta.
"""
import re

from normalize import normalize_ingredient, ingredients_match
from nutrition_data import NUTRITION, ALIASES
from unit_weights import UNIT_GRAMS, UNIT_GRAMS_BY_CLASS, WHOLE_UNIT_GRAMS, SPICE_DEFAULT_GRAMS

_FRACTIONS = {
    '½': 0.5, '¼': 0.25, '¾': 0.75, '⅓': 1 / 3, '⅔': 2 / 3,
    '⅛': 0.125, '⅜': 0.375, '⅝': 0.625, '⅞': 0.875,
}
_FRACTION_CHARS = ''.join(_FRACTIONS.keys())

_NEGLIGIBLE = re.compile(
    r'^(al gusto|opcional|para (decorar|servir|acompañar|cocinar)|c/n|c\.n\.|'
    r'en spray|una pizca de|pizca de)', re.IGNORECASE)

# Fragmentos que no son ingredientes (metadatos de la celda, restos de otra
# columna, números sueltos): se descartan por completo, no se listan.
_JUNK_LINE = re.compile(
    r'^(porciones?|dificultad|tiempo|preparaci[oó]n|rinde|receta[s]?)\s*:?\s*\d*$|'
    r'^\(?[a-záéíóúñ ]{1,3}\)?$|^\d+$', re.IGNORECASE)


def is_junk_line(raw: str) -> bool:
    raw = raw.strip().strip('-*• \t.')
    if len(raw) < 3:
        return True
    return bool(_JUNK_LINE.match(raw))


# Descriptores que no cambian el valor nutricional y se recortan del nombre
# para que quede corto y legible en la ficha de la receta (igual que las
# 957 recetas existentes: "pollo", no "pollo cortado en trozos medianos").
_TRIM_AFTER = re.compile(r'\s*[,(].*$')
_ADJ_STRIP = re.compile(
    r'\b(picad[oa]s?|cortad[oa]s?( en (cubos|trozos|tiras|rodajas|láminas))?|'
    r'rallad[oa]s?|finamente|en (rodajas|cubos|tiras|láminas|trozos)|'
    r'mediano?s?|grande?s?|pequeñ[oa]s?|marud[oa]s?|madur[oa]s?|frescas?|frescos?|'
    r'congelad[oa]s?|al gusto|opcional(es)?|entero?s?|derretid[oa]s?|'
    r'batid[oa]s?|hervid[oa]s?|cocid[oa]s?|tostad[oa]s?|desmenuzad[oa]s?)\b',
    re.IGNORECASE)

# Alternativa de unidades conocidas (no un genérico "cualquier palabra"): así
# "¾ de cucharadita de vainilla" no confunde la palabra "de" con una unidad
# (que quedaría capturando "de" y dejando el nombre roto en dos "de" sueltos).
_UNIT_ALT = '|'.join(sorted((re.escape(u) for u in UNIT_GRAMS.keys()), key=len, reverse=True))

_QTY_RE = re.compile(
    r'^\(?\s*(?P<qty>'
    r'\d+\s+\d+\s*/\s*\d+'          # mixto: "1 1/2", "3 1/4"
    r'|\d+\s+[' + _FRACTION_CHARS + r']'  # mixto unicode: "3 ½"
    r'|\d+[\d.,]*\s*/\s*\d+'        # fracción simple: "1/2"
    r'|\d+[.,]?\d*\s*-\s*\d+[.,]?\d*'  # rango: "1-2"
    r'|\d+[.,]?\d*'                 # entero o decimal: "3", "2.5"
    r'|[' + _FRACTION_CHARS + r']'  # fracción unicode sola: "¾"
    r')\s*(?:de\s+)?'  # "¾ DE cucharadita..." — a veces la unidad viene tras un "de".
    r'(?:(?P<unit>' + _UNIT_ALT + r')(?![a-zñáéíóú]))?\.?\s*(?:de\s+|d[e]\s)?(?P<rest>.*)$',
    re.IGNORECASE,
)


def _parse_qty_number(txt: str):
    txt = txt.strip()
    if txt in _FRACTIONS:
        return _FRACTIONS[txt]
    # Mixto "N N/D" o "N <fracción unicode>" (con espacio de por medio).
    m = re.match(r'^(\d+)\s+(.+)$', txt)
    if m:
        whole, frac_part = m.groups()
        frac_val = _parse_qty_number(frac_part)
        if frac_val is not None:
            return int(whole) + frac_val
        return None
    if '-' in txt and '/' not in txt:
        a, b = txt.split('-', 1)
        try:
            return (float(a.replace(',', '.')) + float(b.replace(',', '.'))) / 2
        except ValueError:
            return None
    if '/' in txt:
        a, b = txt.split('/', 1)
        try:
            return float(a.replace(',', '.').strip()) / float(b.replace(',', '.').strip())
        except (ValueError, ZeroDivisionError):
            return None
    try:
        return float(txt.replace(',', '.'))
    except ValueError:
        return None


def _clean_name(rest: str) -> str:
    rest = _TRIM_AFTER.sub('', rest)
    rest = _ADJ_STRIP.sub('', rest)
    rest = re.sub(r'\s+', ' ', rest).strip(' .,-')
    return rest


def _match_nutrition_name(name: str):
    """
    Busca el nombre canónico en NUTRITION, vía alias o coincidencia flexible.
    Entre varios candidatos flexibles se queda con el MÁS ESPECÍFICO (el de
    más palabras) — si no, un alias corto y genérico como "pollo" (->
    "pechuga de pollo") le ganaría por casualidad a uno más preciso como
    "caldo de pollo" (-> "agua") cuando el texto real es "caldo de pollo
    con bajo contenido graso", inflando la receta con 480 g de pollo que
    en realidad es casi puro caldo.
    """
    norm = normalize_ingredient(name)
    if not norm:
        return None
    low = name.strip().lower()
    if low in ALIASES and ALIASES[low] in NUTRITION:
        return ALIASES[low]
    for key in NUTRITION:
        if normalize_ingredient(key) == norm:
            return key
    for alias, canon in ALIASES.items():
        if normalize_ingredient(alias) == norm and canon in NUTRITION:
            return canon

    # Coincidencia flexible: entre TODOS los candidatos (llaves de NUTRITION
    # y alias) que matchean, gana el de texto normalizado más largo (más
    # específico), no el primero que aparezca.
    best_key, best_len = None, -1
    for key in NUTRITION:
        if ingredients_match(name, key):
            l = len(normalize_ingredient(key))
            if l > best_len:
                best_key, best_len = key, l
    for alias, canon in ALIASES.items():
        if canon in NUTRITION and ingredients_match(name, alias):
            l = len(normalize_ingredient(alias))
            if l > best_len:
                best_key, best_len = canon, l
    return best_key


_QTY_PREFIX_RE = re.compile(
    r'^(?P<num>\d+\s+\d+\s*/\s*\d+|\d+\s+[' + _FRACTION_CHARS + r']|\d+[\d.,]*\s*/\s*\d+|'
    r'\d+[.,]?\d*\s*-\s*\d+[.,]?\d*|\d+[.,]?\d*|[' + _FRACTION_CHARS + r'])\s*(?P<rest>.*)$'
)
_NICE_FRACTIONS = [
    (0.125, '1/8'), (0.25, '1/4'), (1 / 3, '1/3'), (0.375, '3/8'), (0.5, '1/2'),
    (0.625, '5/8'), (2 / 3, '2/3'), (0.75, '3/4'), (0.875, '7/8'),
]


def _format_number(v: float) -> str:
    whole = int(v)
    frac = v - whole
    if frac < 0.03:
        return str(whole) if whole > 0 else ('0' if v < 0.03 else _format_number(v + 0.001))
    for target, label in _NICE_FRACTIONS:
        if abs(frac - target) < 0.035:
            return f"{whole} {label}" if whole > 0 else label
    s = f"{v:.1f}".rstrip('0').rstrip('.')
    return s if s else '0'


def divide_display_qty(qty_text: str, divisor: float) -> str:
    """
    Escala una cantidad ya mostrable ("700 g", "3 ½ tazas", "2") entre
    `divisor` porciones — se usa para que la lista de ingredientes guardada
    represente 1 sola porción, igual que kcal/macros, en vez de la receta
    completa (ver scale_ingredient() en includes/planner.php, que asume que
    la cantidad base YA es de 1 persona y la multiplica al escalar).
    """
    if not qty_text or qty_text == 'al gusto' or not divisor or divisor <= 1.0001:
        return qty_text
    m = _QTY_PREFIX_RE.match(qty_text)
    if not m:
        return qty_text
    val = _parse_qty_number(m.group('num'))
    if val is None:
        return qty_text
    rest = m.group('rest').strip()
    new_val = _format_number(val / divisor)
    return f"{new_val} {rest}".strip() if rest else new_val


def default_portion_grams(match_name: str) -> float:
    """
    Porción típica (g) para un ingrediente identificado pero sin cantidad en
    la fuente, según su perfil nutricional: fuentes de proteína ~150 g,
    almidones cocidos ~90 g, grasas puras ~10 g, verduras/otros ~70 g.
    Las especias tienen prioridad con su propio gramaje mínimo (ver
    SPICE_DEFAULT_GRAMS): su perfil por 100 g es engañoso porque son
    deshidratadas, pero en la práctica se usan en gramos, no en "porciones".
    """
    if match_name in SPICE_DEFAULT_GRAMS:
        return SPICE_DEFAULT_GRAMS[match_name]
    kcal, prot, carbs, fat = NUTRITION[match_name][:4]
    if fat >= 40 and kcal >= 300:
        return 10.0
    if prot >= 15 and kcal >= 80:
        return 150.0
    if carbs >= 15 and prot < 8:
        return 90.0
    if kcal <= 60:
        return 70.0
    return 60.0


def parse_ingredient_line(raw: str):
    """
    Devuelve dict:
      display_name: nombre corto para "nombre|cantidad"
      display_qty:  cantidad tal como debe mostrarse
      match_name:   llave en NUTRITION, o None si no se pudo identificar
      grams:        gramos equivalentes usados para calcular macros
      quantified:   False si el gramaje es una porción típica estimada,
                    no una cantidad realmente medida en la fuente
    """
    raw = raw.strip().strip('-*• \t')
    raw = raw.rstrip('.')
    if not raw:
        return None

    if _NEGLIGIBLE.match(raw):
        name = _clean_name(_NEGLIGIBLE.sub('', raw).strip()) or raw
        match = _match_nutrition_name(name)
        return {
            'display_name': name or raw, 'display_qty': 'al gusto',
            'match_name': match, 'grams': 0.0, 'quantified': True,
            'unit_kind': 'negligible', 'raw_qty': None,
        }

    m = _QTY_RE.match(raw)
    if not m:
        # Sin cantidad reconocible al inicio (p. ej. "Lechuga", "Arroz integral").
        name = _clean_name(raw)
        match = _match_nutrition_name(name)
        grams = default_portion_grams(match) if match else 0.0
        return {
            'display_name': name or raw, 'display_qty': 'al gusto',
            'match_name': match, 'grams': round(grams, 1), 'quantified': False,
            'unit_kind': 'estimated', 'raw_qty': None,
        }

    qty_txt, unit_txt, rest = m.group('qty'), m.group('unit'), m.group('rest')
    qty = _parse_qty_number(qty_txt)
    unit = (unit_txt or '').strip().lower().rstrip('.')

    # Si el "unit" capturado no es una unidad conocida, en realidad es parte
    # del nombre (p. ej. "2 pechugas de pollo" -> unit capturado "pechugas").
    if unit and unit not in UNIT_GRAMS:
        rest = (unit + ' ' + rest).strip()
        unit = ''

    name = _clean_name(rest)
    if not name:
        name = rest.strip() or raw
    match = _match_nutrition_name(name)

    # Peso explícito entre paréntesis, ej. "1 filete de trucha (200 g)" — tiene
    # prioridad porque es información real de la receta, no una estimación.
    # Puede estar dentro de "rest" (antes de recortarlo) o después del match.
    explicit = re.search(r'\(\D*(\d+)\s*g\b', rest) or re.search(
        r'(\d+)\s*g\b', raw[m.end():] if m.end() < len(raw) else '')
    per_unit_override = float(explicit.group(1)) if explicit else None

    grams = 0.0
    quantified = False
    unit_kind = 'estimated'  # 'weight' (g/ml/taza/cda...), 'count' (unidades sueltas)
    if qty is not None:
        if unit and unit in UNIT_GRAMS:
            per_unit = UNIT_GRAMS_BY_CLASS.get(unit, {}).get(match, UNIT_GRAMS[unit])
            grams = qty * per_unit
            quantified = True
            unit_kind = 'weight'
        elif not unit:
            per_unit = per_unit_override or (WHOLE_UNIT_GRAMS.get(match) if match else None)
            if per_unit:
                grams = qty * per_unit
                quantified = True
                unit_kind = 'count'

    if not quantified and match and match in NUTRITION:
        # Cantidad no interpretable en gramos (p. ej. "1 rack pequeño de
        # costillas") pero sí sabemos qué ingrediente es: se aplica porción
        # típica, escalada por el número si había uno ("3 falafels" -> x3).
        multiplier = qty if qty is not None else 1.0
        grams = default_portion_grams(match) * multiplier

    display_qty = (qty_txt + (' ' + unit if unit else '')).strip()

    return {
        'display_name': name, 'display_qty': display_qty,
        'match_name': match, 'grams': round(grams, 1), 'quantified': quantified,
        'unit_kind': unit_kind, 'raw_qty': qty,
    }
