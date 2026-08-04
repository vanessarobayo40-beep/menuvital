# -*- coding: utf-8 -*-
"""
MenúVital — Audita las 957 recetas que YA existían en database/recipes_data.php
antes de esta ampliación (no toca nada, solo genera un informe). Compara la
versión de git en HEAD (antes de este cambio) para no mezclar las recetas
nuevas recién insertadas con las originales.

Revisa: kcal fuera de rango sensato, inconsistencia con Atwater (4P+4C+9F),
imágenes locales que no existen en disco, imágenes en disco que ningún
registro usa (huérfanas), y nombres casi duplicados.

Uso: python audit_existing.py
Salida: tools/informe_auditoria.md
"""
import os
import re
import subprocess
import sys
from collections import Counter

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from normalize import normalize_ingredient

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TOOLS = os.path.dirname(os.path.abspath(__file__))
IMG_DIR = os.path.join(ROOT, 'assets', 'img', 'recetas')

KCAL_RANGE = {
    'desayuno': (80, 900), 'almuerzo': (80, 900), 'cena': (80, 900), 'snack': (30, 450),
}

ENTRY_RE = re.compile(
    r"^\['((?:[^'\\]|\\.)*)', '(desayuno|almuerzo|cena|snack)',\r?\n"
    r" \[.*?\],\r?\n"
    r" \[.*?\],\r?\n"
    r" \[(?:(?:'(?:[^'\\]|\\.)*')(?:, )?)*\], (\d+), (\d+), (\d+), (\d+|NULL), (\d+|NULL), (\d+|NULL), (\d+|NULL)"
    r"(?:, '((?:[^'\\]|\\.)*)')?\],$",
    re.M | re.S,
)


def get_original_text():
    """Versión del archivo antes de esta ampliación (HEAD), si estamos en git
    y el archivo cambió; si no hay diferencia, usa el archivo actual."""
    try:
        out = subprocess.run(
            ['git', 'show', 'HEAD:database/recipes_data.php'],
            cwd=ROOT, capture_output=True, check=True,
        )
        text = out.stdout.decode('utf-8')
        if "// ==================== DESAYUNOS ====================" in text:
            return text
    except Exception:
        pass
    return open(os.path.join(ROOT, 'database', 'recipes_data.php'), encoding='utf-8').read()


