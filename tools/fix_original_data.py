# -*- coding: utf-8 -*-
"""
MenúVital — Corrige errores de datos puntuales en las 957 recetas originales
(no en el pipeline de construcción, porque su script generador se perdió).
Edita database/recipes_data.php directamente, por nombre exacto.

Corre DESPUÉS de la reconstrucción del lote nuevo (para que los conteos de
"cuántas recetas hay" ya reflejen el archivo final) pero antes de emit_php.py
si se vuelve a ejecutar — en la práctica se corre una sola vez sobre el
archivo ya completo.
"""
import re

PATH = r"c:\Users\aleja\Downloads\App menu\database\recipes_data.php"

# ---------- 1. Nombres de ensaladas que perdieron el prefijo "Ensalada " ----------
# Verificado 1:1 contra Recetas2026/ensaladas/Recetas.xlsx (50/50 coinciden
# exactamente con el nombre de la fuente sin el prefijo).
ENSALADA_RENAMES = {
    'Del Campo con Huevo': 'Ensalada Del Campo con Huevo',
    'Pipirrana': 'Ensalada Pipirrana',
    'Sorpresa de Atún': 'Ensalada Sorpresa de Atún',
    'Espinaca con Pollo y Mango': 'Ensalada Espinaca con Pollo y Mango',
    'Fria de Coliflor con Mayonesa Casera': 'Ensalada Fria de Coliflor con Mayonesa Casera',
    'Americana Ligera': 'Ensalada Americana Ligera',
    'Caliente de Repollo con Manzana': 'Ensalada Caliente de Repollo con Manzana',
    'De Brocoli Y Atún': 'Ensalada De Brocoli Y Atún',
    'Asiática de Pepino': 'Ensalada Asiática de Pepino',
    'Fresca de Garbanzos': 'Ensalada Fresca de Garbanzos',
    'Capresse Clásica': 'Ensalada Caprese Clásica',  # + typo Capresse->Caprese
    'Sifrina': 'Ensalada Sifrina',
    'Griega': 'Ensalada Griega',
    'Verde con Jamón Serrano': 'Ensalada Verde con Jamón Serrano',
    'De Zucchini Asado': 'Ensalada De Zucchini Asado',
    'Altamar': 'Ensalada Altamar',
    'Love': 'Ensalada Love',
    'De Salmón Y Maní': 'Ensalada De Salmón Y Maní',
    'De Pollo con Jengibre': 'Ensalada De Pollo con Jengibre',
    'Relax': 'Ensalada Relax',
    'De Tallarines de Zanahoria con Ricota y Nueces': 'Ensalada De Tallarines de Zanahoria con Ricota y Nueces',
    'De Berenjena con Salsa de Yogurt': 'Ensalada De Berenjena con Salsa de Yogurt',
    'Mediterránea de Atún': 'Ensalada Mediterránea de Atún',
    'Coliflor con Langostinos': 'Ensalada Coliflor con Langostinos',
    'De Repollo Agridulce': 'Ensalada De Repollo Agridulce',
    'De Green Beans y Salmón': 'Ensalada De Green Beans y Salmón',
    'Caribeña': 'Ensalada Caribeña',
    'Caponnata': 'Ensalada Caponata',  # + typo Caponnata->Caponata
    'De Verano': 'Ensalada De Verano',
    'Oriental con Aderezo de Cilantro': 'Ensalada Oriental con Aderezo de Cilantro',
    'Atardecer': 'Ensalada Atardecer',
    'UvaPollo': 'Ensalada Uva y Pollo',
    'Picnic': 'Ensalada Picnic',
    'Express de Coliflor': 'Ensalada Express de Coliflor',
    'China Cruda': 'Ensalada China Cruda',
    'Primavera': 'Ensalada Primavera',
    'De Salmón con Salsa Roquefort': 'Ensalada De Salmón con Salsa Roquefort',
    'Carpaccio de Tomate': 'Ensalada Carpaccio de Tomate',
    'Italiana': 'Ensalada Italiana',
    '7 Sabores': 'Ensalada 7 Sabores',
    'Otoño': 'Ensalada Otoño',
    'Kikiriki': 'Ensalada Kikiriki',
    'Thai': 'Ensalada Thai',
    'Chik': 'Ensalada Chik',
    'Catar': 'Ensalada Catar',
    'Garbanzo y Mar': 'Ensalada Garbanzo y Mar',
    'Florida': 'Ensalada Florida',
    'Tricolor': 'Ensalada Tricolor',
    'Ligera Con Aderezo De Maní': 'Ensalada Ligera Con Aderezo De Maní',
    'Cesar con Frutos Rojos': 'Ensalada Cesar con Frutos Rojos',
}

