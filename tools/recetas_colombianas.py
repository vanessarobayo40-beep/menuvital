# -*- coding: utf-8 -*-
"""
MenúVital — Recetas tradicionales colombianas, versión "auténtica pero
aligerada": se respeta el plato y sus ingredientes de identidad, se ajusta
la porción a una persona y se prefieren técnicas al horno/air fryer sobre
la fritura profunda, siguiendo las proporciones del Plato Saludable de la
Familia Colombiana (GABA, ICBF/FAO 2015). Las kcal se calculan con el mismo
motor nutricional que las demás recetas nuevas (tools/nutrition_data.py,
TCAC/ICBF 2018 y USDA FoodData Central) — no se maquillan para que parezcan
más livianas de lo que son; el motor de menús (includes/planner.php)
necesita el dato real para ubicarlas bien entre almuerzo/cena.

Uso: python recetas_colombianas.py  ->  tools/recipes_colombianas.json
"""
import json
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from build_recipes import (parse_ingredients_cell, split_steps, parse_time_min,
                            infer_servings, derive_tags, norm_name, load_existing_names)
from nutrition_calc import sum_macros

OUT_JSON = os.path.join(os.path.dirname(__file__), 'recipes_colombianas.json')

# Cada receta: name, meal_type, time_min, servings (None = inferir), ingredients
# (texto crudo, una línea por ingrediente, mismo estilo que las fuentes .xlsx),
# steps (texto con pasos numerados).
RECIPES = [
    # ---------------------------- DESAYUNOS (10) ----------------------------
    dict(name='Changua santafereña', meal_type='desayuno', time_min=15, servings=1, ingredients="""
1 taza de leche
1 taza de agua
1 huevo
1 rama de cebolla larga picada
1 cda de cilantro picado
1 rebanada de pan blanco tostado
Sal al gusto
""", steps="""
1. Pon a hervir el agua con la leche, la cebolla larga y sal.
2. Cuando rompa el hervor, baja el fuego y agrega el huevo entero con cuidado de no romper la yema.
3. Cocina 3-4 minutos a fuego suave hasta que la clara cuaje.
4. Retira del fuego y espolvorea el cilantro picado.
5. Sirve caliente con el pan tostado partido dentro o al lado.
"""),
    dict(name='Calentado paisa aligerado', meal_type='desayuno', time_min=15, servings=1, ingredients="""
1 taza de arroz blanco cocido
1/2 taza de fríjol rojo cocido
1 huevo
1 arepa de maíz
1/4 taza de cebolla larga picada
1 cdta de aceite
Sal al gusto
""", steps="""
1. Calienta el aceite en un sartén antiadherente a fuego medio.
2. Sofríe la cebolla larga 1 minuto.
3. Agrega el arroz y los fríjoles y revuelve 4-5 minutos hasta que se calienten parejo.
4. En otro sartén, cocina el huevo al gusto (de preferencia poché o frito con poco aceite).
5. Tuesta la arepa en un sartén seco o en el air fryer 4 minutos.
6. Sirve el arroz con fríjoles, el huevo encima y la arepa al lado.
"""),
    dict(name='Arepa de huevo al horno', meal_type='desayuno', time_min=25, servings=1, ingredients="""
1 arepa de maíz cruda (masa)
1 huevo
Aceite en spray
Sal al gusto
""", steps="""
1. Precalienta el horno a 200°C.
2. Extiende la masa de arepa y forma un disco de 1 cm de grosor; sella los bordes dejando el centro más fino.
3. Cocina 6 minutos por cada lado en sartén caliente hasta que dore y se pueda abrir una bolsa por un costado.
4. Abre con cuidado un espacio en el centro y vierte el huevo crudo dentro.
5. Sella el borde y lleva al horno 10-12 minutos hasta que el huevo cuaje.
6. Sazona con sal y sirve caliente.
"""),
    dict(name='Caldo de costilla', meal_type='desayuno', time_min=45, servings=2, ingredients="""
300 g de costilla de res
1 papa pastusa en trozos
1/2 taza de arveja
1/4 taza de cebolla larga picada
1 diente de ajo
1 rama de cilantro
6 tazas de agua
Sal al gusto
""", steps="""
1. En una olla, dora la costilla de res con el ajo 3-4 minutos.
2. Cubre con el agua y lleva a hervor; retira la espuma que suba.
3. Baja el fuego y cocina tapado 25 minutos hasta que la carne esté tierna.
4. Agrega la papa y la arveja y cocina 12 minutos más.
5. Rectifica sal, agrega la cebolla larga y el cilantro picados al final.
6. Sirve bien caliente.
"""),
    dict(name='Arepa boyacense con cuajada y miel', meal_type='desayuno', time_min=15, servings=1, ingredients="""
1 arepa de maíz dulce
80 g de cuajada
1 cda de miel
""", steps="""
1. Tuesta la arepa boyacense en sartén o air fryer 4-5 minutos por lado hasta dorar.
2. Corta la cuajada en tajadas delgadas.
3. Sirve la arepa caliente con la cuajada encima y un hilo de miel.
"""),
    dict(name='Huevos pericos con arepa', meal_type='desayuno', time_min=12, servings=1, ingredients="""
2 huevos
1/2 tomate picado
2 cdas de cebolla larga picada
1 cdta de aceite
1 arepa de maíz
Sal al gusto
""", steps="""
1. Calienta el aceite en un sartén antiadherente y sofríe la cebolla y el tomate 3 minutos.
2. Bate los huevos con sal y viértelos en el sartén.
3. Revuelve a fuego medio-bajo hasta que cuajen sin secarse.
4. Tuesta la arepa aparte y sirve junto a los huevos.
"""),
    dict(name='Tamal tolimense en porción', meal_type='desayuno', time_min=40, servings=2, ingredients="""
1 taza de harina de maíz precocida
150 g de pechuga de pollo
1/2 taza de arveja
1 papa criolla en trozos
1 cda de aceite
1/4 taza de caldo de pollo
Sal al gusto
""", steps="""
1. Cocina la pechuga de pollo en el caldo 15 minutos, desmenuza y reserva el líquido.
2. Mezcla la harina de maíz con el caldo reservado, el aceite y sal hasta formar una masa suave.
3. Sobre una hoja de plátano o papel aluminio, extiende una porción de masa.
4. Rellena con el pollo desmenuzado, la arveja y la papa criolla; envuelve bien.
5. Cocina al vapor 25-30 minutos hasta que la masa esté firme.
6. Deja reposar 5 minutos antes de abrir y servir.
"""),
    dict(name='Almojábana horneada', meal_type='desayuno', time_min=25, servings=6, ingredients="""
200 g de cuajada
1 taza de harina de maíz precocida
1 huevo
1 cda de mantequilla
1 cdta de polvo de hornear
1 cda de azúcar
""", steps="""
1. Precalienta el horno a 190°C y engrasa una bandeja.
2. Desmenuza la cuajada con un tenedor hasta que quede cremosa.
3. Mezcla con la harina de maíz, el huevo, la mantequilla, el azúcar y el polvo de hornear hasta integrar.
4. Forma bolitas medianas y colócalas en la bandeja.
5. Hornea 15-18 minutos hasta que doren ligeramente por fuera.
"""),
    dict(name='Chocolate santafereño con queso', meal_type='desayuno', time_min=10, servings=1, ingredients="""
1 taza de leche
1/2 cuadro de chocolate de mesa
30 g de queso campesino
1 rebanada de pan blanco
""", steps="""
1. Calienta la leche con el chocolate de mesa a fuego medio, batiendo con molinillo o batidor hasta disolver.
2. Deja hervir suave 3 minutos sin dejar de batir para que espume.
3. Sirve caliente con el queso en trozos para remojar y el pan al lado.
"""),
    dict(name='Avena colombiana caliente', meal_type='desayuno', time_min=10, servings=1, ingredients="""
1 taza de leche
2 cdas de avena en hojuelas
1 cdta de panela raspada
1 astilla de canela
""", steps="""
1. Calienta la leche con la canela a fuego medio.
2. Agrega la avena y la panela raspada, revolviendo para que no se pegue.
3. Cocina 5 minutos a fuego suave hasta que espese ligeramente.
4. Retira la astilla de canela y sirve caliente.
"""),

    # ---------------------------- ALMUERZOS (14) ----------------------------
    dict(name='Ajiaco santafereño aligerado', meal_type='almuerzo', time_min=50, servings=2, ingredients="""
200 g de pechuga de pollo
2 papas criollas
1 papa pastusa en trozos
1/2 mazorca en trozos
1/4 taza de guascas frescas
1 cda de crema de leche
1/4 aguacate en tajadas
4 tazas de agua o caldo de pollo
Sal al gusto
""", steps="""
1. Cocina la pechuga de pollo en el agua o caldo 20 minutos hasta que esté tierna; desmenuza y reserva.
2. En el mismo caldo, agrega las papas criollas, la papa pastusa y la mazorca.
3. Cocina 25 minutos a fuego medio, deshaciendo parte de la papa criolla para espesar el caldo.
4. Añade las guascas los últimos 5 minutos.
5. Regresa el pollo desmenuzado a la olla y rectifica sal.
6. Sirve con una cucharada de crema de leche y el aguacate al lado.
"""),
    dict(name='Sancocho de gallina valluno', meal_type='almuerzo', time_min=60, servings=3, ingredients="""
400 g de gallina criolla en presas
1 plátano verde en trozos
1 yuca en trozos
1 mazorca en trozos
1 papa pastusa en trozos
1/4 taza de cebolla larga picada
1 diente de ajo
8 tazas de agua
Sal y comino al gusto
""", steps="""
1. En una olla grande, cocina la gallina con el ajo y el agua durante 30 minutos, retirando la espuma.
2. Agrega el plátano verde y la mazorca; cocina 15 minutos más.
3. Añade la yuca y la papa; cocina 15 minutos hasta que todo esté tierno.
4. Sazona con sal y comino y agrega la cebolla larga al final.
5. Sirve caliente con arroz blanco aparte.
"""),
    dict(name='Sancocho trifásico', meal_type='almuerzo', time_min=60, servings=3, ingredients="""
200 g de pechuga de pollo
150 g de carne de res magra
100 g de costilla de res
1 plátano verde en trozos
1 yuca en trozos
1 mazorca en trozos
1/4 taza de cilantro picado
8 tazas de agua
Sal al gusto
""", steps="""
1. Cocina la costilla y la carne de res en el agua 25 minutos, retirando la espuma.
2. Agrega el pollo y cocina 15 minutos más.
3. Añade el plátano, la yuca y la mazorca; cocina 20 minutos hasta ablandar.
4. Rectifica sal y espolvorea el cilantro antes de servir.
"""),
    dict(name='Bandeja paisa en porción real', meal_type='almuerzo', time_min=40, servings=1, ingredients="""
1/3 taza de fríjol rojo cocido
1/3 taza de arroz blanco cocido
70 g de carne molida de res
1 huevo
1/3 plátano maduro en tajadas
1 arepa de maíz pequeña
1/4 aguacate en tajadas
1 cdta de aceite
""", steps="""
1. Calienta el aceite y dora la carne molida sazonada con sal 6-8 minutos.
2. Hornea o cocina el plátano maduro en sartén antiadherente con muy poco aceite hasta dorar.
3. Fríe u hornea el huevo al gusto.
4. Calienta el arroz y los fríjoles por separado.
5. Sirve todo en un mismo plato: arroz, fríjoles, carne, huevo, plátano, arepa y aguacate.
"""),
    dict(name='Fríjoles antioqueños con garra', meal_type='almuerzo', time_min=45, servings=2, ingredients="""
1 taza de fríjol cargamanto cocido
100 g de carne de res magra en trozos
1/2 plátano verde en trozos
1/4 taza de cebolla picada
1 diente de ajo
1 cda de hogao
1 taza de agua
Sal al gusto
""", steps="""
1. Sofríe la cebolla y el ajo con el hogao 3 minutos.
2. Agrega la carne y dora 5 minutos.
3. Añade los fríjoles, el plátano y el agua; cocina tapado 25 minutos a fuego medio, revolviendo de vez en cuando.
4. Rectifica sal y sirve con arroz blanco aparte.
"""),
    dict(name='Arroz atollado', meal_type='almuerzo', time_min=40, servings=2, ingredients="""
1 taza de arroz blanco cocido
150 g de carne de res magra en trozos
100 g de papa criolla en trozos
1/4 taza de cebolla picada
1 diente de ajo
1 cda de hogao
2 tazas de caldo de res
Sal y comino al gusto
""", steps="""
1. Sofríe la cebolla, el ajo y el hogao 3 minutos.
2. Agrega la carne y dora 5 minutos.
3. Añade el caldo y la papa criolla; cocina 15 minutos.
4. Incorpora el arroz y cocina 8-10 minutos más hasta que quede cremoso, revolviendo seguido.
5. Sazona con sal y comino y sirve caliente.
"""),
    dict(name='Cazuela de mariscos costeña', meal_type='almuerzo', time_min=35, servings=2, ingredients="""
200 g de camarones
100 g de calamar
1/2 taza de leche de coco
1/4 taza de cebolla picada
1/2 pimentón picado
1 diente de ajo
1 cda de pasta de tomate
Sal al gusto
""", steps="""
1. Sofríe la cebolla, el pimentón y el ajo 3 minutos.
2. Agrega la pasta de tomate y cocina 2 minutos.
3. Añade la leche de coco y deja hervir suave 5 minutos.
4. Incorpora los camarones y el calamar; cocina 5-6 minutos hasta que estén firmes (no más, para que no queden duros).
5. Rectifica sal y sirve con arroz blanco.
"""),
    dict(name='Mote de queso cordobés', meal_type='almuerzo', time_min=35, servings=2, ingredients="""
1 ñame en trozos
100 g de queso costeño en cubos
1/4 taza de cebolla larga picada
1 diente de ajo
2 tazas de agua o caldo
Sal al gusto
""", steps="""
1. Cocina el ñame en el agua o caldo 20 minutos hasta ablandar.
2. Machaca parte del ñame contra la olla para espesar.
3. Agrega la cebolla y el ajo y cocina 5 minutos más.
4. Incorpora el queso costeño y deja derretir 3-4 minutos a fuego bajo.
5. Sirve caliente.
"""),
    dict(name='Sudado de pollo', meal_type='almuerzo', time_min=35, servings=2, ingredients="""
300 g de pechuga de pollo en presas
1 papa pastusa en trozos
1/2 taza de arveja
1/4 taza de cebolla picada
1/2 tomate picado
1 diente de ajo
1/2 taza de agua
Sal y comino al gusto
""", steps="""
1. Sofríe la cebolla, el tomate y el ajo 3-4 minutos hasta formar un guiso.
2. Agrega el pollo y dora por todos los lados 5 minutos.
3. Añade la papa, la arveja y el agua; tapa y cocina a fuego medio-bajo 20 minutos.
4. Sazona con sal y comino y sirve con arroz blanco.
"""),
    dict(name='Sudado de posta', meal_type='almuerzo', time_min=45, servings=2, ingredients="""
300 g de posta de res en trozos
1 papa pastusa en trozos
1/4 taza de cebolla picada
1/2 tomate picado
1 diente de ajo
1/2 taza de agua o caldo
Sal y comino al gusto
""", steps="""
1. Sofríe la cebolla, el tomate y el ajo hasta formar un guiso, 4 minutos.
2. Agrega la posta y sella por todos los lados.
3. Añade el agua o caldo, tapa y cocina a fuego bajo 30 minutos hasta ablandar.
4. Agrega la papa los últimos 15 minutos de cocción.
5. Rectifica sal y comino, sirve con arroz.
"""),
    dict(name='Pescado con patacón al horno', meal_type='almuerzo', time_min=30, servings=1, ingredients="""
200 g de mojarra o tilapia
1/2 plátano verde en tajadas
1 cdta de aceite
1/4 taza de cebolla en pluma
Limón al gusto
Sal al gusto
""", steps="""
1. Precalienta el horno o air fryer a 200°C.
2. Sazona el pescado con sal, limón y la cebolla en pluma.
3. Hornea el pescado 12-15 minutos hasta que esté cocido y firme.
4. Aparte, aplana las tajadas de plátano, pincélalas con el aceite y hornea 15 minutos volteando a mitad de cocción hasta dorar.
5. Sirve el pescado con el patacón al horno.
"""),
    dict(name='Arroz con pollo', meal_type='almuerzo', time_min=35, servings=2, ingredients="""
1 taza de arroz blanco cocido
200 g de pechuga de pollo desmenuzada
1/2 taza de arveja
1/2 zanahoria en cubos pequeños
1/4 taza de cebolla picada
1 cda de pasta de tomate
Sal al gusto
""", steps="""
1. Sofríe la cebolla con la pasta de tomate 3 minutos.
2. Agrega la zanahoria y la arveja; cocina 5 minutos.
3. Añade el pollo desmenuzado y mezcla bien.
4. Incorpora el arroz cocido y revuelve a fuego medio 4-5 minutos hasta integrar.
5. Rectifica sal y sirve caliente.
"""),
    dict(name='Mute santandereano', meal_type='almuerzo', time_min=50, servings=2, ingredients="""
150 g de carne de res magra en trozos
1/2 taza de garbanzo cocido
1/2 taza de arveja
1 papa pastusa en trozos
1/4 taza de cebolla picada
1 diente de ajo
4 tazas de caldo de res
Sal al gusto
""", steps="""
1. Cocina la carne en el caldo 20 minutos.
2. Agrega la papa, el garbanzo y la arveja; cocina 20 minutos más.
3. Sofríe aparte la cebolla y el ajo y agrégalos a la olla los últimos 5 minutos.
4. Rectifica sal y sirve bien caliente.
"""),
    dict(name='Encocado de pescado del Pacífico', meal_type='almuerzo', time_min=30, servings=2, ingredients="""
300 g de pescado blanco en trozos
1/2 taza de leche de coco
1/4 taza de cebolla picada
1/2 pimentón picado
1 diente de ajo
1/4 taza de cilantro picado
Sal al gusto
""", steps="""
1. Sofríe la cebolla, el pimentón y el ajo 3-4 minutos.
2. Agrega la leche de coco y deja hervir suave 5 minutos.
3. Incorpora el pescado y cocina 8-10 minutos hasta que esté cocido, sin revolver mucho para que no se deshaga.
4. Espolvorea el cilantro y sirve con arroz de coco o arroz blanco.
"""),

    # ---------------------------- CENAS (12) ----------------------------
    dict(name='Caldo de papa', meal_type='cena', time_min=25, servings=1, ingredients="""
2 papas pastusas en trozos
1/4 taza de cebolla larga picada
1 cda de cilantro picado
30 g de queso campesino
2 tazas de agua o caldo
Sal al gusto
""", steps="""
1. Cocina las papas en el agua o caldo 15 minutos hasta que estén blandas.
2. Machaca parte de la papa contra la olla para espesar el caldo.
3. Agrega la cebolla larga y cocina 3 minutos más.
4. Sirve caliente con el queso en trocitos y el cilantro por encima.
"""),
    dict(name='Sopa de verduras con carne', meal_type='cena', time_min=30, servings=1, ingredients="""
100 g de carne de res magra en trozos pequeños
1/2 zanahoria en cubos
1/2 taza de habichuela picada
1/4 taza de arveja
1 papa criolla en trozos
2 tazas de agua o caldo
Sal al gusto
""", steps="""
1. Cocina la carne en el agua o caldo 15 minutos.
2. Agrega la papa criolla, la zanahoria, la habichuela y la arveja.
3. Cocina 12-15 minutos más hasta que las verduras estén tiernas.
4. Rectifica sal y sirve caliente.
"""),
    dict(name='Ajiaco ligero de cena', meal_type='cena', time_min=35, servings=1, ingredients="""
120 g de pechuga de pollo desmenuzada
2 papas criollas
1/4 mazorca en trozos
2 cdas de guascas frescas
1 taza de agua o caldo de pollo
Sal al gusto
""", steps="""
1. Cocina las papas criollas y la mazorca en el agua o caldo 15 minutos, deshaciendo un poco la papa para espesar.
2. Agrega el pollo desmenuzado y las guascas.
3. Cocina 5 minutos más y rectifica sal.
4. Sirve caliente, sin crema, para una cena más liviana.
"""),
    dict(name='Pollo sudado ligero', meal_type='cena', time_min=30, servings=1, ingredients="""
150 g de pechuga de pollo en presas
1/4 taza de cebolla picada
1/2 tomate picado
1 diente de ajo
1/4 taza de agua
Sal y comino al gusto
""", steps="""
1. Sofríe la cebolla, el tomate y el ajo 3 minutos.
2. Agrega el pollo y sella por ambos lados.
3. Añade el agua, tapa y cocina a fuego medio-bajo 15-18 minutos hasta que esté bien cocido.
4. Sazona con sal y comino; sirve con ensalada verde.
"""),
    dict(name='Sopa de lentejas', meal_type='cena', time_min=25, servings=1, ingredients="""
1 taza de lenteja cocida
1/4 taza de cebolla picada
1/2 zanahoria en cubos
1 diente de ajo
1 1/2 tazas de agua o caldo
Sal al gusto
""", steps="""
1. Sofríe la cebolla, la zanahoria y el ajo 3-4 minutos.
2. Agrega las lentejas y el agua o caldo.
3. Cocina 12-15 minutos a fuego medio hasta que espese ligeramente.
4. Rectifica sal y sirve caliente.
"""),
    dict(name='Crema de ahuyama', meal_type='cena', time_min=25, servings=1, ingredients="""
1 1/2 tazas de ahuyama en trozos
1/4 taza de cebolla picada
1 diente de ajo
1/4 taza de leche
1 taza de agua o caldo
Sal al gusto
""", steps="""
1. Cocina la ahuyama con la cebolla y el ajo en el agua o caldo 15 minutos hasta ablandar.
2. Licúa todo hasta obtener una crema suave.
3. Regresa a la olla, agrega la leche y calienta 3 minutos sin dejar hervir fuerte.
4. Rectifica sal y sirve caliente.
"""),
    dict(name='Sopa de arroz con pollo', meal_type='cena', time_min=25, servings=1, ingredients="""
1/2 taza de arroz blanco cocido
100 g de pechuga de pollo desmenuzada
1/4 taza de cebolla larga picada
1/2 zanahoria en cubos pequeños
1 1/2 tazas de caldo de pollo
Sal al gusto
""", steps="""
1. Calienta el caldo y agrega la zanahoria; cocina 8 minutos.
2. Añade el arroz cocido y el pollo desmenuzado.
3. Cocina 5 minutos más hasta que todo esté caliente y el arroz suelte un poco de almidón.
4. Espolvorea la cebolla larga y sirve.
"""),
    dict(name='Trucha al ajillo del Eje Cafetero', meal_type='cena', time_min=20, servings=1, ingredients="""
1 filete de trucha (200 g)
1 diente de ajo picado
1 cdta de aceite de oliva
1 cda de perejil picado
Limón al gusto
Sal al gusto
""", steps="""
1. Sazona la trucha con sal y limón.
2. Calienta el aceite en un sartén y dora el ajo 30 segundos sin quemar.
3. Agrega la trucha y cocina 3-4 minutos por lado hasta que esté firme.
4. Espolvorea perejil fresco y sirve con ensalada.
"""),
    dict(name='Mojarra al vapor con ensalada', meal_type='cena', time_min=25, servings=1, ingredients="""
1 mojarra entera (250 g)
1/4 taza de cebolla en pluma
1/2 tomate en rodajas
Limón al gusto
1 taza de lechuga
Sal al gusto
""", steps="""
1. Sazona la mojarra por dentro y por fuera con sal y limón.
2. Coloca la cebolla y el tomate sobre el pescado y envuelve en papel aluminio.
3. Cocina al vapor o al horno a 190°C durante 18-20 minutos hasta que esté cocida.
4. Sirve con la lechuga fresca al lado.
"""),
    dict(name='Pechuga a la plancha con patacón al horno', meal_type='cena', time_min=25, servings=1, ingredients="""
150 g de pechuga de pollo
1/2 plátano verde en tajadas
1 cdta de aceite
Sal y limón al gusto
""", steps="""
1. Sazona la pechuga con sal y limón y cocina en plancha caliente 4-5 minutos por lado.
2. Aplana las tajadas de plátano, pincela con el aceite y hornea a 200°C 15 minutos, volteando a mitad de cocción.
3. Sirve la pechuga con el patacón al horno y ensalada si se desea.
"""),
    dict(name='Sopa de guineo santandereana', meal_type='cena', time_min=25, servings=1, ingredients="""
1 guineo verde en trozos
80 g de carne de res magra en trozos pequeños
1/4 taza de cebolla picada
1 diente de ajo
1 1/2 tazas de agua o caldo
Sal al gusto
""", steps="""
1. Sofríe la cebolla y el ajo 2 minutos.
2. Agrega la carne y dora 3-4 minutos.
3. Añade el agua o caldo y el guineo; cocina 15-18 minutos hasta que el guineo esté tierno.
4. Rectifica sal y sirve caliente.
"""),
    dict(name='Bistec a caballo en porción', meal_type='cena', time_min=15, servings=1, ingredients="""
120 g de posta de res delgada
1 huevo
1/4 taza de cebolla en pluma
1 cdta de aceite
Sal al gusto
""", steps="""
1. Sazona la posta con sal y cocina en sartén caliente con la mitad del aceite, 3 minutos por lado.
2. Retira y en el mismo sartén sofríe la cebolla 2 minutos.
3. Con el aceite restante, fríe el huevo al gusto.
4. Sirve el bistec con la cebolla encima y el huevo montado arriba.
"""),

    # ---------------------------- SNACKS (9) ----------------------------
    dict(name='Patacón con hogao al horno', meal_type='snack', time_min=20, servings=1, ingredients="""
1/2 plátano verde en tajadas
1 cda de hogao
1 cdta de aceite
Sal al gusto
""", steps="""
1. Precalienta el horno o air fryer a 200°C.
2. Aplana las tajadas de plátano y pincélalas con el aceite.
3. Hornea 15 minutos, volteando a mitad de cocción, hasta dorar y crocante.
4. Sirve caliente con el hogao encima.
"""),
    dict(name='Aborrajado al horno', meal_type='snack', time_min=25, servings=1, ingredients="""
1/2 plátano maduro
30 g de queso campesino
1 huevo
2 cdas de harina de trigo
Aceite en spray
""", steps="""
1. Precalienta el horno a 190°C.
2. Corta el plátano maduro a lo largo y rellena con el queso.
3. Pasa por harina y luego por huevo batido.
4. Coloca en bandeja, rocía con aceite en spray y hornea 15-18 minutos volteando a la mitad, hasta dorar.
"""),
    dict(name='Empanada de pipián en air fryer', meal_type='snack', time_min=20, servings=1, ingredients="""
1 empanada grande de masa de maíz rellena de pipián
Aceite en spray
Ají al gusto
""", steps="""
1. Precalienta el air fryer a 190°C.
2. Coloca la empanada en la canasta y rocía con aceite en spray.
3. Cocina 10 minutos, voltea con cuidado y cocina 5-8 minutos más hasta dorar.
4. Sirve con ají al gusto.
"""),
    dict(name='Carimañola', meal_type='snack', time_min=30, servings=1, ingredients="""
110 g de yuca cocida
25 g de queso costeño
1 huevo
Aceite en spray
Sal al gusto
""", steps="""
1. Machaca la yuca cocida caliente hasta formar una masa suave; sazona con sal.
2. Forma un óvalo con la masa y rellena con el queso costeño.
3. Sella bien la forma de carimañola.
4. Pasa por huevo batido, rocía con aceite en spray y hornea o cocina en air fryer a 200°C 12-15 minutos hasta dorar.
"""),
    dict(name='Pandebono', meal_type='snack', time_min=25, servings=4, ingredients="""
150 g de cuajada
1 taza de harina de maíz precocida
1 huevo
30 g de queso costeño rallado
1 cdta de polvo de hornear
""", steps="""
1. Precalienta el horno a 190°C.
2. Desmenuza la cuajada y mezcla con el queso rallado.
3. Agrega la harina de maíz, el huevo y el polvo de hornear; amasa hasta integrar.
4. Forma bolitas u óvalos y colócalos en una bandeja.
5. Hornea 15-18 minutos hasta que doren por fuera.
"""),
    dict(name='Mazamorra con panela', meal_type='snack', time_min=30, servings=2, ingredients="""
1/2 taza de maíz trillado cocido
1 taza de leche
1 cda de panela raspada
1 astilla de canela
""", steps="""
1. Cocina el maíz trillado en agua hasta que esté muy blando (o usa maíz ya cocido).
2. Agrega la leche, la panela y la canela; cocina a fuego bajo 8-10 minutos revolviendo.
3. Retira la canela y sirve tibia o fría.
"""),
    dict(name='Salpicón de frutas', meal_type='snack', time_min=15, servings=2, ingredients="""
1/2 taza de piña picada
1/2 taza de papaya picada
1/2 banano en rodajas
1/2 taza de sandía picada
1/2 taza de agua
1 cdta de miel
""", steps="""
1. Pica todas las frutas en cubos pequeños.
2. Mezcla en un bol con el agua y la miel.
3. Refrigera 10 minutos antes de servir bien frío.
"""),
    dict(name='Lulada vallecaucana', meal_type='snack', time_min=10, servings=1, ingredients="""
2 lulos
1 taza de agua
1 cdta de azúcar
Hielo al gusto
""", steps="""
1. Pela los lulos y licúa la pulpa con el agua unos segundos (sin triturar del todo las semillas).
2. Cuela ligeramente si se prefiere menos pulpa, o deja entera al estilo tradicional.
3. Endulza con el azúcar y sirve bien fría con hielo.
"""),
    dict(name='Chontaduro con miel', meal_type='snack', time_min=5, servings=1, ingredients="""
2 chontaduros cocidos
1 cdta de miel
Sal al gusto
""", steps="""
1. Pela los chontaduros cocidos y córtalos por la mitad, retirando la semilla.
2. Sirve con un hilo de miel y una pizca de sal al gusto, al estilo tradicional del Pacífico.
"""),
]


