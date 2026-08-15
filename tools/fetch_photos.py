# -*- coding: utf-8 -*-
"""
MenúVital — Descarga fotos reales para las recetas que no tienen ninguna
guardada: platos colombianos vía Wikimedia Commons (Pexels no tiene ajiaco,
sancocho, etc.), el resto vía Pexels. Descarga el archivo a
assets/img/recetas/ y escribe candidatos a tools/photo_candidates.json para
revisión visual manual (uno por uno) antes de aplicarlos al seed.

Uso: python fetch_photos.py
"""
import json
import os
import re
import time
import unicodedata

import requests

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(ROOT, 'assets', 'img', 'recetas')
PEXELS_API_KEY = 'jP7sMHmS35sFuJ3Q81dBu6FyWSdJT0urjLRFvCaSRSc2bZk23mZvJKfw'
UA = 'MenuVitalPhotoFetcher/1.0 (https://menuvital.hannierco.com; vanessarobayo40@gmail.com)'

# Recetas colombianas: término de búsqueda curado a mano para Wikimedia
# Commons (el nombre del plato tal cual casi siempre encuentra algo real).
COLOMBIAN_SEARCH = {
    'Changua santafereña': 'changua colombian soup',
    'Calentado paisa aligerado': 'calentado paisa',
    'Arepa de huevo al horno': 'arepa de huevo',
    'Caldo de costilla': 'caldo de costilla colombia',
    'Arepa boyacense con cuajada y miel': 'arepa boyacense',
    'Huevos pericos con arepa': 'huevos pericos colombia',
    'Tamal tolimense en porción': 'tamal tolimense',
    'Almojábana horneada': 'almojabana colombiana',
    'Chocolate santafereño con queso': 'chocolate santafereño',
    'Avena colombiana caliente': 'avena colombiana bebida',
    'Ajiaco santafereño aligerado': 'ajiaco santafereño',
    'Sancocho de gallina valluno': 'sancocho de gallina colombia',
    'Sancocho trifásico': 'sancocho trifasico colombia',
    'Bandeja paisa en porción real': 'bandeja paisa',
    'Fríjoles antioqueños con garra': 'frijoles antioqueños',
    'Arroz atollado': 'arroz atollado colombia',
    'Cazuela de mariscos costeña': 'cazuela de mariscos colombia',
    'Mote de queso cordobés': 'mote de queso colombia',
    'Sudado de pollo': 'pollo sudado colombiano',
    'Sudado de posta': 'posta negra colombiana',
    'Pescado con patacón al horno': 'pescado con patacon',
    'Arroz con pollo': 'arroz con pollo colombiano',
    'Mute santandereano': 'mute santandereano',
    'Encocado de pescado del Pacífico': 'encocado de pescado colombia',
    'Caldo de papa': 'caldo de papa colombiano',
    'Sopa de verduras con carne': 'sopa de verduras con carne',
    'Caldo de albóndigas ligero': 'caldo de albondigas',
    'Pescado sudado ligero': 'pescado sudado colombiano',
    'Crema de ahuyama': 'crema de ahuyama zapallo',
    'Sopa de arroz con pollo': 'sopa de arroz con pollo',
    'Trucha al ajillo del Eje Cafetero': 'trucha al ajillo',
    'Mojarra al vapor con ensalada': 'mojarra colombiana',
    'Pechuga a la plancha con patacón al horno': 'pechuga a la plancha patacon',
    'Sopa de guineo santandereana': 'sopa de guineo verde',
    'Bistec a caballo en porción': 'bistec a caballo colombia',
    'Patacón con hogao al horno': 'patacon con hogao',
    'Aborrajado al horno': 'aborrajado colombiano',
    'Empanada de pipián en air fryer': 'empanada de pipian colombia',
    'Carimañola': 'carimañola colombiana',
    'Pandebono': 'pandebono colombiano',
    'Mazamorra con panela': 'mazamorra antioqueña',
    'Salpicón de frutas': 'salpicon de frutas colombiano',
    'Lulada vallecaucana': 'lulada cali',
    'Chontaduro con miel': 'chontaduro colombia',
}

