# -*- coding: utf-8 -*-
"""MenúVital — Clasificación de ingredientes para derivar tags automáticos
(vegetariano, sin gluten, económico) a partir de sus llaves en NUTRITION."""
from normalize import normalize_ingredient

ANIMAL_PROTEIN_KEYS = {
    'pechuga de pollo', 'muslo de pollo', 'pollo entero', 'gallina criolla',
    'carne de res magra', 'carne molida de res', 'lomo de cerdo', 'chuleta de cerdo',
    'costilla de res', 'sobrebarriga', 'posta de res', 'tocino', 'chicharrón',
    'chorizo', 'morcilla', 'jamón de pavo', 'pechuga de pavo', 'pavo molido',
    'tilapia', 'mojarra', 'trucha', 'salmón', 'atún en agua', 'sardinas',
    'camarones', 'calamar', 'mariscos mixtos', 'pescado blanco', 'merluza',
    'pargo', 'bacalao',
    'pulpo cocido', 'langostinos', 'albóndigas de res',
    # El huevo NO está aquí a propósito: en MenúVital "vegetariano" es
    # ovo-vegetariano (el uso común en Colombia/Latinoamérica), decisión
    # explícita — ver conversación. "Vegano" sería una etiqueta aparte.
}

GLUTEN_KEYS = {
    'harina de trigo', 'harina integral', 'pan integral', 'pan blanco',
    'pan árabe integral', 'pan rallado', 'panko', 'pasta cocida',
    'pasta integral cocida', 'cuscús cocido', 'tortilla de trigo',
    'masa de hojaldre', 'galleta', 'harina sazonada',
}

# Ingredientes que suben el costo de la receta por encima de lo "económico".
EXPENSIVE_KEYS = {
    'salmón', 'camarones', 'calamar', 'mariscos mixtos', 'langostinos',
    'pulpo cocido', 'queso parmesano', 'nueces de macadamia', 'proteína en polvo',
    'proteína en polvo (scoop)', 'almendras', 'avellanas', 'piñones',
    'harina de almendra', 'aceite de coco', 'coco', 'açaí', 'chontaduro',
    'granadilla', 'trucha',
}


# Palabras que delatan carne/pescado/huevo con solo verlas en el nombre CRUDO
# del ingrediente, no solo en el match_name que quedó guardado. Hace falta
# porque cuando una línea nombra dos alimentos a la vez ("Pan y queso
# parmesano", "Pasta ... con salsa de albahaca, queso cottage y pollo"), el
# emparejador solo guarda UN match_name — si escoge "queso parmesano" o
# "queso cottage", el "pan" o el "pollo" de la misma línea desaparece por
# completo del match_name y la receta queda mal marcada "vegetariano"/
# "sin gluten" aunque sí lleve carne o trigo.
_MEAT_WORDS = {
    'pollo', 'res', 'cerdo', 'carne', 'tocineta', 'tocino', 'jamon', 'chorizo',
    'pavo', 'pescado', 'atun', 'salmon', 'camaron', 'camarones', 'mariscos',
    'mojarra', 'tilapia', 'trucha', 'gallina', 'costilla', 'costillas', 'posta',
    'lomo', 'bistec', 'panceta', 'chicharron', 'albondigas', 'salchicha',
    'anchoas', 'sardinas', 'pulpo', 'calamares', 'langostinos', 'gambas',
    'cordero', 'ternera', 'morcilla', 'bacon', 'pechuga', 'pechugas', 'muslos',
    'muslo', 'alitas', 'nuggets', 'hamburguesa',
    'bagre', 'merluza', 'pargo', 'bacalao', 'anchoa',
    # huevo/clara/yema NO cuentan como carne aquí — "vegetariano" en
    # MenúVital es ovo-vegetariano, decisión explícita (ver ANIMAL_PROTEIN_KEYS).
}
_GLUTEN_WORDS = {
    'pan', 'panko', 'espagueti', 'macarrones', 'macarron', 'galleta', 'galletas',
    'cuscus', 'cebada', 'centeno', 'croissant', 'bagel', 'baguette', 'semola',
    'fideos', 'raviolis', 'noquis', 'pita', 'wrap', 'empanizado', 'apanado',
    'rebozado', 'cerveza', 'espelta', 'malta', 'trigo', 'harina',
}
# Frases que SÍ contienen una palabra de la lista de arriba pero no tienen
# gluten (o no son carne) de verdad — se descartan antes de buscar palabras.
_GLUTEN_SAFE_PHRASES = [
    'trigo sarraceno', 'pasta tomate', 'harina almendra', 'harina coco',
    'harina maiz', 'harina arroz', 'harina garbanzo', 'harina quinua',
    'harina yuca', 'harina platano', 'harina linaza', 'harina avena',
    'harina lino',
]


def _text_has_word(text: str, word_set: set, safe_phrases=()) -> bool:
    norm = ' ' + normalize_ingredient(text) + ' '
    for phrase in safe_phrases:
        norm = norm.replace(' ' + phrase + ' ', ' ')
    return bool(set(norm.split()) & word_set)


def is_vegetarian(match_names, raw_names=()) -> bool:
    if any(m in ANIMAL_PROTEIN_KEYS for m in match_names if m):
        return False
    return not any(_text_has_word(n, _MEAT_WORDS) for n in raw_names if n)


def is_gluten_free(match_names, raw_names=()) -> bool:
    if any(m in GLUTEN_KEYS for m in match_names if m):
        return False
    return not any(_text_has_word(n, _GLUTEN_WORDS, _GLUTEN_SAFE_PHRASES) for n in raw_names if n)


def is_economical(match_names) -> bool:
    matched = [m for m in match_names if m]
    if not matched:
        return True
    return not any(m in EXPENSIVE_KEYS for m in matched)