# ---------- 2. Nombres truncados a mitad de frase (se completan con el
# contexto real de ingredientes/pasos de la propia receta) ----------
NAME_FIXES = {
    'Huevos revueltos con espinacas y': 'Huevos revueltos con espinacas y tomate',
    'Tortilla de calabacín rellena de jamón y': 'Tortilla de calabacín rellena de jamón y queso',
}

# ---------- 3. Typos de ortografía real ----------
TYPO_FIXES = {
    'Pan De Zuccchini Con Huevos Revueltos': 'Pan De Zucchini Con Huevos Revueltos',
    'Sándwich Capresse': 'Sándwich Caprese',
    'Fritatta Génova': 'Frittata Génova',
    'Panninis de Atún y Bacon': 'Paninis de Atún y Bacon',
    'Hummurs': 'Hummus',
    'Moiss de chocolate': 'Mousse de chocolate',
    'Camarrones con coco': 'Camarones con coco',
    'Zanahoriias con miel y salvia': 'Zanahorias con miel y salvia',
    'Zanahoriias gourmet': 'Zanahorias gourmet',
    'Gelina de manzan verde': 'Gelatina de manzana verde',
    'Sopa de acelga con queso y aguacte': 'Sopa de acelga con queso y aguacate',
}

# ---------- 4. Recetas mal categorizadas: son un acompañamiento/condimento,
# no un plato principal (verificado por nombre, todas <420 kcal) ----------
RECATEGORIZE_TO_SNACK = [
    'Compota de mandarina con queso fresco y nueces',
    'Compota de manzana con queso Fresco',
    'Hummus de aguacate',
    'Dip de queso crema y ciboulette',
    'Guacamole con un toque de lima',
    'Hummus clásico de garbanzos',
    'Hummus de remolacha y comino',
    'Hummus de zapallo asado y cúrcuma',
    'Pesto de albahaca y almendras',
    'Salsa criolla argentina',
    'Salsa de yogur con eneldo',
    'Hummus de mantequilla de mani',
    'Salsa de espinaca con champiñones',
    'Salsa de fritas con nachos dulces',
    'Salsa de mango',
]

# ---------- 5. Azúcar/fibra > carbohidratos (imposible): se sube carbs al
# máximo de los tres, en vez de inventar un recorte de azúcar/fibra que no
# se puede justificar sin la fuente original. ----------
FIX_CARBS_FLOOR = [
    'Tosta con Salmón, aguacate y huevo',
    'Batida de fresa con yogurt',
    'Aguacate relleno de atún',
    'Gelatina de frutas sin azúcar',
    'Helado de platano con nueces',
    'Ponche huracanado',
]

