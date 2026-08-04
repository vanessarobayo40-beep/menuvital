# -*- coding: utf-8 -*-
"""
MenúVital — Detecta recetas duplicadas o muy parecidas en el recetario final
(las 1226: las 957 originales + las 269 nuevas), por dos criterios:
  1. Nombre muy similar (aunque no sea idéntico).
  2. Mismo meal_type + ingredientes casi iguales (similitud de Jaccard alta),
     que es la señal real de "es básicamente la misma receta".
No modifica nada — solo informa.
"""
import os
import re
import sys
from difflib import SequenceMatcher
from itertools import combinations

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from normalize import normalize_ingredient

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

ENTRY_RE = re.compile(
    r"^\['((?:[^'\\]|\\.)*)', '(desayuno|almuerzo|cena|snack)',\r?\n"
    r" \[((?:(?:'(?:[^'\\]|\\.)*')(?:, )?)*)\],\r?\n"
    r" \[.*?\],\r?\n"
    r" \[(?:(?:'(?:[^'\\]|\\.)*')(?:, )?)*\], \d+, \d+, \d+, (?:\d+|NULL), (?:\d+|NULL), (?:\d+|NULL), (?:\d+|NULL)"
    r"(?:, '(?:[^'\\]|\\.)*')?\],$",
    re.M | re.S,
)
ING_ITEM_RE = re.compile(r"'((?:[^'\\]|\\.)*)'")


def ingredient_key_set(ing_blob: str) -> set:
    names = set()
    for m in ING_ITEM_RE.finditer(ing_blob):
        raw = m.group(1).split('|')[0]
        n = normalize_ingredient(raw)
        if n:
            names.add(n)
    return names


def name_key(name: str) -> str:
    return normalize_ingredient(name)


def main():
    text = open(os.path.join(ROOT, 'database', 'recipes_data.php'), encoding='utf-8').read()
    recipes = []
    for m in ENTRY_RE.finditer(text):
        name, meal_type, ing_blob = m.group(1), m.group(2), m.group(3)
        recipes.append({
            'name': name, 'meal_type': meal_type,
            'ings': ingredient_key_set(ing_blob),
            'name_norm': name_key(name),
        })
    print(f'Total recetas analizadas: {len(recipes)}')

    # ---- 1. Nombres muy parecidos (no idénticos) ----
    by_first_word = {}
    for i, r in enumerate(recipes):
        w = r['name_norm'].split(' ')[0] if r['name_norm'] else ''
        by_first_word.setdefault(w, []).append(i)

    name_near = []
    seen_pairs = set()
    for w, idxs in by_first_word.items():
        if len(idxs) < 2 or len(idxs) > 400:
            continue
        for i, j in combinations(idxs, 2):
            a, b = recipes[i], recipes[j]
            if a['name_norm'] == b['name_norm']:
                continue  # esto ya se bloqueó en validate_recipes.py
            ratio = SequenceMatcher(None, a['name_norm'], b['name_norm']).ratio()
            if ratio >= 0.82:
                key = tuple(sorted([a['name'], b['name']]))
                if key not in seen_pairs:
                    seen_pairs.add(key)
                    name_near.append((ratio, a, b))

    # ---- 2. Mismo tipo de comida + ingredientes casi iguales ----
    by_type = {}
    for i, r in enumerate(recipes):
        by_type.setdefault(r['meal_type'], []).append(i)

    ing_near = []
    for meal_type, idxs in by_type.items():
        for i, j in combinations(idxs, 2):
            a, b = recipes[i], recipes[j]
            if not a['ings'] or not b['ings']:
                continue
            inter = len(a['ings'] & b['ings'])
            union = len(a['ings'] | b['ings'])
            if union == 0:
                continue
            jac = inter / union
            if jac >= 0.70 and min(len(a['ings']), len(b['ings'])) >= 3:
                ing_near.append((jac, a, b))
    ing_near.sort(key=lambda x: -x[0])
    name_near.sort(key=lambda x: -x[0])

    report_path = os.path.join(os.path.dirname(__file__), 'informe_duplicados.md')
    with open(report_path, 'w', encoding='utf-8') as f:
        f.write('# Recetas duplicadas o muy parecidas (1226 recetas actuales)\n\n')
        f.write(f'## Nombres muy similares, no idénticos ({len(name_near)})\n\n')
        for ratio, a, b in name_near[:150]:
            f.write(f"- {ratio:.0%} — **{a['name']}** [{a['meal_type']}]  vs  **{b['name']}** [{b['meal_type']}]\n")
        f.write(f'\n## Mismo tipo de comida + ingredientes muy parecidos ({len(ing_near)})\n\n')
        f.write('(similitud de Jaccard sobre el conjunto de ingredientes, ≥70%)\n\n')
        for jac, a, b in ing_near[:150]:
            f.write(f"- {jac:.0%} — **{a['name']}**  vs  **{b['name']}**  [{a['meal_type']}]\n")
            f.write(f"  - ingredientes A: {sorted(a['ings'])}\n")
            f.write(f"  - ingredientes B: {sorted(b['ings'])}\n")

    print(f'Nombres muy similares (no idénticos): {len(name_near)}')
    print(f'Mismo tipo + ingredientes ≥70% iguales: {len(ing_near)}')
    print(f'Informe: {report_path}')


if __name__ == '__main__':
    main()
