# -*- coding: utf-8 -*-
"""
MenúVital — Reencuadra todas las fotos de recetas a una proporción 3:2
uniforme. Las imágenes hoy son caóticas (algunas 1:3.4 verticales, otras
3:1 horizontales) y la tarjeta las corta con object-fit:cover en una franja
fija — de una foto muy vertical solo se ve una tajada del centro.

En vez de recortar siempre por el centro geométrico (que en una foto vertical
de un plato angosto puede cortar la comida), se recorta hacia la zona con
más "detalle" (más variación de bordes) a lo largo del eje que sobra, usando
la desviación estándar de un filtro de bordes como proxy de "aquí hay algo
interesante, no es solo mesa vacía".

Uso:
  python normalize_images.py --dry-run   # escribe a tools/img_staging/, no toca los originales
  python normalize_images.py --apply     # sobreescribe assets/img/recetas/ con el resultado
"""
import argparse
import os

import numpy as np
from PIL import Image, ImageFilter

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC_DIR = os.path.join(ROOT, 'assets', 'img', 'recetas')
STAGING_DIR = os.path.join(os.path.dirname(__file__), 'img_staging')

TARGET_RATIO = 3 / 2  # ancho/alto
OUT_W, OUT_H = 640, 427
QUALITY = 78


def best_crop_box(im: Image.Image):
    """Devuelve (left, top, right, bottom) del recorte 3:2 con más detalle."""
    w, h = im.size
    cur_ratio = w / h

    # Ya está cerca de 3:2 -> recorte mínimo centrado, no vale la pena
    # analizar detalle para un ajuste de unos pocos píxeles.
    if abs(cur_ratio - TARGET_RATIO) < 0.03:
        target_w, target_h = w, h
        if cur_ratio > TARGET_RATIO:
            target_w = round(h * TARGET_RATIO)
        else:
            target_h = round(w / TARGET_RATIO)
        left = (w - target_w) // 2
        top = (h - target_h) // 2
        return (left, top, left + target_w, top + target_h)

    # Mapa de bordes (proxy de "detalle") en baja resolución, rápido.
    small = im.convert('L').resize((min(w, 200), min(h, int(200 * h / w))))
    edges = np.asarray(small.filter(ImageFilter.FIND_EDGES), dtype=np.float32)
    sy = edges.shape[0] / h
    sx = edges.shape[1] / w

    if cur_ratio > TARGET_RATIO:
        # Muy ancha: hay que recortar el ancho, se desliza una ventana en X.
        target_w = round(h * TARGET_RATIO)
        target_h = h
        win_w = max(1, round(target_w * sx))
        best_score, best_left = -1, (w - target_w) // 2
        for left_px in range(0, edges.shape[1] - win_w + 1, max(1, win_w // 8)):
            score = edges[:, left_px:left_px + win_w].std()
            if score > best_score:
                best_score, best_left = score, left_px
        left = min(max(0, round(best_left / sx)), w - target_w)
        return (left, 0, left + target_w, target_h)
    else:
        # Muy alta: hay que recortar el alto, se desliza una ventana en Y.
        target_w = w
        target_h = round(w / TARGET_RATIO)
        win_h = max(1, round(target_h * sy))
        best_score, best_top = -1, (h - target_h) // 2
        for top_px in range(0, edges.shape[0] - win_h + 1, max(1, win_h // 8)):
            score = edges[top_px:top_px + win_h, :].std()
            if score > best_score:
                best_score, best_top = score, top_px
        top = min(max(0, round(best_top / sy)), h - target_h)
        return (0, top, target_w, top + target_h)


def process_one(src_path, dest_path):
    with Image.open(src_path) as im:
        im = im.convert('RGB')
        box = best_crop_box(im)
        cropped = im.crop(box)
        out = cropped.resize((OUT_W, OUT_H), Image.LANCZOS)
        out.save(dest_path, 'JPEG', quality=QUALITY, optimize=True)


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--apply', action='store_true', help='Sobreescribe assets/img/recetas/ de verdad')
    ap.add_argument('--limit', type=int, default=0, help='Procesa solo N archivos (para pruebas)')
    args = ap.parse_args()

    out_dir = SRC_DIR if args.apply else STAGING_DIR
    if not args.apply:
        os.makedirs(STAGING_DIR, exist_ok=True)

    files = sorted(f for f in os.listdir(SRC_DIR) if f.lower().endswith(('.jpg', '.jpeg', '.png')))
    if args.limit:
        files = files[:args.limit]

    before_total = 0
    after_total = 0
    errors = []
    for i, fname in enumerate(files):
        src = os.path.join(SRC_DIR, fname)
        dest_name = os.path.splitext(fname)[0] + '.jpg'
        dest = os.path.join(out_dir, dest_name)
        try:
            before_total += os.path.getsize(src)
            process_one(src, dest)
            after_total += os.path.getsize(dest)
        except Exception as e:
            errors.append((fname, str(e)))
        if (i + 1) % 100 == 0:
            print(f'  {i + 1}/{len(files)}...')

    print(f'\nProcesadas: {len(files) - len(errors)} / {len(files)}')
    if errors:
        print(f'Errores: {len(errors)}')
        for fn, err in errors[:10]:
            print('  ', fn, '->', err)
    if before_total:
        print(f'Peso: {before_total/1024/1024:.1f} MB -> {after_total/1024/1024:.1f} MB')
    print(f'Salida: {out_dir}')


if __name__ == '__main__':
    main()
