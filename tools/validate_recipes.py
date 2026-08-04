# -*- coding: utf-8 -*-
"""
MenúVital — Valida tools/recipes_build.json (+ recetas colombianas) antes de
insertarlas en database/recipes_data.php. Es la puerta del pipeline: si hay
errores duros, emit_php.py no debe correr.

Uso: python validate_recipes.py
Salida: tools/recipes_validated.json (las que pasaron) +
        tools/informe_validacion.md (rechazadas y advertencias, con motivo)
"""
import json
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from normalize import normalize_ingredient

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TOOLS = os.path.dirname(os.path.abspath(__file__))
IMG_DIR = os.path.join(ROOT, 'assets', 'img', 'recetas')

VALID_MEAL_TYPES = {'desayuno', 'almuerzo', 'cena', 'snack'}
VALID_TAGS = {'tradicional', 'ligero', 'alto en proteína', 'económico', 'rápido', 'vegetariano', 'sin gluten'}

KCAL_RANGE = {
    'desayuno': (80, 900), 'almuerzo': (80, 900), 'cena': (80, 900), 'snack': (30, 450),
}


def load_existing_names():
    php = open(os.path.join(ROOT, 'database', 'recipes_data.php'), encoding='utf-8').read()
    return {normalize_ingredient(m.group(1)) for m in re.finditer(r"^\['([^']*)'", php, re.M)}


def validate_one(r, existing_names, seen_names):
    """Devuelve (ok: bool, errors: list[str], warnings: list[str])."""
    errors, warnings = [], []

    name = r.get('name', '').strip()
    if not name:
        errors.append('nombre vacío')
    elif len(name) > 150:
        errors.append(f'nombre supera 150 caracteres ({len(name)})')
    norm = normalize_ingredient(name)
    if norm in existing_names:
        errors.append('nombre ya existe en recipes_data.php')
    elif norm in seen_names:
        errors.append('nombre duplicado dentro del propio lote nuevo')

    if r.get('meal_type') not in VALID_MEAL_TYPES:
        errors.append(f"meal_type inválido: {r.get('meal_type')!r}")

    tags = r.get('tags', [])
    if not isinstance(tags, list) or len(tags) > 4:
        errors.append('tags: debe ser lista de máx 4 elementos')
    bad_tags = [t for t in tags if t not in VALID_TAGS]
    if bad_tags:
        errors.append(f'tags fuera de la lista permitida: {bad_tags}')

    ingredients = r.get('ingredients', [])
    if not ingredients:
        errors.append('sin ingredientes')
    for ing in ingredients:
        if ing.count('|') != 1:
            errors.append(f'ingrediente sin separador "|" correcto: {ing!r}')
            break
        iname, iqty = ing.split('|')
        if not iname.strip():
            errors.append(f'ingrediente sin nombre: {ing!r}')
            break

    steps = r.get('steps', [])
    if not steps:
        errors.append('sin pasos de preparación')
    for s in steps:
        if len(s) > 300:
            errors.append(f'paso supera 300 caracteres: {s[:50]!r}...')
            break

    for field in ('kcal', 'protein', 'time_min', 'carbs', 'fat', 'sugar', 'fiber'):
        v = r.get(field)
        if not isinstance(v, (int, float)) or v < 0:
            errors.append(f'{field} inválido: {v!r}')

    image_url = r.get('image_url')
    if image_url:
        fname = image_url.rsplit('/', 1)[-1]
        if not os.path.isfile(os.path.join(IMG_DIR, fname)):
            errors.append(f'image_url apunta a archivo inexistente: {image_url}')

    if not errors:
        meal_type = r['meal_type']
        lo, hi = KCAL_RANGE[meal_type]
        kcal = r['kcal']
        if not (lo <= kcal <= hi):
            errors.append(f'kcal fuera de rango sensato para {meal_type}: {kcal} (esperado {lo}-{hi})')

        atwater = 4 * r['protein'] + 4 * r['carbs'] + 9 * r['fat']
        if kcal > 0:
            diff = abs(atwater - kcal) / kcal
            if diff > 0.20:
                errors.append(f'no cuadra con Atwater: kcal={kcal} vs 4P+4C+9F={round(atwater)} ({diff:.0%} de diferencia)')

        stats = r.get('_stats', {})
        if stats.get('match_ratio', 1) < 0.5:
            warnings.append(f"confianza de ingredientes baja (match_ratio={stats.get('match_ratio')})")

    return (len(errors) == 0), errors, warnings


def main():
    build_path = os.path.join(TOOLS, 'recipes_build.json')
    colombian_path = os.path.join(TOOLS, 'recipes_colombianas.json')

    recipes = []
    if os.path.isfile(build_path):
        recipes += json.load(open(build_path, encoding='utf-8'))
    if os.path.isfile(colombian_path):
        recipes += json.load(open(colombian_path, encoding='utf-8'))

    if not recipes:
        print('No hay recetas para validar (corre build_recipes.py / recetas_colombianas.py primero).')
        sys.exit(1)

    existing_names = load_existing_names()
    seen_names = set()
    accepted, rejected = [], []

    for r in recipes:
        ok, errors, warnings = validate_one(r, existing_names, seen_names)
        r['_warnings'] = warnings
        if ok:
            seen_names.add(normalize_ingredient(r['name']))
            accepted.append(r)
        else:
            r['_errors'] = errors
            rejected.append(r)

    by_type = {}
    for r in accepted:
        by_type[r['meal_type']] = by_type.get(r['meal_type'], 0) + 1

    print(f'Aceptadas: {len(accepted)}  |  Rechazadas: {len(rejected)}  |  Total: {len(recipes)}')
    print('Aceptadas por tipo:', by_type)

    out_path = os.path.join(TOOLS, 'recipes_validated.json')
    with open(out_path, 'w', encoding='utf-8') as f:
        json.dump(accepted, f, ensure_ascii=False, indent=1)
    print(f'Escrito: {out_path}')

    report_path = os.path.join(TOOLS, 'informe_validacion.md')
    with open(report_path, 'w', encoding='utf-8') as f:
        f.write('# Informe de validación de recetas nuevas\n\n')
        f.write(f'Aceptadas: **{len(accepted)}** — Rechazadas: **{len(rejected)}**\n\n')
        f.write('## Aceptadas por tipo\n\n')
        for t, c in by_type.items():
            f.write(f'- {t}: {c}\n')
        warn_count = sum(1 for r in accepted if r.get('_warnings'))
        f.write(f'\nCon advertencia de confianza (aceptadas igual, revisar si se quiere): {warn_count}\n')
        f.write('\n## Rechazadas (no se insertan en el recetario)\n\n')
        for r in rejected:
            f.write(f"- **{r.get('name', '(sin nombre)')}** [{r.get('_source', r.get('meal_type', '?'))}]\n")
            for e in r['_errors']:
                f.write(f'  - {e}\n')
    print(f'Informe: {report_path}')


if __name__ == '__main__':
    main()
