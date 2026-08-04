# -*- coding: utf-8 -*-
"""
MenúVital — Inserta tools/recipes_validated.json dentro de
database/recipes_data.php, en la sección de cada meal_type, sin tocar ni una
línea de las 957 recetas existentes. Reproduce el formato exacto del archivo
(UTF-8 sin BOM, saltos CRLF, 4 líneas por receta + línea en blanco).

Uso:
    python emit_php.py          # inserta
    python emit_php.py --check  # solo valida que el archivo ya escrito
                                 # sigue teniendo la estructura correcta
"""
import json
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TOOLS = os.path.dirname(os.path.abspath(__file__))
PHP_PATH = os.path.join(ROOT, 'database', 'recipes_data.php')

MARKERS = {
    'desayuno': '// ==================== DESAYUNOS ====================',
    'almuerzo': '// ==================== ALMUERZOS ====================',
    'cena': '// ==================== CENAS ====================',
    'snack': '// ==================== SNACKS ====================',
}
ORDER = ['desayuno', 'almuerzo', 'cena', 'snack']


def php_str(s: str) -> str:
    return "'" + str(s).replace('\\', '\\\\').replace("'", "\\'") + "'"


def format_block(r: dict) -> str:
    lines = [
        f"[{php_str(r['name'])}, {php_str(r['meal_type'])},",
        f" [{', '.join(php_str(i) for i in r['ingredients'])}],",
        f" [{', '.join(php_str(s) for s in r['steps'])}],",
    ]
    tags = ', '.join(php_str(t) for t in r['tags'])
    nums = (f"{int(r['kcal'])}, {int(r['protein'])}, {int(r['time_min'])}, "
            f"{int(r['carbs'])}, {int(r['fat'])}, {int(r['sugar'])}, {int(r['fiber'])}")
    if r.get('image_url'):
        lines.append(f" [{tags}], {nums}, {php_str(r['image_url'])}],")
    else:
        lines.append(f" [{tags}], {nums}],")
    return '\r\n'.join(lines)


def load_recipes():
    path = os.path.join(TOOLS, 'recipes_validated.json')
    if not os.path.isfile(path):
        print('No existe recipes_validated.json — corre validate_recipes.py primero.')
        sys.exit(1)
    return json.load(open(path, encoding='utf-8'))


def check_structure(text, expected_min_new=0, original_count=None):
    errors = []
    if text.startswith('﻿'):
        errors.append('el archivo tiene BOM (debe ser UTF-8 sin BOM)')
    if not text.startswith('<?php'):
        errors.append('no empieza con <?php')
    if not text.rstrip().endswith('];'):
        errors.append('no termina en "];"')
    lf_only = len(re.findall(r'(?<!\r)\n', text))
    if lf_only > 0:
        errors.append(f'{lf_only} saltos de línea sueltos sin \\r (deben ser CRLF)')
    total_entries = len(re.findall(r"(?m)^\['", text))
    if original_count is not None and total_entries < original_count + expected_min_new:
        errors.append(f'se esperaban al menos {original_count + expected_min_new} recetas, hay {total_entries}')
    for name, marker in MARKERS.items():
        if text.count(marker) != 1:
            errors.append(f'marcador de sección "{marker}" no aparece exactamente 1 vez')
    return total_entries, errors


def main():
    check_only = '--check' in sys.argv

    with open(PHP_PATH, 'r', encoding='utf-8', newline='') as f:
        original_text = f.read()
    original_count = len(re.findall(r"(?m)^\['", original_text))

    if check_only:
        total, errors = check_structure(original_text, 0, None)
        print(f'Total recetas en el archivo: {total}')
        if errors:
            print('ERRORES:')
            for e in errors:
                print(' -', e)
            sys.exit(1)
        print('Estructura OK.')
        return

    recipes = load_recipes()
    by_type = {t: [] for t in ORDER}
    for r in recipes:
        by_type[r['meal_type']].append(r)

    idx = {t: original_text.index(MARKERS[t]) for t in ORDER}
    end_idx = original_text.rindex('];')

    bounds = [idx['desayuno'], idx['almuerzo'], idx['cena'], idx['snack'], end_idx]
    segments = [
        original_text[bounds[0]:bounds[1]],
        original_text[bounds[1]:bounds[2]],
        original_text[bounds[2]:bounds[3]],
        original_text[bounds[3]:bounds[4]],
    ]
    tail = original_text[end_idx:]
    head = original_text[:bounds[0]]

    new_text = head
    for t, seg in zip(ORDER, segments):
        new_text += seg
        for r in by_type[t]:
            new_text += format_block(r) + '\r\n\r\n'
    new_text += tail

    total, errors = check_structure(new_text, len(recipes), original_count)
    if errors:
        print('ERRORES DE ESTRUCTURA — no se escribió el archivo:')
        for e in errors:
            print(' -', e)
        sys.exit(1)

    with open(PHP_PATH, 'w', encoding='utf-8', newline='') as f:
        f.write(new_text)

    print(f'Recetas originales: {original_count}')
    print(f'Recetas nuevas insertadas: {len(recipes)}')
    for t in ORDER:
        print(f'  {t}: +{len(by_type[t])}')
    print(f'Total final: {total}')
    print(f'Escrito: {PHP_PATH}')


if __name__ == '__main__':
    main()