# ---------- 6. Tag "vegetariano"/"sin gluten" incorrecto: la receta SÍ lleva
# carne/pescado real o un producto de trigo real (no huevo — esa es una
# definición de producto a decidir aparte, ver conversación). ----------
REMOVE_VEGETARIANO = [
    'Té verde con desayuno mediterráneo', 'Bocaditos De Atún Con Salsa De Yogurt',
    'Creps De Avena Con Queso Y Jamón', 'Ensalada Saludable',
    'Pasta cottahaca (con salsa de albahaca, queso cottage y pollo)',
    'Pasta ﬂorencia (crema de pollo y espinacas)', 'Pavo al curry con batata especiada',
    'Ensalada fresca con pescado dorado', 'Pepinos rellenos', 'Pizza blanca con salchicha',
    'Sopa de acelga con queso y aguacate', 'Tacos de lechuga con pescado',
    'Ensalada 7 Sabores', 'Ensalada De Brocoli Y Atún', 'Ensalada De Green Beans y Salmón',
    'Ensalada Garbanzo y Mar', 'Ensalada Pipirrana', 'Ensalada Sifrina', 'Ensalada Thai',
    'Crema de auyama', 'Tortilla integral de pollo con aguacate',
    'Barquitas de Pepino con Pollo y Tzatziki', 'Tacos de lechuga con carne de res',
]
REMOVE_SIN_GLUTEN = [
    'Tostadas de centeno con rúcula y hummus de garbanzos', 'Cous- Cous',
    'Pan de centeno sin gluten', 'Camarones con coco', 'Tostada de salmón con huacamole',
    'Cheescake de calabaza sin azucar',
]