def main():
    text = get_original_text()
    entries = []
    for m in ENTRY_RE.finditer(text):
        name, meal_type, kcal, protein, time_min, carbs, fat, sugar, fiber, image = m.groups()
        entries.append({
            'name': name, 'meal_type': meal_type, 'kcal': int(kcal), 'protein': int(protein),
            'time_min': int(time_min),
            'carbs': None if carbs == 'NULL' else int(carbs),
            'fat': None if fat == 'NULL' else int(fat),
            'sugar': None if sugar == 'NULL' else int(sugar),
            'fiber': None if fiber == 'NULL' else int(fiber),
            'image_url': image,
        })

    print(f'Recetas originales parseadas: {len(entries)}')

    kcal_out_of_range = []
    atwater_bad = []
    missing_images = []
    used_images = set()
    for r in entries:
        lo, hi = KCAL_RANGE[r['meal_type']]
        if not (lo <= r['kcal'] <= hi):
            kcal_out_of_range.append(r)
        if r['carbs'] is not None and r['fat'] is not None:
            atwater = 4 * r['protein'] + 4 * r['carbs'] + 9 * r['fat']
            if r['kcal'] > 0:
                diff = abs(atwater - r['kcal']) / r['kcal']
                if diff > 0.20:
                    atwater_bad.append((r, atwater, diff))
        if r['image_url']:
            used_images.add(r['image_url'].rsplit('/', 1)[-1])
            if r['image_url'].startswith('/assets/img/recetas/'):
                fname = r['image_url'].rsplit('/', 1)[-1]
                if not os.path.isfile(os.path.join(IMG_DIR, fname)):
                    missing_images.append(r)

    name_counts = Counter(normalize_ingredient(r['name']) for r in entries)
    near_dupes = [n for n, c in name_counts.items() if c > 1]

    # Para huérfanas se compara contra el archivo ACTUAL completo (original +
    # recetas nuevas ya insertadas), no solo contra las 957 originales, para
    # no marcar como "huérfanas" las imágenes que sí usan las recetas nuevas.
    current_text = open(os.path.join(ROOT, 'database', 'recipes_data.php'), encoding='utf-8').read()
    all_used_now = {m.rsplit('/', 1)[-1] for m in re.findall(r"'(/assets/img/recetas/[^']+)'", current_text)}
    all_disk_images = set(os.listdir(IMG_DIR)) if os.path.isdir(IMG_DIR) else set()
    orphan_images = sorted(all_disk_images - all_used_now)

    almuerzo_should_be_cena = [
        r for r in entries if r['meal_type'] == 'almuerzo' and r['kcal'] <= 300 and r['protein'] >= 15
    ]

    report_path = os.path.join(TOOLS, 'informe_auditoria.md')
    with open(report_path, 'w', encoding='utf-8') as f:
        f.write('# Auditoría de las recetas existentes (previas a esta ampliación)\n\n')
        f.write(f'Total analizado: **{len(entries)}**\n\n')
        f.write('Esto es solo un informe — no se modificó ninguna receta existente.\n\n')

        f.write(f'## kcal fuera de rango sensato ({len(kcal_out_of_range)})\n\n')
        f.write('Rango esperado: 80-900 kcal desayuno/almuerzo/cena, 30-450 snack.\n\n')
        for r in sorted(kcal_out_of_range, key=lambda x: x['kcal'])[:60]:
            f.write(f"- **{r['name']}** [{r['meal_type']}] — {r['kcal']} kcal\n")
        if len(kcal_out_of_range) > 60:
            f.write(f'- … y {len(kcal_out_of_range) - 60} más\n')

        f.write(f'\n## No cuadra con Atwater, 4P+4C+9F vs kcal, >20% de diferencia ({len(atwater_bad)})\n\n')
        for r, atwater, diff in sorted(atwater_bad, key=lambda x: -x[2])[:60]:
            f.write(f"- **{r['name']}** [{r['meal_type']}] — kcal={r['kcal']} vs 4P+4C+9F={round(atwater)} ({diff:.0%})\n")
        if len(atwater_bad) > 60:
            f.write(f'- … y {len(atwater_bad) - 60} más\n')

        f.write(f'\n## Imágenes locales que no existen en disco ({len(missing_images)})\n\n')
        for r in missing_images[:40]:
            f.write(f"- **{r['name']}** — {r['image_url']}\n")

        f.write(f'\n## Imágenes en disco que ninguna receta usa ({len(orphan_images)})\n\n')
        f.write('(no se borran automáticamente; son candidatas a limpieza manual)\n\n')
        for fname in orphan_images[:40]:
            f.write(f'- {fname}\n')
        if len(orphan_images) > 40:
            f.write(f'- … y {len(orphan_images) - 40} más\n')

        f.write(f'\n## Nombres duplicados o casi duplicados ({len(near_dupes)})\n\n')
        for n in near_dupes[:40]:
            matching = [r['name'] for r in entries if normalize_ingredient(r['name']) == n]
            f.write(f"- {matching}\n")

        f.write(f'\n## Almuerzos que por kcal/proteína encajarían mejor como cena ({len(almuerzo_should_be_cena)})\n\n')
        f.write('(≤300 kcal y ≥15 g proteína — candidatos a revisar, las cenas están más escasas que los almuerzos)\n\n')
        for r in almuerzo_should_be_cena[:40]:
            f.write(f"- **{r['name']}** — {r['kcal']} kcal, {r['protein']} g proteína\n")

    print(f'kcal fuera de rango: {len(kcal_out_of_range)}')
    print(f'Atwater inconsistente: {len(atwater_bad)}')
    print(f'Imágenes locales faltantes: {len(missing_images)}')
    print(f'Imágenes huérfanas en disco: {len(orphan_images)}')
    print(f'Nombres duplicados: {len(near_dupes)}')
    print(f'Almuerzos candidatos a cena: {len(almuerzo_should_be_cena)}')
    print(f'\nInforme: {report_path}')


if __name__ == '__main__':
    main()
