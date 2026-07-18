<?php
/**
 * MenúVital — Base curada de recetas saludables colombianas/latinas
 * Formato por receta:
 * [nombre, tipo (desayuno|almuerzo|cena|snack), ingredientes ["item|cantidad por porción"],
 *  pasos [], tags [], kcal aprox/porción, proteína g, tiempo min,
 *  carbohidratos g, grasa g, azúcar g, fibra g, foto (URL Pexels) — todo por porción, estimado]
 * Cantidades pensadas para 1 porción; la app las multiplica por número de personas.
 */

return [

// ==================== DESAYUNOS ====================
['Huevos pericos con arepa', 'desayuno',
 ['huevos|2 unidades', 'tomate|1 unidad', 'cebolla larga|1 tallo', 'harina de maíz|1/2 taza', 'aceite|1 cdta'],
 ['Prepara la arepa con harina de maíz, agua y una pizca de sal; ásala por lado y lado.', 'Sofríe el tomate y la cebolla larga picados 3 minutos.', 'Agrega los huevos batidos y revuelve a fuego medio hasta que cuajen.', 'Sirve con la arepa caliente.'],
 ['tradicional', 'alto en proteína', 'económico'], 380, 18, 20, 40, 20, 5, 4, 'https://images.pexels.com/photos/7696496/pexels-photo-7696496.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Avena caliente con canela y banano', 'desayuno',
 ['avena en hojuelas|1/2 taza', 'leche|1 taza', 'banano|1 unidad', 'canela|1 pizca', 'miel|1 cdta'],
 ['Cocina la avena con la leche a fuego medio 5-7 minutos, revolviendo.', 'Endulza con miel y agrega canela.', 'Sirve con rodajas de banano encima.'],
 ['vegetariano', 'económico', 'rápido'], 340, 12, 10, 55, 10, 20, 6, 'https://images.pexels.com/photos/7655885/pexels-photo-7655885.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arepa rellena de queso y aguacate', 'desayuno',
 ['harina de maíz|3/4 taza', 'queso campesino|50 g', 'aguacate|1/2 unidad', 'tomate|1/2 unidad'],
 ['Prepara y asa la arepa hasta dorar.', 'Ábrela con cuidado y rellénala con queso para que se derrita.', 'Agrega aguacate en láminas y tomate en rodajas.'],
 ['vegetariano', 'tradicional'], 390, 14, 20, 45, 25, 5, 5, 'https://images.pexels.com/photos/5864741/pexels-photo-5864741.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Calentado ligero de fríjoles con huevo', 'desayuno',
 ['fríjol rojo|1/2 taza cocido', 'arroz integral|1/2 taza cocido', 'huevos|1 unidad', 'cebolla|1/4 unidad', 'tomate|1/2 unidad', 'aceite|1 cdta'],
 ['Sofríe cebolla y tomate picados (hogao rápido).', 'Agrega el arroz y los fríjoles cocidos; mezcla y calienta bien.', 'Sirve con un huevo frito en poco aceite encima.'],
 ['tradicional', 'alto en proteína', 'económico'], 420, 19, 15, 50, 15, 5, 8, 'https://images.pexels.com/photos/34130182/pexels-photo-34130182.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Yogur griego con granola y fresas', 'desayuno',
 ['yogur griego|1 taza', 'granola|1/4 taza', 'fresa|6 unidades', 'miel|1 cdta'],
 ['Sirve el yogur en un bowl.', 'Agrega la granola, las fresas en mitades y un hilo de miel.'],
 ['vegetariano', 'rápido', 'alto en proteína'], 320, 18, 5, 35, 15, 20, 4, 'https://images.pexels.com/photos/566564/pexels-photo-566564.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Batido de papaya y avena', 'desayuno',
 ['papaya|1 taza picada', 'avena en hojuelas|2 cdas', 'leche|1 taza', 'miel|1 cdta'],
 ['Licúa todos los ingredientes 1 minuto.', 'Sirve frío; digestivo y llenador.'],
 ['vegetariano', 'rápido', 'ligero'], 250, 9, 5, 40, 8, 25, 5, 'https://images.pexels.com/photos/879202/pexels-photo-879202.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Omelette de espinaca y champiñones', 'desayuno',
 ['huevos|2 unidades', 'espinaca|1 taza', 'champiñones|1/2 taza', 'queso campesino|30 g', 'aceite|1 cdta'],
 ['Saltea los champiñones y la espinaca 3 minutos.', 'Vierte los huevos batidos y cocina a fuego bajo.', 'Agrega el queso, dobla el omelette y sirve.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 310, 22, 15, 10, 20, 2, 3, 'https://images.pexels.com/photos/5639284/pexels-photo-5639284.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostadas integrales con aguacate y huevo', 'desayuno',
 ['pan integral|2 tajadas', 'aguacate|1/2 unidad', 'huevos|1 unidad', 'limón|1/2 unidad'],
 ['Tuesta el pan.', 'Machaca el aguacate con limón, sal y pimienta; úntalo en las tostadas.', 'Corona con huevo cocido o pochado.'],
 ['vegetariano', 'rápido'], 350, 15, 10, 30, 22, 2, 6, 'https://images.pexels.com/photos/3920506/pexels-photo-3920506.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Changua bogotana ligera', 'desayuno',
 ['leche|1 taza', 'huevos|1 unidad', 'cebolla larga|1 tallo', 'cilantro|1 rama', 'pan integral|1 tajada'],
 ['Hierve la leche con media taza de agua y la cebolla larga picada.', 'Agrega el huevo sin batir y deja cuajar 3 minutos.', 'Sirve con cilantro picado y pan integral tostado.'],
 ['tradicional', 'ligero', 'económico'], 280, 14, 15, 35, 12, 10, 4, 'https://images.pexels.com/photos/16149475/pexels-photo-16149475.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pancakes de avena y banano', 'desayuno',
 ['avena en hojuelas|1/2 taza', 'banano|1 unidad', 'huevos|1 unidad', 'canela|1 pizca', 'miel|1 cdta'],
 ['Licúa la avena, el banano, el huevo y la canela.', 'Vierte porciones en sartén antiadherente a fuego medio; voltea cuando burbujee.', 'Sirve con un toque de miel y fruta.'],
 ['vegetariano', 'económico'], 330, 13, 15, 45, 15, 20, 6, 'https://images.pexels.com/photos/30882594/pexels-photo-30882594.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevos revueltos con queso y tomate', 'desayuno',
 ['huevos|2 unidades', 'queso campesino|40 g', 'tomate|1/2 unidad', 'aceite|1 cdta'],
 ['Bate los huevos y revuélvelos a fuego bajo.', 'Antes de que cuajen del todo, agrega el queso en cubos y el tomate picado.', 'Sirve jugoso.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 300, 21, 10, 10, 20, 5, 2, 'https://images.pexels.com/photos/30855418/pexels-photo-30855418.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bowl tropical de frutas con chía', 'desayuno',
 ['papaya|1/2 taza', 'mango|1/2 taza', 'banano|1/2 unidad', 'yogur natural|1/2 taza', 'semillas de chía|1 cda'],
 ['Pica las frutas en cubos y colócalas en un bowl.', 'Cubre con yogur y espolvorea la chía.'],
 ['vegetariano', 'ligero', 'rápido'], 270, 8, 10, 50, 8, 30, 8, 'https://images.pexels.com/photos/16744880/pexels-photo-16744880.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arepa con pollo desmechado', 'desayuno',
 ['harina de maíz|3/4 taza', 'pechuga de pollo|80 g', 'tomate|1/2 unidad', 'cebolla|1/4 unidad', 'aceite|1 cdta'],
 ['Cocina y desmecha la pechuga (o usa pollo que tengas listo).', 'Prepara un hogao con tomate y cebolla; mezcla con el pollo.', 'Sirve sobre la arepa asada.'],
 ['alto en proteína', 'tradicional'], 400, 26, 25, 30, 25, 5, 4, 'https://images.pexels.com/photos/13191276/pexels-photo-13191276.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Smoothie verde de piña y jengibre', 'desayuno',
 ['espinaca|1 taza', 'piña|1 taza', 'jengibre|1 trocito', 'agua|1 taza', 'semillas de chía|1 cda'],
 ['Licúa todo con hielo 1 minuto.', 'Tómalo fresco, ideal para empezar el día con energía.'],
 ['vegetariano', 'ligero', 'sin gluten'], 180, 4, 5, 30, 6, 20, 5, 'https://images.pexels.com/photos/10003793/pexels-photo-10003793.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Caldo de papa con huevo', 'desayuno',
 ['papa|1 unidad', 'huevos|1 unidad', 'cebolla larga|1 tallo', 'cilantro|1 rama'],
 ['Hierve la papa en cubos con la cebolla larga 15 minutos.', 'Agrega el huevo entero y deja cuajar 3 minutos.', 'Sirve con cilantro fresco.'],
 ['tradicional', 'ligero', 'económico'], 240, 11, 20, 30, 10, 5, 4, 'https://images.pexels.com/photos/35408993/pexels-photo-35408993.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tajadas de maduro con queso y huevo', 'desayuno',
 ['plátano maduro|1/2 unidad', 'queso campesino|40 g', 'huevos|1 unidad'],
 ['Hornea o asa las tajadas de maduro hasta dorar (sin freír).', 'Prepara el huevo a tu gusto en sartén antiadherente.', 'Sirve las tajadas con el queso y el huevo.'],
 ['tradicional', 'vegetariano', 'sin gluten'], 370, 16, 20, 40, 20, 15, 6, 'https://images.pexels.com/photos/37228247/pexels-photo-37228247.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Avena remojada (overnight) con mango', 'desayuno',
 ['avena en hojuelas|1/2 taza', 'leche|3/4 taza', 'yogur natural|1/4 taza', 'mango|1/2 taza', 'semillas de chía|1 cda'],
 ['La noche anterior mezcla avena, leche, yogur y chía en un frasco.', 'Refrigera toda la noche.', 'En la mañana agrega el mango picado y listo.'],
 ['vegetariano', 'rápido', 'ligero'], 350, 14, 5, 55, 12, 20, 6, 'https://images.pexels.com/photos/5150202/pexels-photo-5150202.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sándwich integral de pavo y queso', 'desayuno',
 ['pan integral|2 tajadas', 'jamón de pavo|2 tajadas', 'queso campesino|30 g', 'tomate|1/2 unidad', 'lechuga|2 hojas'],
 ['Arma el sándwich con todos los ingredientes.', 'Pásalo por sartén o sanduchera si lo quieres caliente.'],
 ['alto en proteína', 'rápido'], 330, 20, 10, 30, 15, 5, 4, 'https://images.pexels.com/photos/30876302/pexels-photo-30876302.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Wrap de huevo y aguacate', 'desayuno',
 ['pan árabe integral|1 unidad', 'huevos|2 unidades', 'aguacate|1/4 unidad', 'tomate|1/2 unidad'],
 ['Revuelve los huevos a fuego bajo.', 'Unta el pan árabe con aguacate machacado.', 'Rellena con el huevo y tomate; enrolla y sirve.'],
 ['alto en proteína', 'rápido'], 360, 19, 10, 25, 20, 5, 7, 'https://images.pexels.com/photos/4491396/pexels-photo-4491396.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Batido de mora y avena', 'desayuno',
 ['mora|1 taza', 'avena en hojuelas|2 cdas', 'leche|1 taza', 'miel|1 cdta'],
 ['Licúa todo 1 minuto y sirve frío.'],
 ['vegetariano', 'rápido', 'económico'], 240, 9, 5, 40, 8, 25, 4, 'https://images.pexels.com/photos/19573135/pexels-photo-19573135.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Quinua caliente con leche y canela', 'desayuno',
 ['quinua|1/2 taza', 'leche|1 taza', 'canela|1 pizca', 'miel|1 cdta', 'manzana|1/2 unidad'],
 ['Cocina la quinua lavada en la leche con canela 15 minutos.', 'Endulza con miel y sirve con manzana picada.'],
 ['vegetariano', 'sin gluten', 'alto en proteína'], 330, 12, 20, 50, 10, 20, 5, 'https://images.pexels.com/photos/6259288/pexels-photo-6259288.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevos horneados en salsa de tomate', 'desayuno',
 ['huevos|2 unidades', 'tomate|2 unidades', 'cebolla|1/4 unidad', 'pimentón|1/4 unidad', 'pan integral|1 tajada'],
 ['Sofríe cebolla, pimentón y tomate picados hasta formar una salsa.', 'Haz dos huecos y rompe los huevos ahí; tapa y cocina 5 minutos.', 'Sirve con pan integral para acompañar.'],
 ['alto en proteína', 'ligero'], 320, 17, 20, 20, 18, 5, 3, 'https://images.pexels.com/photos/29385750/pexels-photo-29385750.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Migas de arepa con huevo', 'desayuno',
 ['arepas|1 unidad', 'huevos|2 unidades', 'cebolla larga|1 tallo', 'tomate|1/2 unidad', 'aceite|1 cdta'],
 ['Desmenuza la arepa en trozos pequeños.', 'Sofríe cebolla y tomate, agrega la arepa y luego los huevos batidos.', 'Revuelve hasta que cuaje; un clásico santandereano.'],
 ['tradicional', 'económico', 'alto en proteína'], 390, 17, 15, 35, 20, 5, 4, 'https://images.pexels.com/photos/36116434/pexels-photo-36116434.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Fruta picada con kumis y granola', 'desayuno',
 ['kumis|1 taza', 'granola|1/4 taza', 'banano|1/2 unidad', 'fresa|4 unidades', 'miel|1 cdta'],
 ['Pica las frutas y sírvelas con el kumis.', 'Agrega granola y miel por encima.'],
 ['vegetariano', 'rápido'], 300, 11, 5, 45, 12, 25, 5, 'https://images.pexels.com/photos/11182247/pexels-photo-11182247.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostada francesa integral con fresas', 'desayuno',
 ['pan integral|2 tajadas', 'huevos|1 unidad', 'leche|1/4 taza', 'canela|1 pizca', 'fresa|5 unidades', 'miel|1 cdta'],
 ['Bate huevo, leche y canela; remoja el pan.', 'Dora en sartén antiadherente por ambos lados.', 'Sirve con fresas y un hilo de miel.'],
 ['vegetariano', 'económico'], 340, 14, 15, 50, 15, 20, 5, 'https://images.pexels.com/photos/36904810/pexels-photo-36904810.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Batido de banano y mantequilla de maní', 'desayuno',
 ['banano|1 unidad', 'mantequilla de maní|1 cda', 'leche|1 taza', 'avena en hojuelas|1 cda', 'canela|1 pizca'],
 ['Licúa todo 1 minuto.', 'Perfecto antes de entrenar o para un desayuno rápido.'],
 ['vegetariano', 'alto en proteína', 'rápido'], 380, 15, 5, 55, 20, 25, 6, 'https://images.pexels.com/photos/32946783/pexels-photo-32946783.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Chocolate santafereño saludable', 'desayuno',
 ['chocolate de mesa|1 pastilla pequeña', 'leche|1 taza', 'queso campesino|40 g', 'pan integral|1 tajada'],
 ['Prepara el chocolate en la leche caliente, batiendo hasta espumar.', 'Acompaña con queso (¡adentro si te gusta!) y pan integral.', 'Porción moderada: un gusto tradicional sin exceso.'],
 ['tradicional', 'vegetariano'], 360, 15, 15, 45, 18, 20, 4, 'https://images.pexels.com/photos/17506136/pexels-photo-17506136.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Omelette caprese de tomate y albahaca', 'desayuno',
 ['huevos|2 unidades', 'tomate cherry|5 unidades', 'queso mozarella|30 g', 'albahaca|4 hojas', 'aceite de oliva|1 cdta'],
 ['Bate los huevos y viértelos en sartén a fuego bajo.', 'Agrega los tomates en mitades, el queso y la albahaca.', 'Dobla y sirve cuando el queso se derrita.'],
 ['vegetariano', 'ligero', 'sin gluten'], 310, 20, 12, 20, 20, 5, 2, 'https://images.pexels.com/photos/1487509/pexels-photo-1487509.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Bandeja paisa en versión ligera', 'almuerzo',
 ['fríjol rojo|3/4 taza cocido', 'arroz integral|1/2 taza cocido', 'huevos|1 unidad', 'aguacate|1/4 unidad', 'plátano maduro|1/3 unidad', 'carne molida|80 g', 'tomate|1/2 unidad', 'cebolla|1/4 unidad'],
 ['Guisa la carne molida con tomate y cebolla.', 'Hornea las tajadas de maduro en lugar de freírlas.', 'Sirve fríjoles, arroz, carne, huevo, maduro y aguacate en porciones moderadas.'],
 ['tradicional', 'alto en proteína'], 620, 35, 40, 70, 30, 10, 10, 'https://images.pexels.com/photos/28902907/pexels-photo-28902907.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ajiaco santafereño aligerado', 'almuerzo',
 ['pechuga de pollo|120 g', 'papa criolla|4 unidades', 'papa|1 unidad', 'mazorca|1/2 unidad', 'guascas|1 puñado', 'cebolla larga|1 tallo', 'aguacate|1/4 unidad'],
 ['Cocina el pollo con la cebolla larga; retíralo y desméchalo.', 'En el mismo caldo cocina las papas y la mazorca 25 minutos.', 'Agrega las guascas al final, vuelve a poner el pollo.', 'Sirve con aguacate (crema y alcaparras opcionales, con moderación).'],
 ['tradicional', 'alto en proteína', 'sin gluten'], 480, 32, 50, 60, 20, 5, 8, 'https://images.pexels.com/photos/37039787/pexels-photo-37039787.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sancocho de pollo ligero', 'almuerzo',
 ['muslos de pollo|1 unidad sin piel', 'yuca|1 trozo', 'papa|1 unidad', 'plátano verde|1/3 unidad', 'mazorca|1/2 unidad', 'cilantro|2 ramas', 'cebolla larga|1 tallo'],
 ['Cocina el pollo sin piel con cebolla larga 15 minutos.', 'Agrega yuca, papa, plátano y mazorca; cocina 25 minutos más.', 'Sirve con cilantro fresco y una porción pequeña de arroz si deseas.'],
 ['tradicional', 'alto en proteína', 'sin gluten'], 520, 34, 55, 65, 25, 5, 9, 'https://images.pexels.com/photos/35156471/pexels-photo-35156471.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pechuga a la plancha con arroz y ensalada', 'almuerzo',
 ['pechuga de pollo|150 g', 'arroz integral|1/2 taza cocido', 'lechuga|2 hojas', 'tomate|1/2 unidad', 'zanahoria|1/2 unidad', 'limón|1/2 unidad', 'aceite de oliva|1 cdta'],
 ['Sazona la pechuga con ajo, sal y pimienta; ásala 5-6 minutos por lado.', 'Prepara la ensalada con lechuga, tomate y zanahoria rallada.', 'Adereza con limón y aceite de oliva; sirve con el arroz.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 450, 40, 25, 40, 20, 5, 6, 'https://images.pexels.com/photos/34463128/pexels-photo-34463128.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pollo sudado con papa y yuca', 'almuerzo',
 ['muslos de pollo|1 unidad sin piel', 'papa|1 unidad', 'yuca|1 trozo', 'tomate|1 unidad', 'cebolla|1/2 unidad', 'ajo|1 diente', 'cilantro|2 ramas'],
 ['Haz un guiso con tomate, cebolla y ajo.', 'Agrega el pollo, la papa y la yuca con un poco de agua.', 'Tapa y cocina a fuego medio 30 minutos; termina con cilantro.'],
 ['tradicional', 'económico', 'sin gluten'], 490, 30, 40, 60, 20, 8, 6, 'https://images.pexels.com/photos/35156471/pexels-photo-35156471.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Carne asada con papa criolla y guacamole', 'almuerzo',
 ['carne de res|150 g', 'papa criolla|6 unidades', 'aguacate|1/2 unidad', 'tomate|1/2 unidad', 'cebolla roja|1/4 unidad', 'limón|1/2 unidad'],
 ['Asa la carne a la plancha al punto que prefieras.', 'Cocina las papas criollas y dóralas al horno o sartén.', 'Prepara guacamole con aguacate, tomate, cebolla y limón.'],
 ['alto en proteína', 'sin gluten', 'tradicional'], 560, 38, 30, 40, 30, 6, 4, 'https://images.pexels.com/photos/11089568/pexels-photo-11089568.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Lentejas guisadas con arroz y maduro al horno', 'almuerzo',
 ['lentejas|3/4 taza cocidas', 'arroz integral|1/2 taza cocido', 'plátano maduro|1/3 unidad', 'tomate|1 unidad', 'cebolla|1/4 unidad', 'zanahoria|1/2 unidad', 'comino|1 pizca'],
 ['Guisa las lentejas con tomate, cebolla, zanahoria y comino.', 'Hornea las tajadas de maduro hasta caramelizar.', 'Sirve con el arroz.'],
 ['vegetariano', 'económico', 'alto en proteína'], 510, 22, 35, 70, 15, 10, 8, 'https://images.pexels.com/photos/27556985/pexels-photo-27556985.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Fríjoles rojos con arroz y aguacate', 'almuerzo',
 ['fríjol rojo|3/4 taza cocido', 'arroz integral|1/2 taza cocido', 'aguacate|1/4 unidad', 'tomate|1 unidad', 'cebolla|1/4 unidad', 'comino|1 pizca'],
 ['Prepara un hogao con tomate y cebolla; mézclalo con los fríjoles.', 'Sazona con comino.', 'Sirve con arroz y aguacate.'],
 ['tradicional', 'vegetariano', 'económico'], 520, 21, 30, 65, 18, 8, 7, 'https://images.pexels.com/photos/19725426/pexels-photo-19725426.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tilapia a la plancha con patacones al horno', 'almuerzo',
 ['tilapia|1 filete (150 g)', 'plátano verde|1/2 unidad', 'lechuga|2 hojas', 'tomate|1/2 unidad', 'limón|1 unidad', 'ajo|1 diente'],
 ['Adoba la tilapia con ajo, limón, sal y pimienta; ásala 4 minutos por lado.', 'Haz patacones aplastando el plátano cocido y dorándolo al horno.', 'Acompaña con ensalada fresca.'],
 ['alto en proteína', 'sin gluten'], 470, 36, 35, 50, 22, 6, 5, 'https://images.pexels.com/photos/8352785/pexels-photo-8352785.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Mojarra al horno con arroz con coco ligero', 'almuerzo',
 ['mojarra|1 unidad', 'arroz|1/2 taza', 'leche de coco|1/4 taza', 'limón|1 unidad', 'ajo|2 dientes', 'lechuga|2 hojas', 'tomate|1/2 unidad'],
 ['Adoba la mojarra con ajo y limón; hornéala 25 minutos a 200°C.', 'Cocina el arroz con mitad agua, mitad leche de coco.', 'Sirve con ensalada fresca; sabor costeño sin fritos.'],
 ['tradicional', 'alto en proteína', 'sin gluten'], 540, 38, 45, 55, 25, 7, 4, 'https://images.pexels.com/photos/15735639/pexels-photo-15735639.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pasta integral con pollo y verduras', 'almuerzo',
 ['pasta integral|80 g', 'pechuga de pollo|120 g', 'calabacín|1/2 unidad', 'pimentón|1/2 unidad', 'tomate|1 unidad', 'ajo|1 diente', 'aceite de oliva|1 cdta'],
 ['Cocina la pasta al dente.', 'Saltea el pollo en tiras con ajo; agrega las verduras 5 minutos.', 'Mezcla con la pasta y un toque de aceite de oliva.'],
 ['alto en proteína', 'rápido'], 520, 38, 25, 60, 20, 8, 6, 'https://images.pexels.com/photos/31064159/pexels-photo-31064159.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arroz con pollo saludable', 'almuerzo',
 ['arroz|3/4 taza', 'pechuga de pollo|120 g', 'arveja|1/4 taza', 'zanahoria|1/2 unidad', 'pimentón|1/4 unidad', 'cebolla|1/4 unidad', 'ajo|1 diente', 'cilantro|2 ramas'],
 ['Sofríe cebolla, ajo y pimentón; agrega el pollo en cubos.', 'Añade el arroz, las verduras y el doble de agua caliente.', 'Cocina tapado 20 minutos; termina con cilantro.'],
 ['tradicional', 'alto en proteína', 'económico'], 540, 35, 35, 65, 22, 9, 7, 'https://images.pexels.com/photos/34668500/pexels-photo-34668500.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sudado de carne con yuca', 'almuerzo',
 ['carne de res|150 g', 'yuca|1 trozo grande', 'tomate|1 unidad', 'cebolla|1/2 unidad', 'ajo|1 diente', 'cilantro|2 ramas', 'comino|1 pizca'],
 ['Sella la carne en trozos; agrega el guiso de tomate, cebolla y ajo.', 'Añade la yuca y agua; cocina tapado 35 minutos hasta ablandar.', 'Sirve con cilantro fresco.'],
 ['tradicional', 'alto en proteína', 'sin gluten'], 530, 36, 45, 45, 28, 8, 6, 'https://images.pexels.com/photos/37099829/pexels-photo-37099829.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pescado encocado (en salsa de coco)', 'almuerzo',
 ['pescado blanco|1 filete (150 g)', 'leche de coco|1/2 taza', 'tomate|1 unidad', 'cebolla|1/2 unidad', 'pimentón|1/4 unidad', 'arroz|1/2 taza cocido', 'cilantro|2 ramas'],
 ['Haz un sofrito con tomate, cebolla y pimentón.', 'Agrega la leche de coco y el pescado; cocina tapado 10 minutos.', 'Sirve con arroz y cilantro; sabor del Pacífico.'],
 ['tradicional', 'alto en proteína', 'sin gluten'], 520, 34, 30, 35, 32, 10, 4, 'https://images.pexels.com/photos/38324319/pexels-photo-38324319.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Garbanzos guisados con espinaca', 'almuerzo',
 ['garbanzos|3/4 taza cocidos', 'espinaca|2 tazas', 'tomate|1 unidad', 'cebolla|1/4 unidad', 'ajo|1 diente', 'comino|1 pizca', 'arroz integral|1/2 taza cocido'],
 ['Sofríe cebolla, ajo y tomate; agrega los garbanzos con un poco de su caldo.', 'Añade la espinaca al final hasta marchitar.', 'Sirve con arroz integral.'],
 ['vegetariano', 'alto en proteína', 'económico'], 480, 20, 25, 60, 15, 8, 10, 'https://images.pexels.com/photos/7189415/pexels-photo-7189415.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de pollo con aguacate y maíz', 'almuerzo',
 ['pechuga de pollo|120 g', 'lechuga|3 hojas', 'aguacate|1/2 unidad', 'mazorca|1/2 unidad desgranada', 'tomate cherry|6 unidades', 'limón|1/2 unidad', 'aceite de oliva|1 cdta'],
 ['Asa el pollo y córtalo en tiras.', 'Mezcla lechuga, maíz cocido, tomates y aguacate.', 'Adereza con limón, aceite de oliva, sal y pimienta.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 440, 35, 25, 25, 25, 5, 7, 'https://images.pexels.com/photos/5192433/pexels-photo-5192433.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Lomo de cerdo al horno con puré de ahuyama', 'almuerzo',
 ['lomo de cerdo|150 g', 'ahuyama|1 taza', 'ajo|2 dientes', 'tomillo|1 rama', 'habichuela|1 taza'],
 ['Adoba el lomo con ajo y tomillo; hornéalo 25 minutos a 200°C.', 'Cocina la ahuyama y hazla puré con sal y pimienta.', 'Acompaña con habichuelas al vapor.'],
 ['alto en proteína', 'sin gluten', 'ligero'], 460, 36, 40, 30, 25, 5, 5, 'https://images.pexels.com/photos/14537709/pexels-photo-14537709.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bowl de quinua con pollo y verduras', 'almuerzo',
 ['quinua|1/2 taza', 'pechuga de pollo|120 g', 'brócoli|1 taza', 'zanahoria|1/2 unidad', 'aguacate|1/4 unidad', 'limón|1/2 unidad'],
 ['Cocina la quinua lavada 15 minutos.', 'Saltea el pollo en cubos; cocina el brócoli y la zanahoria al vapor.', 'Arma el bowl y termina con aguacate y limón.'],
 ['alto en proteína', 'sin gluten', 'ligero'], 490, 38, 30, 40, 20, 5, 8, 'https://images.pexels.com/photos/34227769/pexels-photo-34227769.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Estofado de pollo con verduras', 'almuerzo',
 ['muslos de pollo|1 unidad sin piel', 'papa|1 unidad', 'zanahoria|1/2 unidad', 'arveja|1/4 taza', 'tomate|1 unidad', 'cebolla|1/2 unidad', 'laurel|1 hoja'],
 ['Dora el pollo; agrega cebolla y tomate para el guiso.', 'Añade papa, zanahoria, arvejas, laurel y agua.', 'Cocina tapado 30 minutos.'],
 ['tradicional', 'económico', 'sin gluten'], 470, 30, 40, 50, 20, 8, 7, 'https://images.pexels.com/photos/37505669/pexels-photo-37505669.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Picadillo de carne con arroz', 'almuerzo',
 ['carne molida|120 g', 'papa|1 unidad', 'zanahoria|1/2 unidad', 'arveja|1/4 taza', 'tomate|1 unidad', 'cebolla|1/4 unidad', 'arroz|1/2 taza cocido'],
 ['Sofríe la carne con cebolla y tomate.', 'Agrega papa en cubos pequeños, zanahoria y arvejas con poca agua.', 'Cocina 20 minutos y sirve con arroz.'],
 ['económico', 'alto en proteína'], 510, 32, 30, 55, 25, 8, 6, 'https://images.pexels.com/photos/12916873/pexels-photo-12916873.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sopa de lentejas con verduras', 'almuerzo',
 ['lentejas|1/2 taza secas', 'papa|1 unidad', 'zanahoria|1/2 unidad', 'tomate|1 unidad', 'cebolla|1/4 unidad', 'ajo|1 diente', 'comino|1 pizca'],
 ['Sofríe cebolla, ajo y tomate.', 'Agrega las lentejas, papa, zanahoria y agua.', 'Cocina 30 minutos hasta que las lentejas ablanden.'],
 ['vegetariano', 'económico', 'ligero'], 380, 18, 40, 60, 10, 8, 10, 'https://images.pexels.com/photos/29850843/pexels-photo-29850843.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Salmón a la plancha con puré y brócoli', 'almuerzo',
 ['salmón|1 filete (140 g)', 'papa|1 unidad', 'brócoli|1 taza', 'limón|1/2 unidad', 'aceite de oliva|1 cdta'],
 ['Asa el salmón 4 minutos por lado con sal, pimienta y limón.', 'Haz puré con la papa cocida.', 'Sirve con brócoli al vapor y un hilo de aceite de oliva.'],
 ['alto en proteína', 'sin gluten', 'ligero'], 520, 36, 30, 20, 30, 2, 5, 'https://images.pexels.com/photos/20182304/pexels-photo-20182304.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pollo al curry suave con leche de coco', 'almuerzo',
 ['pechuga de pollo|150 g', 'leche de coco|1/2 taza', 'cúrcuma|1/2 cdta', 'jengibre|1 trocito', 'cebolla|1/2 unidad', 'arroz|1/2 taza cocido'],
 ['Sofríe cebolla, jengibre y cúrcuma.', 'Agrega el pollo en cubos y la leche de coco; cocina 15 minutos.', 'Sirve con arroz; un toque diferente sin dejar de ser casero.'],
 ['alto en proteína', 'sin gluten'], 530, 36, 30, 60, 25, 10, 5, 'https://images.pexels.com/photos/31653129/pexels-photo-31653129.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tacos caseros de pollo desmechado', 'almuerzo',
 ['pan árabe integral|2 unidades pequeñas', 'pechuga de pollo|120 g', 'aguacate|1/4 unidad', 'tomate|1/2 unidad', 'cebolla roja|1/4 unidad', 'limón|1/2 unidad'],
 ['Cocina y desmecha el pollo; sazónalo con limón y comino.', 'Calienta los panes y rellénalos con pollo.', 'Agrega aguacate, tomate y cebolla roja en plumas.'],
 ['alto en proteína', 'rápido'], 480, 34, 25, 40, 20, 5, 8, 'https://images.pexels.com/photos/17429140/pexels-photo-17429140.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pechuga rellena de espinaca y queso', 'almuerzo',
 ['pechuga de pollo|150 g', 'espinaca|1 taza', 'queso campesino|40 g', 'tomate|1/2 unidad', 'lechuga|2 hojas', 'aceite de oliva|1 cdta'],
 ['Abre la pechuga en mariposa y rellénala con espinaca y queso.', 'Ciérrala con palillos y ásala a fuego medio 8 minutos por lado.', 'Sirve con ensalada fresca.'],
 ['alto en proteína', 'sin gluten', 'ligero'], 450, 42, 35, 20, 25, 2, 5, 'https://images.pexels.com/photos/15059692/pexels-photo-15059692.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arroz atollado ligero de pollo', 'almuerzo',
 ['arroz|3/4 taza', 'muslos de pollo|1 unidad sin piel', 'papa criolla|3 unidades', 'tomate|1 unidad', 'cebolla larga|1 tallo', 'ajo|1 diente', 'cilantro|2 ramas'],
 ['Cocina el pollo con el guiso de tomate, cebolla y ajo.', 'Agrega arroz, papa criolla y bastante agua; cocina hasta quedar cremoso.', 'Termina con cilantro; plato vallecaucano reconfortante.'],
 ['tradicional', 'económico'], 560, 32, 45, 70, 20, 5, 8, 'https://images.pexels.com/photos/34668500/pexels-photo-34668500.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Albóndigas de pavo en salsa natural', 'almuerzo',
 ['pavo molido|120 g', 'pasta integral|70 g', 'tomate|2 unidades', 'cebolla|1/4 unidad', 'ajo|1 diente', 'albahaca|4 hojas', 'huevos|1/2 unidad'],
 ['Mezcla el pavo con huevo, ajo y forma albóndigas; dóralas.', 'Licúa los tomates y haz una salsa con cebolla y albahaca.', 'Cocina las albóndigas en la salsa 15 minutos; sirve con la pasta.'],
 ['alto en proteína'], 500, 36, 40, 40, 25, 5, 8, 'https://images.pexels.com/photos/34480434/pexels-photo-34480434.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Trucha a la plancha con papas al vapor', 'almuerzo',
 ['trucha|1 filete (150 g)', 'papa|2 unidades pequeñas', 'aguacate|1/4 unidad', 'lechuga|2 hojas', 'limón|1/2 unidad'],
 ['Asa la trucha con sal, pimienta y limón, 4 minutos por lado.', 'Cocina las papas al vapor.', 'Sirve con ensalada de lechuga y aguacate.'],
 ['alto en proteína', 'sin gluten', 'ligero'], 460, 35, 25, 20, 25, 2, 5, 'https://images.pexels.com/photos/8567177/pexels-photo-8567177.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sopa de verduras con pollo desmechado', 'almuerzo',
 ['pechuga de pollo|100 g', 'papa|1 unidad', 'zanahoria|1/2 unidad', 'habichuela|1/2 taza', 'mazorca|1/4 unidad', 'cilantro|2 ramas', 'cebolla larga|1 tallo'],
 ['Cocina el pollo en agua con cebolla larga; retíralo y desméchalo.', 'Cocina las verduras en el caldo 20 minutos.', 'Regresa el pollo, ajusta la sazón y sirve con cilantro.'],
 ['ligero', 'tradicional', 'sin gluten'], 350, 28, 40, 40, 10, 8, 6, 'https://images.pexels.com/photos/10172749/pexels-photo-10172749.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Chuleta de cerdo con ensalada de repollo', 'almuerzo',
 ['chuleta de cerdo|1 unidad (150 g)', 'arroz integral|1/2 taza cocido', 'repollo|1 taza', 'zanahoria|1/2 unidad', 'limón|1/2 unidad', 'yogur natural|2 cdas'],
 ['Asa la chuleta a la plancha (sin apanar).', 'Prepara ensalada de repollo y zanahoria con aderezo de yogur y limón.', 'Sirve con el arroz integral.'],
 ['alto en proteína'], 520, 36, 30, 30, 25, 5, 4, 'https://images.pexels.com/photos/10939223/pexels-photo-10939223.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Wrap integral de carne y verduras', 'almuerzo',
 ['pan árabe integral|1 unidad', 'carne de res|120 g', 'pimentón|1/2 unidad', 'cebolla|1/4 unidad', 'lechuga|2 hojas', 'yogur natural|2 cdas'],
 ['Saltea la carne en tiras con pimentón y cebolla.', 'Unta el pan con yogur, agrega lechuga y el salteado.', 'Enrolla bien y sirve.'],
 ['alto en proteína', 'rápido'], 470, 34, 20, 45, 18, 6, 7, 'https://images.pexels.com/photos/36750262/pexels-photo-36750262.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arroz salteado casero con huevo y verduras', 'almuerzo',
 ['arroz integral|3/4 taza cocido', 'huevos|2 unidades', 'zanahoria|1/2 unidad', 'arveja|1/4 taza', 'cebolla larga|1 tallo', 'salsa de soya|1 cdta', 'jengibre|1 trocito'],
 ['Revuelve los huevos y resérvalos.', 'Saltea las verduras con jengibre; agrega el arroz frío y la soya.', 'Incorpora el huevo, mezcla y sirve caliente.'],
 ['vegetariano', 'rápido', 'económico'], 460, 18, 20, 55, 15, 5, 5, 'https://images.pexels.com/photos/32655065/pexels-photo-32655065.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Muchacho en salsa criolla con puré', 'almuerzo',
 ['carne de res|150 g', 'tomate|2 unidades', 'cebolla|1/2 unidad', 'papa|1 unidad', 'ajo|1 diente', 'laurel|1 hoja'],
 ['Cocina la carne en trozos con agua, ajo y laurel hasta ablandar.', 'Prepara salsa criolla con tomate y cebolla; baña la carne.', 'Sirve con puré de papa casero.'],
 ['tradicional', 'alto en proteína', 'sin gluten'], 540, 38, 60, 35, 28, 4, 4, 'https://images.pexels.com/photos/38441087/pexels-photo-38441087.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de atún con papa y huevo', 'almuerzo',
 ['atún en lata|1 lata', 'papa|1 unidad', 'huevos|1 unidad', 'lechuga|2 hojas', 'tomate|1/2 unidad', 'limón|1/2 unidad', 'aceite de oliva|1 cdta'],
 ['Cocina la papa y el huevo; pícalos en cubos.', 'Mezcla con el atún escurrido, lechuga y tomate.', 'Adereza con limón y aceite de oliva.'],
 ['alto en proteína', 'rápido', 'económico'], 420, 32, 20, 25, 20, 3, 3, 'https://images.pexels.com/photos/19859336/pexels-photo-19859336.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Crema de ahuyama con semillas', 'cena',
 ['ahuyama|1.5 tazas', 'cebolla|1/4 unidad', 'ajo|1 diente', 'leche|1/4 taza', 'semillas de chía|1 cdta', 'queso campesino|30 g'],
 ['Cocina la ahuyama con cebolla y ajo hasta ablandar.', 'Licúa con la leche y un poco del agua de cocción.', 'Sirve con semillas y cubitos de queso.'],
 ['vegetariano', 'ligero', 'sin gluten'], 260, 10, 25, 35, 12, 8, 5, 'https://images.pexels.com/photos/1277483/pexels-photo-1277483.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Crema de tomate natural con tostadas', 'cena',
 ['tomate|3 unidades', 'cebolla|1/4 unidad', 'ajo|1 diente', 'albahaca|3 hojas', 'tostadas integrales|2 unidades', 'leche|1/4 taza'],
 ['Asa los tomates con cebolla y ajo; licúalos con la leche.', 'Calienta la crema y ajusta la sazón.', 'Sirve con albahaca y tostadas integrales.'],
 ['vegetariano', 'ligero'], 240, 8, 25, 40, 8, 10, 4, 'https://images.pexels.com/photos/18097893/pexels-photo-18097893.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Omelette de verduras con ensalada', 'cena',
 ['huevos|2 unidades', 'calabacín|1/4 unidad', 'pimentón|1/4 unidad', 'cebolla|1/4 unidad', 'lechuga|2 hojas', 'tomate|1/2 unidad'],
 ['Saltea las verduras picadas 3 minutos.', 'Agrega los huevos batidos y cocina a fuego bajo.', 'Acompaña con ensalada fresca de lechuga y tomate.'],
 ['vegetariano', 'ligero', 'alto en proteína'], 300, 19, 15, 25, 15, 5, 4, 'https://images.pexels.com/photos/26161037/pexels-photo-26161037.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada césar criolla con pollo', 'cena',
 ['pechuga de pollo|120 g', 'lechuga|3 hojas', 'yogur natural|3 cdas', 'queso parmesano|1 cda', 'limón|1/2 unidad', 'pan integral|1 tajada', 'ajo|1 diente'],
 ['Asa el pollo y córtalo en tiras.', 'Prepara aderezo con yogur, limón, ajo y parmesano.', 'Mezcla con la lechuga y crutones de pan integral tostado.'],
 ['alto en proteína', 'ligero'], 380, 35, 20, 20, 20, 5, 5, 'https://images.pexels.com/photos/29935339/pexels-photo-29935339.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Wrap de atún con verduras', 'cena',
 ['pan árabe integral|1 unidad', 'atún en lata|1 lata', 'lechuga|2 hojas', 'tomate|1/2 unidad', 'cebolla roja|1/4 unidad', 'limón|1/2 unidad'],
 ['Mezcla el atún escurrido con limón y cebolla en plumas.', 'Rellena el pan con lechuga, tomate y el atún.', 'Enrolla y sirve.'],
 ['alto en proteína', 'rápido', 'ligero'], 350, 30, 10, 30, 15, 5, 5, 'https://images.pexels.com/photos/9026808/pexels-photo-9026808.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sopa ligera de verduras', 'cena',
 ['papa|1 unidad', 'zanahoria|1/2 unidad', 'habichuela|1/2 taza', 'apio|1 tallo', 'cebolla larga|1 tallo', 'cilantro|2 ramas'],
 ['Cocina todas las verduras picadas en agua o caldo 20 minutos.', 'Ajusta sal y pimienta.', 'Sirve caliente con cilantro fresco.'],
 ['vegetariano', 'ligero', 'económico'], 180, 5, 25, 35, 5, 5, 6, 'https://images.pexels.com/photos/10172749/pexels-photo-10172749.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pechuga a la plancha con verduras salteadas', 'cena',
 ['pechuga de pollo|130 g', 'brócoli|1 taza', 'zanahoria|1/2 unidad', 'calabacín|1/2 unidad', 'ajo|1 diente', 'aceite de oliva|1 cdta'],
 ['Asa la pechuga con sal, pimienta y ajo.', 'Saltea las verduras en aceite de oliva 5 minutos (que queden crocantes).', 'Sirve caliente.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 350, 36, 20, 20, 15, 5, 5, 'https://images.pexels.com/photos/37575746/pexels-photo-37575746.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tilapia al horno con ensalada fresca', 'cena',
 ['tilapia|1 filete (140 g)', 'lechuga|2 hojas', 'pepino|1/2 unidad', 'tomate|1/2 unidad', 'limón|1 unidad', 'aceite de oliva|1 cdta'],
 ['Hornea la tilapia con limón, sal y pimienta 15 minutos a 200°C.', 'Prepara ensalada de lechuga, pepino y tomate.', 'Adereza y sirve.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 320, 32, 25, 15, 15, 5, 4, 'https://images.pexels.com/photos/37991403/pexels-photo-37991403.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Calabacines rellenos de carne', 'cena',
 ['calabacín|1 unidad', 'carne molida|100 g', 'tomate|1/2 unidad', 'cebolla|1/4 unidad', 'queso campesino|30 g'],
 ['Parte los calabacines a lo largo y retira parte del centro.', 'Rellénalos con la carne guisada con tomate y cebolla.', 'Agrega queso y hornea 20 minutos a 180°C.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 360, 28, 35, 25, 20, 5, 4, 'https://images.pexels.com/photos/36865407/pexels-photo-36865407.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tortilla de papa al horno con ensalada', 'cena',
 ['papa|1 unidad', 'huevos|2 unidades', 'cebolla|1/4 unidad', 'lechuga|2 hojas', 'tomate|1/2 unidad'],
 ['Cocina la papa en rodajas finas.', 'Mezcla con huevo batido y cebolla; cuaja en sartén u horno.', 'Sirve con ensalada fresca.'],
 ['vegetariano', 'económico'], 340, 16, 30, 35, 15, 5, 5, 'https://images.pexels.com/photos/14415378/pexels-photo-14415378.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arepa con pollo y aguacate (cena ligera)', 'cena',
 ['harina de maíz|1/2 taza', 'pechuga de pollo|80 g', 'aguacate|1/4 unidad', 'tomate|1/2 unidad'],
 ['Asa una arepa mediana.', 'Cúbrela con pollo desmechado, aguacate y tomate.', 'Una cena tradicional, completa y liviana.'],
 ['tradicional', 'ligero', 'sin gluten'], 380, 26, 25, 40, 15, 5, 6, 'https://images.pexels.com/photos/32924384/pexels-photo-32924384.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bowl mediterráneo de garbanzos', 'cena',
 ['garbanzos|3/4 taza cocidos', 'tomate|1/2 unidad', 'pepino|1/2 unidad', 'cebolla roja|1/4 unidad', 'queso campesino|30 g', 'limón|1/2 unidad', 'aceite de oliva|1 cdta'],
 ['Mezcla los garbanzos con las verduras picadas.', 'Agrega el queso en cubos.', 'Adereza con limón, aceite de oliva y orégano.'],
 ['vegetariano', 'ligero', 'alto en proteína'], 380, 17, 15, 45, 18, 8, 8, 'https://images.pexels.com/photos/9213971/pexels-photo-9213971.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sopa de avena con pollo desmechado', 'cena',
 ['avena en hojuelas|1/3 taza', 'pechuga de pollo|80 g', 'papa|1 unidad pequeña', 'zanahoria|1/2 unidad', 'cilantro|2 ramas', 'cebolla larga|1 tallo'],
 ['Cocina el pollo y desméchalo; conserva el caldo.', 'Agrega la avena, papa y zanahoria al caldo; cocina 15 minutos.', 'Regresa el pollo y sirve con cilantro; abuela-approved.'],
 ['tradicional', 'ligero', 'económico'], 320, 24, 30, 35, 10, 5, 5, 'https://images.pexels.com/photos/19503784/pexels-photo-19503784.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de quinua con verduras y huevo', 'cena',
 ['quinua|1/2 taza', 'huevos|1 unidad', 'tomate cherry|5 unidades', 'pepino|1/2 unidad', 'espinaca|1 taza', 'limón|1/2 unidad'],
 ['Cocina la quinua y déjala enfriar.', 'Mezcla con espinaca, tomates, pepino y huevo cocido en cuartos.', 'Adereza con limón y aceite de oliva.'],
 ['vegetariano', 'ligero', 'sin gluten'], 380, 17, 25, 40, 15, 6, 7, 'https://images.pexels.com/photos/18535642/pexels-photo-18535642.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pescado al papillote con verduras', 'cena',
 ['pescado blanco|1 filete (140 g)', 'calabacín|1/2 unidad', 'zanahoria|1/2 unidad', 'limón|1/2 unidad', 'tomillo|1 rama'],
 ['Pon el pescado sobre papel de horno con las verduras en julianas.', 'Agrega limón, tomillo, sal y pimienta; cierra el paquete.', 'Hornea 18 minutos a 200°C; jugoso y sin ensuciar nada.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 300, 30, 25, 10, 12, 2, 3, 'https://images.pexels.com/photos/10973355/pexels-photo-10973355.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Salteado de champiñones y espinaca con huevo', 'cena',
 ['champiñones|1 taza', 'espinaca|2 tazas', 'huevos|2 unidades', 'ajo|1 diente', 'aceite de oliva|1 cdta'],
 ['Saltea los champiñones con ajo hasta dorar.', 'Agrega la espinaca hasta marchitar.', 'Sirve con huevos al gusto encima.'],
 ['vegetariano', 'ligero', 'sin gluten'], 280, 18, 15, 20, 15, 4, 4, 'https://images.pexels.com/photos/19653600/pexels-photo-19653600.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Crema de espinaca con huevo cocido', 'cena',
 ['espinaca|2 tazas', 'papa|1 unidad pequeña', 'cebolla|1/4 unidad', 'leche|1/4 taza', 'huevos|1 unidad'],
 ['Cocina la papa con la cebolla; agrega la espinaca 2 minutos.', 'Licúa con la leche y calienta de nuevo.', 'Sirve con huevo cocido en rodajas.'],
 ['vegetariano', 'ligero', 'sin gluten'], 260, 14, 25, 30, 12, 6, 4, 'https://images.pexels.com/photos/36511368/pexels-photo-36511368.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sándwich caliente de pollo y verduras', 'cena',
 ['pan integral|2 tajadas', 'pechuga de pollo|100 g', 'tomate|1/2 unidad', 'lechuga|2 hojas', 'queso campesino|30 g'],
 ['Asa el pollo en tiras.', 'Arma el sándwich con queso y verduras; caliéntalo en sartén.', 'Corta a la mitad y sirve.'],
 ['alto en proteína', 'rápido'], 400, 32, 15, 45, 18, 8, 6, 'https://images.pexels.com/photos/1603898/pexels-photo-1603898.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pizza saludable en pan árabe', 'cena',
 ['pan árabe integral|1 unidad', 'tomate|1 unidad', 'queso mozarella|50 g', 'champiñones|1/2 taza', 'albahaca|3 hojas', 'orégano|1 pizca'],
 ['Unta el pan con tomate rallado o licuado.', 'Agrega queso, champiñones y orégano.', 'Hornea 10 minutos a 200°C; termina con albahaca.'],
 ['vegetariano', 'rápido'], 380, 18, 15, 40, 15, 8, 6, 'https://images.pexels.com/photos/4669279/pexels-photo-4669279.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ceviche de camarones con patacón al horno', 'cena',
 ['camarones|120 g', 'limón|2 unidades', 'cebolla roja|1/4 unidad', 'tomate|1/2 unidad', 'cilantro|2 ramas', 'plátano verde|1/3 unidad'],
 ['Cocina los camarones 2 minutos y báñalos en jugo de limón.', 'Mezcla con cebolla roja, tomate y cilantro; refrigera 15 minutos.', 'Sirve con patacones horneados.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 340, 28, 30, 20, 20, 6, 4, 'https://images.pexels.com/photos/38330331/pexels-photo-38330331.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevos con tajadas horneadas y ensalada', 'cena',
 ['huevos|2 unidades', 'plátano maduro|1/3 unidad', 'lechuga|2 hojas', 'tomate|1/2 unidad', 'aguacate|1/4 unidad'],
 ['Hornea las tajadas de maduro hasta dorar.', 'Prepara los huevos a tu gusto en sartén antiadherente.', 'Sirve con ensalada y aguacate.'],
 ['vegetariano', 'tradicional', 'sin gluten'], 380, 16, 20, 35, 20, 10, 7, 'https://images.pexels.com/photos/20532517/pexels-photo-20532517.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pollo desmechado con puré de ahuyama', 'cena',
 ['pechuga de pollo|120 g', 'ahuyama|1 taza', 'cebolla|1/4 unidad', 'tomate|1/2 unidad', 'cilantro|2 ramas'],
 ['Cocina y desmecha el pollo; mézclalo con guiso de tomate y cebolla.', 'Haz puré la ahuyama cocida.', 'Sirve el pollo sobre el puré con cilantro.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 340, 30, 30, 25, 15, 5, 5, 'https://images.pexels.com/photos/31987728/pexels-photo-31987728.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Caldo de pollo con verduras', 'cena',
 ['muslos de pollo|1 unidad sin piel', 'papa criolla|3 unidades', 'zanahoria|1/2 unidad', 'apio|1 tallo', 'cilantro|2 ramas', 'cebolla larga|1 tallo'],
 ['Cocina el pollo con las verduras 25 minutos.', 'Ajusta la sazón.', 'Sirve caliente con cilantro; reconfortante y liviano.'],
 ['tradicional', 'ligero', 'sin gluten'], 300, 26, 35, 30, 10, 5, 5, 'https://images.pexels.com/photos/10172749/pexels-photo-10172749.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada tibia de lentejas', 'cena',
 ['lentejas|3/4 taza cocidas', 'tomate|1/2 unidad', 'cebolla roja|1/4 unidad', 'pimentón|1/4 unidad', 'limón|1/2 unidad', 'aceite de oliva|1 cdta', 'perejil|2 ramas'],
 ['Saltea el pimentón y la cebolla 3 minutos.', 'Mezcla con las lentejas tibias y el tomate picado.', 'Adereza con limón, aceite y perejil.'],
 ['vegetariano', 'ligero', 'económico'], 320, 15, 15, 45, 10, 8, 10, 'https://images.pexels.com/photos/6145899/pexels-photo-6145899.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostadas de atún con aguacate', 'cena',
 ['tostadas integrales|2 unidades', 'atún en lata|1 lata', 'aguacate|1/4 unidad', 'tomate|1/2 unidad', 'limón|1/2 unidad'],
 ['Machaca el aguacate con limón y úntalo en las tostadas.', 'Agrega el atún escurrido y tomate picado.', 'Termina con pimienta y sirve.'],
 ['alto en proteína', 'rápido', 'ligero'], 330, 28, 10, 25, 20, 5, 7, 'https://images.pexels.com/photos/6953372/pexels-photo-6953372.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arroz de coliflor con pollo salteado', 'cena',
 ['coliflor|1.5 tazas', 'pechuga de pollo|120 g', 'zanahoria|1/2 unidad', 'cebolla larga|1 tallo', 'ajo|1 diente', 'salsa de soya|1 cdta'],
 ['Ralla o procesa la coliflor hasta que parezca arroz.', 'Saltea el pollo en cubos con ajo; agrega zanahoria y coliflor.', 'Termina con soya y cebolla larga; bajo en carbohidratos.'],
 ['alto en proteína', 'ligero', 'sin gluten'], 310, 32, 20, 20, 15, 5, 5, 'https://images.pexels.com/photos/12824413/pexels-photo-12824413.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Crema de zanahoria y jengibre', 'cena',
 ['zanahoria|2 unidades', 'jengibre|1 trocito', 'cebolla|1/4 unidad', 'leche|1/4 taza', 'tostadas integrales|1 unidad'],
 ['Cocina la zanahoria con cebolla y jengibre hasta ablandar.', 'Licúa con la leche y calienta.', 'Sirve con tostada integral.'],
 ['vegetariano', 'ligero', 'económico'], 210, 6, 25, 30, 10, 8, 4, 'https://images.pexels.com/photos/8696571/pexels-photo-8696571.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Manzana con mantequilla de maní', 'snack',
 ['manzana|1 unidad', 'mantequilla de maní|1 cda'],
 ['Corta la manzana en cascos y úntalos con la mantequilla de maní.'],
 ['vegetariano', 'rápido', 'sin gluten'], 180, 5, 3, 20, 12, 15, 2, 'https://images.pexels.com/photos/33489598/pexels-photo-33489598.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Yogur con miel y nueces', 'snack',
 ['yogur natural|1 taza', 'miel|1 cdta', 'nueces|6 unidades'],
 ['Sirve el yogur con la miel y las nueces troceadas.'],
 ['vegetariano', 'rápido', 'sin gluten'], 200, 10, 3, 25, 8, 18, 1, 'https://images.pexels.com/photos/7111398/pexels-photo-7111398.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Palitos de verdura con hummus casero', 'snack',
 ['garbanzos|1/2 taza cocidos', 'zanahoria|1/2 unidad', 'pepino|1/2 unidad', 'limón|1/2 unidad', 'ajo|1/2 diente', 'aceite de oliva|1 cdta'],
 ['Licúa los garbanzos con limón, ajo y aceite hasta obtener el hummus.', 'Corta las verduras en bastones y úsalas para untar.'],
 ['vegetariano', 'ligero', 'sin gluten'], 170, 6, 10, 20, 10, 5, 5, 'https://images.pexels.com/photos/35440206/pexels-photo-35440206.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Mezcla energética de maní y pasas', 'snack',
 ['maní|2 cdas', 'uvas pasas|1 cda', 'almendras|5 unidades'],
 ['Mezcla todo en un recipiente pequeño; porción de un puñado.'],
 ['vegetariano', 'rápido', 'sin gluten'], 190, 6, 2, 20, 12, 10, 3, 'https://images.pexels.com/photos/5386465/pexels-photo-5386465.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Batido de fresa y yogur', 'snack',
 ['fresa|6 unidades', 'yogur natural|3/4 taza', 'miel|1 cdta'],
 ['Licúa todo con hielo y sirve frío.'],
 ['vegetariano', 'rápido', 'sin gluten'], 160, 8, 5, 30, 4, 20, 2, 'https://images.pexels.com/photos/1098758/pexels-photo-1098758.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Mini arepa con queso', 'snack',
 ['harina de maíz|1/3 taza', 'queso campesino|30 g'],
 ['Prepara una arepa pequeña y ásala.', 'Sírvela caliente con el queso encima o adentro.'],
 ['vegetariano', 'tradicional', 'sin gluten'], 220, 9, 15, 30, 12, 2, 2, 'https://images.pexels.com/photos/19964400/pexels-photo-19964400.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevo cocido con pimienta', 'snack',
 ['huevos|1 unidad'],
 ['Cocina el huevo 10 minutos, pélalo y sazona con sal y pimienta.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 80, 7, 12, 1, 5, 0, 0, 'https://images.pexels.com/photos/2402495/pexels-photo-2402495.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Fruta picada con limón y hierbabuena', 'snack',
 ['sandía|1 taza', 'piña|1/2 taza', 'limón|1/2 unidad', 'hierbabuena|3 hojas'],
 ['Pica la fruta, exprime el limón encima y decora con hierbabuena.'],
 ['vegetariano', 'ligero', 'sin gluten'], 110, 2, 5, 25, 0, 20, 2, 'https://images.pexels.com/photos/11051722/pexels-photo-11051722.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostada integral con aguacate', 'snack',
 ['tostadas integrales|1 unidad', 'aguacate|1/4 unidad', 'limón|1/4 unidad'],
 ['Machaca el aguacate con limón y sal; úntalo en la tostada.'],
 ['vegetariano', 'rápido'], 150, 3, 5, 20, 8, 2, 4, 'https://images.pexels.com/photos/7936672/pexels-photo-7936672.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Kumis con granola', 'snack',
 ['kumis|1 taza', 'granola|2 cdas'],
 ['Sirve el kumis frío con la granola encima.'],
 ['vegetariano', 'rápido'], 190, 8, 2, 25, 6, 15, 2, 'https://images.pexels.com/photos/4006347/pexels-photo-4006347.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bolitas de avena y cacao', 'snack',
 ['avena en hojuelas|1/2 taza', 'cacao en polvo|1 cda', 'miel|1 cda', 'mantequilla de maní|1 cda'],
 ['Mezcla todo hasta formar una masa.', 'Arma bolitas y refrigera 15 minutos; rinde para 2-3 meriendas.'],
 ['vegetariano', 'económico'], 210, 6, 10, 30, 10, 15, 4, 'https://images.pexels.com/photos/27850074/pexels-photo-27850074.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Guayaba con queso campesino', 'snack',
 ['guayaba|1 unidad', 'queso campesino|30 g'],
 ['Corta la guayaba y el queso en tajadas y combínalos; el clásico colombiano en versión fruta real.'],
 ['vegetariano', 'tradicional', 'sin gluten'], 150, 7, 3, 10, 8, 5, 0, 'https://images.pexels.com/photos/17391585/pexels-photo-17391585.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Chips de plátano al horno', 'snack',
 ['plátano verde|1/2 unidad', 'aceite|1 cdta'],
 ['Corta el plátano en rodajas muy finas.', 'Úntalas con poquito aceite y sal; hornea 15 minutos a 200°C volteando a la mitad.'],
 ['vegetariano', 'sin gluten', 'económico'], 160, 1, 20, 30, 4, 10, 2, 'https://images.pexels.com/photos/27556971/pexels-photo-27556971.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Smoothie de mango y maracuyá', 'snack',
 ['mango|1/2 taza', 'maracuyá|1 unidad', 'agua|1 taza', 'miel|1 cdta'],
 ['Licúa todo con hielo y sirve; vitamina pura.'],
 ['vegetariano', 'ligero', 'sin gluten'], 140, 2, 5, 30, 1, 25, 2, 'https://images.pexels.com/photos/6688840/pexels-photo-6688840.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Almendras con arándanos', 'snack',
 ['almendras|10 unidades', 'arándanos|2 cdas'],
 ['Mezcla y disfruta; porción de un puñado.'],
 ['vegetariano', 'rápido', 'sin gluten'], 170, 5, 2, 15, 12, 8, 4, 'https://images.pexels.com/photos/18639236/pexels-photo-18639236.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Crispetas naturales caseras', 'snack',
 ['maíz pira|3 cdas', 'aceite|1 cdta'],
 ['Calienta el aceite en una olla tapada y agrega el maíz.', 'Cuando dejen de explotar, sazona con una pizca de sal.'],
 ['vegetariano', 'económico', 'sin gluten'], 130, 3, 8, 25, 2, 2, 3, 'https://images.pexels.com/photos/7033900/pexels-photo-7033900.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

];