# ---------- 7. Tag "vegetariano" que faltaba solo por llevar huevo ----------
# ANIMAL_PROTEIN_KEYS/_MEAT_WORDS en nutrition_flags.py tenían el huevo
# contando como "no vegetariano" — decisión de producto que Vanessa corrigió
# en la conversación: en MenúVital "vegetariano" es ovo-vegetariano (el uso
# común en Colombia/Latinoamérica). Ya se corrigió la causa raíz para las
# recetas nuevas; esta lista son las que ya existían sin la etiqueta por
# ese motivo (llevan huevo, no llevan carne/pescado real).
ADD_VEGETARIANO_EGG = [
    'Huevos al horno sobre aguacate con queso',
    'Tortilla de espinacas y champiñones',
    'Tortilla de espinacas y queso',
    'Tortilla de zanahoria al estilo keto',
    'Avena con aguacate y huevo escalfado',
    'Fritata de Verduras',
    'Huevo Horneado en Aguacate',
    'Huevo Horneado en Pimiento',
    'Huevos Rancheros con pico de gallo',
    'Huevos Revueltos',
    'Huevos a la mexicana',
    'Menemen',
    'Mini Tortilla Española con Calabacín',
    'Mini frittatas de claras y champiñones',
    'Mini tortita de espárrago con huevo',
    'Muffins de Huevo',
    'Omelette con verduras',
    'Pan de plátano con chocolate sin azúcar añadido',
    'Panqueques de Almendras',
    'Panqueques de Crema de cacahuete y platano',
    'Panqueques de quinoa y banana',
    'Quiche sin corteza con espinacas',
    'Tortilla crujiente con gambas, aguacate y espinacas',
    'bistec y huevos con chimichurri',
    'Hash browns de batata',
    'Huevos revueltos con espinaca y cúrcuma',
    'Omelette de espinaca y queso de cabra',
    'Omelette de palta y tomate seco',
    'Panqueques de avena y banana',
    'Panqueques de manzana y canela',
    'Tostadas de batata (camote) con palta y huevo',
    'Waffles de batata sin gluten',
    'Bowl energético de huevos revueltos con vegetales',
    'Pan De Zucchini Con Huevos Revueltos',
    'Pancakes De Blueberries',
    'Pancakes De Zanahoria Con Crema De Coco',
    'Pancakes Moka',
    'Pancitos Encantados',
    'Papilla De Chocolate Y Naranja',
    'Pastel De Avena Y Manzana',
    'Pastel De Quinoa',
    'Pizza En Taza',
    'Sándwich De Huevo',
    'Tortilla De Arroz',
    'Tortilla Italianisima',
    'Tostadas De Avena',
    'Waffles De Sweet Potato Con Topping De Cacao',
    'Huevos a la cazuela',
    'Pan de Plátano y Calabacín',
    'Sandwixh de huevo y espinacas',
    'Wrap con claras de queso feta',
    'Tortilla de huevo a la capresa',
    'Chaffles, waffles de queso y huevo',
    'Pan cetogénico de harina de coco',
    'Tostada de aguacate con pan keto',
    'Tostada con Aguacate y Huevo Revuelto',
    'Huevos Revueltos con Queso y Pan',
    'Perico Venezolano',
    'Tostada Griega con Yogur y Huevo',
    'Arepa con Queso y Huevo',
    'Shakshuka Clásica',
    'Frittata de Espinacas y Ricotta',
    'Tortilla Española',
    'Panneer Bhurji (Revuelto de Queso Indio)',
    'Menemen Turco',
    'Changua con Queso y Pan',
    'Calentado Bogotano Fitness',
    'Chilaquiles Rojos con Huevo',
    'Loaded Protein Bowl',
    'Desayuno Costeño Completo',
    'Bowl de Huevo, Frijoles y Arroz',
    'Pancakes de Proteína (2 ingredientes)',
    'French Toast Proteico',
    'Pancakes de Avena y Proteína',
    'Banana Protein Pancakes con Mantequilla de Maní',
    'Huevos En Cazuela',
    'Changua santafereña',
    'Calentado paisa aligerado',
    'Arepa de huevo al horno',
    'Huevos pericos con arepa',
    'Almojábana horneada',
    'Calamares a la romana keto',
    'Bollito hervido valenciano/ Caldo de verduras',
    'Ensalada de espinacas, garbanzos tostados, tomate, huevo y aguacate',
    'Shakshuka',
    'Tortitas de Zanahoria y Manzana con nueces',
    'Fideos de harina de almendras',
    'Masa de pizza con harina de arroz integral',
    'Masa de pizza con yogur y harina de almendras',
    'Masa de pizza de batata',
    'Masa de pizza de coliflor',
    'Masa de pizza de zanahoria y avena sin gluten',
    'Masa de pizza keto (sin carbohidratos)',
    'Pan de garbanzo sin harina',
    'Pan de harina de almendras y semillas',
    'Pasta de harina de arroz integral',
    'Ravioles de harina de arroz',
    'Ravioles de harina de sarraceno',
    'Ñoquis de batata sin harina',
    'Ensalada de Huevo',
    'Muffins de Avena y manzana',
    'Salteado de Verduras de Verano y Huevos revuelto',
    'Soufflé de barata',
    'Ensalada Kikiriki',
    'Proteína Express',
    'Huevo y Aguacate',
    'Arroz, Huevo y Vegetales',
    'Huevos, Papa y Aguacate',
    'Pancakes de Brocoli y queso',
    'Pizza de Coliflor',
    'Tortilla de Tomate y Huevo',
    'Berenjenas rellenas',
    'Snaacks de granola',
    'Calamares Apanados',
    'Pan de Coliflor sin Gluten',
    'Pan de Espinacas y Parmesano',
    'Pan de Papas Sin Gluten',
    'Mini brownie SOP',
    'Brownies de batata y cacao sin harina',
    'Brownies de chocolate amargo y batata',
    'Flan de cacao sin azúcar',
    'Flan de coco sin azúcar',
    'Galletas de avellana y cacao sin azúcar',
    'Galletas de cacao y almendras sin azúcar',
    'Galletas de mantequilla de maní y avena',
    'Huevos revueltos con palta y tomate',
    'Muffins de chocolate y banana',
    'Torta de chocolate sin azúcar y sin harina',
    'Brownie sin azucar',
    'Flan de Café Aromático',
    'Flan de huevo sin azucar',
    'Galletas de almendra sin azúcar',
    'Galletas de avena y pasas sin azúcar',
    'Galletas de yogur sin azúcar',
    'Flan de coco keto',
    'Huevos Rellenos con Mayonesa y Mostaza',
    'Calabacín Crocante',
    'Palitos de Queso Apanados',
    'Nuggets de Coliflor',
    'Huevitos de Codorniz Crocantes',
    'Barras de Coco y Almendras',
    'Torta de Banano y Avena',
    'Torta de Zanahoria Saludable',
    'Torta de Manzana y Canela',
    'Torta de Coco y Almendras',
    'Torta Integral de Yogur',
    'Torta de Naranja Casera Saludable',
    'Torta de Avena y Arándanos',
    'Torta de Calabaza y Canela',
    'Torta de Café y Cacao',
    'Torta de Limón y Yogur',
    'Torta de Fresas Saludable',
    'Torta de Pera y Canela',
    'Torta de Avena y Nueces',
    'Torta Marmoleada Fit',
    'Torta de Piña y Coco',
    'Torta de Almendras y Vainilla',
    'Torta de Avena y Cacao',
    'Torta de Mandarina',
    'Torta de Yogur y Arándanos',
    'Torta de Ciruelas y Canela',
    'Torta de Batata y Coco',
    'Torta de Maracuyá Saludable',
    'Torta de Cacao y Café',
    'Torta de Mango y Coco',
    'Torta de Chía y Limón',
    'Torta de Durazno Casera',
    'Torta Integral de Banano y Chocolate',
    'Torta de Coco y Limón',
    'Torta de Vainilla y Canela',
    'Flan de coco y vainilla',
    'Aborrajado al horno',
    'Carimañola',
    'Pandebono',
]


