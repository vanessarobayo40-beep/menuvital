# -*- coding: utf-8 -*-
"""
MenúVital — Conversión de medidas caseras a gramos/mililitros.

Dos niveles:
1. UNIT_GRAMS: gramos por unidad de medida "genérica" (cucharada, taza, etc.)
   cuando el ingrediente es denso/líquido tipo agua (aceites, líquidos, polvos finos).
2. UNIT_GRAMS_BY_CLASS: overrides por densidad — algunas medidas caseras pesan
   distinto según el ingrediente (1 taza de harina ≠ 1 taza de leche).
3. WHOLE_UNIT_GRAMS: peso aproximado de "1 unidad" para ingredientes contables
   (1 aguacate, 1 huevo, 1 banano...), fuente TCAC/USDA para tamaño porción estándar.
"""

# Gramos por unidad de medida genérica (referencia: líquidos/densidad ~agua).
UNIT_GRAMS = {
    'g': 1, 'gr': 1, 'gramo': 1, 'gramos': 1,
    'kg': 1000, 'kilo': 1000, 'kilos': 1000,
    'ml': 1, 'mililitro': 1, 'mililitros': 1,
    'l': 1000, 'litro': 1000, 'litros': 1,
    'taza': 240, 'tazas': 240,
    'cda': 15, 'cdas': 15, 'cucharada': 15, 'cucharadas': 15, 'c/p': 15, 'cucharón': 15,
    'cdta': 5, 'cdtas': 5, 'cucharadita': 5, 'cucharaditas': 5, 'c/c': 5,
    'pizca': 0.5, 'pizcas': 0.5,
    'chorrito': 5, 'chorro': 8,
    'puñado': 30, 'puñados': 30,
    'diente': 5, 'dientes': 5,
    'rebanada': 30, 'rebanadas': 30, 'tajada': 30, 'tajadas': 30,
    'rodaja': 10, 'rodajas': 10,
    'hoja': 2, 'hojas': 2,
    'ramita': 2, 'ramitas': 2, 'rama': 2,
}

# Overrides por clase de ingrediente para tazas/cucharadas (densidad distinta al agua).
UNIT_GRAMS_BY_CLASS = {
    'taza': {
        'harina de trigo': 120, 'harina integral': 120, 'harina de avena': 90,
        'harina de maíz precocida': 130, 'harina de almendra': 96, 'harina de coco': 112,
        'avena': 90, 'arroz blanco cocido': 195, 'arroz integral cocido': 195,
        'quinua cocida': 185, 'lenteja cocida': 200, 'fríjol rojo cocido': 200,
        'fríjol cargamanto cocido': 200, 'fríjol negro cocido': 200, 'garbanzo cocido': 165,
        'queso mozzarella': 112, 'queso parmesano': 100, 'coco rallado': 80,
        'granola': 100, 'pasas': 145, 'nueces': 100, 'almendras': 95, 'maní': 145,
        'espinaca': 30, 'lechuga': 55, 'repollo': 90, 'cilantro': 16, 'perejil': 16,
        'pan rallado': 108, 'panko': 50, 'azúcar': 200, 'panela': 200,
    },
    'cda': {
        'harina de trigo': 8, 'harina integral': 8, 'harina de avena': 6,
        'mantequilla de maní': 16, 'mantequilla': 14, 'aceite de coco': 13,
        'miel': 21, 'panela': 15, 'azúcar': 12, 'cacao en polvo': 5,
        'coco rallado': 5, 'semillas de chía': 10, 'semillas de lino': 10,
        'queso parmesano': 5, 'crema de leche': 15,
    },
    'cdta': {
        'polvo de hornear': 4, 'maicena': 3, 'canela': 2.6, 'cacao en polvo': 2,
        'sal': 6, 'azúcar': 4,
    },
}

# Peso típico de "1 unidad" para ingredientes contables (fuente: TCAC/USDA, porción estándar).
WHOLE_UNIT_GRAMS = {
    'huevo': 50, 'clara de huevo': 33, 'yema de huevo': 17,
    'aguacate': 200, 'banano': 120, 'guineo': 120, 'manzana': 180, 'pera': 180,
    'naranja': 180, 'mandarina': 100, 'limón': 60, 'durazno': 150, 'kiwi': 75,
    'papa': 150, 'papa criolla': 40, 'papa pastusa': 150, 'batata': 130, 'camote': 130,
    'tomate': 120, 'tomate cherry': 15, 'cebolla': 110, 'cebolla larga': 25,
    'cebolla roja': 110, 'ajo': 5, 'zanahoria': 70, 'pepino': 300, 'pimentón': 120,
    'calabacín': 200, 'berenjena': 250, 'remolacha': 100, 'rábano': 10,
    'mazorca': 150, 'maíz tierno': 150, 'arepa de maíz': 90, 'pan blanco': 30,
    'pan integral': 35, 'pan árabe integral': 60, 'plátano maduro': 150,
    'plátano verde': 150, 'yuca': 200, 'arracacha': 150, 'ñame': 150,
    'chorizo': 60, 'muslo de pollo': 120, 'pechuga de pollo': 180,
    'salmón': 150, 'tilapia': 180, 'mojarra': 250, 'trucha': 200,
    'coco': 400, 'granadilla': 60, 'maracuyá': 40, 'lulo': 60, 'guayaba': 90,
    'chontaduro': 60, 'sardinas': 90, 'proteína en polvo (scoop)': 30,
    'tortilla de trigo': 45, 'pimentón': 120, 'ajo': 5,
}

# Especias y saborizantes: su perfil nutricional por 100 g es engañoso (son
# deshidratados y concentrados) pero en la práctica se usan en gramos, nunca
# en porciones — así que NUNCA deben recibir la porción "típica" de 60-150 g
# que aplica default_portion_grams() al resto de ingredientes sin cantidad.
SPICE_DEFAULT_GRAMS = {
    'sal': 0, 'pimienta': 1, 'comino': 1, 'orégano': 1, 'canela': 2,
    'jengibre': 2, 'cúrcuma': 1, 'paprika en polvo': 1, 'vainilla': 1,
    'polvo de hornear': 3, 'maicena': 5, 'curry en polvo': 2, 'hierbabuena': 2,
    'cilantro': 5, 'perejil': 5, 'albahaca': 3, 'ajo': 5,
}
