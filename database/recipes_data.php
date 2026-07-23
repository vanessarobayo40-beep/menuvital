<?php
/**
 * MenúVital — Base curada de recetas saludables
 * [nombre, tipo, ingredientes, pasos, tags, kcal, protein, time_min, carbs, fat, sugar, fiber, image_url]
 */

return [

// ==================== DESAYUNOS ====================
['Huevos al horno sobre aguacate con queso', 'desayuno',
 ['aguacate|1 unidad', 'huevos|2 unidades', 'queso mozarella|30 g', 'sal|1 pizca', 'pimienta|1 pizca', 'perejil|1 rama'],
 ['Precalienta el horno a 200°C y saca los huevos de la nevera unos minutos antes para que no estén tan fríos.', 'Parte el aguacate a la mitad a lo largo y retira la semilla.', 'Con una cucharita, ahonda un poco el centro de cada mitad para que quepa bien el huevo sin que se derrame.', 'Coloca las mitades en un molde para horno, apoyándolas sobre papel aluminio arrugado o en tazas para que no se volteen.', 'Casca un huevo dentro de cada hueco, procurando que la yema quede entera.', 'Espolvorea el queso rallado por encima y sazona con sal y pimienta.', 'Hornea 12-15 minutos, hasta que la clara esté cuajada y la yema al punto que prefieras.', 'Retira del horno, decora con perejil fresco picado y sirve de inmediato mientras está caliente.'],
 ['alto en proteína', 'rápido'], 420, 26, 20, 10, 32, 2, 7, 'https://images.pexels.com/photos/11719758/pexels-photo-11719758.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tortilla de espinacas y queso', 'desayuno',
 ['huevos|3 unidades', 'espinacas|1 taza', 'queso mozarella|30 g', 'cebolla|1/4 unidad', 'aceite de oliva|1 cdta'],
 ['Calienta el aceite de oliva en una sartén antiadherente a fuego medio.', 'Agrega la cebolla picada finamente y sofríe 3-4 minutos hasta que esté transparente y suave.', 'Incorpora las espinacas lavadas y cocina 1-2 minutos, moviendo con una espátula, hasta que reduzcan su volumen y se marchiten.', 'En un bowl aparte, bate los huevos con una pizca de sal y pimienta hasta que estén bien integrados.', 'Vierte los huevos batidos sobre las espinacas, repartiendo parejo por toda la sartén.', 'Espolvorea el queso mozarella rallado por encima.', 'Baja el fuego a medio-bajo, tapa la sartén y cocina 4-5 minutos hasta que la parte de abajo esté dorada y la de arriba casi cuajada.', 'Con ayuda de una espátula, dobla la tortilla por la mitad con cuidado y cocina 1 minuto más de cada lado.', 'Sirve caliente, recién salida de la sartén.'],
 ['alto en proteína', 'vegetariano', 'rápido'], 320, 22, 15, 10, 22, 4, 2, 'https://images.pexels.com/photos/5840304/pexels-photo-5840304.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostadas de aguacate y salmón', 'desayuno',
 ['pan integral|2 tajadas', 'aguacate|1/2 unidad', 'salmón ahumado|60 g', 'limón|1/4 unidad', 'pimienta|1 pizca'],
 ['Tuesta las tajadas de pan integral en la tostadora o en una sartén seca hasta que estén doradas y crocantes.', 'Mientras el pan se tuesta, corta el aguacate a la mitad, retira la semilla y saca la pulpa con una cuchara.', 'Machaca el aguacate en un bowl con un tenedor, agregando un chorrito de jugo de limón y una pizca de sal para evitar que se oxide y darle sabor.', 'Unta generosamente el aguacate machacado sobre cada tostada, cubriendo bien toda la superficie.', 'Distribuye las láminas de salmón ahumado encima, doblándolas ligeramente para darle volumen.', 'Termina con pimienta recién molida, unas gotas más de limón y, si quieres, un poco de eneldo fresco o cebollín picado.', 'Sirve de inmediato para que el pan mantenga su textura crocante.'],
 ['alto en proteína', 'rápido'], 420, 35, 10, 30, 25, 5, 10, 'https://images.pexels.com/photos/31584178/pexels-photo-31584178.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Avena con frutas y nueces', 'desayuno',
 ['avena en hojuelas|1/2 taza', 'leche deslactosada|1 taza', 'banano|1/2 unidad', 'fresas|4 unidades', 'nueces|1 cda', 'miel|1 cdta'],
 ['Coloca la avena en hojuelas y la leche en una olla pequeña.', 'Cocina a fuego medio-bajo, revolviendo cada minuto para que no se pegue, durante 5-6 minutos hasta que espese y la avena esté tierna.', 'Retira del fuego y deja reposar 2 minutos; la mezcla seguirá espesando un poco más.', 'Sirve la avena caliente en un bowl.', 'Lava y corta el banano en rodajas y las fresas en trozos pequeños.', 'Corona la avena con la fruta picada, repartiéndola de forma pareja.', 'Agrega las nueces troceadas por encima para darle textura crujiente.', 'Termina con un chorrito de miel al gusto y sirve enseguida.'],
 ['vegetariano', 'rápido'], 420, 15, 10, 60, 18, 25, 8, 'https://images.pexels.com/photos/7655880/pexels-photo-7655880.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostadas de aguacate y huevo', 'desayuno',
 ['pan integral|2 tajadas', 'aguacate|1/2 unidad', 'huevos|2 unidades', 'sal|1 pizca', 'ají en hojuelas|1 pizca'],
 ['Tuesta el pan integral hasta que quede dorado y crocante.', 'Machaca el aguacate con un tenedor en un bowl, agregando sal al gusto.', 'Unta el aguacate machacado sobre las tostadas.', 'En una sartén con un poco de aceite, cocina los huevos al gusto: fritos con la yema líquida, o pochados en agua hirviendo con un chorrito de vinagre durante 3 minutos.', 'Coloca un huevo sobre cada tostada de aguacate.', 'Espolvorea ají en hojuelas y una pizca de sal por encima.', 'Sirve de inmediato, cortando la tostada por la mitad si quieres compartirla o hacerla más fácil de comer.'],
 ['alto en proteína', 'rápido'], 420, 18, 12, 30, 26, 4, 10, 'https://images.pexels.com/photos/793785/pexels-photo-793785.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Yogur con granola y frutas', 'desayuno',
 ['yogur griego|1 taza', 'granola|3 cdas', 'fresas|5 unidades', 'arándanos|1/4 taza', 'miel|1 cdta'],
 ['Elige un vaso o bowl transparente si quieres que se vean las capas.', 'Coloca la mitad del yogur griego en el fondo.', 'Lava y pica las fresas en trozos pequeños.', 'Agrega una capa de fresas y arándanos sobre el yogur.', 'Cubre con el resto del yogur griego.', 'Corona con la granola justo antes de servir, para que se mantenga crocante y no se humedezca.', 'Decora con la fruta restante y termina con un chorrito de miel por encima.'],
 ['vegetariano', 'rápido'], 350, 20, 5, 40, 15, 25, 5, 'https://images.pexels.com/photos/11182249/pexels-photo-11182249.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tortilla de espinacas y champiñones', 'desayuno',
 ['huevos|3 unidades', 'espinacas|1 taza', 'champiñones|1/2 taza', 'cebolla|1/4 unidad', 'aceite de oliva|1 cdta'],
 ['Calienta el aceite de oliva en una sartén a fuego medio.', 'Agrega la cebolla picada y sofríe 2 minutos hasta que empiece a ablandar.', 'Incorpora los champiñones en láminas y cocina 4-5 minutos, revolviendo de vez en cuando, hasta que doren y suelten su agua.', 'Añade las espinacas y cocina 1-2 minutos más hasta que se marchiten por completo.', 'Bate los huevos aparte con sal y pimienta.', 'Vierte los huevos batidos sobre las espinacas y champiñones, repartiendo bien por toda la sartén.', 'Baja el fuego, tapa y cocina 4-5 minutos hasta que la base esté firme y dorada.', 'Dobla la tortilla por la mitad con una espátula y cocina 1 minuto más por cada lado.', 'Sirve caliente.'],
 ['vegetariano', 'alto en proteína'], 220, 18, 15, 6, 14, 2, 2, 'https://images.pexels.com/photos/13119151/pexels-photo-13119151.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostadas de salmón ahumado y aguacate', 'desayuno',
 ['pan integral|2 tajadas', 'salmón ahumado|60 g', 'aguacate|1/2 unidad', 'queso crema|1 cda', 'eneldo|1 pizca'],
 ['Tuesta el pan integral hasta que esté dorado.', 'Unta una capa fina y pareja de queso crema sobre cada tostada.', 'Corta el aguacate en láminas delgadas.', 'Coloca las láminas de aguacate sobre el queso crema, acomodándolas en abanico.', 'Distribuye el salmón ahumado encima, doblándolo suavemente para darle altura.', 'Espolvorea eneldo fresco picado por encima.', 'Sirve de inmediato con un poco de pimienta recién molida si lo deseas.'],
 ['alto en proteína', 'rápido'], 420, 30, 8, 25, 25, 5, 7, 'https://images.pexels.com/photos/6896523/pexels-photo-6896523.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Panqueques de avena y plátano', 'desayuno',
 ['avena en hojuelas|1 taza', 'plátano maduro|1 unidad', 'huevos|2 unidades', 'canela|1 pizca', 'aceite|1 cdta'],
 ['Coloca la avena, el plátano maduro pelado, los huevos y la canela en la licuadora.', 'Licúa hasta obtener una mezcla homogénea y sin grumos, con la consistencia de una masa de panqueque.', 'Deja reposar la mezcla 2-3 minutos para que la avena se hidrate un poco.', 'Calienta una sartén antiadherente a fuego medio-bajo con un poco de aceite.', 'Vierte porciones pequeñas de la mezcla (aproximadamente 1/4 de taza cada una) en la sartén.', 'Cocina 2-3 minutos hasta que se formen burbujas en la superficie y los bordes se vean firmes.', 'Voltea con cuidado y cocina 2 minutos más del otro lado hasta dorar.', 'Repite con el resto de la mezcla, apilando los panqueques listos en un plato.', 'Sirve calientes, solos o con fruta picada por encima.'],
 ['vegetariano', 'rápido'], 320, 12, 15, 45, 12, 20, 4, 'https://images.pexels.com/photos/7144976/pexels-photo-7144976.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevos revueltos con espinacas y tomate', 'desayuno',
 ['huevos|3 unidades', 'espinacas|1 taza', 'tomate|1 unidad', 'cebolla|1/4 unidad', 'aceite de oliva|1 cdta'],
 ['Calienta el aceite de oliva en una sartén a fuego medio.', 'Agrega la cebolla picada y sofríe 2 minutos.', 'Incorpora el tomate picado en cubos y cocina 2-3 minutos hasta que empiece a soltar su jugo.', 'Añade las espinacas y cocina 1-2 minutos hasta que se ablanden.', 'Bate los huevos en un bowl aparte con sal y pimienta.', 'Vierte los huevos sobre las verduras y baja el fuego a medio-bajo.', 'Revuelve constantemente con una espátula, formando pliegues suaves, hasta que los huevos cuajen pero queden cremosos (2-3 minutos).', 'Retira del fuego justo antes de que estén completamente secos, ya que siguen cocinándose fuera de la sartén.', 'Sirve de inmediato.'],
 ['alto en proteína', 'rápido', 'vegetariano'], 220, 18, 12, 6, 14, 2, 2, 'https://images.pexels.com/photos/17335057/pexels-photo-17335057.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Muesli casero con frutos secos y yogur', 'desayuno',
 ['avena en hojuelas|1/2 taza', 'almendras|2 cdas', 'nueces|2 cdas', 'pasas|1 cda', 'yogur griego|1 taza'],
 ['En un bowl, mezcla la avena en hojuelas con las almendras y nueces troceadas.', 'Agrega las pasas y mezcla bien todos los ingredientes secos.', 'Sirve el yogur griego en un bowl o vaso.', 'Agrega la mezcla de avena y frutos secos justo encima del yogur.', 'Si prefieres una textura más suave, deja reposar 5-10 minutos para que la avena se hidrate un poco con el yogur.', 'Si prefieres que quede crocante, sírvelo de inmediato sin dejar reposar.', 'Puedes agregar un poco de fruta fresca picada o miel por encima antes de servir.'],
 ['vegetariano', 'rápido'], 420, 20, 8, 60, 20, 20, 8, 'https://images.pexels.com/photos/8892362/pexels-photo-8892362.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostadas de queso cottage y piña', 'desayuno',
 ['pan integral|2 tajadas', 'queso cottage|1/2 taza', 'piña|1/2 taza en trozos', 'canela|1 pizca'],
 ['Tuesta el pan integral hasta que quede dorado y firme.', 'Escurre bien el queso cottage si tiene exceso de líquido.', 'Unta una capa generosa de queso cottage sobre cada tostada.', 'Corta la piña fresca en trozos pequeños, si no la tienes ya picada.', 'Distribuye los trozos de piña sobre el queso cottage.', 'Espolvorea una pizca de canela por encima.', 'Sirve de inmediato para aprovechar la textura crocante del pan.'],
 ['vegetariano', 'rápido', 'alto en proteína'], 320, 25, 8, 35, 12, 20, 4, 'https://images.pexels.com/photos/5836438/pexels-photo-5836438.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sándwich de pavo y aguacate', 'desayuno',
 ['pan integral|2 tajadas', 'pechuga de pavo en lonchas|60 g', 'aguacate|1/2 unidad', 'lechuga|2 hojas', 'tomate|1/2 unidad'],
 ['Machaca el aguacate en un bowl con un tenedor, agregando sal al gusto.', 'Unta el aguacate machacado sobre una de las tajadas de pan.', 'Lava la lechuga y sécala bien con un paño o papel absorbente.', 'Coloca las lonchas de pechuga de pavo sobre el aguacate.', 'Agrega las hojas de lechuga encima del pavo.', 'Corta el tomate en rodajas finas y colócalas sobre la lechuga.', 'Sazona con una pizca de sal y pimienta si lo deseas.', 'Cierra el sándwich con la otra tajada de pan y presiona ligeramente.', 'Corta por la mitad en diagonal y sirve.'],
 ['alto en proteína', 'rápido'], 420, 35, 8, 30, 20, 5, 7, 'https://images.pexels.com/photos/5112594/pexels-photo-5112594.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostada francesa saludable', 'desayuno',
 ['pan integral|2 tajadas', 'huevos|2 unidades', 'leche deslactosada|1/4 taza', 'canela|1 pizca', 'fresas|4 unidades'],
 ['En un bowl ancho, bate los huevos con la leche deslactosada y la canela hasta integrar bien.', 'Sumerge cada tajada de pan integral en la mezcla, dejándola remojar unos 10-15 segundos por cada lado para que absorba bien el líquido sin desbaratarse.', 'Calienta una sartén antiadherente a fuego medio con una gota de aceite o mantequilla.', 'Cocina cada tajada de pan remojado 2-3 minutos por lado, hasta que esté dorada y firme al tacto.', 'Retira y repite con las demás tajadas.', 'Lava y corta las fresas en trozos o láminas.', 'Sirve las tostadas francesas calientes, coronadas con las fresas frescas.'],
 ['vegetariano', 'rápido'], 320, 22, 12, 40, 12, 20, 4, 'https://images.pexels.com/photos/36904808/pexels-photo-36904808.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tortilla de calabacín rellena de jamón y queso', 'desayuno',
 ['huevos|3 unidades', 'calabacín|1/2 unidad rallado', 'jamón|40 g', 'queso mozarella|30 g'],
 ['Ralla el calabacín con la parte gruesa del rallador.', 'Colócalo sobre un paño limpio o papel absorbente y exprime bien para sacar el exceso de agua; esto evita que la tortilla quede aguada.', 'En un bowl, bate los huevos e incorpora el calabacín rallado y escurrido, con una pizca de sal y pimienta.', 'Calienta una sartén antiadherente a fuego medio con un poco de aceite.', 'Vierte la mezcla de huevo y calabacín, repartiendo pareja por toda la sartén.', 'Cuando empiece a cuajar por los bordes (1-2 minutos), agrega el jamón picado y el queso mozarella en el centro.', 'Tapa la sartén y cocina a fuego bajo 3-4 minutos más hasta que el queso derrita y el huevo esté firme.', 'Con una espátula, dobla la tortilla por la mitad con cuidado.', 'Sirve caliente, recién hecha.'],
 ['alto en proteína'], 320, 22, 15, 10, 22, 4, 2, 'https://images.pexels.com/photos/11094170/pexels-photo-11094170.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tortilla de zanahoria al estilo keto', 'desayuno',
 ['huevos|3 unidades', 'zanahoria|1 unidad rallada', 'queso parmesano|2 cdas', 'cebolla larga|1 tallo', 'aceite de oliva|1 cdta'],
 ['Ralla la zanahoria con la parte gruesa del rallador.', 'Calienta el aceite de oliva en una sartén a fuego medio.', 'Sofríe la zanahoria rallada junto con la cebolla larga picada durante 2-3 minutos, hasta que la zanahoria empiece a ablandar.', 'Bate los huevos en un bowl con el queso parmesano rallado, sal y pimienta.', 'Mezcla los huevos batidos con la zanahoria y cebolla ya sofritas.', 'Vierte todo de nuevo en la sartén caliente, repartiendo parejo.', 'Baja el fuego, tapa y cocina 4-5 minutos hasta que la base esté dorada.', 'Dobla la tortilla por la mitad o voltéala con cuidado y cocina 2 minutos más hasta que cuaje por completo.', 'Sirve caliente.'],
 ['vegetariano', 'sin gluten'], 320, 22, 15, 6, 24, 2, 2, 'https://images.pexels.com/photos/11654228/pexels-photo-11654228.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Desayuno de frutas con yogur y nueces', 'desayuno',
 ['yogur griego|1 taza', 'manzana|1/2 unidad', 'banano|1/2 unidad', 'fresas|4 unidades', 'nueces|1 cda', 'miel|1 cdta'],
 ['Lava bien la manzana, el banano y las fresas.', 'Pica la manzana en cubos pequeños, dejando la cáscara si prefieres más fibra.', 'Corta el banano en rodajas y las fresas en trozos.', 'En un bowl grande, coloca el yogur griego como base.', 'Agrega toda la fruta picada y mezcla suavemente con una cuchara, o distribúyela por encima si prefieres presentarla en capas.', 'Corona con las nueces troceadas para dar textura crocante.', 'Termina con un chorrito de miel al gusto justo antes de servir.'],
 ['vegetariano', 'rápido', 'sin gluten'], 420, 20, 8, 60, 15, 30, 6, 'https://images.pexels.com/photos/6823329/pexels-photo-6823329.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Hot cake de calabacín', 'desayuno',
 ['calabacín|1 unidad rallado', 'huevos|2 unidades', 'harina de avena|1/2 taza', 'queso parmesano|2 cdas', 'aceite|1 cdta'],
 ['Ralla el calabacín con la parte gruesa del rallador.', 'Colócalo sobre un paño limpio y exprime firmemente para sacar todo el exceso de agua posible.', 'En un bowl, mezcla el calabacín escurrido con los huevos, la harina de avena y el queso parmesano rallado.', 'Integra bien todos los ingredientes hasta obtener una mezcla espesa y homogénea; sazona con sal y pimienta.', 'Calienta una sartén antiadherente a fuego medio con un poco de aceite.', 'Vierte porciones de la mezcla formando hot cakes de tamaño mediano.', 'Cocina 3 minutos de un lado hasta que esté dorado y firme, luego voltea con cuidado.', 'Cocina 2-3 minutos más del otro lado hasta que esté bien cocido por dentro.', 'Sirve caliente, solo o acompañado de una ensalada pequeña.'],
 ['vegetariano', 'rápido'], 320, 18, 15, 25, 18, 5, 4, 'https://images.pexels.com/photos/6142849/pexels-photo-6142849.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevo hervido, aguacate y rodaja de pan', 'desayuno',
 ['huevos|2 unidades', 'aguacate|1/2 unidad', 'pan integral|1 tajada', 'sal|1 pizca', 'pimienta|1 pizca'],
 ['Coloca los huevos en una olla y cúbrelos con agua fría.', 'Lleva a hervor a fuego alto y, cuando rompa el hervor, baja a fuego medio y cocina 8-9 minutos para que queden duros pero con la yema aún cremosa en el centro.', 'Retira los huevos y sumérgelos en agua fría o con hielo por un par de minutos para detener la cocción y facilitar que se pelen.', 'Pela los huevos con cuidado y córtalos por la mitad.', 'Mientras tanto, tuesta la tajada de pan integral.', 'Corta el aguacate en láminas y colócalas sobre el pan tostado.', 'Acomoda las mitades de huevo sobre el aguacate.', 'Sazona con sal y pimienta recién molida antes de servir.'],
 ['alto en proteína', 'rápido'], 320, 18, 12, 25, 18, 4, 7, 'https://images.pexels.com/photos/824635/pexels-photo-824635.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tortillas de huevo con espinacas', 'desayuno',
 ['huevos|4 unidades', 'espinacas|1 taza', 'queso mozarella|30 g', 'ajo|1 diente', 'aceite de oliva|1 cdta'],
 ['Calienta el aceite de oliva en una sartén a fuego medio.', 'Sofríe el ajo picado 30 segundos hasta que suelte aroma, sin dejar que se queme.', 'Agrega las espinacas y cocina 1-2 minutos hasta que se marchiten; retira del fuego y deja enfriar un poco.', 'Bate los huevos en un bowl grande e incorpora las espinacas sofritas y el queso mozarella rallado.', 'Sazona con sal y pimienta.', 'Calienta de nuevo la sartén con un poco de aceite a fuego medio-bajo.', 'Vierte porciones pequeñas de la mezcla formando tortillas individuales de unos 10 cm.', 'Cocina 2 minutos por lado hasta que doren y cuajen por completo.', 'Retira y repite con el resto de la mezcla; sirve calientes, apiladas o una al lado de la otra.'],
 ['alto en proteína', 'vegetariano'], 320, 22, 15, 10, 22, 2, 4, 'https://images.pexels.com/photos/10756661/pexels-photo-10756661.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Pollo con queso y espinacas gratinadas', 'almuerzo',
 ['pechuga de pollo|200 g', 'espinacas|1 taza', 'queso mozarella|40 g', 'ajo|1 diente', 'aceite de oliva|1 cdta'],
 ['Precalienta el horno a 200°C.', 'Sazona la pechuga de pollo con sal y pimienta por ambos lados.', 'Calienta el aceite en una sartén apta para horno (o una sartén normal) a fuego medio-alto.', 'Sella la pechuga 3-4 minutos por cada lado hasta que esté dorada por fuera (no necesita estar cocida por dentro todavía).', 'Retira la pechuga y en la misma sartén sofríe el ajo picado 30 segundos.', 'Agrega las espinacas y cocina 1-2 minutos hasta que se marchiten.', 'Coloca la pechuga en un molde para horno, cubre con las espinacas sofritas y espolvorea el queso mozarella rallado por encima.', 'Hornea 10-12 minutos hasta que el pollo esté bien cocido por dentro y el queso esté dorado y burbujeante.', 'Deja reposar 2 minutos antes de servir.'],
 ['alto en proteína'], 420, 37, 30, 10, 28, 2, 4, 'https://images.pexels.com/photos/15059692/pexels-photo-15059692.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sopa de pollo y aguacate', 'almuerzo',
 ['pechuga de pollo|150 g', 'caldo de pollo|2 tazas', 'aguacate|1/2 unidad', 'cilantro|2 ramas', 'limón|1/2 unidad'],
 ['Coloca la pechuga de pollo en una olla con el caldo de pollo.', 'Lleva a hervor, luego baja el fuego y cocina tapado 15-18 minutos hasta que el pollo esté completamente cocido.', 'Retira el pollo del caldo y desmenúzalo con dos tenedores en trozos pequeños.', 'Regresa el pollo desmenuzado a la olla con el caldo y calienta a fuego medio 5 minutos más para que se integren los sabores.', 'Rectifica la sal.', 'Sirve la sopa caliente en platos hondos.', 'Justo antes de servir, agrega el aguacate cortado en cubos y el cilantro fresco picado encima.', 'Termina con un chorrito de jugo de limón.'],
 ['alto en proteína', 'sin gluten'], 420, 35, 25, 20, 25, 5, 7, 'https://images.pexels.com/photos/9251295/pexels-photo-9251295.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de pollo césar', 'almuerzo',
 ['pechuga de pollo|180 g', 'lechuga romana|2 tazas', 'queso parmesano|2 cdas', 'crutones|1/4 taza', 'aderezo césar|2 cdas'],
 ['Sazona la pechuga de pollo con sal y pimienta.', 'Calienta una sartén o plancha a fuego medio-alto con un poco de aceite.', 'Cocina la pechuga 5-6 minutos por cada lado hasta que esté bien dorada y cocida por dentro.', 'Retira del fuego, deja reposar 3 minutos y luego córtala en tiras.', 'Lava y seca bien la lechuga romana, y trocéala en un bowl grande.', 'Agrega las tiras de pollo sobre la lechuga.', 'Espolvorea el queso parmesano rallado y los crutones.', 'Baña con el aderezo césar y mezcla todo suavemente hasta que quede bien integrado.', 'Sirve de inmediato para que la lechuga y los crutones mantengan su textura.'],
 ['alto en proteína', 'rápido'], 420, 37, 20, 10, 28, 2, 4, 'https://images.pexels.com/photos/6107789/pexels-photo-6107789.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pollo con espinacas y champiñones al ajo', 'almuerzo',
 ['pechuga de pollo|200 g', 'espinacas|1 taza', 'champiñones|1 taza', 'ajo|2 dientes', 'aceite de oliva|1 cda'],
 ['Corta la pechuga de pollo en trozos medianos y sazona con sal y pimienta.', 'Calienta el aceite de oliva en una sartén grande a fuego medio-alto.', 'Sella los trozos de pollo 3-4 minutos, moviendo ocasionalmente, hasta que doren por todos lados; retira y reserva.', 'En la misma sartén, sofríe el ajo picado 30 segundos hasta que suelte aroma.', 'Agrega los champiñones en láminas y cocina 4-5 minutos hasta que doren y suelten su agua.', 'Incorpora las espinacas y cocina 1-2 minutos hasta que se marchiten.', 'Regresa el pollo a la sartén, mezcla bien con las verduras y cocina 3-4 minutos más hasta que el pollo esté completamente cocido.', 'Rectifica sal y pimienta y sirve caliente.'],
 ['alto en proteína', 'sin gluten'], 420, 40, 25, 10, 24, 2, 4, 'https://images.pexels.com/photos/4013340/pexels-photo-4013340.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pollo al pesto con brócoli al vapor', 'almuerzo',
 ['pechuga de pollo|200 g', 'pesto de albahaca|2 cdas', 'brócoli|1 taza', 'aceite de oliva|1 cdta'],
 ['Corta el brócoli en floretes medianos.', 'Cocínalo al vapor 5-6 minutos hasta que esté tierno pero aún firme al morder; retira y reserva.', 'Sazona la pechuga de pollo con sal y pimienta.', 'Calienta el aceite de oliva en una sartén a fuego medio-alto.', 'Sella la pechuga 5-6 minutos por cada lado hasta que esté dorada y bien cocida por dentro.', 'Retira del fuego y deja reposar un par de minutos.', 'Mezcla el pollo con el pesto de albahaca, cubriéndolo bien por todos lados (puedes cortarlo en trozos antes para que se impregne mejor).', 'Sirve el pollo con pesto acompañado del brócoli al vapor a un lado.'],
 ['alto en proteína', 'rápido'], 540, 37, 20, 20, 34, 5, 8, 'https://images.pexels.com/photos/1309595/pexels-photo-1309595.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Wrap de pollo con hojas de lechuga', 'almuerzo',
 ['pechuga de pollo|180 g', 'lechuga|4 hojas grandes', 'tomate|1/2 unidad', 'aguacate|1/4 unidad', 'aderezo yogur|2 cdas'],
 ['Sazona la pechuga de pollo con sal y pimienta.', 'Cocina a la plancha con un poco de aceite, 5-6 minutos por cada lado, hasta que esté bien dorada y cocida.', 'Deja reposar 3 minutos y córtala en tiras delgadas.', 'Lava las hojas de lechuga con cuidado de no romperlas y sécalas bien.', 'Extiende las hojas de lechuga sobre una superficie plana, una al lado de la otra, como si fueran la "tortilla".', 'Reparte el pollo en tiras sobre cada hoja.', 'Agrega el tomate en cubos y el aguacate en láminas.', 'Termina con una cucharada del aderezo de yogur sobre el relleno.', 'Enrolla cada hoja sosteniendo el relleno con firmeza y sirve de inmediato.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 420, 35, 15, 25, 20, 5, 7, 'https://images.pexels.com/photos/9980749/pexels-photo-9980749.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pollo a la parrilla con espárragos', 'almuerzo',
 ['pechuga de pollo|200 g', 'espárragos|8 unidades', 'ajo|1 diente', 'aceite de oliva|1 cda', 'limón|1/2 unidad'],
 ['En un bowl, mezcla el ajo picado, el aceite de oliva y el jugo de limón.', 'Sumerge la pechuga de pollo en esta marinada y déjala reposar al menos 10 minutos (o hasta 30 si tienes tiempo) para que tome sabor.', 'Calienta una parrilla o sartén acanalada a fuego medio-alto.', 'Cocina la pechuga 5-6 minutos por cada lado hasta que tenga marcas doradas y esté bien cocida por dentro.', 'Retira el pollo y déjalo reposar 3 minutos antes de cortarlo.', 'En la misma parrilla, asa los espárragos 4-5 minutos, volteándolos a la mitad, hasta que estén tiernos y ligeramente dorados.', 'Sirve el pollo junto a los espárragos con un poco más de jugo de limón por encima.'],
 ['alto en proteína', 'sin gluten', 'rápido'], 320, 40, 20, 10, 12, 2, 5, 'https://images.pexels.com/photos/2418486/pexels-photo-2418486.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Chili de pollo bajo en carbohidratos', 'almuerzo',
 ['pollo molido|200 g', 'tomate|2 unidades', 'pimentón|1/2 unidad', 'cebolla|1/2 unidad', 'comino|1 cdta', 'frijoles rojos|1/2 taza'],
 ['Calienta un poco de aceite en una olla a fuego medio.', 'Sofríe la cebolla y el pimentón picados 3-4 minutos hasta que ablanden.', 'Agrega el pollo molido y cocina 5-6 minutos, desbaratándolo con una cuchara de madera, hasta que esté completamente dorado.', 'Incorpora el tomate picado y el comino, mezclando bien.', 'Agrega los frijoles rojos escurridos.', 'Baja el fuego, tapa la olla y cocina 15 minutos, revolviendo de vez en cuando, hasta que el chili espese y los sabores se integren.', 'Rectifica sal y pimienta al gusto.', 'Sirve caliente, en bowls individuales.'],
 ['alto en proteína', 'económico'], 420, 37, 30, 10, 28, 5, 5, 'https://images.pexels.com/photos/9213869/pexels-photo-9213869.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pollo al limón con espárragos', 'almuerzo',
 ['pechuga de pollo|200 g', 'espárragos|8 unidades', 'limón|1 unidad', 'ajo|1 diente', 'aceite de oliva|1 cda'],
 ['Sazona la pechuga de pollo con sal y pimienta.', 'Calienta el aceite de oliva en una sartén a fuego medio-alto.', 'Sella la pechuga 5-6 minutos por cada lado hasta dorar y cocinar bien por dentro; retira y reserva.', 'En la misma sartén, baja el fuego a medio y sofríe el ajo picado 30 segundos.', 'Agrega el jugo de limón y la ralladura, raspando el fondo de la sartén para incorporar los jugos del pollo.', 'Cocina la salsa 1-2 minutos hasta que reduzca ligeramente.', 'En otra sartén, saltea los espárragos con un poco de aceite 4-5 minutos hasta que estén tiernos.', 'Regresa el pollo a la salsa de limón, báñalo bien y calienta 1 minuto más.', 'Sirve el pollo bañado en la salsa junto a los espárragos.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 420, 40, 20, 10, 24, 2, 4, 'https://images.pexels.com/photos/1247677/pexels-photo-1247677.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Salmón a la parrilla con espárragos', 'almuerzo',
 ['filete de salmón|180 g', 'espárragos|8 unidades', 'limón|1/2 unidad', 'aceite de oliva|1 cda', 'eneldo|1 pizca'],
 ['Seca bien el filete de salmón con papel absorbente.', 'Sazona con sal, pimienta y el eneldo picado por ambos lados.', 'Calienta una sartén o parrilla a fuego medio-alto con un poco de aceite.', 'Coloca el salmón con la piel hacia abajo (si la tiene) y cocina 4 minutos sin moverlo, hasta que la piel esté crocante.', 'Voltea con cuidado y cocina 3-4 minutos más del otro lado, hasta el punto de cocción que prefieras.', 'Retira y deja reposar un minuto.', 'En otra sartén, saltea los espárragos con aceite de oliva 4-5 minutos hasta que estén tiernos y ligeramente dorados.', 'Sirve el salmón con los espárragos y un toque de jugo de limón fresco.'],
 ['alto en proteína', 'sin gluten', 'rápido'], 420, 40, 18, 6, 26, 2, 4, 'https://images.pexels.com/photos/16845479/pexels-photo-16845479.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Camarones al ajillo', 'almuerzo',
 ['camarones|200 g', 'ajo|3 dientes', 'aceite de oliva|2 cdas', 'perejil|2 ramas', 'ají en hojuelas|1 pizca'],
 ['Mezcla los camarones con el ajo, la mantequilla derretida, el chile y la sal.', 'Coloca en una sola capa en la canasta.', 'Cocina a 200°C por 4 minutos, sacude la canasta.', 'Cocina 4 minutos más. Termina con limón y perejil.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 320, 26, 15, 6, 20, 1, 2, 'https://images.pexels.com/photos/8633745/pexels-photo-8633745.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de aguacate y camarones', 'almuerzo',
 ['camarones|150 g', 'aguacate|1 unidad', 'tomate cherry|8 unidades', 'lechuga|2 tazas', 'limón|1/2 unidad'],
 ['Lleva una olla con agua a hervor.', 'Agrega los camarones y cocina 2-3 minutos hasta que estén rosados y firmes.', 'Escurre de inmediato y pásalos por agua fría o hielo para detener la cocción.', 'Lava y trocea la lechuga en un bowl grande.', 'Corta el aguacate en cubos y agrégalo a la lechuga.', 'Parte los tomates cherry a la mitad e incorpóralos también.', 'Agrega los camarones fríos encima de la ensalada.', 'Aliña con jugo de limón, aceite de oliva, sal y pimienta.', 'Mezcla suavemente para no aplastar el aguacate, y sirve de inmediato.'],
 ['alto en proteína', 'sin gluten', 'rápido'], 420, 30, 15, 20, 25, 5, 10, 'https://images.pexels.com/photos/10875293/pexels-photo-10875293.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Calamares a la romana keto', 'almuerzo',
 ['calamares|200 g', 'harina de almendra|1/2 taza', 'huevos|1 unidad', 'aceite|2 cdas', 'limón|1/2 unidad'],
 ['Corta los calamares en aros de aproximadamente 1 cm de ancho.', 'Sécalos muy bien con papel absorbente; esto es clave para que la harina se adhiera y queden crocantes.', 'Bate el huevo en un bowl.', 'Coloca la harina de almendra en otro bowl.', 'Pasa cada aro de calamar primero por el huevo batido, dejando que escurra el exceso.', 'Luego pásalo por la harina de almendra, cubriendo bien por todos lados.', 'Calienta el aceite en una sartén a fuego medio-alto hasta que esté bien caliente.', 'Fríe los aros en tandas pequeñas, sin amontonarlos, 2-3 minutos hasta que doren y queden crocantes.', 'Retira con una espumadera y colócalos sobre papel absorbente para quitar el exceso de aceite.', 'Sirve calientes con jugo de limón por encima.'],
 ['alto en proteína', 'sin gluten'], 420, 35, 25, 5, 32, 1, 2, 'https://images.pexels.com/photos/8250284/pexels-photo-8250284.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Langosta con mantequilla de ajo y brócoli al vapor', 'almuerzo',
 ['cola de langosta|1 unidad', 'mantequilla|1 cda', 'ajo|2 dientes', 'brócoli|1 taza', 'perejil|1 rama'],
 ['Lleva una olla grande con agua y sal a hervor.', 'Sumerge la cola de langosta y cocina 5-6 minutos hasta que la carne esté opaca y firme.', 'Retira, deja enfriar un poco y con unas tijeras de cocina corta el caparazón por encima para extraer la carne entera.', 'Corta la carne de langosta en medallones.', 'En una sartén pequeña, derrite la mantequilla a fuego bajo.', 'Agrega el ajo picado y cocina 1 minuto hasta que suelte aroma, sin dorar demasiado.', 'Baña los medallones de langosta con la mantequilla de ajo.', 'Mientras tanto, cocina el brócoli al vapor 5 minutos hasta que esté tierno.', 'Sirve la langosta bañada en la mantequilla, con el brócoli al lado y perejil picado por encima.'],
 ['alto en proteína', 'sin gluten'], 420, 35, 25, 10, 30, 2, 5, 'https://images.pexels.com/photos/24246111/pexels-photo-24246111.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de atún y aguacate', 'almuerzo',
 ['atún en lata|1 lata', 'aguacate|1 unidad', 'tomate|1 unidad', 'cebolla morada|1/4 unidad', 'limón|1/2 unidad'],
 ['Escurre bien el atún en lata, presionando con un tenedor para sacar el exceso de líquido.', 'Colócalo en un bowl mediano y desmenúzalo un poco con el tenedor.', 'Corta el aguacate en cubos y agrégalo al bowl.', 'Pica el tomate en cubos pequeños e incorpóralo.', 'Corta la cebolla morada en juliana muy fina para que no domine el sabor, y añádela.', 'Agrega el jugo de limón, sal, pimienta y un chorrito de aceite de oliva.', 'Mezcla todo con suavidad para no deshacer demasiado el aguacate.', 'Sirve de inmediato, recién preparada.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 420, 35, 10, 20, 25, 5, 10, 'https://images.pexels.com/photos/17597485/pexels-photo-17597485.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pulpo a la parrilla con aceite de oliva y limón', 'almuerzo',
 ['pulpo cocido|200 g', 'aceite de oliva|2 cdas', 'limón|1 unidad', 'ajo|1 diente', 'pimentón dulce|1 pizca'],
 ['Corta el pulpo cocido en trozos grandes, dejando los tentáculos enteros si es posible para que se vean apetitosos al servir.', 'En un bowl, mezcla el aceite de oliva, el ajo picado y el jugo de limón.', 'Marina el pulpo en esta mezcla 5-10 minutos.', 'Calienta una parrilla o sartén de hierro a fuego alto hasta que esté muy caliente.', 'Sella el pulpo 2-3 minutos por cada lado, sin moverlo demasiado, hasta que tenga un dorado marcado por fuera.', 'Retira y espolvorea con pimentón dulce.', 'Sirve caliente con un chorrito extra de aceite de oliva y jugo de limón fresco.'],
 ['alto en proteína', 'sin gluten'], 240, 35, 20, 6, 12, 1, 1, 'https://images.pexels.com/photos/26571194/pexels-photo-26571194.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sopa de pescado keto', 'almuerzo',
 ['filete de pescado blanco|200 g', 'caldo de pescado|2 tazas', 'tomate|1 unidad', 'cebolla|1/4 unidad', 'cilantro|2 ramas'],
 ['Calienta un poco de aceite en una olla a fuego medio.', 'Sofríe la cebolla picada 2-3 minutos hasta que esté transparente.', 'Agrega el tomate picado y cocina 2-3 minutos más hasta que suelte su jugo.', 'Vierte el caldo de pescado y lleva a hervor.', 'Baja el fuego y cocina 5 minutos para que los sabores se integren.', 'Corta el pescado en trozos medianos y agrégalo a la olla.', 'Cocina 5-6 minutos más, sin revolver demasiado para que el pescado no se deshaga, hasta que esté opaco y cocido por dentro.', 'Rectifica sal y sirve caliente con cilantro fresco picado por encima.'],
 ['alto en proteína', 'sin gluten'], 420, 35, 25, 5, 32, 2, 1, 'https://images.pexels.com/photos/5041490/pexels-photo-5041490.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sashimi de salmón y atún', 'almuerzo',
 ['salmón fresco de calidad sashimi|100 g', 'atún fresco de calidad sashimi|100 g', 'salsa de soya|2 cdas', 'jengibre|1 cdta', 'wasabi|1 pizca'],
 ['Asegúrate de usar pescado fresco de calidad para consumo crudo (grado sashimi) y mantenlo bien frío hasta el momento de prepararlo.', 'Con un cuchillo muy afilado, corta el salmón en láminas delgadas de unos 5 mm de grosor, cortando siempre en un solo movimiento limpio (no sierres).', 'Repite el mismo proceso con el atún.', 'Acomoda las láminas de salmón y atún en un plato bien frío, alternando colores para que se vea atractivo.', 'Sirve con un pocillo pequeño de salsa de soya aparte.', 'Acompaña con jengibre encurtido y una pizca de wasabi al lado.', 'Sirve de inmediato, recién cortado.'],
 ['alto en proteína', 'sin gluten', 'rápido'], 220, 35, 10, 6, 8, 2, 2, 'https://images.pexels.com/photos/8952740/pexels-photo-8952740.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Mariscada al horno', 'almuerzo',
 ['camarones|100 g', 'mejillones|100 g', 'calamares|100 g', 'ajo|2 dientes', 'aceite de oliva|2 cdas', 'perejil|1 rama'],
 ['Precalienta el horno a 200°C.', 'En un bowl grande, mezcla los camarones, mejillones y calamares.', 'Agrega el ajo picado, el aceite de oliva y una pizca de sal y pimienta.', 'Mezcla bien para que todos los mariscos queden bañados en el aceite y el ajo.', 'Extiende los mariscos en una bandeja para horno en una sola capa.', 'Hornea 12-15 minutos, hasta que los camarones estén rosados, los mejillones abiertos y los calamares firmes.', 'Retira del horno y espolvorea perejil fresco picado.', 'Sirve caliente con el jugo de la cocción por encima.'],
 ['alto en proteína', 'sin gluten'], 420, 35, 25, 10, 25, 2, 2, 'https://images.pexels.com/photos/1122915/pexels-photo-1122915.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pechuga de pavo con espárragos', 'almuerzo',
 ['pechuga de pavo|200 g', 'espárragos|8 unidades', 'ajo|1 diente', 'aceite de oliva|1 cda', 'romero|1 cdta'],
 ['Sazona la pechuga de pavo con el ajo machacado, el romero, sal y pimienta, frotando bien por todos lados.', 'Deja reposar 5 minutos para que tome sabor.', 'Calienta el aceite de oliva en una sartén a fuego medio.', 'Sella la pechuga de pavo 5-6 minutos por cada lado hasta que esté dorada y bien cocida por dentro.', 'Retira del fuego y deja reposar 3 minutos antes de cortarla en tajadas.', 'En la misma sartén, saltea los espárragos 4-5 minutos hasta que estén tiernos.', 'Sirve el pavo en tajadas junto a los espárragos.'],
 ['alto en proteína', 'sin gluten'], 420, 40, 25, 10, 24, 2, 5, 'https://images.pexels.com/photos/8250430/pexels-photo-8250430.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de aguacate y nueces de macadamia', 'almuerzo',
 ['aguacate|1 unidad', 'lechuga|2 tazas', 'nueces de macadamia|2 cdas', 'queso feta|30 g', 'aceite de oliva|1 cda'],
 ['Lava y seca bien la lechuga, luego trocéala en un bowl grande.', 'Corta el aguacate en cubos y agrégalo a la lechuga.', 'Trocea las nueces de macadamia y espolvoréalas por encima.', 'Desmenuza el queso feta con los dedos o un tenedor y agrégalo también.', 'Aliña con aceite de oliva, sal y pimienta al gusto.', 'Mezcla con suavidad, procurando no aplastar el aguacate.', 'Sirve de inmediato, fresca.'],
 ['vegetariano', 'sin gluten', 'rápido'], 540, 15, 10, 20, 45, 5, 10, 'https://images.pexels.com/photos/6327655/pexels-photo-6327655.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Filete de res con espárragos y mantequilla de ajo', 'almuerzo',
 ['filete de res|200 g', 'espárragos|8 unidades', 'mantequilla|1 cda', 'ajo|2 dientes', 'sal|1 pizca'],
 ['Saca el filete de la nevera 10 minutos antes de cocinarlo para que no esté tan frío.', 'Sazona generosamente con sal y pimienta por ambos lados.', 'Calienta una sartén a fuego alto hasta que esté muy caliente.', 'Sella el filete 3-4 minutos por cada lado para término medio (ajusta el tiempo según el grosor y el punto que prefieras).', 'Retira el filete y déjalo reposar 3-5 minutos sobre una tabla, cubierto con papel aluminio, para que los jugos se redistribuyan.', 'En la misma sartén, baja el fuego a medio y derrite la mantequilla.', 'Agrega el ajo picado y cocina 1 minuto hasta que suelte aroma.', 'Saltea los espárragos en esta mantequilla 4-5 minutos hasta que estén tiernos.', 'Corta el filete y sírvelo bañado con la mantequilla de ajo, junto a los espárragos.'],
 ['alto en proteína', 'sin gluten'], 540, 50, 20, 10, 35, 2, 5, 'https://images.pexels.com/photos/8840884/pexels-photo-8840884.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bistec de cerdo con coles de Bruselas', 'almuerzo',
 ['bistec de cerdo|200 g', 'coles de bruselas|1 taza', 'ajo|1 diente', 'aceite de oliva|1 cda', 'limón|1/2 unidad'],
 ['Sazona el bistec de cerdo con sal y pimienta por ambos lados.', 'Calienta una sartén a fuego medio-alto con un poco de aceite.', 'Sella el bistec 4-5 minutos por cada lado, hasta que esté bien dorado por fuera y cocido por dentro (el cerdo debe quedar bien cocido, sin partes rosadas).', 'Retira y deja reposar 3 minutos.', 'Corta las coles de Bruselas a la mitad.', 'En la misma sartén, agrega el ajo picado y las coles con el corte hacia abajo.', 'Cocina 5-6 minutos, sin mover mucho, hasta que las coles estén doradas por ese lado y tiernas por dentro.', 'Sirve el bistec con las coles de Bruselas y un toque de jugo de limón.'],
 ['alto en proteína', 'sin gluten'], 540, 37, 25, 20, 34, 5, 7, 'https://images.pexels.com/photos/36678410/pexels-photo-36678410.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Muslos de pollo con pimientos y cebolla', 'almuerzo',
 ['muslos de pollo|250 g', 'pimentón|1 unidad', 'cebolla|1/2 unidad', 'ajo|2 dientes', 'aceite de oliva|1 cda'],
 ['Sazona los muslos de pollo con sal y pimienta.', 'Calienta el aceite de oliva en una sartén grande a fuego medio-alto.', 'Sella los muslos con la piel hacia abajo primero, 4-5 minutos, hasta que estén dorados y crocantes.', 'Voltea y dora 3-4 minutos más del otro lado.', 'Retira los muslos y en la misma sartén agrega la cebolla y el pimentón cortados en tiras.', 'Sofríe 3-4 minutos hasta que empiecen a ablandar, y agrega el ajo picado.', 'Regresa los muslos de pollo a la sartén, sobre las verduras.', 'Tapa y cocina a fuego medio-bajo 18-20 minutos, hasta que el pollo esté completamente cocido y jugoso.', 'Sirve caliente con los pimientos y la cebolla.'],
 ['alto en proteína', 'económico'], 420, 37, 35, 10, 26, 4, 3, 'https://images.pexels.com/photos/13065207/pexels-photo-13065207.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Berenjenas rellenas de carne molida', 'almuerzo',
 ['berenjena|1 unidad', 'carne molida de res|150 g', 'tomate|1 unidad', 'cebolla|1/4 unidad', 'queso mozarella|30 g'],
 ['Precalienta el horno a 200°C.', 'Corta la berenjena a la mitad a lo largo.', 'Con una cuchara, retira parte de la pulpa del centro, dejando un borde de aproximadamente 1 cm; pica la pulpa retirada y resérvala.', 'Calienta un poco de aceite en una sartén y sofríe la cebolla picada 2-3 minutos.', 'Agrega el tomate y la pulpa de berenjena picada, cocinando 3-4 minutos.', 'Incorpora la carne molida y cocina 6-8 minutos, desbaratándola con una cuchara, hasta que esté bien dorada.', 'Sazona con sal y pimienta.', 'Rellena las mitades de berenjena con la mezcla de carne, presionando un poco.', 'Cubre cada mitad con queso mozarella rallado.', 'Coloca en una bandeja para horno y hornea 20-25 minutos hasta que la berenjena esté tierna y el queso dorado.'],
 ['alto en proteína'], 420, 35, 40, 20, 25, 8, 6, 'https://images.pexels.com/photos/29040190/pexels-photo-29040190.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Berenjenas a la parmesana baja en carbohidratos', 'almuerzo',
 ['berenjena|1 unidad', 'salsa de tomate|1/2 taza', 'queso mozarella|40 g', 'queso parmesano|2 cdas', 'orégano|1 pizca'],
 ['Precalienta el horno a 200°C.', 'Corta la berenjena en rodajas de aproximadamente 1 cm de grosor.', 'Ásalas en una sartén con un poco de aceite, 2-3 minutos por lado, hasta que doren ligeramente (esto evita que queden aguadas al hornear).', 'En un molde para horno, coloca una capa de rodajas de berenjena.', 'Cubre con un poco de salsa de tomate y queso mozarella rallado.', 'Repite las capas: berenjena, salsa, queso, hasta terminar los ingredientes.', 'Espolvorea el queso parmesano y el orégano en la capa final.', 'Hornea 15-18 minutos hasta que el queso esté dorado y burbujeante.', 'Deja reposar 3 minutos antes de servir.'],
 ['vegetariano'], 420, 35, 35, 10, 30, 6, 5, 'https://images.pexels.com/photos/13214151/pexels-photo-13214151.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de brócoli con tocino y queso cheddar', 'almuerzo',
 ['brócoli|2 tazas', 'tocino|60 g', 'queso cheddar|30 g', 'cebolla morada|1/4 unidad', 'yogur griego|2 cdas'],
 ['Corta el brócoli en floretes pequeños.', 'Cocínalo al vapor 4-5 minutos hasta que esté tierno pero firme; retira y deja enfriar por completo.', 'Mientras tanto, dora el tocino en una sartén a fuego medio hasta que esté crocante, unos 5-6 minutos.', 'Retira el tocino sobre papel absorbente y trocéalo cuando esté frío.', 'En un bowl, mezcla el brócoli frío con el tocino troceado.', 'Corta el queso cheddar en cubos pequeños y agrégalo.', 'Pica la cebolla morada finamente e incorpórala.', 'Aliña con el yogur griego, mezclando bien hasta cubrir todos los ingredientes.', 'Sirve frío o a temperatura ambiente.'],
 ['alto en proteína', 'sin gluten'], 420, 25, 20, 15, 30, 5, 5, 'https://images.pexels.com/photos/30700803/pexels-photo-30700803.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de salchichas y col rizada', 'almuerzo',
 ['salchichas|2 unidades', 'col rizada|2 tazas', 'tomate cherry|8 unidades', 'aceite de oliva|1 cda', 'limón|1/2 unidad'],
 ['Cocina las salchichas a la plancha con un poco de aceite, 4-5 minutos por lado, hasta que estén doradas y bien cocidas.', 'Deja enfriar un poco y córtalas en rodajas.', 'Mientras tanto, lava la col rizada y quita los tallos duros, luego trocéala.', 'Coloca la col en un bowl grande, rocía con un poco de aceite de oliva y jugo de limón.', 'Masajea la col con las manos durante 1-2 minutos; esto ayuda a suavizarla y quitarle el amargor.', 'Agrega las rodajas de salchicha a la col.', 'Parte los tomates cherry a la mitad e incorpóralos.', 'Mezcla bien todos los ingredientes y sirve tibio o frío.'],
 ['económico', 'rápido'], 420, 25, 15, 20, 30, 5, 5, 'https://images.pexels.com/photos/12363203/pexels-photo-12363203.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Lasaña keto', 'almuerzo',
 ['berenjena|1 unidad', 'carne molida de res|200 g', 'salsa de tomate|1/2 taza', 'queso mozarella|60 g', 'queso parmesano|2 cdas'],
 ['Precalienta el horno a 200°C.', 'Corta la berenjena en láminas delgadas a lo largo, de unos 3-4 mm de grosor, para que hagan de "pasta".', 'Ásalas ligeramente en una sartén, 1-2 minutos por lado, para quitarles humedad; reserva.', 'En una olla, cocina la carne molida a fuego medio hasta que dore, unos 6-8 minutos.', 'Agrega la salsa de tomate y cocina 5 minutos más hasta que espese un poco; sazona con sal y pimienta.', 'En un molde para horno, arma una primera capa de láminas de berenjena.', 'Cubre con una capa de la carne en salsa y un poco de queso mozarella.', 'Repite las capas (berenjena, carne, queso) hasta terminar los ingredientes, dejando queso para el final.', 'Espolvorea el queso parmesano encima.', 'Hornea 25 minutos hasta que esté bien caliente por dentro y dorado por encima.', 'Deja reposar 5 minutos antes de cortar y servir, para que tome consistencia.'],
 ['alto en proteína'], 540, 37, 45, 10, 42, 6, 5, 'https://images.pexels.com/photos/5949887/pexels-photo-5949887.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pastelón de pavo', 'almuerzo',
 ['pavo molido|200 g', 'plátano maduro|2 unidades', 'queso mozarella|40 g', 'tomate|1 unidad', 'cebolla|1/4 unidad'],
 ['Corta el plátano maduro en tajadas a lo largo.', 'Ásalas en una sartén con un poco de aceite, 2-3 minutos por lado, hasta que doren; reserva.', 'En la misma sartén, sofríe la cebolla y el tomate picados 3 minutos.', 'Agrega el pavo molido y cocina 6-8 minutos, desbaratándolo, hasta que esté bien dorado; sazona con sal y pimienta.', 'Precalienta el horno a 200°C.', 'En un molde, arma una capa de tajadas de plátano.', 'Cubre con la mitad del pavo cocido.', 'Repite: otra capa de plátano y el resto del pavo.', 'Cubre todo con el queso mozarella rallado.', 'Hornea 15-18 minutos hasta que el queso esté dorado y gratinado.'],
 ['alto en proteína', 'tradicional'], 420, 35, 40, 25, 22, 8, 4, 'https://images.pexels.com/photos/37206688/pexels-photo-37206688.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pastelón de coliflor con carne molida', 'almuerzo',
 ['coliflor|2 tazas', 'carne molida de res|200 g', 'tomate|1 unidad', 'queso mozarella|40 g', 'cebolla|1/4 unidad'],
 ['Corta la coliflor en floretes y cocínala al vapor 8-10 minutos hasta que esté muy tierna.', 'Haz un puré grueso con un tenedor o prensapapas, dejando algo de textura; sazona con sal y pimienta.', 'Mientras tanto, sofríe la cebolla y el tomate en una sartén 3 minutos.', 'Agrega la carne molida y cocina 6-8 minutos hasta que esté bien dorada.', 'Precalienta el horno a 200°C.', 'En un molde, coloca una capa del puré de coliflor.', 'Cubre con la carne molida cocida.', 'Termina con otra capa de puré de coliflor por encima.', 'Espolvorea el queso mozarella rallado.', 'Hornea 15 minutos hasta que el queso esté dorado y gratinado.'],
 ['alto en proteína', 'sin gluten'], 420, 35, 35, 20, 25, 5, 5, 'https://images.pexels.com/photos/7474378/pexels-photo-7474378.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pastelón de berenjena y pollo', 'almuerzo',
 ['berenjena|1 unidad', 'pollo desmechado|200 g', 'salsa de tomate|1/2 taza', 'queso mozarella|40 g'],
 ['Corta la berenjena en láminas delgadas a lo largo.', 'Ásalas ligeramente en una sartén con un poco de aceite, 1-2 minutos por lado.', 'En un bowl, mezcla el pollo desmechado con la salsa de tomate hasta cubrir bien.', 'Precalienta el horno a 200°C.', 'En un molde para horno, arma una capa de berenjena.', 'Cubre con la mitad del pollo en salsa.', 'Repite las capas: berenjena, pollo, terminando con berenjena arriba.', 'Cubre con el queso mozarella rallado.', 'Hornea 18-20 minutos hasta que la berenjena esté tierna y el queso dorado.'],
 ['alto en proteína'], 420, 35, 40, 25, 22, 8, 5, 'https://images.pexels.com/photos/19177208/pexels-photo-19177208.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pastelón de calabacín con cerdo desmenuzado', 'almuerzo',
 ['calabacín|2 unidades', 'cerdo desmenuzado|200 g', 'salsa de tomate|1/2 taza', 'queso mozarella|40 g'],
 ['Corta el calabacín en láminas delgadas a lo largo con un pelador o cuchillo afilado.', 'En un bowl, mezcla el cerdo desmenuzado con la salsa de tomate.', 'Precalienta el horno a 200°C.', 'En un molde para horno, coloca una primera capa de láminas de calabacín.', 'Cubre con la mitad del cerdo en salsa.', 'Repite las capas hasta terminar los ingredientes.', 'Cubre la capa final con el queso mozarella rallado.', 'Hornea 18-20 minutos, hasta que el calabacín esté tierno y el queso dorado y burbujeante.', 'Deja reposar unos minutos antes de servir para que asiente.'],
 ['alto en proteína'], 420, 35, 40, 20, 25, 5, 4, 'https://images.pexels.com/photos/32039641/pexels-photo-32039641.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pescado a la plancha con brócoli', 'almuerzo',
 ['filete de pescado blanco|200 g', 'brócoli|1 taza', 'limón|1/2 unidad', 'ajo|1 diente', 'aceite de oliva|1 cda'],
 ['Seca bien el filete de pescado con papel absorbente.', 'Sazona con sal, pimienta, el ajo picado y un poco de jugo de limón.', 'Calienta el aceite de oliva en una sartén a fuego medio-alto.', 'Cocina el pescado 3-4 minutos por el primer lado, sin mover, hasta que esté dorado.', 'Voltea con cuidado usando una espátula y cocina 3-4 minutos más del otro lado, hasta que esté opaco y se desmenuce fácil con un tenedor.', 'Mientras tanto, cocina el brócoli al vapor 5 minutos hasta que esté tierno.', 'Sirve el pescado con el brócoli al lado y un poco más de limón al gusto.'],
 ['alto en proteína', 'sin gluten', 'rápido'], 320, 35, 18, 10, 15, 2, 5, 'https://images.pexels.com/photos/5860604/pexels-photo-5860604.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de quinoa, lechuga, tomate y pollo', 'almuerzo',
 ['quinoa|1/2 taza cocida', 'pechuga de pollo|150 g', 'lechuga|1 taza', 'tomate|1 unidad', 'aceite de oliva|1 cda'],
 ['Sazona la pechuga de pollo con sal y pimienta.', 'Cocina a la plancha 5-6 minutos por cada lado hasta que esté dorada y bien cocida.', 'Deja reposar 3 minutos y córtala en cubos.', 'En un bowl grande, coloca la quinoa ya cocida.', 'Agrega la lechuga picada y el tomate en cubos.', 'Incorpora el pollo en cubos.', 'Aliña con aceite de oliva, jugo de limón, sal y pimienta.', 'Mezcla bien todos los ingredientes y sirve.'],
 ['alto en proteína', 'sin gluten'], 420, 35, 20, 30, 20, 5, 5, 'https://images.pexels.com/photos/25315523/pexels-photo-25315523.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Espaguetis de calabacín con camarones al ajillo', 'almuerzo',
 ['calabacín|2 unidades', 'camarones|150 g', 'ajo|2 dientes', 'aceite de oliva|1 cda', 'perejil|1 rama'],
 ['Con un pelador o espiralizador, corta el calabacín en tiras finas tipo espagueti.', 'Colócalas sobre papel absorbente para quitar el exceso de humedad mientras preparas el resto.', 'Calienta el aceite de oliva en una sartén a fuego medio.', 'Sofríe el ajo picado 30 segundos.', 'Agrega los camarones y cocina 2 minutos por lado hasta que estén rosados; retira y reserva.', 'En la misma sartén, agrega el "espagueti" de calabacín y saltea 2 minutos, moviendo constantemente, hasta que esté apenas tierno (evita cocinarlo de más para que no suelte mucha agua).', 'Regresa los camarones a la sartén y mezcla bien.', 'Espolvorea perejil fresco picado y sirve de inmediato.'],
 ['alto en proteína', 'sin gluten', 'rápido'], 420, 35, 20, 20, 25, 5, 4, 'https://images.pexels.com/photos/6718709/pexels-photo-6718709.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tallarines de pepino con salmón ahumado', 'almuerzo',
 ['pepino|2 unidades', 'salmón ahumado|100 g', 'queso crema|1 cda', 'eneldo|1 pizca', 'limón|1/2 unidad'],
 ['Con un pelador, corta el pepino en tiras finas a lo largo, tipo tallarín, girándolo a medida que avanzas.', 'Colócalas en un bowl.', 'En un bowl pequeño, mezcla el queso crema con un poco de jugo de limón hasta que quede una salsa suave y untable.', 'Baña los tallarines de pepino con esta salsa, mezclando con cuidado.', 'Corta el salmón ahumado en trozos y colócalo encima.', 'Espolvorea eneldo fresco picado.', 'Sirve frío, recién armado.'],
 ['alto en proteína', 'sin gluten', 'rápido'], 420, 35, 12, 20, 25, 5, 4, 'https://images.pexels.com/photos/20051297/pexels-photo-20051297.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de quinoa con garbanzos y vegetales', 'almuerzo',
 ['quinoa|1/2 taza cocida', 'garbanzos|1/2 taza', 'tomate|1 unidad', 'pepino|1/2 unidad', 'aceite de oliva|1 cda'],
 ['En un bowl grande, coloca la quinoa ya cocida y fría.', 'Escurre bien los garbanzos y agrégalos a la quinoa.', 'Corta el tomate y el pepino en cubos pequeños e incorpóralos.', 'Aliña con aceite de oliva, jugo de limón, sal y pimienta.', 'Mezcla bien todos los ingredientes hasta integrar.', 'Deja reposar 5 minutos antes de servir para que los sabores se asienten, o sírvela de inmediato si prefieres.'],
 ['vegetariano', 'sin gluten', 'rápido'], 420, 18, 15, 60, 15, 8, 10, 'https://images.pexels.com/photos/5639367/pexels-photo-5639367.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tacos de pescado con salsa de aguacate', 'almuerzo',
 ['filete de pescado blanco|200 g', 'tortillas de maíz|4 unidades', 'aguacate|1 unidad', 'col morada|1/2 taza', 'limón|1/2 unidad'],
 ['Sazona el filete de pescado con sal, pimienta y un poco de limón.', 'Calienta una sartén a fuego medio-alto con un poco de aceite.', 'Cocina el pescado 3-4 minutos por lado hasta que esté opaco y se desmenuce fácilmente.', 'Retira y desmenúzalo en trozos medianos con un tenedor.', 'En un bowl, machaca el aguacate con jugo de limón y sal hasta obtener una salsa cremosa.', 'Calienta las tortillas de maíz en un comal o sartén seca, unos 20-30 segundos por lado.', 'Corta la col morada en juliana fina.', 'Arma los tacos: coloca el pescado desmenuzado sobre cada tortilla, agrega la col morada y termina con una cucharada de salsa de aguacate.', 'Sirve de inmediato con un gajo extra de limón.'],
 ['alto en proteína', 'rápido'], 420, 35, 20, 30, 20, 5, 7, 'https://images.pexels.com/photos/10296469/pexels-photo-10296469.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Salteado de camarones y espinacas', 'cena',
 ['camarones|180 g', 'espinacas|1 taza', 'ajo|2 dientes', 'aceite de oliva|1 cda', 'limón|1/2 unidad'],
 ['Pela y limpia los camarones si es necesario.', 'Calienta el aceite de oliva en una sartén a fuego medio-alto.', 'Sofríe el ajo picado 30 segundos hasta que suelte aroma.', 'Agrega los camarones en una sola capa y cocina 2 minutos sin mover.', 'Voltea los camarones y cocina 1-2 minutos más hasta que estén rosados y firmes.', 'Incorpora las espinacas y saltea 1-2 minutos, moviendo constantemente, hasta que se marchiten.', 'Retira del fuego y termina con un chorrito de jugo de limón.', 'Sirve caliente de inmediato.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 320, 30, 15, 10, 20, 2, 5, 'https://images.pexels.com/photos/36865007/pexels-photo-36865007.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Hamburguesa keto clásica', 'cena',
 ['carne molida de res|200 g', 'lechuga|2 hojas', 'tomate|1/2 unidad', 'queso cheddar|1 tajada', 'cebolla|1/4 unidad'],
 ['En un bowl, sazona la carne molida con sal y pimienta, mezclando con las manos sin trabajarla demasiado.', 'Forma una hamburguesa gruesa y despareja apenas en el centro (para que no se abombe al cocinar).', 'Calienta una sartén o parrilla a fuego medio-alto.', 'Cocina la hamburguesa 4 minutos por el primer lado sin moverla.', 'Voltea y coloca la tajada de queso cheddar encima; cocina 3-4 minutos más hasta el punto que prefieras.', 'Retira del fuego y deja reposar 2 minutos.', 'Lava las hojas de lechuga y sécalas; úsalas como "envoltorio" en lugar de pan.', 'Arma la hamburguesa sobre la lechuga con rodajas de tomate y cebolla.', 'Envuelve con la lechuga y sirve de inmediato.'],
 ['alto en proteína', 'sin gluten'], 540, 37, 20, 5, 46, 2, 2, 'https://images.pexels.com/photos/11663137/pexels-photo-11663137.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Hamburguesas keto de salmón con puré de brócoli y mantequilla de limón', 'cena',
 ['salmón|200 g', 'brócoli|1 taza', 'mantequilla|1 cda', 'limón|1/2 unidad', 'huevo|1 unidad'],
 ['Pica el salmón finamente con un cuchillo, dejando algo de textura (no lo licúes).', 'En un bowl, mezcla el salmón picado con el huevo, sal y pimienta hasta integrar.', 'Forma dos hamburguesas con la mezcla, presionando bien para que no se deshagan al cocinar.', 'Calienta una sartén con un poco de aceite a fuego medio.', 'Cocina las hamburguesas de salmón 3-4 minutos por cada lado hasta que doren por fuera y estén cocidas por dentro.', 'Mientras tanto, cocina el brócoli al vapor 6-7 minutos hasta que esté muy tierno.', 'Machaca el brócoli con un tenedor o prensapapas, agregando la mantequilla y el jugo de limón, hasta obtener un puré cremoso.', 'Sirve las hamburguesas de salmón sobre una cama del puré de brócoli.'],
 ['alto en proteína', 'sin gluten'], 540, 37, 25, 6, 42, 2, 3, 'https://images.pexels.com/photos/20182311/pexels-photo-20182311.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Burrito de tortilla de coliflor', 'cena',
 ['coliflor|2 tazas ralladas', 'huevos|2 unidades', 'queso mozarella|30 g', 'pollo desmechado|100 g', 'aguacate|1/4 unidad'],
 ['Ralla la coliflor cruda o pícala muy fino en el procesador.', 'En un bowl, mezcla la coliflor rallada con los huevos y el queso mozarella, sazonando con sal y pimienta.', 'Calienta una sartén antiadherente a fuego medio con un poco de aceite.', 'Extiende la mezcla en la sartén formando una tortilla delgada y pareja.', 'Cocina 3-4 minutos hasta que la base esté firme y dorada, luego voltea con cuidado usando un plato.', 'Cocina 3-4 minutos más del otro lado hasta que esté bien cocida.', 'Retira y deja enfriar un par de minutos sobre una superficie plana.', 'Rellena con el pollo desmechado y el aguacate en láminas.', 'Enrolla con cuidado como un burrito y sirve.'],
 ['alto en proteína', 'sin gluten'], 520, 37, 25, 20, 34, 5, 10, 'https://images.pexels.com/photos/30392937/pexels-photo-30392937.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Burrito de pollo y aguacate', 'cena',
 ['tortilla integral|1 unidad', 'pechuga de pollo|150 g', 'aguacate|1/2 unidad', 'lechuga|2 hojas', 'tomate|1/2 unidad'],
 ['Sazona la pechuga de pollo con sal y pimienta.', 'Cocina a la plancha 5-6 minutos por cada lado hasta que esté dorada y bien cocida.', 'Deja reposar 3 minutos y córtala en tiras.', 'Calienta ligeramente la tortilla integral en una sartén seca o directo sobre la llama, unos segundos por lado.', 'Extiende la tortilla sobre un plato.', 'Coloca las tiras de pollo en el centro.', 'Agrega el aguacate en láminas, la lechuga y el tomate en rodajas.', 'Dobla los extremos hacia adentro y enrolla firmemente desde un lado.', 'Corta a la mitad en diagonal y sirve.'],
 ['alto en proteína', 'rápido'], 542, 37, 15, 44, 24, 6, 10, 'https://images.pexels.com/photos/10696501/pexels-photo-10696501.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Burrito de huevo y tocino', 'cena',
 ['tortilla integral|1 unidad', 'huevos|2 unidades', 'tocino|30 g', 'queso mozarella|20 g', 'cebolla larga|1 tallo'],
 ['Calienta una sartén a fuego medio y dora el tocino 4-5 minutos hasta que esté crocante.', 'Retira el tocino y resérvalo, dejando un poco de la grasa en la sartén.', 'Bate los huevos con la cebolla larga picada.', 'Vierte los huevos en la misma sartén y revuelve constantemente a fuego medio-bajo hasta que cuajen suave y cremoso.', 'Calienta la tortilla integral unos segundos por cada lado.', 'Coloca los huevos revueltos en el centro de la tortilla.', 'Agrega el tocino y el queso mozarella rallado encima.', 'Enrolla firmemente y sirve caliente.'],
 ['alto en proteína'], 550, 35, 15, 40, 30, 5, 6, 'https://images.pexels.com/photos/5848088/pexels-photo-5848088.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Burrito de camarones al ajillo', 'cena',
 ['tortilla integral|1 unidad', 'camarones|150 g', 'ajo|2 dientes', 'aceite de oliva|1 cda', 'lechuga|2 hojas'],
 ['Calienta el aceite de oliva en una sartén a fuego medio.', 'Sofríe el ajo picado 30 segundos.', 'Agrega los camarones y cocina 2-3 minutos por lado hasta que estén rosados y firmes.', 'Retira del fuego.', 'Calienta la tortilla integral unos segundos por cada lado.', 'Coloca las hojas de lechuga como base sobre la tortilla.', 'Agrega los camarones al ajillo encima.', 'Enrolla firmemente sosteniendo el relleno y sirve de inmediato.'],
 ['alto en proteína', 'rápido'], 420, 30, 15, 35, 20, 5, 6, 'https://images.pexels.com/photos/17146253/pexels-photo-17146253.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostada de huevo', 'cena',
 ['pan integral|1 tajada', 'huevos|1 unidad', 'sal|1 pizca', 'pimienta|1 pizca'],
 ['Tuesta la tajada de pan integral hasta que esté dorada.', 'Calienta una sartén pequeña con un poco de aceite a fuego medio.', 'Cocina el huevo al gusto: frito con la yema líquida, o poché sumergiéndolo en agua hirviendo con un chorrito de vinagre durante 3 minutos.', 'Coloca el huevo sobre la tostada.', 'Sazona con sal y pimienta recién molida.', 'Sirve de inmediato.'],
 ['rápido', 'vegetariano'], 220, 14, 8, 25, 8, 2, 4, 'https://images.pexels.com/photos/5720793/pexels-photo-5720793.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostada de huevo y aguacate', 'cena',
 ['pan integral|1 tajada', 'huevos|1 unidad', 'aguacate|1/2 unidad', 'sal|1 pizca'],
 ['Tuesta la tajada de pan integral.', 'Machaca el aguacate con un tenedor en un bowl pequeño, agregando sal al gusto.', 'Unta el aguacate machacado sobre la tostada.', 'Cocina el huevo al gusto en una sartén con un poco de aceite.', 'Coloca el huevo sobre el aguacate.', 'Sazona con pimienta y una pizca extra de sal si es necesario.', 'Sirve caliente, recién preparada.'],
 ['rápido', 'vegetariano'], 420, 22, 10, 30, 26, 4, 7, 'https://images.pexels.com/photos/5589043/pexels-photo-5589043.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pancakes de ahuyama', 'cena',
 ['ahuyama|1/2 taza cocida y en puré', 'huevos|2 unidades', 'harina de avena|1/2 taza', 'canela|1 pizca'],
 ['Si la ahuyama no está cocida todavía, córtala en cubos y cocínala al vapor o hervida 10-12 minutos hasta que esté muy tierna.', 'Hazla puré con un tenedor hasta que quede suave, sin grumos grandes.', 'En un bowl, mezcla el puré de ahuyama con los huevos, la harina de avena y la canela.', 'Integra bien hasta obtener una mezcla homogénea.', 'Calienta una sartén antiadherente a fuego medio-bajo con un poco de aceite.', 'Vierte porciones pequeñas de la mezcla formando pancakes.', 'Cocina 2-3 minutos por lado hasta que doren y estén firmes.', 'Sirve calientes, solos o con un toque de miel.'],
 ['vegetariano', 'rápido'], 320, 15, 20, 45, 12, 10, 5, 'https://images.pexels.com/photos/5377572/pexels-photo-5377572.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Ensalada de atún', 'cena',
 ['atún en lata|1 lata', 'lechuga|2 tazas', 'tomate|1 unidad', 'cebolla morada|1/4 unidad', 'aceite de oliva|1 cda'],
 ['Escurre bien el atún en lata.', 'Colócalo en un bowl y desmenúzalo con un tenedor.', 'Lava y trocea la lechuga, agregándola al bowl.', 'Corta el tomate en cubos e incorpóralo.', 'Corta la cebolla morada en juliana fina y agrégala.', 'Aliña con aceite de oliva, jugo de limón, sal y pimienta.', 'Mezcla bien todos los ingredientes y sirve fría.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 420, 35, 10, 10, 30, 5, 5, 'https://images.pexels.com/photos/19572488/pexels-photo-19572488.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tortilla de vegetales', 'cena',
 ['huevos|3 unidades', 'calabacín|1/2 unidad', 'pimentón|1/2 unidad', 'cebolla|1/4 unidad', 'aceite de oliva|1 cdta'],
 ['Corta el calabacín y el pimentón en cubos pequeños.', 'Pica la cebolla finamente.', 'Calienta el aceite de oliva en una sartén a fuego medio.', 'Sofríe la cebolla, el calabacín y el pimentón 4-5 minutos hasta que ablanden.', 'Bate los huevos aparte con sal y pimienta.', 'Vierte los huevos sobre las verduras, repartiendo bien por toda la sartén.', 'Baja el fuego, tapa y cocina 4-5 minutos hasta que la base esté firme y dorada.', 'Dobla la tortilla por la mitad con una espátula, o voltéala con cuidado, y cocina 1-2 minutos más.', 'Sirve caliente.'],
 ['vegetariano', 'sin gluten'], 220, 18, 15, 8, 14, 4, 2, 'https://images.pexels.com/photos/26161037/pexels-photo-26161037.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pizza baja en carbohidratos', 'cena',
 ['coliflor|2 tazas ralladas', 'huevos|1 unidad', 'queso mozarella|60 g', 'salsa de tomate|3 cdas', 'orégano|1 pizca'],
 ['Precalienta el horno a 200°C.', 'Si la coliflor no está cocida, cocínala al vapor y luego rállala o pícala fina; escúrrela muy bien con un paño para quitar toda el agua posible.', 'En un bowl, mezcla la coliflor bien escurrida con el huevo y la mitad del queso mozarella.', 'Extiende la mezcla sobre una bandeja para horno forrada con papel, formando una base delgada y pareja.', 'Hornea 12-15 minutos hasta que la base esté dorada y firme.', 'Retira del horno y cubre con la salsa de tomate, el resto del queso mozarella y el orégano.', 'Hornea 8-10 minutos más hasta que el queso derrita y dore.', 'Deja reposar 2 minutos antes de cortar y servir.'],
 ['vegetariano', 'sin gluten'], 420, 25, 35, 10, 32, 5, 5, 'https://images.pexels.com/photos/35063434/pexels-photo-35063434.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Wraps de atún con lechuga y aguacate', 'cena',
 ['atún en lata|1 lata', 'lechuga|4 hojas grandes', 'aguacate|1/2 unidad', 'tomate|1/2 unidad', 'limón|1/2 unidad'],
 ['Escurre bien el atún en lata.', 'En un bowl, mezcla el atún con el aguacate machacado y jugo de limón hasta integrar.', 'Sazona con sal y pimienta.', 'Lava las hojas de lechuga y sécalas bien.', 'Extiéndelas sobre una superficie plana.', 'Corta el tomate en cubos y repártelo sobre las hojas.', 'Agrega la mezcla de atún y aguacate encima.', 'Enrolla cada hoja sosteniendo el relleno con firmeza.', 'Sirve de inmediato.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 420, 35, 10, 20, 25, 5, 7, 'https://images.pexels.com/photos/37112193/pexels-photo-37112193.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Smoothie de chocolate y almendras', 'snack',
 ['leche de almendras|1 taza', 'banano|1/2 unidad', 'cacao en polvo|1 cda', 'almendras|1 cda', 'hielo|1/2 taza'],
 ['Pela el banano y córtalo en trozos.', 'Coloca la leche de almendras, el banano, el cacao en polvo y las almendras en la licuadora.', 'Agrega el hielo.', 'Licúa a velocidad alta 40-60 segundos, hasta que quede completamente suave y sin grumos.', 'Prueba y ajusta el dulzor si lo deseas.', 'Sirve de inmediato en un vaso bien frío.'],
 ['vegetariano', 'rápido', 'sin gluten'], 220, 5, 5, 35, 10, 20, 4, 'https://images.pexels.com/photos/27850088/pexels-photo-27850088.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Smoothie de bayas y espinacas', 'snack',
 ['espinacas|1 taza', 'fresas|1/2 taza', 'arándanos|1/4 taza', 'yogur griego|1/2 taza', 'agua|1/4 taza'],
 ['Lava bien las espinacas.', 'Colócalas en la licuadora junto con el yogur griego.', 'Licúa 20-30 segundos hasta que las espinacas queden bien trituradas y no se sientan hojas enteras.', 'Agrega las fresas y los arándanos.', 'Licúa de nuevo 30-40 segundos hasta obtener una mezcla homogénea y cremosa.', 'Si queda muy espeso, agrega un poco de agua y licúa unos segundos más.', 'Sirve frío de inmediato.'],
 ['vegetariano', 'rápido', 'sin gluten'], 170, 15, 5, 25, 7, 15, 4, 'https://images.pexels.com/photos/7937002/pexels-photo-7937002.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Smoothie de frutos secos', 'snack',
 ['leche deslactosada|1 taza', 'almendras|1 cda', 'nueces|1 cda', 'dátiles|2 unidades', 'canela|1 pizca'],
 ['Si los dátiles están un poco secos, remójalos en agua tibia 5-10 minutos para que se ablanden y se licúen mejor; luego escúrrelos y quítales el hueso si lo tienen.', 'Tuesta ligeramente las almendras y las nueces en una sartén seca 1-2 minutos: esto realza su sabor y hace el batido más aromático (opcional pero vale la pena).', 'Coloca en la licuadora la leche deslactosada, las almendras, las nueces, los dátiles y la canela.', 'Licúa a velocidad alta durante 1 minuto completo, hasta que los frutos secos queden bien triturados y la mezcla esté cremosa y sin trozos grandes.', 'Prueba y ajusta el dulzor: si lo quieres más dulce, agrega medio dátil más y vuelve a licuar unos segundos.', 'Si prefieres una textura muy suave, cuela el batido con un colador fino, presionando con una cuchara para aprovechar toda la pulpa.', 'Sirve de inmediato en un vaso alto. Si lo quieres más frío, agrega 2-3 cubos de hielo antes de licuar.'],
 ['vegetariano', 'rápido'], 320, 8, 8, 40, 16, 24, 6, 'https://images.pexels.com/photos/18142611/pexels-photo-18142611.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Smoothie de fresa, calabacín y chía', 'snack',
 ['fresas|1 taza', 'calabacín|1/4 unidad', 'semillas de chía|1 cdta', 'agua|1/2 taza', 'miel|1 cdta'],
 ['Lava el calabacín y córtalo en trozos pequeños (no es necesario pelarlo).', 'Colócalo en la licuadora junto con el agua.', 'Licúa 30-40 segundos hasta que quede completamente líquido y sin trozos.', 'Agrega las fresas lavadas, las semillas de chía y la miel.', 'Licúa de nuevo 30 segundos más hasta integrar todo bien.', 'Sirve de inmediato; las semillas de chía empiezan a espesar el líquido con el tiempo, así que es mejor tomarlo fresco.'],
 ['vegetariano', 'rápido', 'sin gluten'], 170, 3, 5, 35, 7, 25, 5, 'https://images.pexels.com/photos/1438080/pexels-photo-1438080.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Smoothie de coco, mora y menta', 'snack',
 ['leche de coco|1 taza', 'moras|1/2 taza', 'hojas de menta|4 unidades', 'hielo|1/2 taza'],
 ['Coloca la leche de coco y las moras en la licuadora.', 'Agrega las hojas de menta, procurando quitar los tallos duros.', 'Añade el hielo.', 'Licúa a velocidad alta 40-60 segundos hasta que quede una mezcla suave, fría y espumosa.', 'Prueba y ajusta con un poco de miel si prefieres más dulce.', 'Sirve de inmediato en un vaso bien frío.'],
 ['vegetariano', 'rápido', 'sin gluten'], 220, 3, 5, 35, 12, 25, 5, 'https://images.pexels.com/photos/7937485/pexels-photo-7937485.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo antiinflamatorio de piña y cúrcuma', 'snack',
 ['piña|1 taza', 'cúrcuma|1/2 cdta', 'jengibre|1/2 cdta rallado', 'agua|1 taza', 'limón|1/2 unidad'],
 ['Pela y corta la piña en trozos.', 'Colócala en la licuadora con el agua.', 'Agrega la cúrcuma y el jengibre rallado.', 'Licúa 40-50 segundos hasta obtener una mezcla homogénea.', 'Si prefieres un jugo sin pulpa, cuela con un colador fino o un lienzo, presionando bien para extraer todo el líquido.', 'Agrega el jugo de limón y mezcla.', 'Sirve frío de inmediato, sobre hielo si lo prefieres.'],
 ['vegetariano', 'rápido', 'sin gluten'], 60, 1, 5, 15, 0, 10, 2, 'https://images.pexels.com/photos/8215113/pexels-photo-8215113.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo verde clásico', 'snack',
 ['espinacas|1 taza', 'manzana verde|1 unidad', 'apio|1 tallo', 'limón|1/2 unidad', 'agua|1 taza'],
 ['Lava bien las espinacas.', 'Lava la manzana y córtala en trozos, quitando las semillas (puedes dejar la cáscara).', 'Lava el apio y córtalo en trozos.', 'Coloca las espinacas, la manzana y el apio en la licuadora junto con el agua.', 'Licúa 40-60 segundos hasta que quede bien triturado.', 'Cuela con un colador fino si prefieres un jugo sin fibra.', 'Agrega jugo de limón al gusto y sirve frío.'],
 ['vegetariano', 'rápido', 'sin gluten'], 55, 1, 5, 13, 0, 9, 2, 'https://images.pexels.com/photos/8169572/pexels-photo-8169572.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo detox de pepino y limón', 'snack',
 ['pepino|1 unidad', 'limón|1/2 unidad', 'hojas de menta|4 unidades', 'agua|1 taza', 'jengibre|1/2 cdta rallado'],
 ['Lava el pepino y córtalo en trozos (no es necesario pelarlo si es orgánico).', 'Colócalo en la licuadora con el agua, el jengibre rallado y las hojas de menta.', 'Licúa 40-50 segundos hasta obtener una mezcla homogénea.', 'Cuela con un colador fino si prefieres una textura más ligera.', 'Agrega el jugo de limón y mezcla bien.', 'Sirve bien frío, con hielo si lo deseas.'],
 ['vegetariano', 'rápido', 'sin gluten'], 45, 1, 5, 11, 0, 8, 2, 'https://images.pexels.com/photos/18142599/pexels-photo-18142599.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo energizante de naranja y zanahoria', 'snack',
 ['naranja|2 unidades', 'zanahoria|1 unidad', 'jengibre|1/2 cdta rallado'],
 ['Exprime las naranjas y reserva el jugo.', 'Lava y pica la zanahoria en trozos pequeños.', 'Coloca la zanahoria en la licuadora junto con el jugo de naranja recién exprimido.', 'Agrega el jengibre rallado.', 'Licúa 40-60 segundos hasta que la zanahoria quede bien triturada.', 'Cuela con un colador fino si prefieres una textura más ligera y sin pulpa.', 'Sirve de inmediato, bien frío.'],
 ['vegetariano', 'rápido', 'sin gluten'], 120, 2, 8, 28, 0, 20, 2, 'https://images.pexels.com/photos/18142602/pexels-photo-18142602.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo digestivo de papaya y linaza', 'snack',
 ['papaya|1 taza', 'agua|1 taza', 'semillas de linaza|1 cdta', 'limón|1/4 unidad'],
 ['Elige una papaya bien madura (cede al presionar suavemente): así el jugo queda naturalmente dulce sin necesidad de azúcar.', 'Corta la papaya a la mitad, retira todas las semillas con una cuchara y saca la pulpa; córtala en trozos medianos.', 'Coloca la papaya en la licuadora junto con el agua y las semillas de linaza.', 'Licúa 40-50 segundos hasta obtener una mezcla suave y homogénea; la linaza aportará fibra y una textura ligeramente espesa.', 'Agrega el jugo de limón y licúa 5 segundos más para integrarlo; el limón realza el sabor y ayuda a que no se oxide tan rápido.', 'Prueba y, si lo quieres más líquido, añade un poco más de agua.', 'Sirve de inmediato, ya que la papaya se oxida y cambia de color y sabor con el tiempo. Ideal en ayunas para favorecer la digestión.'],
 ['vegetariano', 'rápido', 'sin gluten'], 120, 2, 5, 25, 3, 20, 4, 'https://images.pexels.com/photos/16744880/pexels-photo-16744880.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo para la salud ocular de zanahoria y naranja', 'snack',
 ['zanahoria|2 unidades', 'naranja|1 unidad', 'agua|1/2 taza'],
 ['Lava y pica la zanahoria en trozos.', 'Colócala en la licuadora con el agua.', 'Licúa 40-60 segundos hasta que quede bien triturada.', 'Cuela con un colador fino, presionando bien para extraer todo el jugo.', 'Exprime la naranja y mezcla su jugo con el de zanahoria colado.', 'Sirve frío de inmediato.'],
 ['vegetariano', 'rápido', 'sin gluten'], 45, 1, 8, 10, 0, 6, 2, 'https://images.pexels.com/photos/4443447/pexels-photo-4443447.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo para la salud del corazón de remolacha y manzana', 'snack',
 ['remolacha|1/2 unidad', 'manzana|1 unidad', 'limón|1/2 unidad', 'agua|1 taza'],
 ['Lava y pela la remolacha, luego córtala en trozos pequeños.', 'Lava la manzana, córtala en trozos y quita las semillas.', 'Coloca la remolacha y la manzana en la licuadora junto con el agua.', 'Licúa 50-60 segundos hasta que quede bien triturado (la remolacha es dura, así que puede tomar un poco más de tiempo).', 'Cuela con un colador fino si prefieres un jugo más ligero.', 'Agrega el jugo de limón y mezcla.', 'Sirve frío.'],
 ['vegetariano', 'rápido', 'sin gluten'], 60, 1, 8, 15, 0, 10, 2, 'https://images.pexels.com/photos/4443472/pexels-photo-4443472.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo para limpiar el hígado de remolacha y jengibre', 'snack',
 ['remolacha|1/2 unidad', 'zanahoria|1 unidad', 'jengibre|1 cdta rallado', 'limón|1/2 unidad', 'agua|1 taza'],
 ['Lava y pela la remolacha, córtala en trozos.', 'Lava y pela la zanahoria, córtala también en trozos.', 'Coloca la remolacha, la zanahoria y el jengibre rallado en la licuadora con el agua.', 'Licúa 50-60 segundos hasta obtener una mezcla homogénea.', 'Cuela con un colador fino, presionando bien para extraer todo el líquido.', 'Agrega el jugo de limón y mezcla.', 'Sirve frío de inmediato.'],
 ['vegetariano', 'rápido', 'sin gluten'], 45, 1, 10, 10, 0, 6, 2, 'https://images.pexels.com/photos/4443478/pexels-photo-4443478.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo para perder peso y refrescar de pepino y toronja', 'snack',
 ['pepino|1 unidad', 'toronja|1 unidad', 'hojas de menta|4 unidades', 'agua|1/2 taza'],
 ['Lava el pepino y córtalo en trozos.', 'Colócalo en la licuadora con el agua y las hojas de menta.', 'Licúa 40-50 segundos hasta que quede bien triturado.', 'Cuela con un colador fino.', 'Exprime la toronja y mezcla su jugo con el licuado de pepino colado.', 'Sirve bien frío, con hielo si lo prefieres.'],
 ['vegetariano', 'rápido', 'sin gluten'], 45, 1, 8, 11, 0, 8, 2, 'https://images.pexels.com/photos/7208619/pexels-photo-7208619.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Jugo para adelgazar y reducir el colesterol de manzana y avena', 'snack',
 ['manzana|1 unidad', 'avena en hojuelas|2 cdas', 'canela|1 pizca', 'agua|1 taza'],
 ['Remoja la avena en el agua durante 10 minutos, para que se ablande un poco.', 'Lava la manzana, córtala en trozos y quita las semillas.', 'Coloca la manzana, la avena remojada (con su agua) y la canela en la licuadora.', 'Licúa 40-50 segundos hasta obtener una mezcla suave.', 'Cuela con un colador fino si prefieres un jugo más ligero, o déjalo tal cual si te gusta con más cuerpo.', 'Sirve frío de inmediato.'],
 ['vegetariano', 'rápido'], 120, 2, 12, 30, 0, 20, 4, 'https://images.pexels.com/photos/9002779/pexels-photo-9002779.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Papas Fritas Perfectas en Air Fryer', 'snack',
 ['papas pastusa|4 unidades', 'aceite de oliva|1 cda', 'sal gruesa|al gusto', 'ajo en polvo|al gusto', 'pimentón en polvo|al gusto'],
 ['Pela y corta las papas en bastones uniformes de 1 cm.', 'Remoja en agua fría 30 minutos --- este paso es el secreto del crujiente.', 'Seca completamente con papel absorbente.', 'Mezcla con el aceite, la sal y el ajo.', 'Cocina a 200°C por 15 minutos, sacude bien la canasta.', 'Cocina 10 minutos más hasta doradas y crujientes.', 'Sirve de inmediato.'],
 ['económico', 'rápido'], 220, 4, 20, 30, 10, 2, 4, 'https://images.pexels.com/photos/19141651/pexels-photo-19141651.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Deditos de Queso Costeño en Air Fryer', 'snack',
 ['queso costeño|200 g', 'huevos batidos|2 unidades', 'pan rallado|80 g', 'ajo en polvo|1 cdta', 'aceite en spray|al gusto', 'hogao|al gusto'],
 ['Corta el queso en bastones de 1 cm de grosor.', 'Pasa cada bastón por huevo batido y luego por el pan rallado mezclado con ajo. Repite una vez más para doble cobertura.', 'Congela 15 minutos antes de cocinar --- evita que el queso se derrita antes de dorarse.', 'Rocía con spray y cocina a 200°C por 8-10 minutos hasta dorados.'],
 ['vegetariano', 'rápido'], 320, 18, 20, 20, 18, 2, 1, 'https://images.pexels.com/photos/37279725/pexels-photo-37279725.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Empanadas de Pipián en Air Fryer', 'snack',
 ['empanadas de masa de maíz rellenas de pipián|8 unidades', 'aceite en spray|al gusto', 'ají|al gusto'],
 ['Forma las empanadas con el relleno de pipián bien selladas.', 'Rocía generosamente con aceite en spray por ambos lados.', 'Cocina a 190°C por 10 minutos, voltea con cuidado.', 'Cocina 8-10 minutos más hasta que la masa esté dorada y crujiente.'],
 ['tradicional', 'económico'], 320, 8, 20, 40, 16, 2, 4, 'https://images.pexels.com/photos/28902906/pexels-photo-28902906.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostones con Hogao en Air Fryer', 'snack',
 ['plátanos verdes|2 unidades', 'aceite en spray|al gusto', 'sal|al gusto', 'hogao casero para servir (cebolla larga|al gusto'],
 ['Pela los plátanos y corta en rodajas de 2 cm. Rocía con spray y', 'Retira y aplasta cada rodaja con un vaso o tostonera hasta 5 mm de', 'Rocía nuevamente con spray y devuelve al air fryer.', 'Cocina 8-10 minutos más hasta dorados y crujientes. Sirve con hogao'],
 ['tradicional', 'vegetariano'], 220, 2, 20, 45, 8, 10, 4, 'https://images.pexels.com/photos/32655071/pexels-photo-32655071.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Yuca Frita Crujiente en Air Fryer', 'snack',
 ['yuca cocida y fría|400 g', 'aceite|1 cda', 'sal gruesa|al gusto', 'ajo en polvo|al gusto', 'salsa rosada|al gusto'],
 ['Corta la yuca cocida en bastones gruesos, retira el hilo central.', 'Mezcla con el aceite, la sal y el ajo en polvo.', 'Cocina a 200°C por 12 minutos, sacude bien.', 'Cocina 10-12 minutos más hasta bordes dorados y crujientes.'],
 ['económico', 'vegetariano'], 220, 2, 20, 30, 10, 2, 4, 'https://images.pexels.com/photos/14457456/pexels-photo-14457456.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Chicharrón de Cerdo Crujiente en Air Fryer', 'snack',
 ['tocino|400 g', 'sal gruesa|al gusto', 'ajo en polvo|al gusto', 'comino|al gusto', 'limón|al gusto'],
 ['Seca muy bien la piel del cerdo con papel absorbente. Sazona generosamente.', 'Cocina a 180°C por 15 minutos para que la grasa se rinda.', 'Sube la temperatura a 200°C y cocina 15 minutos más hasta que la piel reviente y quede crujiente.', 'Escurre el exceso de grasa y sirve con limón.'],
 ['tradicional', 'alto en proteína'], 420, 35, 20, 2, 34, 0, 0, 'https://images.pexels.com/photos/34438654/pexels-photo-34438654.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Croquetas de Papa y Queso en Air Fryer', 'snack',
 ['puré de papa espeso|300 g', 'queso mozarela en cubos pequeños|80 g', 'huevo|1 unidad', 'pan rallado|80 g', 'sal|al gusto', 'aceite en spray|al gusto'],
 ['Mezcla el puré con sal, pimienta y nuez moscada. Refrigera 30 minutos.', 'Forma cilindros con el puré, coloca un cubo de queso en el centro y cierra bien.', 'Pasa por huevo batido y luego por pan rallado.', 'Rocía con spray y cocina a 195°C por 15-18 minutos hasta doradas.'],
 ['vegetariano', 'económico'], 220, 12, 20, 25, 10, 2, 2, 'https://images.pexels.com/photos/166031/pexels-photo-166031.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Calamares Apanados en Air Fryer', 'snack',
 ['anillos de calamar|300 g', 'huevos batidos|2 unidades', 'harina sazonada con sal|100 g', 'panko|80 g', 'aceite en spray|al gusto', 'limón y salsa tártara para servir panko|al gusto'],
 ['Seca los calamares muy bien. Pasa por harina, luego huevo, luego', 'Rocía con spray generosamente por todos los lados.', 'Cocina a 200°C por 6 minutos, voltea con cuidado.', 'Cocina 5-6 minutos más hasta dorados. Sirve con limón de inmediato.'],
 ['alto en proteína', 'rápido'], 320, 25, 20, 20, 15, 2, 1, 'https://images.pexels.com/photos/32153517/pexels-photo-32153517.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Pollo Entero Estilo Rotisserie en Air Fryer', 'cena',
 ['pollo entero de 1.2 kg|1 unidad', 'aceite de oliva|1 cda', 'paprika ahumada|1 cdta', 'ajo en polvo|1 cdta', 'cebolla en polvo|1 cdta', 'tomillo|al gusto', 'limón para el interior|1 unidad'],
 ['Seca el pollo completamente por dentro y por fuera. Mezcla el aceite con todas las especias.', 'Frota la mezcla por todo el pollo incluyendo debajo de la piel de la pechuga.', 'Coloca el limón cortado dentro del pollo. Cocina a 180°C con la pechuga hacia abajo 30 minutos.', 'Voltea y cocina 30 minutos más hasta piel dorada y temperatura interna de 74°C.'],
 ['alto en proteína', 'tradicional'], 420, 55, 20, 0, 24, 0, 0, 'https://images.pexels.com/photos/6163330/pexels-photo-6163330.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Chuletas de Cerdo con Ajo y Limón en Air Fryer', 'cena',
 ['chuletas de cerdo de 200g c/u|2 unidades', 'ajo machacados|3 dientes', 'jugo de 1 limón|al gusto', 'tomillo|1 cdta', 'sal y pimienta negra|al gusto', 'aceite en spray minutos. minutos. ##### Hamburguesa Clásica|al gusto', 'carne molida 80/20|400 g', 'sal gruesa y pimienta negra|al gusto', 'queso americano|2 rebanadas', 'pan para hamburgues a|al gusto', 'lechuga|al gusto'],
 ['Marina las chuletas en ajo, limón, tomillo, sal y pimienta mínimo 30', 'Seca el exceso de marinada con papel antes de cocinar.', 'Rocía con spray y cocina a 195°C por 8 minutos.', 'Voltea y cocina 8 minutos más. Temperatura interna: 63°C. Reposa 3', 'Forma 2 hamburguesas sin amasar demasiado --- eso las endurece. Haz', 'Sazona generosamente con sal gruesa y pimienta.', 'Cocina a 190°C por 6 minutos. Voltea, agrega el queso y cocina 5-6', 'Reposa 2 minutos. Arma con los acompañamientos.'],
 ['alto en proteína', 'rápido'], 540, 43, 20, 6, 37, 1, 1, 'https://images.pexels.com/photos/11600756/pexels-photo-11600756.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pechuga Rellena de Jamón y Queso en Air Fryer', 'cena',
 ['pechugas grandes de pollo|2 unidades', 'jamón|4 lonjas', 'queso mozarela en lonjas|80 g', 'sal|al gusto', 'palillos de madera|al gusto', 'aceite en spray|al gusto'],
 ['Abre cada pechuga en mariposa sin cortar del todo. Aplana ligeramente.', 'Rellena con el jamón y el queso. Cierra y asegura con palillos.', 'Sazona el exterior con sal, pimienta y ajo. Rocía con spray.', 'Cocina a 185°C por 22 minutos hasta temperatura interna de 74°C.'],
 ['alto en proteína'], 320, 37, 20, 4, 18, 1, 0, 'https://images.pexels.com/photos/5718117/pexels-photo-5718117.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Costillas BBQ en Air Fryer', 'cena',
 ['costillas de cerdo individuales|600 g', 'sal|al gusto', 'salsa BBQ (comercial|4 cdas', 'aceite en spray|al gusto'],
 ['Sazona las costillas generosamente con sal, pimienta, paprika y ajo.', 'Cocina a 180°C por 20 minutos volteando a mitad.', 'Pinta con la salsa BBQ generosamente por todos los lados.', 'Cocina 15 minutos más hasta que la salsa caramelice y las costillas estén tiernas.'],
 ['tradicional', 'alto en proteína'], 420, 35, 20, 20, 25, 10, 0, 'https://images.pexels.com/photos/410648/pexels-photo-410648.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bistec Apanado Estilo Milanesa en Air Fryer', 'cena',
 ['bisteces de res delgados|2 unidades', 'huevos batidos|2 unidades', 'pan rallado sazonado|100 g', 'sal|al gusto', 'aceite en spray|al gusto', 'limón para servir por pan rallado presionando bien. Sirve co|al gusto'],
 ['Sazona los bisteces con sal, pimienta y ajo. Pasa por huevo y luego', 'Rocía con spray generosamente por ambos lados.', 'Cocina a 200°C por 7 minutos.', 'Voltea con cuidado y cocina 6-7 minutos más hasta cobertura dorada.'],
 ['alto en proteína', 'rápido'], 420, 37, 20, 20, 24, 2, 1, 'https://images.pexels.com/photos/3660/food-restaurant-dinner-lunch.jpg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Alitas Teriyaki en Air Fryer', 'snack',
 ['alitas de pollo|500 g', 'soja baja en sodio|3 cdas', 'miel|1 cda', 'jengibre rallado|1 cdta', 'ajo|1 diente', 'maicena|1 cdta', 'sésamo y cebollín|al gusto'],
 ['Seca las alitas y cocina a 200°C por 12 minutos. Voltea y cocina 10 minutos más.', 'Mientras tanto cocina la salsa teriyaki en olla pequeña hasta espesar.', 'Baña las alitas en la salsa teriyaki caliente.', 'Devuelve al air fryer 3 minutos para que la salsa caramelice.'],
 ['alto en proteína', 'rápido'], 420, 37, 20, 20, 24, 8, 2, 'https://images.pexels.com/photos/13065185/pexels-photo-13065185.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Lomo de Cerdo con Mostaza y Panela en Air Fryer', 'cena',
 ['lomo de cerdo magro|600 g', 'mostaza Dijon|2 cdas', 'panela raspada|1 cda', 'tomillo|1 cdta', 'sal y pimienta|al gusto', 'aceite en spray|al gusto'],
 ['Mezcla la mostaza con la panela y el tomillo formando un glaseado.', 'Cubre todo el lomo con la mezcla y marina 20 minutos.', 'Cocina a 185°C por 12 minutos.', 'Voltea, aplica más glaseado y cocina 12 minutos más. Temperatura interna 63°C. Reposa 5 minutos antes de cortar.'],
 ['alto en proteína', 'tradicional'], 420, 55, 20, 10, 20, 5, 2, 'https://images.pexels.com/photos/12149700/pexels-photo-12149700.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Pollo al Curry Colombiano en Air Fryer', 'almuerzo',
 ['muslos de pollo sin piel en trozos|500 g', 'curry en polvo|2 cdas', 'cúrcuma|1 cdta', 'yogur natural|150 g', 'ajo|2 dientes', 'jengibre en polvo|1 cdta', 'sal y aceite en spray|al gusto'],
 ['Mezcla el yogur con el curry, la cúrcuma, el ajo, el jengibre y la sal.', 'Marina el pollo mínimo 2 horas --- overnight es ideal.', 'Rocía con spray y cocina a 190°C por 12 minutos. Voltea.', 'Cocina 10 minutos más hasta bordes dorados. Sirve con arroz blanco.'],
 ['alto en proteína', 'tradicional'], 420, 37, 20, 10, 24, 2, 2, 'https://images.pexels.com/photos/17593646/pexels-photo-17593646.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Pernil de Cerdo Crujiente en Air Fryer', 'cena',
 ['pernil de cerdo con piel en trozos grandes|800 g', 'ajo|4 dientes', 'jugo de 1 naranja|al gusto', 'comino|1 cdta', 'orégano|al gusto', 'aceite en spray|al gusto'],
 ['Marina el pernil en ajo machacado, naranja, comino, orégano, sal y pimienta mínimo 4 horas.', 'Seca bien el exceso de marinada. Rocía con spray.', 'Cocina a 180°C por 25 minutos, voltea y cocina 20 minutos más.', 'Sube a 200°C los últimos 5 minutos para que la piel quede completamente crujiente.'],
 ['tradicional', 'alto en proteína'], 420, 35, 20, 6, 30, 2, 1, 'https://images.pexels.com/photos/34314497/pexels-photo-34314497.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Deditos de Pollo Estilo KFC en Air Fryer', 'snack',
 ['pechuga de pollo en tiras|400 g', 'huevos batidos|2 unidades', 'harina de trigo|100 g', 'pan rallado|80 g', 'paprika|1 cdta', 'sal y pimienta|al gusto', 'aceite en spray|al gusto'],
 ['Sazona el pollo. Pasa por harina, luego huevo, luego pan rallado mezclado con las especias.', 'Refrigera 10 minutos antes de cocinar.', 'Rocía con spray y cocina a 200°C por 9 minutos.', 'Voltea, rocía nuevamente y cocina 8 minutos más. Sirve con salsa de tu elección.'],
 ['alto en proteína', 'rápido'], 420, 37, 20, 20, 24, 2, 2, 'https://images.pexels.com/photos/7883807/pexels-photo-7883807.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Carne Desmechada para Arepas en Air Fryer', 'almuerzo',
 ['carne de res cocida y desmechada|400 g', 'hogao (cebolla larga|1 taza', 'sal|al gusto', 'aceite en spray|al gusto', 'arepas|al gusto'],
 ['Mezcla la carne desmechada con el hogao, sal, pimienta y color.', 'Extiende en la canasta del air fryer en capa delgada.', 'Cocina a 200°C por 10 minutos, remueve bien.', 'Cocina 10 minutos más hasta que los bordes estén dorados y caramelizados. Sirve sobre arepas.'],
 ['tradicional', 'alto en proteína'], 420, 35, 20, 30, 20, 2, 4, 'https://images.pexels.com/photos/5779368/pexels-photo-5779368.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Camarones al Ajillo en Air Fryer', 'almuerzo',
 ['camarones grandes pelados|400 g', 'ajo picados|4 dientes', 'mantequilla|1 cda', 'chile en hojuelas|al gusto', 'jugo de 1 limón|al gusto', 'perejil fresco y sal|al gusto'],
 ['Mezcla los camarones con el ajo, la mantequilla derretida, el chile y la sal.', 'Coloca en una sola capa en la canasta.', 'Cocina a 200°C por 4 minutos, sacude la canasta.', 'Cocina 4 minutos más. Termina con limón y perejil.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 240, 20, 20, 6, 14, 1, 1, 'https://images.pexels.com/photos/7111475/pexels-photo-7111475.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Salmón con Mantequilla y Eneldo en Air Fryer', 'cena',
 ['salmón de 200g c/u|2 filetes', 'mantequilla derretida|1 cda', 'eneldo fresco|al gusto', 'jugo de 1 limón|al gusto', 'sal y pimienta|al gusto', 'rodajas de limón|al gusto'],
 ['Sazona el salmón con sal y pimienta. Unta la mantequilla derretida sobre la parte superior.', 'Espolvorea el eneldo generosamente.', 'Coloca con la piel hacia abajo. Cocina a 180°C por 10-12 minutos sin voltear.', 'El salmón está listo cuando se separa en capas al tocarlo. Sirve con limón.'],
 ['alto en proteína', 'sin gluten', 'rápido'], 320, 35, 20, 0, 20, 0, 0, 'https://images.pexels.com/photos/31146494/pexels-photo-31146494.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Mojarra Frita Entera en Air Fryer', 'almuerzo',
 ['mojarras de 300g c/u|2 unidades', 'ajo machacados|3 dientes', 'jugo de 1 limón|al gusto', 'comino|1 cdta', 'sal|al gusto', 'aceite en spray|al gusto', 'patacones y ensalada|al gusto'],
 ['Haz 3-4 cortes profundos en cada lado de las mojarras. Marina en ajo, limón, comino, sal y color 30 minutos.', 'Seca bien el exceso de marinada. Rocía con spray por todos los lados.', 'Cocina a 200°C por 11 minutos, voltea con cuidado.', 'Cocina 11 minutos más hasta piel dorada y crujiente.'],
 ['tradicional', 'alto en proteína'], 420, 35, 20, 10, 24, 2, 2, 'https://images.pexels.com/photos/29721167/pexels-photo-29721167.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Camarones Empanizados con Coco en Air Fryer', 'snack',
 ['camarones grandes|300 g', 'claras de huevo|2 unidades', 'coco rallado sin azúcar|50 g', 'panko|50 g', 'sal y pimienta|al gusto', 'aceite en spray|al gusto', 'salsa de mango y ají|al gusto'],
 ['Pasa los camarones por clara de huevo y luego por mezcla de panko y coco. Presiona bien.', 'Refrigera 10 minutos antes de cocinar.', 'Rocía con spray y cocina a 200°C por 6 minutos.', 'Voltea con cuidado y cocina 5-6 minutos más hasta dorados.'],
 ['alto en proteína', 'rápido'], 320, 26, 20, 12, 18, 6, 2, 'https://images.pexels.com/photos/5272102/pexels-photo-5272102.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Tilapia con Costra de Ajo y Hierbas en Air Fryer', 'almuerzo',
 ['tilapia de 180g c/u|2 filetes', 'mantequilla derretida|2 cdas', 'ajo picados muy fino|3 dientes', 'pan rallado|2 cdas', 'perejil y cilantro frescos|al gusto', 'sal|al gusto'],
 ['Sazona los filetes con sal y pimienta.', 'Mezcla la mantequilla con el ajo, el pan rallado y las hierbas. Extiende sobre la parte superior de cada filete.', 'Cocina a 195°C por 12-14 minutos sin voltear.', 'La costra debe estar dorada. Sirve con limón.'],
 ['alto en proteína', 'rápido'], 340, 37, 20, 10, 18, 1, 1, 'https://images.pexels.com/photos/36378583/pexels-photo-36378583.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Brochetas de Camarón y Chorizo en Air Fryer', 'cena',
 ['camarones grandes pelados|200 g', 'chorizo colombiano en rodajas|150 g', 'pimentón rojo en cuadros|1 unidad', 'aceite de oliva|1 cda', 'ajo|al gusto', 'palitos remojados 30 minutos|al gusto'],
 ['Marina los camarones y el chorizo en aceite, ajo, comino y sal 15 minutos.', 'Ensarta alternando camarón, chorizo y pimentón.', 'Cocina a 195°C por 6 minutos, voltea.', 'Cocina 5-6 minutos más hasta que el chorizo esté dorado y los camarones rosados.'],
 ['alto en proteína', 'rápido'], 320, 26, 20, 10, 20, 2, 2, 'https://images.pexels.com/photos/8250276/pexels-photo-8250276.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Pulpo a la Gallega Express en Air Fryer', 'almuerzo',
 ['pulpo cocido|400 g', 'aceite de oliva|2 cdas', 'paprika ahumada abundante|al gusto', 'sal gruesa|al gusto', 'papas cocidas en rodajas|2 unidades', 'perejil fresco|al gusto'],
 ['Corta el pulpo cocido en trozos gruesos. Mezcla con aceite y sal.', 'Cocina a 200°C por 8 minutos, remueve.', 'Cocina 6 minutos más hasta bordes ligeramente chamuscados.', 'Sirve sobre las papas en rodajas. Espolvorea paprika abundante y perejil.'],
 ['tradicional', 'alto en proteína'], 420, 35, 20, 20, 25, 2, 4, 'https://images.pexels.com/photos/37068830/pexels-photo-37068830.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bagre Apanado con Limón en Air Fryer', 'almuerzo',
 ['filetes de bagre|400 g', 'huevos batidos|2 unidades', 'harina de maíz|100 g', 'ajo en polvo|1 cdta', 'comino|1 cdta', 'sal|al gusto', 'aceite en spray|al gusto'],
 ['Sazona el bagre con sal, pimienta, ajo y comino. Pasa por huevo y luego por harina de maíz.', 'Rocía con spray generosamente.', 'Cocina a 200°C por 8 minutos, voltea con cuidado.', 'Cocina 7-8 minutos más hasta cobertura dorada. Sirve con limón y patacones.'],
 ['alto en proteína', 'rápido'], 320, 35, 20, 10, 15, 1, 2, 'https://images.pexels.com/photos/4198344/pexels-photo-4198344.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Langostinos con Mantequilla de Ajo en Air Fryer', 'cena',
 ['langostinos con cáscara|400 g', 'mantequilla derretida|3 cdas', 'ajo picados|4 dientes', 'jugo de 1 limón|al gusto', 'perejil fresco|al gusto', 'sal y pimienta|al gusto', 'pan para el jugo|al gusto'],
 ['Haz un corte en el lomo de cada langostino sin retirar la cáscara.', 'Mezcla la mantequilla con el ajo, el limón y la sal. Unta sobre los langostinos.', 'Cocina a 200°C por 5 minutos, bañalos con el jugo que queda en la canasta.', 'Cocina 4-5 minutos más. Sirve con pan para mojar en el jugo.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 240, 20, 20, 6, 16, 1, 1, 'https://images.pexels.com/photos/16845598/pexels-photo-16845598.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Tacos de Pescado con Repollo en Air Fryer', 'almuerzo',
 ['tilapia|400 g', 'comino|1 cdta', 'aceite en spray|al gusto', 'tortillas de maíz pequeñas|8 unidades', 'repollo morado rallado|al gusto', 'yogur griego|al gusto'],
 ['Sazona el pescado con las especias y rocía con spray.', 'Cocina a 200°C por 7 minutos por lado hasta dorado.', 'Calienta las tortillas en el air fryer 1 minuto.', 'Arma los tacos con el pescado, el repollo, el yogur y la salsa. Exprime limón.'],
 ['alto en proteína', 'rápido'], 420, 37, 20, 25, 20, 5, 4, 'https://images.pexels.com/photos/15434303/pexels-photo-15434303.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Mazorca Asada con Mantequilla en Air Fryer', 'snack',
 ['mazorcas tiernas partidas en mitades|2 unidades', 'mantequilla derretida|2 cdas', 'sal gruesa|al gusto', 'ajo en polvo|al gusto', 'queso costeño rallado|al gusto', 'limón opcional ajo|al gusto'],
 ['Unta las mazorcas con la mantequilla derretida mezclada con sal y', 'Cocina a 200°C por 8 minutos, voltea.', 'Unta más mantequilla y cocina 6-7 minutos más hasta marcas doradas.', 'Sirve con queso costeño rallado encima y limón.'],
 ['económico', 'vegetariano'], 220, 4, 20, 30, 12, 6, 4, 'https://images.pexels.com/photos/12681316/pexels-photo-12681316.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Coliflor Entera Asada en Air Fryer', 'almuerzo',
 ['coliflor mediana entera|1 unidad', 'aceite de oliva|2 cdas', 'cúrcuma|1 cdta', 'comino|1 cdta', 'ajo en polvo|1 cdta', 'sal|al gusto', 'yogur con limón y cilantro|al gusto'],
 ['Mezcla el aceite con todas las especias. Unta sobre toda la coliflor cubriendo bien entre los floretes.', 'Coloca con el tallo hacia arriba en la canasta.', 'Cocina a 190°C por 15 minutos, voltea.', 'Cocina 15 minutos más hasta exterior dorado y tallo tierno al pincharlo.'],
 ['vegetariano', 'sin gluten'], 120, 5, 20, 25, 2, 5, 10, 'https://images.pexels.com/photos/6708438/pexels-photo-6708438.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Champiñones Rellenos de Queso y Ajo en Air Fryer', 'snack',
 ['champiñones portobello|8 unidades', 'queso crema|100 g', 'ajo picados|2 dientes', 'queso parmesano rallado|2 cdas', 'perejil fresco|al gusto', 'aceite en spray|al gusto'],
 ['Retira los tallos de los champiñones y pícalos fino.', 'Mezcla el queso crema con el ajo, los tallos picados, el parmesano y el perejil.', 'Rellena cada champiñón con la mezcla. Rocía con spray.', 'Cocina a 185°C por 12-15 minutos hasta que el relleno burbujee y dore.'],
 ['vegetariano', 'rápido'], 220, 12, 20, 8, 16, 2, 2, 'https://images.pexels.com/photos/5950435/pexels-photo-5950435.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Espárragos con Parmesano y Limón en Air Fryer', 'snack',
 ['espárragos|400 g', 'aceite de oliva|1 cda', 'parmesano rallado|3 cdas', 'jugo de 1 limón|al gusto', 'sal|al gusto'],
 ['Mezcla los espárragos con el aceite, sal, pimienta y ajo.', 'Coloca en una sola capa --- en tandas si es necesario.', 'Cocina a 200°C por 6-8 minutos según grosor.', 'Agrega el parmesano los últimos 2 minutos. Termina con limón.'],
 ['vegetariano', 'sin gluten', 'rápido'], 120, 6, 20, 6, 8, 2, 3, 'https://images.pexels.com/photos/33283957/pexels-photo-33283957.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Papas al Horno con Crema y Cebollín en Air Fryer', 'snack',
 ['papas pastusa medianas|4 unidades', 'aceite en spray|al gusto', 'sal gruesa|al gusto', 'crema de leche|al gusto', 'queso rallado|al gusto', 'cebollín y tocineta|al gusto'],
 ['Pincha cada papa varias veces con tenedor. Rocía con spray y espolvorea sal gruesa.', 'Cocina a 200°C por 20 minutos, voltea.', 'Cocina 15 minutos más hasta que al pincharlas el centro ceda completamente.', 'Haz un corte en cruz, abre y rellena con crema, queso y cebollín.'],
 ['vegetariano', 'económico'], 420, 15, 20, 40, 24, 4, 6, 'https://images.pexels.com/photos/88924/pexels-photo-88924.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Berenjena a la Parmigiana en Air Fryer', 'almuerzo',
 ['berenjenas en rodajas de 1 cm|2 unidades', 'sal para purgar|al gusto', 'salsa de tomate|1 taza', 'queso mozarela en lonjas|150 g', 'parmesano rallado|al gusto', 'albahaca fresca y aceite en spray|al gusto'],
 ['Espolvorea sal sobre las rodajas de berenjena y deja 20 minutos. Seca bien.', 'Rocía con spray y cocina a 185°C por 8 minutos por lado.', 'Arma en capas: berenjena, salsa de tomate, mozarela, parmesano.', 'Cocina 8 minutos más hasta que el queso burbujee. Decora con albahaca.'],
 ['vegetariano'], 220, 15, 20, 20, 10, 8, 5, 'https://images.pexels.com/photos/1707917/pexels-photo-1707917.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Pimentones Asados en Air Fryer', 'snack',
 ['pimentones de colores en mitades sin semillas|3 unidades', 'aceite de oliva|2 cdas', 'ajo laminados|3 dientes', 'sal y pimienta|al gusto', 'vinagre balsámico|al gusto', 'pan rústico para servir Sazona y sirve|al gusto'],
 ['Coloca los pimentones con la piel hacia arriba en la canasta.', 'Rocía con aceite y agrega el ajo laminado dentro de cada mitad.', 'Cocina a 200°C por 15-18 minutos hasta que la piel se ennegrezca.', 'Cubre con papel de aluminio 10 minutos --- la piel se pela sola.'],
 ['vegetariano', 'sin gluten', 'económico'], 120, 2, 20, 20, 7, 10, 4, 'https://images.pexels.com/photos/16575688/pexels-photo-16575688.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== DESAYUNOS ====================
['Arepa de Maíz con Queso Derretido en Air Fryer', 'desayuno',
 ['arepas de maíz blanco medianas|4 unidades', 'queso mozarela|100 g', 'aceite en spray|al gusto', 'mantequilla|al gusto'],
 ['Haz un corte en la mitad de cada arepa sin separar completamente.', 'Introduce el queso dentro de cada arepa.', 'Unta mantequilla por fuera si deseas y rocía con spray.', 'Cocina a 185°C por 10-12 minutos hasta que el queso se derrita completamente y la arepa esté dorada.'],
 ['vegetariano', 'tradicional'], 320, 12, 20, 35, 16, 2, 4, 'https://images.pexels.com/photos/32333982/pexels-photo-32333982.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Zanahorias Glaseadas con Miel y Canela en Air Fryer', 'snack',
 ['zanahorias en bastones|400 g', 'miel|1 cda', 'mantequilla derretida|1 cda', 'canela|1 cdta', 'sal|al gusto', 'perejil|al gusto'],
 ['Mezcla las zanahorias con la mantequilla, la miel, la canela y la sal.', 'Extiende en una capa en la canasta.', 'Cocina a 190°C por 10 minutos, sacude bien.', 'Cocina 8 minutos más hasta tiernas y caramelizadas en los bordes.'],
 ['vegetariano', 'sin gluten'], 120, 1, 20, 25, 4, 15, 3, 'https://images.pexels.com/photos/35017919/pexels-photo-35017919.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Plátano Maduro Asado en Air Fryer', 'snack',
 ['plátanos maduros (bien maduros|2 unidades', 'aceite en spray|al gusto', 'sal una pizca|al gusto', 'queso blanco|al gusto', 'canela|al gusto'],
 ['Pela los plátanos y córtalos en diagonal en tajadas de 1.5 cm.', 'Rocía con spray y espolvorea una pizca de sal.', 'Cocina a 185°C por 7 minutos, voltea con cuidado.', 'Cocina 5 minutos más hasta dorados y caramelizados. Sirve con queso.'],
 ['vegetariano', 'económico', 'rápido'], 220, 4, 20, 35, 9, 20, 4, 'https://images.pexels.com/photos/12362298/pexels-photo-12362298.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Churros con Chocolate en Air Fryer', 'snack',
 ['agua|250 ml', 'harina de trigo|150 g', 'mantequilla|1 cda', 'pizca de sal|1 unidad', 'aceite en spray|al gusto', 'azúcar y canela para cubrir|al gusto', 'chocolate caliente para mojar|al gusto'],
 ['Hierve el agua con la mantequilla y la sal. Agrega la harina de golpe y revuelve hasta masa que se despega de la olla.', 'Deja enfriar 5 minutos. Pasa la masa a manga pastelera con boquilla estrella.', 'Forma los churros directamente en la canasta engrasada.', 'Cocina a 195°C por 10-12 minutos hasta dorados. Pasa por mezcla de azúcar y canela.'],
 ['tradicional', 'vegetariano'], 320, 4, 20, 45, 15, 20, 2, 'https://images.pexels.com/photos/36361401/pexels-photo-36361401.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Buñuelos Colombianos en Air Fryer', 'snack',
 ['queso costeño rallado fino|200 g', 'almidón de yuca|100 g', 'huevos|2 unidades', 'azúcar|1 cdta', 'polvo de hornear|1 cdta', 'sal|al gusto', 'aceite en spray|al gusto'],
 ['Mezcla el queso rallado con el almidón, los huevos, el azúcar, el polvo de hornear y la sal.', 'Forma bolitas del tamaño de una pelota de golf --- la masa debe quedar compacta.', 'Rocía la canasta con spray y coloca los buñuelos sin que se toquen.', 'Cocina a 180°C por 12-15 minutos hasta dorados y huecos al golpearlos.'],
 ['tradicional', 'vegetariano'], 220, 12, 20, 25, 10, 5, 2, 'https://images.pexels.com/photos/34352096/pexels-photo-34352096.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Galletas de Chocolate y Maní en Air Fryer', 'snack',
 ['mantequilla de maní|150 g', 'azúcar morena|100 g', 'huevo|1 unidad', 'vainilla|1 cdta', 'chips de chocolate|80 g', 'polvo de hornear|1/2 cdta', 'pizca de sal|al gusto'],
 ['Mezcla la mantequilla de maní con el azúcar, el huevo y la vainilla.', 'Incorpora el polvo de hornear, la sal y los chips de chocolate.', 'Forma bolitas y aplánalas ligeramente. Refrigera 15 minutos.', 'Cocina a 160°C por 10-12 minutos. Deja enfriar en la canasta --- terminan de endurecerse al enfriarse.'],
 ['vegetariano', 'rápido'], 220, 4, 20, 25, 12, 15, 2, 'https://images.pexels.com/photos/8081573/pexels-photo-8081573.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Manzanas Asadas con Panela y Canela en Air Fryer', 'snack',
 ['manzanas rojas medianas|4 unidades', 'panela raspada|3 cdas', 'canela|1 cdta', 'mantequilla|2 cdas', 'nueces|al gusto', 'helado de vainilla|al gusto'],
 ['Descorazona las manzanas sin atravesar el fondo --- forma un hueco central.', 'Mezcla la panela, la canela, la mantequilla y las nueces. Rellena cada manzana.', 'Cocina a 175°C por 15-18 minutos hasta que la piel esté arrugada y el relleno burbujee.', 'Sirve caliente con helado de vainilla encima.'],
 ['vegetariano', 'sin gluten'], 220, 2, 20, 35, 10, 25, 4, 'https://images.pexels.com/photos/29172205/pexels-photo-29172205.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Torrejas con Arequipe en Air Fryer', 'snack',
 ['rebanadas gruesas de pan tajado del día anterior|4 unidades', 'huevos batidos|2 unidades', 'leche|100 ml', 'azúcar|2 cdas', 'vainilla y canela|1 cdta', 'aceite en spray|al gusto', 'arequipe para servir bien. Sirve con arequipe|al gusto'],
 ['Mezcla los huevos con la leche, el azúcar, la vainilla y la canela.', 'Sumerge el pan en la mezcla 30 segundos por lado --- que absorba', 'Rocía la canasta con spray. Cocina a 185°C por 6 minutos.', 'Voltea, rocía con spray y cocina 5-6 minutos más hasta doradas.'],
 ['tradicional', 'vegetariano'], 320, 12, 20, 45, 12, 20, 2, 'https://images.pexels.com/photos/30458468/pexels-photo-30458468.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Plátano Maduro con Bocadillo y Queso en Air Fryer', 'snack',
 ['plátanos maduros|2 unidades', 'bocadillo en tiras|100 g', 'queso blanco|80 g', 'aceite en spray|al gusto', 'canela|al gusto'],
 ['Pela los plátanos y córtalos por la mitad a lo largo sin separar completamente.', 'Rellena cada plátano con tiras de bocadillo y lonjas de queso.', 'Cierra suavemente y rocía con spray.', 'Cocina a 180°C por 8-10 minutos hasta que el queso se derrita y el plátano esté caramelizado.'],
 ['tradicional', 'vegetariano'], 420, 15, 20, 55, 20, 25, 4, 'https://images.pexels.com/photos/37297540/pexels-photo-37297540.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Donas Glaseadas en Air Fryer', 'snack',
 ['harina de trigo|250 g', 'levadura seca|7 g', 'leche tibia|100 ml', 'azúcar|50 g', 'huevo|1 unidad', 'mantequilla blanda|30 g', 'pizca de sal|al gusto', 'glaseado: 150g de azúcar pulverizada|al gusto'],
 ['Disuelve la levadura en la leche tibia con una pizca de azúcar. Deja reposar 5 minutos.', 'Mezcla la harina, azúcar y sal. Incorpora la levadura, el huevo y la mantequilla. Amasa 8 minutos hasta masa suave.', 'Deja levar 1 hora. Extiende, corta las donas y deja levar 30 minutos más.', 'Rocía con spray y cocina a 175°C por 5-6 minutos por lado. Glasea en caliente.'],
 ['vegetariano'], 320, 5, 20, 45, 15, 25, 2, 'https://images.pexels.com/photos/37220991/pexels-photo-37220991.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Brownie en Taza en Air Fryer', 'snack',
 ['harina de trigo|3 cdas', 'azúcar|4 cdas', 'cacao en polvo sin azúcar|2 cdas', 'huevo|1 unidad', 'mantequilla derretida|3 cdas', 'vainilla|1 cdta', 'pizca de sal|al gusto', 'chips de chocolate|al gusto'],
 ['Mezcla la harina, el azúcar, el cacao y la sal en un recipiente apto para horno.', 'Agrega el huevo, la mantequilla derretida y la vainilla. Mezcla hasta integrar.', 'Incorpora los chips de chocolate si los usas.', 'Cocina en el recipiente a 160°C por 10-12 minutos. El centro debe quedar ligeramente húmedo. --------------------- -------------- -------------- ---------- ---------- --------------------- -------------- -------------- ---------- ----------'],
 ['vegetariano', 'rápido'], 320, 4, 20, 35, 18, 20, 2, 'https://images.pexels.com/photos/3734023/pexels-photo-3734023.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== DESAYUNOS ====================
['Cottage Cheese Bowl con Fruta', 'desayuno',
 ['queso cottage|200 g', 'fresas|1 taza', 'miel|1 cda', 'semillas de chía|1 cda', 'canela|al gusto'],
 ['Sirve el queso cottage en un tazón.', 'Distribuye la fruta encima.', 'Rocía con miel y espolvorea la canela y las semillas de chía.', 'Consume de inmediato.'],
 ['alto en proteína', 'rápido', 'vegetariano'], 220, 28, 5, 25, 8, 15, 4, 'https://images.pexels.com/photos/15048297/pexels-photo-15048297.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Greek Yogurt Parfait Proteico', 'desayuno',
 ['yogur griego natural|200 g', 'granola baja en azúcar|30 g', 'banano en rodajas|1 unidad', 'mantequilla de maní|1 cda', 'semillas de lino|1 cda'],
 ['Coloca el yogur en un vaso o tazón.', 'Agrega la granola y el banano en capas.', 'Añade la mantequilla de maní en un hilo.', 'Finaliza con semillas de lino.'],
 ['alto en proteína', 'rápido', 'vegetariano'], 420, 25, 5, 55, 20, 25, 8, 'https://images.pexels.com/photos/1066658/pexels-photo-1066658.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevos Revueltos con Queso y Pan Tostado', 'desayuno',
 ['huevos|3 unidades', 'queso mozarella|30 g', 'pan integral|2 rebanadas', 'mantequilla|1 cdta', 'sal y pimienta|al gusto'],
 ['Bate los huevos con sal y pimienta.', 'Calienta la mantequilla en sartén a fuego bajo.', 'Vierte los huevos y revuelve despacio hasta casi cuajar.', 'Agrega el queso, retira del fuego y sirve sobre el pan tostado.'],
 ['alto en proteína', 'rápido', 'vegetariano'], 420, 24, 7, 30, 26, 4, 4, 'https://images.pexels.com/photos/14415378/pexels-photo-14415378.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Perico Venezolano', 'desayuno',
 ['huevos|3 unidades', 'tomate en cubos|1/2 unidad', 'cebolla cabezona picada|1/4 unidad', 'pimentón verde|1/4 unidad', 'aceite vegetal|al gusto', 'sal y pimienta|al gusto'],
 ['Sofríe la cebolla y el pimentón en aceite a fuego medio 3 minutos.', 'Agrega el tomate y cocina 2 minutos más.', 'Bate los huevos, viértelos sobre el sofrito.', 'Revuelve a fuego bajo hasta cuajar. Sirve con arepa o pan.'],
 ['alto en proteína', 'rápido', 'tradicional'], 320, 20, 10, 20, 20, 5, 2, 'https://images.pexels.com/photos/7696496/pexels-photo-7696496.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Chía Pudding de Proteína', 'desayuno',
 ['semillas de chía|3 cdas', 'leche (entera|250 ml', 'proteína en polvo sabor vainilla|1 scoop', 'miel|1 cda', 'fruta fresca|al gusto'],
 ['Mezcla la leche, la proteína en polvo y la miel en un frasco.', 'Agrega las semillas de chía y revuelve bien.', 'Refrigerar al menos 4 horas o toda la noche.', 'Al servir, decora con fruta fresca.'],
 ['alto en proteína', 'vegetariano'], 250, 20, 5, 35, 8, 15, 5, 'https://images.pexels.com/photos/5645175/pexels-photo-5645175.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostada Griega con Yogur y Huevo', 'desayuno',
 ['pan integral|2 rebanadas', 'yogur griego natural|150 g', 'huevos|2 unidades', 'pepino en rodajas|1/2 unidad', 'eneldo|al gusto', 'aceite de oliva|al gusto'],
 ['Tuesta el pan hasta dorar.', 'Mezcla el yogur con sal, un chorrito de limón y eneldo.', 'Extiende el yogur sobre las tostadas.', 'Fríe los huevos y colócalos encima. Añade el pepino.'],
 ['alto en proteína', 'rápido', 'vegetariano'], 420, 28, 8, 35, 24, 10, 4, 'https://images.pexels.com/photos/5840517/pexels-photo-5840517.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Smoothie de Banano y Proteína', 'desayuno',
 ['banano congelado|1 unidad', 'proteína en polvo (vainilla|1 scoop', 'leche|250 ml', 'mantequilla de maní|1 cda', 'hielo|al gusto'],
 ['Coloca todos los ingredientes en la licuadora.', 'Licúa 30 segundos hasta obtener una mezcla suave.', 'Ajusta la consistencia con más leche si es necesario.', 'Sirve de inmediato en vaso grande.'],
 ['alto en proteína', 'rápido', 'vegetariano'], 420, 28, 5, 55, 20, 35, 5, 'https://images.pexels.com/photos/775030/pexels-photo-775030.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan con Jamón y Huevo Duro', 'desayuno',
 ['pan integral|2 rebanadas', 'jamón de cerdo|3 lonjas', 'huevos|2 unidades', 'mostaza|al gusto', 'hojas de espinaca|al gusto', 'sal y pimienta|al gusto'],
 ['Hierve los huevos 8 minutos. Pélalos y córtalos en rodajas.', 'Tuesta el pan ligeramente.', 'Unta la mostaza o mayonesa sobre el pan.', 'Arma el sandwich con el jamón, los huevos y las hojas verdes.'],
 ['alto en proteína', 'rápido'], 420, 26, 10, 35, 24, 4, 4, 'https://images.pexels.com/photos/4087575/pexels-photo-4087575.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arepa con Queso y Huevo', 'desayuno',
 ['arepa precocida|1 unidad', 'queso blanco|50 g', 'huevos|2 unidades', 'aguacate|1/2 unidad', 'aceite para la sartén|al gusto', 'sal y pimienta|al gusto'],
 ['Calienta la arepa en sartén o tostadora hasta dorar.', 'Fríe los huevos en aceite a fuego medio.', 'Abre la arepa por la mitad y rellena con el queso.', 'Sirve con los huevos fritos y el aguacate al lado. CLÁSICOS ELEVADOS'],
 ['alto en proteína', 'tradicional'], 420, 22, 10, 35, 24, 4, 6, 'https://images.pexels.com/photos/31823012/pexels-photo-31823012.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Shakshuka Clásica', 'desayuno',
 ['huevos|4 unidades', 'tomates triturados|400 g', 'pimentón rojo|1 unidad', 'cebolla mediana|1 unidad', 'ajo|3 dientes', 'comino|al gusto', 'aceite de oliva|al gusto'],
 ['Sofríe la cebolla y el pimentón en aceite de oliva 5 minutos. Agrega el ajo y las especias 1 minuto más.', 'Incorpora los tomates triturados y cocina a fuego bajo 10 minutos hasta espesar.', 'Haz 4 huecos en la salsa y rompe un huevo en cada uno.', 'Cubre la sartén y cocina 5-7 minutos hasta que las claras estén firmes. Sirve con pan pita.'],
 ['alto en proteína', 'vegetariano'], 320, 22, 25, 20, 20, 8, 4, 'https://images.pexels.com/photos/6275102/pexels-photo-6275102.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Frittata de Espinacas y Ricotta', 'desayuno',
 ['huevos|4 unidades', 'ricotta|100 g', 'espinacas baby|100 g', 'queso parmesano|50 g', 'ajo|1 diente', 'aceite de oliva|al gusto'],
 ['Precalienta el horno a 180°C. Sofríe el ajo en aceite, agrega las espinacas 2 minutos.', 'Bate los huevos y mezcla con la ricotta, el parmesano y las espinacas. Sazona bien.', 'Vierte en una sartén apta para horno engrasada.', 'Hornea 12-15 minutos hasta que cuaje y dore. Corta en porciones.'],
 ['alto en proteína', 'vegetariano'], 320, 26, 20, 10, 22, 4, 2, 'https://images.pexels.com/photos/5639286/pexels-photo-5639286.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Omelette Clásico con Queso y Jamón', 'desayuno',
 ['huevos|3 unidades', 'jamón en cubos|50 g', 'queso rallado|40 g', 'cebolla cabezona|1/4 unidad', 'pimentón|1/2 unidad', 'mantequilla|al gusto'],
 ['Bate los huevos con sal y pimienta. Sofríe la cebolla y el pimentón en mantequilla 2 minutos.', 'Vierte los huevos sobre el sofrito. No revolver --- dejar cuajar los bordes.', 'Cuando el centro esté casi cuajado, agrega el jamón y el queso en un lado.', 'Dobla el omelette a la mitad y sirve de inmediato.'],
 ['alto en proteína', 'rápido'], 320, 26, 10, 8, 22, 2, 1, 'https://images.pexels.com/photos/9615726/pexels-photo-9615726.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tortilla Española', 'desayuno',
 ['huevos|4 unidades', 'papas medianas peladas en láminas finas|2 unidades', 'cebolla en juliana|1/2 unidad', 'aceite de oliva|al gusto', 'sal y pimienta|al gusto'],
 ['Fríe las papas y la cebolla en aceite a fuego bajo 15 minutos hasta blandas. Escurre el exceso de aceite.', 'Bate los huevos con sal y mezcla con las papas y la cebolla.', 'Cocina en sartén a fuego medio 3-4 minutos hasta cuajar el fondo.', 'Voltea con un plato y cocina el otro lado 3 minutos. Sirve tibia o fría.'],
 ['tradicional', 'vegetariano'], 320, 20, 25, 25, 20, 2, 3, 'https://images.pexels.com/photos/34490390/pexels-photo-34490390.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Full English Saludable', 'desayuno',
 ['huevos|4 unidades', 'salchichas de pavo|4 unidades', 'champiñones|100 g', 'tomates en mitades|2 unidades', 'frijoles en salsa de tomate|200 g', 'pan integral tostado|al gusto', 'aceite de oliva|al gusto'],
 ['Saltea los champiñones y los tomates en aceite de oliva 5 minutos.', 'Cocina las salchichas en la misma sartén.', 'Calienta los frijoles en olla pequeña.', 'Fríe o pocha los huevos. Dispón todo en el plato con el pan tostado.'],
 ['alto en proteína'], 542, 38, 25, 44, 24, 8, 8, 'https://images.pexels.com/photos/15640157/pexels-photo-15640157.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevos Benedictinos al Estilo Español', 'desayuno',
 ['huevos|4 unidades', 'pan rústico|4 rebanadas', 'jamón serrano|100 g', 'tomates maduros|2 unidades', 'ajo|2 dientes', 'aceite de oliva extra virgen|al gusto', 'vinagre blanco|al gusto'],
 ['Frota el pan con ajo y tomate cortado. Rocía con aceite y tuesta hasta dorar.', 'Hierve agua con un chorrito de vinagre a fuego suave.', 'Rompe cada huevo en un cuenco pequeño y desliza al agua. Cocina 3-4 minutos.', 'Coloca el jamón sobre el pan y corona con el huevo pochado. Sazona.'],
 ['alto en proteína'], 420, 28, 20, 25, 26, 4, 2, 'https://images.pexels.com/photos/16556114/pexels-photo-16556114.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Panneer Bhurji (Revuelto de Queso Indio)', 'desayuno',
 ['queso campesino|200 g', 'huevos|2 unidades', 'cebolla picada|1 unidad', 'tomate picado|1 unidad', 'pimentón verde|1 unidad', 'comino|al gusto', 'aceite|al gusto'],
 ['Sofríe la cebolla con el comino en aceite o mantequilla 3 minutos.', 'Agrega el tomate y el pimentón; cocina 4 minutos más.', 'Incorpora las especias y el queso desmenuzado.', 'Bate los huevos y vierte sobre la mezcla. Revuelve hasta cuajar.'],
 ['alto en proteína', 'vegetariano', 'sin gluten'], 320, 26, 20, 10, 22, 6, 2, 'https://images.pexels.com/photos/20271149/pexels-photo-20271149.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Menemen Turco', 'desayuno',
 ['huevos|4 unidades', 'tomates maduros en cubos|2 unidades', 'pimientos verdes en aros|2 unidades', 'queso blanco desmenuzado|80 g', 'mantequilla|al gusto', 'sal y pimienta roja molida|al gusto'],
 ['Saltea los pimientos en mantequilla 3 minutos.', 'Agrega los tomates y cocina 5 minutos hasta reducir.', 'Bate los huevos e incorpora a la sartén. Revuelve suavemente.', 'Cuando casi cuajen, agrega el queso desmenuzado. Retira del fuego y sirve.'],
 ['alto en proteína', 'vegetariano'], 320, 22, 15, 10, 24, 6, 2, 'https://images.pexels.com/photos/23708404/pexels-photo-23708404.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Changua con Queso y Pan', 'desayuno',
 ['leche entera|500 ml', 'huevos|2 unidades', 'queso blanco en cubos|50 g', 'pan|2 rebanadas', 'cilantro fresco|al gusto', 'sal|al gusto'],
 ['Hierve la leche a fuego medio con sal.', 'Rompe los huevos directamente en la leche hirviendo y cocina 3-4 minutos.', 'Añade el queso en cubos y retira del fuego cuando empiece a ablandarse.', 'Sirve en un tazón con el pan y el cilantro fresco.'],
 ['tradicional', 'vegetariano'], 320, 20, 15, 30, 18, 6, 2, 'https://images.pexels.com/photos/37039787/pexels-photo-37039787.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Gallo Pinto Costarricense', 'desayuno',
 ['arroz cocido|1 taza', 'frijoles negros cocidos con caldo|1 taza', 'cebolla picada|1/4 unidad', 'pimentón picado|1/4 unidad', 'salsa lizano|al gusto', 'aceite|al gusto'],
 ['Sofríe la cebolla y el pimentón en aceite 3 minutos.', 'Agrega los frijoles con un poco de caldo y cocina 2 minutos.', 'Incorpora el arroz y mezcla bien.', 'Añade la salsa lizano al gusto. Sirve con huevo frito y maduro.'],
 ['económico', 'vegetariano', 'tradicional'], 420, 18, 15, 60, 15, 5, 8, 'https://images.pexels.com/photos/6675081/pexels-photo-6675081.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Calentado Bogotano Fitness', 'desayuno',
 ['arroz y frijoles cocidos del día anterior|1 taza', 'huevos|2 unidades', 'hogao|1/4 unidad', 'queso campesino|50 g', 'aceite|al gusto'],
 ['Calienta el hogao en sartén y agrega el arroz y los frijoles. Saltea 5 minutos.', 'Empuja la mezcla hacia los bordes y fríe los huevos en el centro.', 'Sirve con el queso campesino desmenuzado encima.', 'Acompaña con arepa o pan.'],
 ['tradicional', 'alto en proteína'], 420, 22, 15, 40, 20, 5, 6, 'https://images.pexels.com/photos/693270/pexels-photo-693270.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Chilaquiles Rojos con Huevo', 'desayuno',
 ['tortillas de maíz cortadas en triángulos y fritas|2 unidades', 'salsa roja (tomate|200 ml', 'huevos|2 unidades', 'queso blanco desmenuzado|50 g', 'crema agria|al gusto', 'cebolla morada y cilantro|al gusto'],
 ['Prepara la salsa roja licuando tomates, ajo, cebolla y chile. Cocina 10 minutos.', 'Agrega las tortillas a la salsa y deja que absorban 2-3 minutos.', 'Fríe los huevos aparte.', 'Sirve los chilaquiles con el huevo encima, queso, crema y cilantro. PROTEICOS'],
 ['tradicional'], 420, 24, 20, 40, 24, 6, 6, 'https://images.pexels.com/photos/376463/pexels-photo-376463.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Overnight Oats de Chocolate y Proteína', 'desayuno',
 ['avena en hojuelas|80 g', 'leche|250 ml', 'proteína en polvo sabor chocolate|1 scoop', 'cacao en polvo sin azúcar|1 cda', 'miel|1 cda', 'fruta|al gusto'],
 ['Mezcla todos los ingredientes en un frasco con tapa.', 'Revuelve bien hasta integrar el cacao y la proteína sin grumos.', 'Cierra y refrigera mínimo 6 horas o toda la noche.', 'En la mañana agrega fruta fresca encima y consume.'],
 ['alto en proteína', 'vegetariano'], 420, 28, 5, 55, 15, 20, 8, 'https://images.pexels.com/photos/27850085/pexels-photo-27850085.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Porridge de Avena con Frutos Secos', 'desayuno',
 ['avena gruesa|80 g', 'leche|300 ml', 'proteína en polvo sabor vainilla|2 cdas', 'almendras picadas|30 g', 'nueces|20 g', 'miel|1 cda', 'canela|al gusto'],
 ['Hierve la leche en olla pequeña.', 'Agrega la avena y una pizca de sal. Cocina 5 minutos revolviendo.', 'Retira del fuego y mezcla la proteína en polvo hasta integrar.', 'Sirve en tazón con los frutos secos, miel y canela.'],
 ['vegetariano', 'rápido'], 420, 22, 10, 60, 20, 20, 8, 'https://images.pexels.com/photos/32963319/pexels-photo-32963319.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Açaí Bowl Proteico', 'desayuno',
 ['pulpa de açaí congelada|100 g', 'banano congelado|1 unidad', 'yogur griego|150 g', 'proteína en polvo|1 scoop', 'granola|al gusto'],
 ['Licúa el açaí, el banano, el yogur y la proteína con mínima cantidad de líquido.', 'La mezcla debe quedar espesa --- consistencia de helado.', 'Sirve en un tazón amplio.', 'Decora con granola, fruta fresca y semillas de chía.'],
 ['alto en proteína', 'vegetariano'], 370, 22, 10, 45, 15, 25, 8, 'https://images.pexels.com/photos/3035261/pexels-photo-3035261.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Quinoa Bowl Dulce con Frutas', 'desayuno',
 ['quinoa|150 g', 'leche|400 ml', 'miel|2 cdas', 'canela y vainilla|al gusto', 'nueces|50 g', 'fruta de temporada|al gusto', 'ralladura de naranja|al gusto'],
 ['Enjuaga la quinoa y cocina en leche a fuego bajo 15 minutos.', 'Agrega miel, canela, vainilla y ralladura de naranja.', 'Cocina 5 minutos más hasta obtener consistencia cremosa.', 'Sirve con fruta fresca y los frutos secos picados.'],
 ['vegetariano', 'sin gluten', 'rápido'], 420, 18, 20, 60, 15, 20, 8, 'https://images.pexels.com/photos/6978186/pexels-photo-6978186.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Smoothie Bowl Verde con Proteína', 'desayuno',
 ['espinacas baby|1 taza', 'banano congelado|1 unidad', 'yogur griego|200 g', 'proteína en polvo sabor vainilla|1 scoop', 'leche|100 ml', 'granola y fruta|al gusto'],
 ['Licúa las espinacas, el banano, el yogur, la proteína y la leche.', 'Licúa hasta obtener mezcla espesa y homogénea.', 'Sirve en un tazón amplio.', 'Decora con granola, fruta y semillas al gusto.'],
 ['alto en proteína', 'vegetariano'], 420, 25, 8, 60, 15, 30, 8, 'https://images.pexels.com/photos/6327140/pexels-photo-6327140.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Overnight Oats de Mantequilla de Maní', 'desayuno',
 ['avena en hojuelas|80 g', 'leche|250 ml', 'mantequilla de maní sin azúcar|2 cdas', 'proteína en polvo|1 scoop', 'miel|1 cda', 'banano|1 unidad'],
 ['Mezcla la avena, leche, proteína, mantequilla de maní y miel en un frasco.', 'Revuelve bien hasta que la mantequilla de maní se integre.', 'Refrigera mínimo 6 horas.', 'Al servir, agrega el banano en rodajas encima.'],
 ['vegetariano'], 420, 26, 5, 55, 18, 20, 8, 'https://images.pexels.com/photos/29150523/pexels-photo-29150523.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Loaded Protein Bowl', 'desayuno',
 ['huevos revueltos|3 unidades', 'frijoles negros cocidos|1/2 taza', 'aguacate|1/2 unidad', 'queso campesino|50 g', 'arroz integral cocido|1/2 taza', 'salsa|al gusto', 'cilantro fresco|al gusto'],
 ['Calienta los frijoles y el arroz en sartén o microondas.', 'Revuelve los huevos en sartén con aceite.', 'Arma el bowl: arroz y frijoles de base, huevos encima.', 'Añade el aguacate, el queso y la salsa. Termina con cilantro.'],
 ['alto en proteína', 'sin gluten'], 520, 35, 15, 60, 24, 8, 10, 'https://images.pexels.com/photos/6823322/pexels-photo-6823322.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Porridge de Quinoa con Canela', 'desayuno',
 ['quinoa|150 g', 'leche|400 ml', 'canela molida|1 cdta', 'miel|2 cdas', 'arándanos|50 g', 'almendras laminadas|30 g'],
 ['Cocina la quinoa en leche con canela a fuego bajo 15 minutos.', 'Revuelve frecuentemente hasta obtener consistencia cremosa.', 'Endulza con miel y sirve en tazón.', 'Corona con los arándanos y las almendras.'],
 ['vegetariano', 'sin gluten'], 420, 20, 20, 60, 15, 20, 8, 'https://images.pexels.com/photos/7111532/pexels-photo-7111532.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Skyr Bowl con Frutas y Granola', 'desayuno',
 ['yogur griego|200 g', 'banano en rodajas|1 unidad', 'fresas|100 g', 'granola sin azúcar|30 g', 'semillas de chía|1 cda', 'miel|1 cda'],
 ['Sirve el yogur en un tazón amplio.', 'Distribuye la fruta por encima.', 'Agrega la granola y las semillas de chía.', 'Termina con un hilo de miel.'],
 ['alto en proteína', 'vegetariano', 'rápido'], 370, 24, 5, 55, 10, 30, 8, 'https://images.pexels.com/photos/566564/pexels-photo-566564.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Avena con Proteína y Fresas', 'desayuno',
 ['avena gruesa|70 g', 'leche|250 ml', 'proteína en polvo sabor fresa|2 cdas', 'fresas frescas|100 g', 'mantequilla de maní|1 cda', 'semillas de lino|1 cdta'],
 ['Cocina la avena en leche a fuego medio 5 minutos.', 'Retira del fuego y mezcla la proteína en polvo.', 'Agrega la mantequilla de maní y revuelve.', 'Sirve con las fresas cortadas y las semillas de lino. CONTUNDENTES'],
 ['alto en proteína', 'vegetariano', 'rápido'], 420, 22, 10, 55, 17, 20, 8, 'https://images.pexels.com/photos/5604830/pexels-photo-5604830.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Desayuno Costeño Completo', 'desayuno',
 ['huevos fritos|2 unidades', 'arepa de maíz blanco|1 unidad', 'queso costeño|100 g', 'banano maduro asado|1 tajada', 'aguacate|1/2 unidad', 'jugo de naranja|al gusto'],
 ['Prepara la arepa en sartén caliente hasta dorar por ambos lados.', 'Asa el banano maduro en sartén con poco aceite.', 'Fríe los huevos al gusto.', 'Sirve todo junto con el queso y el aguacate al lado.'],
 ['tradicional', 'alto en proteína'], 740, 35, 25, 90, 34, 30, 10, 'https://images.pexels.com/photos/22873804/pexels-photo-22873804.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Steak and Eggs', 'desayuno',
 ['bistec de res|150 g', 'huevos|3 unidades', 'espinacas|1 taza', 'cebolla en aros|1/2 unidad', 'aceite|al gusto'],
 ['Sazona el bistec con sal, pimienta y ajo. Sella en sartén caliente 3 minutos por lado.', 'Deja reposar la carne 3 minutos antes de cortar.', 'En la misma sartén saltea las espinacas y la cebolla 2 minutos.', 'Fríe o revuelve los huevos. Sirve todo junto.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 520, 45, 20, 10, 35, 2, 3, 'https://images.pexels.com/photos/6863/food-plate-toast-restaurant.jpg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Breakfast Burrito Proteico', 'desayuno',
 ['huevos revueltos|3 unidades', 'pechuga de pollo cocida y desmenuzada|100 g', 'tortilla de trigo integral grande|1 unidad', 'queso rallado|50 g', 'aguacate|1/2 unidad', 'salsa picante|al gusto', 'sal|al gusto'],
 ['Revuelve los huevos con sal, pimienta y comino.', 'Calienta la tortilla en sartén seca 30 segundos.', 'Coloca los huevos, el pollo y el queso en el centro de la tortilla.', 'Agrega el aguacate y la salsa. Dobla y sirve.'],
 ['alto en proteína'], 540, 38, 15, 40, 26, 5, 7, 'https://images.pexels.com/photos/5848079/pexels-photo-5848079.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Desayuno de Gym: Bowl de Pollo y Huevo', 'desayuno',
 ['pechuga de pollo a la plancha|150 g', 'huevos revueltos|3 unidades', 'arroz integral cocido|1 taza', 'aguacate|1/2 unidad', 'brócoli al vapor|al gusto', 'sal|al gusto'],
 ['Sazona el pollo y gríllalo en sartén caliente 5-6 minutos por lado.', 'Revuelve los huevos con sal.', 'Arma el bowl: arroz de base, pollo y huevos encima.', 'Añade el aguacate, el brócoli y exprime limón al final.'],
 ['alto en proteína'], 540, 48, 20, 40, 24, 4, 10, 'https://images.pexels.com/photos/14231739/pexels-photo-14231739.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Empanadas de Jamón y Queso al Horno', 'desayuno',
 ['empanadas de masa de maíz|4 unidades', 'jamón en cubos|100 g', 'queso blanco|100 g', 'huevos duros picados|2 unidades', 'aceite|al gusto'],
 ['Precalienta el horno a 200°C.', 'Mezcla el jamón, el queso y los huevos duros.', 'Rellena cada empanada con la mezcla y cierra bien los bordes.', 'Pinta con huevo batido y hornea 20 minutos hasta dorar.'],
 ['alto en proteína'], 420, 22, 30, 35, 24, 4, 2, 'https://images.pexels.com/photos/24375977/pexels-photo-24375977.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tacos de Huevo y Frijoles', 'desayuno',
 ['tortillas de maíz|3 unidades', 'huevos revueltos|3 unidades', 'frijoles negros cocidos|1/2 taza', 'queso rallado|50 g', 'aguacate|1/2 unidad', 'salsa verde|al gusto', 'cilantro y limón|al gusto'],
 ['Calienta las tortillas en comal o sartén seca.', 'Revuelve los huevos con sal.', 'Calienta los frijoles.', 'Arma cada taco con frijoles, huevo, queso, aguacate y salsa.'],
 ['alto en proteína', 'económico'], 420, 26, 15, 45, 22, 6, 8, 'https://images.pexels.com/photos/28895960/pexels-photo-28895960.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arepa de Pabellón (Inspiración Venezolana)', 'desayuno',
 ['arepas|2 unidades', 'carne molida|100 g', 'frijoles negros|1/2 taza', 'queso blanco rallado|50 g', 'aguacate|1/4 unidad', 'ají|al gusto'],
 ['Prepara las arepas en sartén caliente hasta dorar ambos lados.', 'Calienta la carne y los frijoles por separado.', 'Abre las arepas y rellena con carne, frijoles y queso.', 'Añade el aguacate y el ají.'],
 ['tradicional', 'alto en proteína'], 740, 32, 25, 60, 40, 8, 10, 'https://images.pexels.com/photos/27626472/pexels-photo-27626472.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Huevos con Salchicha y Batata', 'desayuno',
 ['huevos|3 unidades', 'salchichas de pollo|2 unidades', 'batata mediana en cubos|1 unidad', 'cebolla picada|1/4 unidad', 'aceite|al gusto'],
 ['Fríe los cubos de batata en aceite a fuego medio-alto 10 minutos hasta dorar.', 'Agrega la cebolla y las salchichas. Saltea 3-4 minutos.', 'Haz espacio en la sartén y agrega los huevos --- revueltos o fritos.', 'Sazona con paprika y sirve en la misma sartén.'],
 ['alto en proteína'], 420, 28, 20, 30, 24, 6, 4, 'https://images.pexels.com/photos/5685176/pexels-photo-5685176.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Sandwich Proteico de Pollo y Aguacate', 'desayuno',
 ['pechuga de pollo a la plancha|150 g', 'pan integral|2 rebanadas', 'aguacate|1/2 unidad', 'queso mozarella|2 lonjas', 'lechuga|al gusto', 'mostaza|al gusto'],
 ['Sazona el pollo y gríllalo en sartén 5 minutos por lado. Deja reposar y rebana.', 'Tuesta el pan ligeramente.', 'Machaca el aguacate con sal y pimienta.', 'Arma el sandwich con todos los ingredientes.'],
 ['alto en proteína'], 540, 38, 15, 35, 26, 5, 7, 'https://images.pexels.com/photos/12310570/pexels-photo-12310570.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bowl de Huevo, Frijoles y Arroz', 'desayuno',
 ['huevos fritos|2 unidades', 'frijoles negros|1 taza', 'arroz blanco cocido|1/2 taza', 'hogao|1 cda', 'queso blanco|50 g', 'aguacate y cilantro|al gusto'],
 ['Calienta los frijoles con un poco de su caldo y el hogao.', 'Calienta el arroz.', 'Fríe los huevos.', 'Sirve en bowl con arroz y frijoles de base, huevos encima, queso, aguacate y cilantro.'],
 ['alto en proteína', 'económico'], 520, 28, 15, 60, 24, 6, 10, 'https://images.pexels.com/photos/5773961/pexels-photo-5773961.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Quiche de Verduras y Jamón', 'desayuno',
 ['huevos|4 unidades', 'leche|200 ml', 'jamón en cubos|100 g', 'queso rallado|80 g', 'espinacas|1/2 taza', 'base de masa para tarta|al gusto', 'sal|al gusto'],
 ['Precalienta horno a 180°C. Si usas base de masa, colócala en molde.', 'Bate los huevos con leche, sal, pimienta y nuez moscada.', 'Distribuye el jamón, las verduras y el queso sobre la base.', 'Vierte la mezcla de huevo y hornea 25 minutos hasta cuajar. VERSIONES DULCES'],
 ['alto en proteína'], 320, 22, 30, 20, 20, 4, 2, 'https://images.pexels.com/photos/35618027/pexels-photo-35618027.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pancakes de Proteína (2 ingredientes)', 'desayuno',
 ['banano|1 unidad', 'huevos|2 unidades'],
 ['Aplasta los bananos en un tazón con un tenedor hasta obtener puré.', 'Agrega los huevos y la proteína en polvo si la usas. Mezcla bien.', 'Calienta una sartén con poco aceite a fuego medio.', 'Vierte porciones y cocina 2 minutos por lado hasta dorar.'],
 ['vegetariano', 'rápido', 'sin gluten'], 220, 18, 12, 25, 8, 15, 2, 'https://images.pexels.com/photos/6947056/pexels-photo-6947056.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['French Toast Proteico', 'desayuno',
 ['rebanadas gruesas de pan integral|2 unidades', 'huevos|2 unidades', 'leche|100 ml', 'proteína en polvo sabor vainilla|1 scoop', 'canela y vainilla|al gusto', 'fruta fresca y miel|al gusto'],
 ['Bate los huevos con la leche, la proteína, la canela y la vainilla.', 'Sumerge el pan en la mezcla y deja que absorba 30 segundos por lado.', 'Cocina en sartén con mantequilla 3 minutos por lado hasta dorar.', 'Sirve con fruta fresca y un hilo de miel.'],
 ['alto en proteína', 'vegetariano'], 420, 24, 12, 55, 15, 20, 4, 'https://images.pexels.com/photos/8995199/pexels-photo-8995199.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pancakes de Avena y Proteína', 'desayuno',
 ['avena en hojuelas|80 g', 'huevos|2 unidades', 'leche|150 ml', 'proteína en polvo|1 scoop', 'polvo de hornear|1 cdta', 'canela y vainilla|al gusto'],
 ['Licúa la avena, los huevos, la leche, la proteína, el polvo de hornear y los aromatizantes.', 'Deja reposar la mezcla 5 minutos.', 'Cocina en sartén con poco aceite, porciones de 1/4 taza a fuego medio.', 'Voltea cuando aparezcan burbujas en la superficie. Sirve con fruta y miel.'],
 ['alto en proteína', 'vegetariano'], 320, 22, 15, 40, 10, 10, 4, 'https://images.pexels.com/photos/6947253/pexels-photo-6947253.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Crepes con Ricotta y Frutos Rojos', 'desayuno',
 ['huevos|2 unidades', 'leche|150 ml', 'harina de trigo|80 g', 'ricotta|200 g', 'miel|2 cdas', 'frutos rojos frescos|100 g'],
 ['Bate los huevos con la leche, la harina y una pizca de sal. Refrigera 10 minutos.', 'Cocina las crepes en sartén antiadherente con un poco de mantequilla, 1-2 minutos por lado.', 'Mezcla la ricotta con la miel.', 'Rellena cada crepe con la ricotta y los frutos rojos. Dobla y sirve.'],
 ['vegetariano'], 320, 22, 20, 40, 12, 20, 4, 'https://images.pexels.com/photos/10686446/pexels-photo-10686446.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Muffins de Huevo y Espinacas con Base Dulce', 'desayuno',
 ['huevos|4 unidades', 'espinacas picadas|100 g', 'queso rallado|80 g', 'jamón|50 g', 'sal|al gusto', 'spray antiadherente|al gusto'],
 ['Precalienta horno a 180°C. Engrasa un molde de 6 muffins.', 'Bate los huevos con sal y pimienta.', 'Distribuye las espinacas, el jamón y el queso en los moldes.', 'Vierte la mezcla de huevo encima y hornea 18-20 minutos.'],
 ['alto en proteína', 'vegetariano'], 320, 18, 25, 30, 18, 8, 4, 'https://images.pexels.com/photos/6529823/pexels-photo-6529823.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Banana Protein Pancakes con Mantequilla de Maní', 'desayuno',
 ['banano maduro|1 unidad', 'huevos|2 unidades', 'proteína en polvo|1 scoop', 'mantequilla de maní|2 cdas', 'fresas|al gusto', 'miel|al gusto'],
 ['Aplasta el banano y mezcla con los huevos y la proteína hasta integrar.', 'Cocina en sartén con poco aceite a fuego medio-bajo.', 'Voltea cuando los bordes estén firmes.', 'Sirve con mantequilla de maní, fresas y un hilo de miel.'],
 ['vegetariano', 'rápido'], 420, 24, 12, 45, 20, 25, 6, 'https://images.pexels.com/photos/38370352/pexels-photo-38370352.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Porridge de Chocolate y Maní', 'desayuno',
 ['avena|80 g', 'leche|250 ml', 'cacao en polvo sin azúcar|2 cdas', 'mantequilla de maní|2 cdas', 'proteína de chocolate|1 scoop', 'banano en rodajas|al gusto'],
 ['Cocina la avena en leche con el cacao a fuego medio 5 minutos.', 'Retira del fuego y agrega la proteína en polvo. Mezcla bien.', 'Incorpora la mantequilla de maní.', 'Sirve con el banano en rodajas encima.'],
 ['vegetariano', 'rápido'], 420, 24, 10, 60, 20, 25, 8, 'https://images.pexels.com/photos/29150522/pexels-photo-29150522.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Avena Colombiana Dulce con Proteína', 'desayuno',
 ['leche|500 ml', 'avena en hojuelas|60 g', 'proteína en polvo sabor vainilla|1 scoop', 'canela|al gusto', 'panela|2 cdas', 'fruta de temporada|al gusto'],
 ['Licúa la avena cruda con la mitad de la leche hasta que quede suave.', 'Calienta en olla a fuego medio con el resto de la leche y la panela.', 'Revuelve constantemente hasta espesar, 5 minutos.', 'Retira del fuego, agrega la proteína y la vainilla. Sirve fría o caliente.'],
 ['alto en proteína', 'tradicional'], 320, 18, 10, 45, 8, 20, 5, 'https://images.pexels.com/photos/7655878/pexels-photo-7655878.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Tostada con Aguacate y Huevo Revuelto', 'desayuno',
 ['pan integral|2 rebanadas', 'huevos|2 unidades', 'aguacate maduro|1/2 unidad', 'sal|al gusto', 'aceite de oliva|al gusto', 'hojuelas de chile|al gusto'],
 ['Tuesta el pan y machaca el aguacate con sal, limón y pimienta.', 'Unta el aguacate sobre las tostadas.', 'Revuelve los huevos en sartén con aceite a fuego medio 2 minutos.', 'Coloca los huevos sobre las tostadas y termina con chile.'],
 ['alto en proteína', 'rápido', 'vegetariano'], 420, 22, 8, 30, 25, 4, 7, 'https://images.pexels.com/photos/29893443/pexels-photo-29893443.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Bowl de Pollo Marinado con Maíz y Vegetales Frescos', 'almuerzo',
 ['pechuga de pollo|200 g en cubos', 'maíz dulce|1/2 taza', 'pimentón rojo|1/2 unidad', 'pepino|1/2 unidad', 'cebolla morada|1/4 unidad', 'perejil|1 rama', 'aceite de oliva|2 cdas', 'limón|1/2 unidad'],
 ['Marina el pollo: En un bol, mezcla los cubos de pollo con aceite de oliva, sal, pimienta, jugo de limón y una pizca de paprika o comino. Déjalo reposar unos 15 minutos para que se impregne de sabor', 'Cocina el pollo: Calienta una sartén o parrilla y cocina los trozos de pollo a fuego medio-alto hasta que estén dorados por fuera y jugosos por dentro (unos 8-10 minutos). El aroma a especias te avisará que ya está perfecto', 'Prepara los vegetales: Corta el pepino, el pimiento y la cebolla morada. Colócalos en un bol grande junto con el maíz y el perejil picado', 'Haz el aderezo: En un vasito, mezcla 2 cucharadas de aceite de oliva, el jugo de medio limón, una pizca de sal, pimienta y, si quieres, una gotita de miel para equilibrar la acidez. Bate bien hasta que tenga textura brillante y ligera', 'Arma la ensalada: Incorpora el pollo caliente sobre los vegetales, vierte el aderezo por encima y mezcla suavemente para que todo quede bien impregnado y colorido', 'Sirve y disfruta: Ideal para un almuerzo fresco o cena ligera. El contraste entre el maíz dulce, el pollo ahumado y el toque cítrico del limón es simplemente irresistible'],
 ['alto en proteína', 'sin gluten'], 420, 35, 25, 30, 20, 10, 5, 'https://images.pexels.com/photos/16266737/pexels-photo-16266737.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Bistec con Puré de Aguacate y Vegetales Salteados', 'cena',
 ['bistec de res|200 g', 'aguacate|1 unidad', 'brócoli|1/2 taza', 'coliflor|1/2 taza', 'papa|2 unidades', 'ajo|2 dientes', 'mantequilla|1 cda'],
 ['Sazona y cocina el bistec Agrega sal, pimienta y un toque de ajo al bistec. Cocínalo en sartén o parrilla bien caliente con un poco de aceite durante 3-4 minutos por lado. Busca ese dorado brillante por fuera y jugoso por dentro... ¡clave total!', 'Prepara las papas doradas: Hierve las papas hasta que estén suaves. Luego saltéalas con mantequilla y un poco de aceite hasta que queden doradas por fuera y cremosas por dentro', 'Saltea las verduras: En otra sartén, saltea el brócoli y la coliflor con aceite de oliva, sal y pimienta. Cocínalos hasta que estén tiernos pero con un ligero toque crujiente (unos 5-7 minutos)', 'Haz el puré de aguacate Machaca el aguacate con un poco de sal, pimienta y unas gotas de limón. Debe quedar cremoso, fresco y suave', 'Arma el plato: Corta el bistec en tiras, sírvelo junto al puré de aguacate y acompaña con las papas doradas y las verduras salteadas. Termina con perejil fresco por encima'],
 ['alto en proteína', 'sin gluten'], 542, 37, 35, 23, 37, 6, 10, 'https://images.pexels.com/photos/36788534/pexels-photo-36788534.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Ensalada Tibia de Pollo, Huevo y Mostaza', 'almuerzo',
 ['pechuga de pollo|180 g', 'huevos|2 unidades', 'espinacas|1 taza', 'tomate cherry|8 unidades', 'cebolla morada|1/4 unidad', 'mostaza|1 cdta', 'aceite de oliva|3 cdas', 'limón|1/2 unidad'],
 ['Cocina el pollo: Sazona la pechuga con sal y pimienta y cocínala en sartén con un poco de aceite a fuego medio. Déjala dorar bien hasta que tenga ese color tostado irresistible y esté jugosa por dentro. Luego córtala en tiras', 'Prepara los huevos: Hierve los huevos durante 8--10 minutos. Pélalos y córtalos por la mitad. La yema debe quedar firme pero aún cremosa en el centro', 'Lava y corta los vegetales Coloca una base de espinaca fresca, añade los tomates cherry cortados y la cebolla morada en rodajas finas', 'Arma la ensalada: Distribuye el pollo caliente o tibio sobre la base de vegetales y añade los huevos. El contraste de temperaturas la hace aún más rica', 'Prepara el aderezo: Mezcla: 3 cucharadas de aceite de oliva 1 cucharadita de mostaza Jugo de medio limón Sal y pimienta Bate hasta que emulsione y quede suave y brillante', 'Toque final: Rocía el aderezo justo antes de servir. Si quieres, agrega semillas o un toque extra de pimienta para más carácter'],
 ['alto en proteína', 'sin gluten'], 420, 37, 25, 10, 24, 4, 4, 'https://images.pexels.com/photos/28933157/pexels-photo-28933157.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== DESAYUNOS ====================
['Bowl de Huevos Revueltos con Aguacate y Vegetales', 'desayuno',
 ['huevos|3 unidades', 'aguacate|1/2 unidad', 'pepino|1/2 unidad', 'pimentón rojo|1/4 unidad', 'pimentón amarillo|1/4 unidad', 'cebolla morada|1/4 unidad', 'cebollín|1 cda', 'semillas de sésamo|1 cdta'],
 ['Saltea los vegetales: En una sartén con un chorrito de aceite de oliva, cocina los pimientos y la cebolla morada a fuego medio. Déjalos unos 4-5 minutos hasta que estén suaves, brillantes y ligeramente caramelizados', 'Bate y cocina los huevos Bate los huevos con sal y pimienta. Agrégalos a la sartén (puedes retirarprimero los vegetales o dejarlos a un lado) y cocina a fuego bajo, removiendo suavemente hasta que queden cremosos y jugosos', 'Prepara los frescos: Corta el aguacate en gajos suaves y el pepino en rodajas finas. Esa textura fresca y crujiente hará un contraste delicioso', 'Arma el bowl: Coloca los huevos revueltos en un lado, añade los pimientos y cebolla salteados, el pepino y el aguacate. Todo bien organizado y colorido', 'Toque final: Espolvorea semillas de sésamo y cebollín por encima. Termina con un chorrito de aceite de oliva o unas gotas de limón para realzar los sabores'],
 ['alto en proteína', 'vegetariano', 'sin gluten'], 420, 22, 20, 20, 30, 6, 10, 'https://images.pexels.com/photos/70078/pexels-photo-70078.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== ALMUERZOS ====================
['Pescado Dorado con Ensalada de Repollo y Zanahoria', 'almuerzo',
 ['filete de pescado blanco|200 g', 'lechuga|1 taza', 'repollo morado|1 taza', 'zanahoria|1 unidad rallada', 'tomate cherry|6 unidades', 'aceite de oliva|3 cdas', 'limón|1 unidad', 'pimentón|1 cdta'],
 ['Sazona el pescado: Espolvorea sal, pimienta y un toque de pimentón por ambos lados. Déjalo reposar unos minutos para que absorba bien los sabores', 'Dora el pescado: En una sartén caliente con un chorrito de aceite de oliva, cocina los filetes a fuego medio-alto. Déjalos unos 3-4 minutos por lado, hasta que estén dorados por fuera y suaves y jugosos por dentro (se deshacen fácilmente con el tenedor)', 'Prepara la ensalada: En un bowl mezcla la lechuga, el repollo morado, la zanahoria rallada y los tomates cherry. Colores vivos = más apetito', 'Haz el aderezo: Mezcla 2 cucharadas de aceite de oliva, 1 cucharada de jugo de limón, una pizca de sal y pimienta. Bate hasta que quede brillante y ligeramente espeso', 'Integra y sirve: Agrega el aderezo a la ensalada y mezcla suavemente. Coloca los filetes dorados encima o al lado... ese contraste entre crujiente por fuera y jugoso por dentro es irresistible'],
 ['alto en proteína', 'sin gluten'], 420, 35, 20, 20, 20, 8, 5, 'https://images.pexels.com/photos/9045146/pexels-photo-9045146.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== CENAS ====================
['Bowl de Carne Molida con Batata Asada y Vegetales', 'cena',
 ['carne molida de res|200 g', 'batata|1 unidad', 'brócoli|1/2 taza', 'zanahoria|1 unidad', 'pimentón rojo|1/2 unidad', 'ajo en polvo|1 cdta', 'pimentón dulce|1 cdta'],
 ['Hornea la batata: Corta la batata en gajos, agrégales un chorrito de aceite de oliva, sal y pimentón. Llévalas al horno a 200°C por unos 25-30 minutos, hasta que estén doradas por fuera y suaves por dentro', 'Cocina la carne: En una sartén caliente, agrega la carne molida con sal, pimienta y ajo. Cocina a fuego medio-alto, desmenuzándola bien, hasta que esté dorada y jugosa (unos 7-10 minutos)', 'Saltea los vegetales: En la misma sartén, añade zanahoria y pimientos en cubitos. Saltéalos unos minutos hasta que estén suaves pero aún ligeramente crujientes. El color y aroma se vuelven irresistibles', 'Cocina el brócoli: Hiérvelo o hazlo al vapor durante 3-4 minutos hasta que esté verde intenso y firme. Un toque de sal al final resalta todo su sabor', 'Arma el bowl: Coloca la carne, los gajos de batata, el brócoli y los vegetales salteados en el plato. Puedes añadir un chorrito extra de aceite de oliva o limón para darle frescura'],
 ['alto en proteína', 'sin gluten'], 520, 35, 40, 40, 25, 10, 8, 'https://images.pexels.com/photos/17543143/pexels-photo-17543143.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== DESAYUNOS ====================
['Waffle Fitness de Proteína', 'desayuno',
 ['huevos|2 unidades', 'proteína en polvo|1 scoop', 'avena molida|80 g', 'leche|100 ml', 'polvo de hornear|1 cdta', 'fruta y yogur|al gusto'],
 ['Precalienta la wafflera.', 'Licúa todos los ingredientes hasta obtener mezcla suave.', 'Engrasa la wafflera y vierte la mezcla.', 'Cocina según las instrucciones de tu wafflera. Sirve con fruta y yogur.'],
 ['alto en proteína', 'rápido'], 320, 24, 15, 40, 10, 10, 4, 'https://images.pexels.com/photos/28919131/pexels-photo-28919131.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Bowl de Yogur con Granola de Coco', 'desayuno',
 ['yogur griego|200 g', 'granola con coco|30 g', 'mango en cubos|1/2 unidad', 'miel|1 cda', 'coco rallado|1 cda', 'semillas de chía|1 cda'],
 ['Sirve el yogur en un tazón.', 'Añade la granola y el mango en capas.', 'Espolvorea el coco rallado y las semillas de chía.', 'Termina con la miel.'],
 ['alto en proteína', 'rápido'], 420, 24, 5, 55, 18, 30, 6, 'https://images.pexels.com/photos/6823321/pexels-photo-6823321.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

// ==================== SNACKS ====================
['Alitas de Pollo Buffalo en Air Fryer', 'snack',
 ['alitas de pollo|500 g', 'aceite en spray|al gusto', 'sal y pimienta|al gusto', 'salsa buffalo: 4 cdas de salsa picante|al gusto'],
 ['Seca las alitas muy bien con papel absorbente. Sazona con sal y', 'Rocía con spray. Cocina a 200°C por 12 minutos, voltea.', 'Cocina 12 minutos más hasta piel crujiente.', 'Mezcla los ingredientes de la salsa buffalo y baña las alitas'],
 ['tradicional', 'rápido'], 420, 37, 20, 6, 28, 2, 1, 'https://images.pexels.com/photos/32067295/pexels-photo-32067295.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Arepa de Chócolo Rellena en Air Fryer', 'snack',
 ['arepas de chócolo medianas|4 unidades', 'queso mozarela|100 g', 'bocadillo en tiras|50 g', 'aceite en spray|al gusto'],
 ['Abre cada arepa por la mitad sin separar completamente.', 'Rellena con el queso y el bocadillo.', 'Cierra bien y rocía con spray por ambos lados.', 'Cocina a 185°C por 8-10 minutos'],
 ['tradicional', 'rápido'], 420, 18, 20, 45, 20, 5, 4, 'https://images.pexels.com/photos/25635789/pexels-photo-25635789.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Lentejas sin Gluten', 'snack',
 ['lentejas cocidas|1 taza', 'huevos|3 unidades', 'aceite de oliva|1/4 taza', 'polvo de hornear|1 cdta', 'sal|1 pizca'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'Coloca las lentejas cocidas, los huevos, el aceite de oliva, el polvo de hornear y la sal en un procesador de alimentos.', 'Procesa hasta obtener una mezcla suave y homogénea, sin grumos de lenteja.', 'Vierte la mezcla en el molde engrasado.', 'Hornea 30-35 minutos, hasta que esté dorado por fuera y firme al tacto en el centro.', 'Deja enfriar 10 minutos en el molde antes de desmoldar y cortar en tajadas.'],
 ['sin gluten', 'vegetariano'], 220, 15, 45, 25, 8, 2, 5, 'https://images.pexels.com/photos/76227/pexels-photo-76227.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Zanahoria sin Gluten y sin Harina', 'snack',
 ['zanahoria rallada|1 taza', 'huevos|3 unidades', 'aceite de coco|1/4 taza', 'miel|1/4 taza', 'canela|1 cdta', 'bicarbonato de sodio|1/2 cdta', 'nueces picadas|1/2 taza'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'En un bowl grande, mezcla la zanahoria rallada, los huevos, el aceite de coco derretido, la miel y la canela.', 'Incorpora el bicarbonato de sodio y una pizca de sal, mezclando bien.', 'Agrega las nueces picadas si las vas a usar.', 'Vierte la mezcla en el molde preparado.', 'Hornea 30-35 minutos, o hasta que al insertar un palillo en el centro salga limpio.', 'Deja enfriar antes de desmoldar y cortar.'],
 ['sin gluten', 'vegetariano'], 220, 4, 45, 25, 12, 10, 3, 'https://images.pexels.com/photos/35159866/pexels-photo-35159866.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Almendra sin Gluten', 'snack',
 ['harina de almendra|2 tazas', 'semillas de chía|1/4 taza', 'semillas de girasol|1/4 taza', 'huevos|4 unidades', 'aceite de coco|2 cdas', 'bicarbonato de sodio|1 cdta', 'vinagre de manzana|1 cda'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'En un bowl, mezcla la harina de almendra con las semillas de chía y girasol, el bicarbonato de sodio y una pizca de sal.', 'Bate los huevos con el aceite de coco derretido y el vinagre de manzana.', 'Combina los ingredientes secos con los húmedos hasta obtener una masa uniforme.', 'Vierte en el molde y espolvorea semillas adicionales por encima si quieres.', 'Hornea 35-40 minutos, hasta que esté firme y dorado.', 'Deja enfriar sobre una rejilla antes de cortar.'],
 ['sin gluten', 'alto en proteína'], 220, 6, 55, 8, 18, 1, 4, 'https://images.pexels.com/photos/9810301/pexels-photo-9810301.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Brócoli sin Gluten', 'snack',
 ['brócoli rallado|2 tazas', 'huevos|2 unidades', 'queso mozarella|1 taza', 'ajo en polvo|1 cdta', 'sal|1 cdta'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'Procesa o ralla el brócoli hasta obtener una textura fina; exprime el exceso de agua con un paño.', 'Mezcla el brócoli con los huevos, el queso rallado, el ajo en polvo, la sal y la pimienta.', 'Vierte la mezcla en el molde engrasado, presionando bien.', 'Hornea 30-35 minutos, hasta que esté dorado y firme.', 'Deja enfriar unos minutos antes de desmoldar y cortar en tajadas.'],
 ['sin gluten', 'vegetariano'], 120, 6, 45, 10, 7, 2, 2, 'https://images.pexels.com/photos/5644942/pexels-photo-5644942.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Papa sin Gluten', 'snack',
 ['papa rallada|2 unidades grandes', 'huevos|2 unidades', 'queso parmesano|1/4 taza', 'polvo de hornear|1 cdta', 'sal|al gusto'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'Ralla las papas y exprime bien el exceso de agua con un paño limpio.', 'Mezcla la papa rallada con los huevos, el queso, el polvo de hornear, sal y pimienta.', 'Vierte la mezcla en el molde preparado.', 'Hornea 25-30 minutos, hasta que esté dorado y cocido por completo (un palillo insertado debe salir limpio).', 'Deja enfriar antes de desmoldar y cortar.'],
 ['sin gluten', 'económico'], 120, 4, 40, 20, 2, 1, 2, 'https://images.pexels.com/photos/20450299/pexels-photo-20450299.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Ahuyama sin Gluten', 'snack',
 ['puré de ahuyama|2 tazas', 'huevos|4 unidades', 'aceite de coco|1/4 taza', 'bicarbonato de sodio|1 cdta', 'polvo de hornear|1 cdta', 'sal|1 cdta'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'Cocina y haz puré la ahuyama si no la tienes lista; escúrrela bien.', 'Bate el puré de ahuyama con los huevos y el aceite de coco derretido.', 'Incorpora el bicarbonato, el polvo de hornear y la sal, mezclando hasta homogeneizar.', 'Vierte la mezcla en el molde.', 'Hornea 45-50 minutos, hasta que un palillo insertado en el centro salga limpio.', 'Deja enfriar antes de desmoldar y cortar.'],
 ['sin gluten', 'vegetariano'], 220, 6, 65, 25, 12, 2, 3, 'https://images.pexels.com/photos/6129281/pexels-photo-6129281.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Coliflor sin Gluten', 'snack',
 ['coliflor|1 unidad', 'huevos|2 unidades', 'queso parmesano|1/2 taza', 'ajo en polvo|1 cdta', 'orégano|1 cdta'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'Ralla o procesa la coliflor hasta obtener una textura fina tipo arroz.', 'Cocina la coliflor al vapor o microondas 5 minutos, luego exprime muy bien el exceso de agua con un paño.', 'Mezcla la coliflor con los huevos, el queso parmesano, el ajo en polvo, el orégano, sal y pimienta.', 'Vierte en el molde preparado, presionando bien.', 'Hornea 40-45 minutos, hasta que esté dorado y firme.', 'Deja enfriar antes de desmoldar y cortar.'],
 ['sin gluten', 'vegetariano'], 120, 6, 60, 6, 8, 2, 2, 'https://images.pexels.com/photos/4963678/pexels-photo-4963678.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Linaza', 'snack',
 ['harina de linaza|1 taza', 'semillas de chía|1/4 taza', 'semillas de girasol|1/4 taza', 'psyllium en polvo|2 cdas', 'polvo de hornear|1 cdta', 'agua|1 taza', 'aceite de oliva|2 cdas'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'En un bowl, mezcla la harina de linaza con las semillas de chía y girasol, el psyllium, el polvo de hornear y la sal.', 'Agrega el agua y el aceite de oliva, mezclando bien hasta formar una masa espesa.', 'Deja reposar la masa 10 minutos para que el psyllium y la chía absorban el líquido.', 'Vierte en el molde preparado, dando forma pareja.', 'Hornea 45 minutos, hasta que esté firme y dorado por fuera.', 'Deja enfriar por completo antes de cortar en tajadas.'],
 ['sin gluten', 'vegetariano'], 220, 5, 60, 25, 12, 2, 8, 'https://images.pexels.com/photos/10058789/pexels-photo-10058789.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Pera y Canela sin Gluten', 'snack',
 ['pera madura|2 unidades', 'huevos|3 unidades', 'aceite de coco|1/4 taza', 'miel|1/4 taza', 'harina de almendra|2 tazas', 'canela|1 cdta', 'bicarbonato de sodio|1 cdta'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'Pela y machaca las peras hasta obtener un puré grueso.', 'Bate los huevos con el aceite de coco derretido, la miel y el puré de pera.', 'Incorpora la harina de almendra, la canela, el bicarbonato de sodio y una pizca de sal.', 'Mezcla hasta obtener una masa uniforme.', 'Vierte en el molde preparado.', 'Hornea 35-40 minutos, hasta que esté dorado y firme.', 'Deja enfriar antes de desmoldar y cortar.'],
 ['sin gluten', 'vegetariano'], 220, 4, 55, 25, 12, 15, 3, 'https://images.pexels.com/photos/5645190/pexels-photo-5645190.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pan de Espinacas y Parmesano sin Gluten', 'snack',
 ['espinacas frescas|2 tazas', 'huevos|3 unidades', 'aceite de oliva|1/4 taza', 'queso parmesano|1/2 taza', 'harina de almendra|1 taza', 'polvo de hornear|1 cdta'],
 ['Precalienta el horno a 180°C y engrasa un molde para pan.', 'Pica finamente las espinacas frescas.', 'Bate los huevos con el aceite de oliva.', 'Incorpora el queso parmesano, la harina de almendra, el polvo de hornear, sal y pimienta.', 'Agrega las espinacas picadas y mezcla bien.', 'Vierte la mezcla en el molde preparado.', 'Hornea 35-40 minutos, hasta que esté dorado y firme al tacto.', 'Deja enfriar antes de desmoldar y cortar.'],
 ['sin gluten', 'vegetariano'], 220, 12, 55, 8, 18, 2, 4, 'https://images.pexels.com/photos/1351238/pexels-photo-1351238.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Guacamole Clásico', 'snack',
 ['aguacate|2 unidades', 'tomate|1 unidad', 'cebolla morada|1/4 unidad', 'ajo|1 diente', 'cilantro|1/4 taza', 'limón|1 unidad'],
 ['Parte los aguacates a la mitad, retira la semilla y saca la pulpa con una cuchara.', 'Machácalos con un tenedor en un bowl, dejando algunos trozos para textura.', 'Agrega el tomate picado en cubos pequeños, la cebolla morada y el ajo finamente picados.', 'Incorpora el cilantro picado y el jugo de limón.', 'Mezcla bien y sazona con sal y pimienta al gusto.', 'Prueba y ajusta la sal o el limón según prefieras. Sirve fresco de inmediato.'],
 ['vegetariano', 'rápido', 'sin gluten'], 170, 3, 10, 8, 16, 2, 7, 'https://images.pexels.com/photos/5737565/pexels-photo-5737565.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Hummus de Garbanzos', 'snack',
 ['garbanzos cocidos|1 taza', 'tahini|2 cdas', 'ajo|1 diente', 'aceite de oliva|3 cdas', 'limón|2 cdas de jugo', 'comino|1/2 cdta'],
 ['Escurre y enjuaga bien los garbanzos.', 'Colócalos en un procesador de alimentos junto con el tahini, el ajo, el aceite de oliva, el jugo de limón y el comino.', 'Procesa hasta obtener una pasta suave y cremosa, raspando los bordes si es necesario.', 'Si queda muy espeso, agrega una cucharada de agua fría y procesa de nuevo.', 'Sazona con sal al gusto.', 'Sirve en un bowl con un chorrito extra de aceite de oliva por encima.'],
 ['vegetariano', 'alto en proteína', 'sin gluten'], 170, 5, 10, 20, 10, 2, 4, 'https://images.pexels.com/photos/6252675/pexels-photo-6252675.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Aderezo de Atún, Aguacate y Maíz', 'snack',
 ['atún en lata|1 lata', 'aguacate|1 unidad', 'maíz dulce|1/2 taza', 'cebolla morada|1/4 taza', 'cilantro|2 cdas', 'limón|1 unidad'],
 ['Escurre bien el atún y colócalo en un bowl.', 'Agrega el aguacate en cubos y el maíz dulce.', 'Incorpora la cebolla morada y el cilantro finamente picados.', 'Añade el jugo de limón, un chorrito de aceite de oliva, sal y pimienta.', 'Mezcla suavemente para no deshacer demasiado el aguacate.', 'Sirve frío como aderezo para ensaladas o acompañante de tostadas.'],
 ['alto en proteína', 'rápido', 'sin gluten'], 220, 25, 10, 10, 12, 2, 4, 'https://images.pexels.com/photos/15611222/pexels-photo-15611222.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pesto de Albahaca', 'snack',
 ['albahaca fresca|2 tazas', 'queso parmesano|1/2 taza', 'aceite de oliva|1/2 taza', 'piñones|1/3 taza', 'ajo|2 dientes'],
 ['Lava y seca bien las hojas de albahaca.', 'Coloca la albahaca, el queso parmesano, los piñones y el ajo en un procesador de alimentos.', 'Procesa unos segundos hasta picar todo.', 'Con el procesador encendido, agrega el aceite de oliva poco a poco hasta obtener una salsa suave.', 'Sazona con sal y pimienta al gusto.', 'Guarda en un frasco hermético; se conserva bien en la nevera unos días.'],
 ['vegetariano', 'rápido'], 170, 3, 10, 6, 17, 1, 1, 'https://images.pexels.com/photos/5604812/pexels-photo-5604812.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pico de Gallo', 'snack',
 ['tomate|3 unidades', 'cebolla morada|1/2 unidad', 'cilantro|1/4 taza', 'jalapeño|1 unidad', 'limón|1 unidad'],
 ['Pica el tomate en cubos pequeños, retirando el exceso de semillas si prefieres que quede menos aguado.', 'Pica finamente la cebolla morada y el cilantro.', 'Pica el jalapeño sin semillas si quieres menos picante.', 'Mezcla todo en un bowl con el jugo de limón.', 'Sazona con sal al gusto y mezcla bien.', 'Deja reposar 10 minutos antes de servir para que los sabores se integren.'],
 ['vegetariano', 'rápido', 'sin gluten'], 25, 1, 10, 6, 0, 4, 1, 'https://images.pexels.com/photos/8523508/pexels-photo-8523508.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Aderezo de Ricotta con Miel y Nueces', 'snack',
 ['queso ricotta|1 taza', 'miel|2 cdas', 'nueces picadas|1/4 taza'],
 ['Coloca el queso ricotta en un bowl y bátelo un poco con un tenedor para suavizarlo.', 'Agrega la miel y mezcla bien hasta integrar.', 'Incorpora las nueces picadas.', 'Añade una pizca de sal para realzar el dulce.', 'Sirve como aderezo para frutas, tostadas o ensaladas con un toque dulce.'],
 ['vegetariano', 'rápido'], 170, 6, 10, 12, 12, 8, 1, 'https://images.pexels.com/photos/4087618/pexels-photo-4087618.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Aderezo de Hummus de Lentejas', 'snack',
 ['lentejas cocidas|1 taza', 'tahini|2 cdas', 'ajo|2 dientes', 'limón|3 cdas de jugo', 'aceite de oliva|2 cdas', 'comino|1/2 cdta'],
 ['Coloca las lentejas cocidas en un procesador de alimentos junto con el tahini, el ajo y el comino.', 'Agrega el jugo de limón y el aceite de oliva.', 'Procesa hasta obtener una pasta suave, agregando un poco de agua si queda muy espesa.', 'Sazona con sal al gusto.', 'Sirve espolvoreado con un poco de pimentón dulce si quieres.'],
 ['vegetariano', 'alto en proteína', 'sin gluten'], 170, 5, 15, 20, 10, 2, 4, 'https://images.pexels.com/photos/6089614/pexels-photo-6089614.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Aderezo de Hummus de Aguacate', 'snack',
 ['aguacate|1 unidad', 'garbanzos cocidos|1 taza', 'ajo|1 diente', 'tahini|2 cdas', 'limón|2 cdas de jugo', 'aceite de oliva|2 cdas'],
 ['Coloca el aguacate, los garbanzos, el ajo y el tahini en un procesador de alimentos.', 'Agrega el jugo de limón y el aceite de oliva.', 'Procesa hasta obtener una mezcla suave y cremosa, agregando un poco de agua si es necesario para ajustar la consistencia.', 'Sazona con sal, pimienta y comino al gusto.', 'Prueba y ajusta el limón o la sal según prefieras. Sirve fresco.'],
 ['vegetariano', 'sin gluten'], 170, 5, 15, 10, 14, 2, 7, 'https://images.pexels.com/photos/6400000/pexels-photo-6400000.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Pesto de Espinacas y Nueces', 'snack',
 ['espinacas frescas|2 tazas', 'nueces|1/2 taza', 'queso parmesano|1/2 taza', 'ajo|2 dientes', 'aceite de oliva|1/2 taza', 'limón|1 unidad'],
 ['Lava bien las espinacas.', 'Coloca las espinacas, las nueces, el queso parmesano y el ajo en un procesador de alimentos.', 'Procesa unos segundos hasta picar todo.', 'Con el procesador encendido, agrega el aceite de oliva poco a poco hasta obtener una textura suave.', 'Termina con un chorrito de jugo de limón, sal y pimienta.', 'Guarda en un frasco hermético en la nevera.'],
 ['vegetariano', 'rápido'], 170, 4, 15, 6, 16, 1, 2, 'https://images.pexels.com/photos/6659543/pexels-photo-6659543.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

['Salsa de Calabacín y Yogur', 'snack',
 ['calabacín|2 unidades', 'cebolla|1 unidad pequeña', 'ajo|2 dientes', 'yogur griego|1 taza', 'orégano|1 cdta'],
 ['Ralla los calabacines y exprime el exceso de agua con un paño.', 'Sofríe la cebolla y el ajo picados en un poco de aceite de oliva hasta ablandar.', 'Agrega el calabacín rallado y cocina 3-4 minutos hasta que se ablande; deja enfriar.', 'Mezcla el calabacín cocido con el yogur griego y el orégano.', 'Sazona con sal y pimienta al gusto.', 'Sirve frío como aderezo o dip para vegetales.'],
 ['vegetariano', 'sin gluten'], 55, 3, 20, 6, 2, 4, 1, 'https://images.pexels.com/photos/35290605/pexels-photo-35290605.jpeg?auto=compress&cs=tinysrgb&h=650&w=940'],

];