def load(path):
    # newline='' es obligatorio: sin esto, Python traduce \r\n -> \n al leer
    # (modo universal newlines) y luego save() escribe esos \n sueltos tal
    # cual, convirtiendo TODO el archivo de CRLF a LF sin que ningún texto
    # visible cambie — justo el bug que encontré la primera vez que corrí esto.
    return open(path, encoding='utf-8', newline='').read()


def save(path, text):
    with open(path, 'w', encoding='utf-8', newline='') as f:
        f.write(text)


def rename_recipe(text, old_name, new_name):
    # Reemplazo por función (no por string) para no depender de cómo re.sub
    # interpreta \1, \g<...> etc. si el nombre tuviera una barra invertida.
    pattern = re.compile(r"\['" + re.escape(old_name) + r"', ")
    new_text, n = pattern.subn(lambda m: "['" + new_name + "', ", text)
    return new_text, n


def recategorize_to_snack(text, name):
    # Reemplaza SOLO el par ('<name>', 'almuerzo'  o  'cena') por 'snack'.
    pattern = re.compile(r"(\['" + re.escape(name) + r"', ')(almuerzo|cena)(')")
    new_text, n = pattern.subn(r"\1snack\3", text)
    return new_text, n


def remove_tag(text, name, tag):
    """Quita `tag` del array de tags de la receta `name`, si está."""
    block_re = re.compile(
        r"(\['" + re.escape(name) + r"', '\w+',\r?\n"
        r" \[.*?\],\r?\n \[.*?\],\r?\n \[)((?:'[^']*'(?:, )?)*)(\])",
        re.S,
    )
    m = block_re.search(text)
    if not m:
        return text, 0
    prefix, tags_blob, suffix = m.groups()
    items = re.findall(r"'([^']*)'", tags_blob)
    if tag not in items:
        return text, 0
    items = [t for t in items if t != tag]
    new_blob = ', '.join(f"'{t}'" for t in items)
    new_text = text[:m.start()] + prefix + new_blob + suffix + text[m.end():]
    return new_text, 1


