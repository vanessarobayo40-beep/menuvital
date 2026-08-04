# -*- coding: utf-8 -*-
"""MenúVital — Clasificación de ingredientes para derivar tags automáticos
(vegetariano, sin gluten, económico) a partir de sus llaves en NUTRITION."""

ANIMAL_PROTEIN_KEYS = {
    'pechuga de pollo', 'muslo de pollo', 'pollo entero', 'gallina criolla',
    'carne de res magra', 'carne molida de res', 'lomo de cerdo', 'chuleta de cerdo',
    'costilla de res', 'sobrebarriga', 'posta de res', 'tocino', 'chicharrón',
    'chorizo', 'morcilla', 'jamón de pavo', 'pechuga de pavo', 'pavo molido',
    'tilapia', 'mojarra', 'trucha', 'salmón', 'atún en agua', 'sardinas',
    'camarones', 'calamar', 'mariscos mixtos', 'pescado blanco', 'merluza',
    'pargo', 'bacalao', 'huevo', 'clara de huevo', 'yema de huevo',
    'pulpo cocido', 'langostinos', 'albóndigas de res',
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


def is_vegetarian(match_names) -> bool:
    return not any(m in ANIMAL_PROTEIN_KEYS for m in match_names if m)


def is_gluten_free(match_names) -> bool:
    return not any(m in GLUTEN_KEYS for m in match_names if m)


def is_economical(match_names) -> bool:
    matched = [m for m in match_names if m]
    if not matched:
        return True
    return not any(m in EXPENSIVE_KEYS for m in matched)
