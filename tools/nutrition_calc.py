# -*- coding: utf-8 -*-
"""MenúVital — Suma macros de una lista de ingredientes ya parseados (ver
ingredient_parse.py) y los reparte entre las porciones de la receta."""
from nutrition_data import NUTRITION


def sum_macros(parsed_ingredients, servings: int = 1):
    """
    parsed_ingredients: lista de dicts de parse_ingredient_line() (sin None).
    Devuelve (macros_por_porcion, stats) donde macros_por_porcion es
    {kcal, protein, carbs, fat, sugar, fiber} redondeados a entero, y stats
    trae la info para el informe de confianza (tools/build_recipes.py).
    """
    servings = max(1, servings)
    totals = {'kcal': 0.0, 'protein': 0.0, 'carbs': 0.0, 'fat': 0.0, 'sugar': 0.0, 'fiber': 0.0}
    matched_grams = 0.0
    unmatched_grams = 0.0
    unquantified_count = 0
    total_count = 0

    for ing in parsed_ingredients:
        total_count += 1
        if not ing.get('quantified'):
            unquantified_count += 1
        grams = ing.get('grams', 0.0)
        match = ing.get('match_name')
        if match and match in NUTRITION:
            matched_grams += grams
            kcal, prot, carbs, fat, sugar, fiber = NUTRITION[match][:6]
            factor = grams / 100.0
            totals['kcal'] += kcal * factor
            totals['protein'] += prot * factor
            totals['carbs'] += carbs * factor
            totals['fat'] += fat * factor
            totals['sugar'] += (sugar or 0) * factor
            totals['fiber'] += (fiber or 0) * factor
        else:
            unmatched_grams += grams

    per_serving = {k: round(v / servings) for k, v in totals.items()}
    total_mass = matched_grams + unmatched_grams
    match_ratio = (matched_grams / total_mass) if total_mass > 0 else 0.0

    # Chequeo de Atwater: 4 kcal/g proteína y carbohidrato, 9 kcal/g grasa.
    atwater_kcal = 4 * totals['protein'] + 4 * totals['carbs'] + 9 * totals['fat']
    atwater_diff = abs(atwater_kcal - totals['kcal']) / totals['kcal'] if totals['kcal'] > 0 else 0.0

    stats = {
        'servings': servings,
        'ingredient_count': total_count,
        'unquantified_count': unquantified_count,
        'match_ratio': round(match_ratio, 2),
        'atwater_diff': round(atwater_diff, 3),
        'total_kcal_recipe': round(totals['kcal']),
    }
    return per_serving, stats