def add_tag(text, name, tag):
    """Agrega `tag` al array de tags de la receta `name`, si no lo tiene ya."""
    block_re = re.compile(
        r"(\['" + re.escape(name) + r"', '\w+',\r?\n"
        r" \[.*?\],\r?\n \[.*?\],\r?\n \[)((?:'[^']*'(?:, )?)*)(\])",
        re.S,
    )
    m = block_re.search(text)
    if not m:
        return text, 0
    prefix, tags_blob, suffix = m.groups()
    items = re.findall(r"'([^']*)'", tags_blob)
    if tag in items:
        return text, 0
    items.append(tag)
    new_blob = ', '.join(f"'{t}'" for t in items)
    new_text = text[:m.start()] + prefix + new_blob + suffix + text[m.end():]
    return new_text, 1


def fix_carbs_floor(text, name):
    """
    Sube carbs = max(carbs, sugar, fiber) — azúcar/fibra no pueden ser mayores
    que el total de carbohidratos del que son parte. Al subir carbs también
    se suman sus kcal (4 kcal/g) para no dejar el Atwater (4P+4C+9F vs kcal)
    roto de nuevo — eso pasó la primera vez que corrí este script.
    """
    block_re = re.compile(
        r"(\['" + re.escape(name) + r"', '\w+',\r?\n"
        r" \[.*?\],\r?\n \[.*?\],\r?\n \[(?:(?:'[^']*')(?:, )?)*\], )"
        r"(\d+), (\d+), (\d+), (\d+), (\d+), (\d+), (\d+)",
        re.S,
    )
    m = block_re.search(text)
    if not m:
        return text, 0
    prefix, kcal, prot, tmin, carbs, fat, sugar, fiber = m.groups()
    kcal_i, carbs_i, sugar_i, fiber_i = int(kcal), int(carbs), int(sugar), int(fiber)
    new_carbs = max(carbs_i, sugar_i, fiber_i)
    if new_carbs == carbs_i:
        return text, 0
    new_kcal = kcal_i + 4 * (new_carbs - carbs_i)
    replacement = f"{prefix}{new_kcal}, {prot}, {tmin}, {new_carbs}, {fat}, {sugar}, {fiber}"
    new_text = text[:m.start()] + replacement + text[m.end():]
    return new_text, 1


def main():
    text = load(PATH)
    report = []

    for old, new in NAME_FIXES.items():
        text, n = rename_recipe(text, old, new)
        report.append((f"nombre truncado -> {new!r}", n))

    for old, new in TYPO_FIXES.items():
        text, n = rename_recipe(text, old, new)
        report.append((f"typo -> {new!r}", n))

    for old, new in ENSALADA_RENAMES.items():
        text, n = rename_recipe(text, old, new)
        report.append((f"Ensalada -> {new!r}", n))

    for name in RECATEGORIZE_TO_SNACK:
        text, n = recategorize_to_snack(text, name)
        report.append((f"recategorizada a snack: {name!r}", n))

    for name in FIX_CARBS_FLOOR:
        text, n = fix_carbs_floor(text, name)
        report.append((f"carbs corregidos: {name!r}", n))

    for name in REMOVE_VEGETARIANO:
        text, n = remove_tag(text, name, 'vegetariano')
        report.append((f"tag 'vegetariano' quitado: {name!r}", n))

    for name in REMOVE_SIN_GLUTEN:
        text, n = remove_tag(text, name, 'sin gluten')
        report.append((f"tag 'sin gluten' quitado: {name!r}", n))

    for name in ADD_VEGETARIANO_EGG:
        text, n = add_tag(text, name, 'vegetariano')
        report.append((f"tag 'vegetariano' agregado: {name!r}", n))

    save(PATH, text)

    ok = sum(1 for _, n in report if n == 1)
    bad = [(msg, n) for msg, n in report if n != 1]
    print(f"Aplicados correctamente: {ok} / {len(report)}")
    if bad:
        print("\nNO se pudieron aplicar (revisar a mano):")
        for msg, n in bad:
            print(f"  [{n} coincidencias] {msg}")


if __name__ == '__main__':
    main()