# El resto: término de búsqueda en INGLÉS para Pexels (banco internacional,
# funciona mejor con términos genéricos de comida en inglés).
GENERIC_SEARCH = {
    'Cottage Cheese Bowl con Fruta': 'cottage cheese bowl fruit',
    'Huevos Revueltos con Queso y Pan': 'scrambled eggs cheese toast',
    'Perico Venezolano': 'venezuelan scrambled eggs tomato onion',
    'Shakshuka Clásica': 'shakshuka eggs tomato',
    'Frittata de Espinacas y Ricotta': 'spinach ricotta frittata',
    'Omelette Clásico con Queso y Jamón': 'ham cheese omelette',
    'Tortilla Española': 'spanish tortilla potato omelette',
    'Panneer Bhurji (Revuelto de Queso Indio)': 'paneer bhurji indian scramble',
    'Menemen Turco': 'menemen turkish eggs',
    'Calentado Bogotano Fitness': 'rice beans breakfast plate',
    'Chilaquiles Rojos con Huevo': 'chilaquiles rojos eggs',
    'Açai Bowl Proteico': 'acai bowl protein',
    'Quinoa Bowl Dulce con Frutas': 'quinoa breakfast bowl fruit',
    'Loaded Protein Bowl': 'protein bowl breakfast',
    'Avena con Proteína y Fresas': 'oatmeal protein strawberries',
    'Pancakes de Proteína (2 ingredientes)': 'protein pancakes banana',
    'French Toast Proteico': 'french toast protein',
    'Pancakes de Avena y Proteína': 'oat protein pancakes',
    'Muffins de Huevo y Espinacas con Base Dulce': 'egg muffins spinach',
    'Avena Colombiana Dulce con Proteína': 'sweet oatmeal bowl',
    'Coliflor Entera Asada': 'whole roasted cauliflower',
    'Champiñones Rellenos de Queso y Ajo': 'stuffed mushrooms cheese garlic',
    'Espárragos con Parmesano y Limón': 'asparagus parmesan lemon',
    'Berenjena a la Parmigiana': 'eggplant parmesan',
    'Pimentones Asados': 'roasted bell peppers',
    'Zanahorias Glaseadas con Miel y Canela': 'honey glazed carrots cinnamon',
    'Pollo Entero Estilo Rotisserie': 'whole rotisserie chicken',
    'Chuletas de Cerdo con Ajo y Limón': 'pork chops garlic lemon',
    'Pechuga Rellena de Jamón y Queso': 'chicken breast stuffed ham cheese',
    'Bistec Apanado Estilo Milanesa': 'breaded steak milanesa',
    'Alitas Teriyaki': 'teriyaki chicken wings',
    'Lomo de Cerdo con Mostaza y Panela': 'pork loin mustard glaze',
    'Pollo al Curry Colombiano': 'chicken curry',
    'Pernil de Cerdo Crujiente': 'crispy pork roast',
    'Deditos de Pollo Estilo KFC': 'crispy chicken tenders',
    'Salmón con Mantequilla y Eneldo': 'salmon butter dill',
    'Mojarra Frita Entera': 'whole fried fish',
    'Camarones Empanizados con Coco': 'coconut shrimp',
    'Tilapia con Costra de Ajo y Hierbas': 'tilapia garlic herb crust',
    'Brochetas de Camarón y Chorizo': 'shrimp chorizo skewers',
    'Pulpo a la Gallega Express': 'octopus galician style',
    'Bagre Apanado con Limón': 'breaded catfish lemon',
    'Langostinos con Mantequilla de Ajo': 'prawns garlic butter',
}


def slugify(name):
    s = unicodedata.normalize('NFKD', name).encode('ascii', 'ignore').decode()
    s = re.sub(r'[^a-zA-Z0-9]+', '-', s).strip('-').lower()
    return s[:60]


