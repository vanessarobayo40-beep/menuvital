# -*- coding: utf-8 -*-
"""Ronda 2: reemplaza las fotos que la revisión visual marcó como
equivocadas, con términos más específicos y, para las que Wikimedia
falló con resultados genéricos irrelevantes, forzando Pexels."""
import sys, os, json, time
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from fetch_photos import wikimedia_search, pexels_search, download, slugify, IMG_DIR

# name -> (termino_nuevo, forzar_fuente)  forzar_fuente in ('pexels','wikimedia',None)
RETRY = {
    'Almojábana horneada': ('colombian cheese bread baked', 'pexels'),
    'Carimañola': ('fried yuca cheese croquette', 'pexels'),
    'Chocolate santafereño con queso': ('hot chocolate with cheese melting', 'pexels'),
    'Crema de ahuyama': ('pumpkin cream soup bowl', 'pexels'),
    'Avena colombiana caliente': ('hot oatmeal porridge bowl drink', 'pexels'),
    'Arroz con pollo': ('chicken rice plate latin', 'pexels'),
    'Bistec Apanado Estilo Milanesa': ('breaded steak milanesa fries', 'pexels'),
    'Bistec a caballo en porción': ('steak fried egg rice plate', 'pexels'),
    'Fríjoles antioqueños con garra': ('red beans stew plate pork', 'pexels'),
    'Langostinos con Mantequilla de Ajo': ('garlic butter shrimp skillet', 'pexels'),
    'Lulada vallecaucana': ('lulo fruit drink glass', 'pexels'),
    'Mazamorra con panela': ('corn milk dessert bowl sweet', 'pexels'),
    'Menemen Turco': ('turkish menemen eggs tomato skillet', 'pexels'),
    'Mojarra al vapor con ensalada': ('steamed whole fish plate salad', 'pexels'),
    'Muffins de Huevo y Espinacas con Base Dulce': ('egg muffins spinach cups', 'pexels'),
    'Pancakes de Avena y Proteína': ('oat protein pancakes stack', 'pexels'),
    'Pancakes de Proteína (2 ingredientes)': ('banana egg pancakes stack', 'pexels'),
    'Pandebono': ('colombian cheese bread rolls', 'pexels'),
    'Patacón con hogao al horno': ('fried plantain tomato sauce', 'pexels'),
    'Pechuga Rellena de Jamón y Queso': ('chicken breast stuffed ham cheese cut', 'pexels'),
    'Perico Venezolano': ('venezuelan scrambled eggs tomato plate', 'pexels'),
    'Pollo al Curry Colombiano': ('chicken curry sauce plate', 'pexels'),
    'Pulpo a la Gallega Express': ('grilled octopus paprika plate', 'pexels'),
    'Sancocho de gallina valluno': ('chicken soup stew bowl latin', 'pexels'),
    'Sopa de arroz con pollo': ('chicken rice soup bowl', 'pexels'),
    'Sudado de posta': ('beef stew tomato sauce plate', 'pexels'),
    'Tortilla Española': ('spanish potato omelette slice', 'pexels'),
    'Trucha al ajillo del Eje Cafetero': ('trout garlic butter plate', 'pexels'),
    'Aborrajado al horno': ('fried sweet plantain cheese', 'pexels'),
    'Arepa boyacense con cuajada y miel': ('corn arepa cheese honey closeup', 'pexels'),
    # dudosas
    'Açaí Bowl Proteico': ('acai bowl purple berries granola', 'pexels'),
    'Chuletas de Cerdo con Ajo y Limón': ('grilled pork chops garlic lemon plate', 'pexels'),
    'Coliflor Entera Asada': ('whole roasted cauliflower plate', 'pexels'),
    'Lomo de Cerdo con Mostaza y Panela': ('glazed pork loin mustard plate', 'pexels'),
    'Mojarra Frita Entera': ('whole fried fish plate golden', 'pexels'),
    'Omelette Clásico con Queso y Jamón': ('folded ham cheese omelette plate', 'pexels'),
    'Huevos pericos con arepa': ('scrambled eggs tomato onion arepa', 'pexels'),
    'Sopa de guineo santandereana': ('green banana soup bowl', 'pexels'),
    'Salpicón de frutas': ('fruit salad juice glass colombian', 'pexels'),
    'Zanahorias Glaseadas con Miel y Canela': ('honey glazed carrots plate cinnamon', 'pexels'),
    'Arepa de huevo al horno': ('arepa stuffed egg fried closeup', 'pexels'),
    'Arroz atollado': ('rice chicken stew plate colombian', 'pexels'),
}


def main():
    cands = json.load(open(os.path.join(os.path.dirname(__file__), 'photo_candidates.json'), encoding='utf-8'))
    by_name = {c['name']: c for c in cands}
    updated = 0
    for name, (term, force) in RETRY.items():
        result = None
        if force == 'wikimedia':
            result = wikimedia_search(term)
        elif force == 'pexels':
            result = pexels_search(term)
        else:
            result = wikimedia_search(term) or pexels_search(term)
        if not result:
            print(f'SIN RESULTADO: {name!r}')
            continue
        slug = slugify(name)
        filename = f'{slug}.jpg'
        dest = os.path.join(IMG_DIR, filename)
        if not download(result['url'], dest):
            print(f'DESCARGA FALLÓ: {name!r}')
            continue
        by_name[name] = {
            'name': name, 'search_term': term, 'file': filename,
            'source': result['source'], 'license': result['license'],
            'author': result['author'], 'page': result.get('page', ''),
        }
        print(f"OK  [{result['source']:9s}] {name!r} -> {filename}")
        updated += 1
        time.sleep(0.3)

    json.dump(list(by_name.values()), open(os.path.join(os.path.dirname(__file__), 'photo_candidates.json'), 'w', encoding='utf-8'),
               ensure_ascii=False, indent=2)
    print(f'\nActualizadas: {updated} / {len(RETRY)}')


if __name__ == '__main__':
    main()