def main():
    existing_names = load_existing_names()
    seen_names = set()
    out = []
    for r in RECIPES:
        display_ings, parsed_ings = parse_ingredients_cell(r['ingredients'])
        steps = split_steps(r['steps'])
        servings = r['servings'] or infer_servings(parsed_ings)
        macros, stats = sum_macros(parsed_ings, servings)
        match_names = [p['match_name'] for p in parsed_ings]
        tags = derive_tags(macros['kcal'], macros['protein'], r['time_min'], match_names,
                            is_colombian=True)
        nm = norm_name(r['name'])
        if nm in existing_names or nm in seen_names:
            print(f"SALTADA (nombre duplicado): {r['name']}")
            continue
        seen_names.add(nm)
        confidence = 'alta' if stats['match_ratio'] >= 0.7 else 'revisar'
        out.append({
            'name': r['name'], 'meal_type': r['meal_type'], 'ingredients': display_ings,
            'steps': steps, 'tags': tags, 'kcal': macros['kcal'], 'protein': macros['protein'],
            'time_min': r['time_min'], 'carbs': macros['carbs'], 'fat': macros['fat'],
            'sugar': macros['sugar'], 'fiber': macros['fiber'], 'image_url': None,
            '_source': 'colombianas (redactadas a mano, GABA/TCAC)', '_confidence': confidence,
            '_stats': stats,
        })

    by_type = {}
    for r in out:
        by_type[r['meal_type']] = by_type.get(r['meal_type'], 0) + 1
    print(f'Total recetas colombianas: {len(out)}')
    print('Por tipo:', by_type)
    low = [r for r in out if r['_confidence'] == 'revisar']
    print(f'Confianza baja: {len(low)}')
    for r in low:
        print(f"  - {r['name']}: match_ratio={r['_stats']['match_ratio']}")

    with open(OUT_JSON, 'w', encoding='utf-8') as f:
        json.dump(out, f, ensure_ascii=False, indent=1)
    print(f'Escrito: {OUT_JSON}')


if __name__ == '__main__':
    main()