def wikimedia_search(term, tries=3):
    """Busca en Wikimedia Commons (namespace 6 = archivos) y devuelve la
    mejor imagen candidata: (url, license_short, author, page_url) o None."""
    url = 'https://commons.wikimedia.org/w/api.php'
    params = {
        'action': 'query', 'format': 'json',
        'generator': 'search', 'gsrsearch': f'{term} filetype:bitmap',
        'gsrnamespace': 6, 'gsrlimit': 5,
        'prop': 'imageinfo', 'iiprop': 'url|size|extmetadata',
        'iiurlwidth': 1000,
    }
    for attempt in range(tries):
        try:
            r = requests.get(url, params=params, headers={'User-Agent': UA}, timeout=15)
            r.raise_for_status()
            data = r.json()
            pages = data.get('query', {}).get('pages', {})
            for _, page in pages.items():
                info = (page.get('imageinfo') or [None])[0]
                if not info:
                    continue
                w, h = info.get('width', 0), info.get('height', 0)
                if w < 400 or h < 300:
                    continue
                meta = info.get('extmetadata', {})
                license_short = meta.get('LicenseShortName', {}).get('value', '?')
                artist = re.sub('<[^<]+?>', '', meta.get('Artist', {}).get('value', '?'))[:60]
                img_url = info.get('thumburl') or info.get('url')
                return {'url': img_url, 'license': license_short, 'author': artist,
                        'source': 'wikimedia', 'page': page.get('title', '')}
            return None
        except requests.RequestException:
            time.sleep(2)
    return None


def pexels_search(term, tries=3):
    url = 'https://api.pexels.com/v1/search'
    params = {'query': term, 'per_page': 3, 'orientation': 'landscape'}
    for attempt in range(tries):
        try:
            r = requests.get(url, params=params, headers={'Authorization': PEXELS_API_KEY}, timeout=15)
            if r.status_code == 429:
                time.sleep(5)
                continue
            r.raise_for_status()
            photos = r.json().get('photos', [])
            if not photos:
                return None
            p = photos[0]
            return {'url': p['src']['large'], 'license': 'Pexels license',
                    'author': p.get('photographer', '?'), 'source': 'pexels',
                    'page': p.get('url', '')}
        except requests.RequestException:
            time.sleep(2)
    return None


def download(url, dest_path, tries=3):
    for attempt in range(tries):
        try:
            r = requests.get(url, headers={'User-Agent': UA}, timeout=20)
            r.raise_for_status()
            with open(dest_path, 'wb') as f:
                f.write(r.content)
            return True
        except requests.RequestException:
            time.sleep(2)
    return False


def main():
    os.makedirs(IMG_DIR, exist_ok=True)
    candidates = []
    all_terms = [(name, term, 'colombian') for name, term in COLOMBIAN_SEARCH.items()]
    all_terms += [(name, term, 'generic') for name, term in GENERIC_SEARCH.items()]

    for name, term, kind in all_terms:
        slug = slugify(name)
        result = wikimedia_search(term) if kind == 'colombian' else None
        if not result:
            result = pexels_search(term)
        if not result:
            print(f'SIN RESULTADO: {name!r} (buscado: {term!r})')
            continue
        ext = '.jpg'
        filename = f'{slug}.jpg'
        dest = os.path.join(IMG_DIR, filename)
        ok = download(result['url'], dest)
        if not ok:
            print(f'DESCARGA FALLÓ: {name!r}')
            continue
        candidates.append({
            'name': name, 'search_term': term, 'file': filename,
            'source': result['source'], 'license': result['license'],
            'author': result['author'], 'page': result.get('page', ''),
        })
        print(f"OK  [{result['source']:9s}] {name!r} -> {filename}")
        time.sleep(0.4)

    with open(os.path.join(os.path.dirname(__file__), 'photo_candidates.json'), 'w', encoding='utf-8') as f:
        json.dump(candidates, f, ensure_ascii=False, indent=2)
    print(f'\nTotal descargadas: {len(candidates)} / {len(all_terms)}')
    print('Guardado: tools/photo_candidates.json (para revisión visual antes de aplicar)')


if __name__ == '__main__':
    main()
