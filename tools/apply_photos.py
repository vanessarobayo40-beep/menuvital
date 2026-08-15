# -*- coding: utf-8 -*-
"""
MenúVital — Aplica las fotos de tools/photo_candidates.json (ya revisadas
visualmente una por una) a database/recipes_data.php: le pone image_url a
cada receta que no tenía ninguna. Genera también tools/creditos_fotos.md
con la fuente y licencia de cada foto nueva.
"""
import json
import os
import re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PHP_PATH = os.path.join(ROOT, 'database', 'recipes_data.php')
CANDIDATES_PATH = os.path.join(os.path.dirname(__file__), 'photo_candidates.json')
CREDITS_PATH = os.path.join(os.path.dirname(__file__), 'creditos_fotos.md')


def load(path):
    return open(path, encoding='utf-8', newline='').read()


def save(path, text):
    with open(path, 'w', encoding='utf-8', newline='') as f:
        f.write(text)


def set_image(text, name, image_url):
    """Encuentra el bloque de la receta (sin foto: termina en ...NULL],  o
    en ...fiber],  sin comilla de imagen después) y le agrega/reemplaza
    image_url."""
    block_re = re.compile(
        r"(\['" + re.escape(name) + r"', '\w+',\r?\n"
        r" \[.*?\],\r?\n \[.*?\],\r?\n \[(?:(?:'[^']*')(?:, )?)*\], \d+, \d+, \d+, \d+, \d+, \d+, \d+)"
        r"(?:, '[^']*')?(\],)",
        re.S,
    )
    m = block_re.search(text)
    if not m:
        return text, 0
    new_text = text[:m.start()] + m.group(1) + f", '{image_url}'" + m.group(2) + text[m.end():]
    return new_text, 1


def main():
    candidates = json.load(open(CANDIDATES_PATH, encoding='utf-8'))
    text = load(PHP_PATH)

    applied, missed = 0, []
    for c in candidates:
        image_url = f"/assets/img/recetas/{c['file']}"
        text, n = set_image(text, c['name'], image_url)
        if n:
            applied += 1
        else:
            missed.append(c['name'])

    save(PHP_PATH, text)
    print(f'Aplicadas: {applied} / {len(candidates)}')
    if missed:
        print('NO encontradas en el archivo (revisar nombre):')
        for m in missed:
            print('  ', m)

    with open(CREDITS_PATH, 'w', encoding='utf-8') as f:
        f.write('# Créditos de fotos agregadas (Wikimedia Commons + Pexels)\n\n')
        f.write('Fotos descargadas y revisadas visualmente una por una antes de aplicarlas. ')
        f.write('Wikimedia Commons: cada una respeta la licencia indicada (casi todas CC BY / CC BY-SA, '
                'exigen atribución si se reutilizan fuera de la app). Pexels: licencia gratuita '
                'de Pexels, no exige atribución pero se deja igual por transparencia.\n\n')
        f.write('| Receta | Archivo | Fuente | Licencia | Autor/a |\n|---|---|---|---|---|\n')
        for c in sorted(candidates, key=lambda x: x['name']):
            f.write(f"| {c['name']} | {c['file']} | {c['source']} | {c['license']} | {c['author']} |\n")
    print(f'Créditos escritos: {CREDITS_PATH}')


if __name__ == '__main__':
    main()
