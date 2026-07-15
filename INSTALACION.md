# MenúVital — Guía de instalación (paso a paso)

Esta guía es para ti, Vanessa. Son las únicas cosas que tienes que hacer **una sola vez**.
Después de esto, cada vez que yo actualice la app, Hostinger la publica sola (no tienes que hacer nada más).

---

## 1. Crear el repositorio en GitHub (5 minutos)

1. Entra a [github.com](https://github.com) con tu cuenta.
2. Arriba a la derecha, clic en **+** → **New repository**.
3. Nómbralo, por ejemplo, `menuvital`.
4. Déjalo en **Private** (privado).
5. **No marques** ninguna casilla de "Add README" ni ".gitignore" — el repositorio debe crearse vacío.
6. Clic en **Create repository**.
7. Copia la URL que te muestra GitHub (algo como `https://github.com/tu-usuario/menuvital.git`) y pásamela — yo hago el primer `push` con todo el código de la app.

---

## 2. Crear la base de datos en Hostinger (5 minutos)

1. Entra a tu **hPanel** de Hostinger.
2. Ve a **Bases de datos → Bases de datos MySQL**.
3. Crea una nueva base de datos (anota el **nombre de la base**, el **usuario** y la **contraseña** que te asigne Hostinger — los vas a necesitar en el paso 4).

---

## 3. Conectar tu repositorio de GitHub con Hostinger (5 minutos)

1. En hPanel, ve a **Avanzado → Git**.
2. Clic en **Crear repositorio** (o "Create a new repository").
3. Pega la URL de tu repositorio de GitHub (la del paso 1).
4. En **Directorio**, escribe `public_html` (o el dominio/subdominio donde quieres que viva la app).
5. Guarda. Hostinger dejará el sitio conectado a tu repo de GitHub.
6. Busca la opción de **auto-deploy** o **webhook automático** y actívala, para que cada vez que yo actualice el código en GitHub, tu sitio se actualice solo.

---

## 4. Configurar `config.php` en el servidor (10 minutos)

Este archivo **no se sube a GitHub** (por seguridad, ya que tiene contraseñas). Debes crearlo directamente en Hostinger:

1. En hPanel, abre el **Administrador de archivos** y entra a `public_html/includes/`.
2. Verás un archivo llamado `config.sample.php`. Haz una copia de ese archivo y renómbrala a `config.php` (en la misma carpeta `includes/`).
3. Edita `config.php` y completa estos datos:

   - `DB_HOST` → normalmente `localhost`
   - `DB_NAME`, `DB_USER`, `DB_PASS` → los datos de la base de datos que creaste en el paso 2
   - `APP_URL` → la URL real de tu app (ej: `https://tudominio.com`)
   - `GROQ_API_KEY` → tu clave de Groq (ver paso 5 abajo)
   - `ADMIN_EMAIL` y `ADMIN_PASSWORD_INITIAL` → tu correo y una contraseña segura para tu cuenta de administradora
   - `INSTALL_KEY` → invéntate una clave larga y secreta (la vas a necesitar solo una vez, en el paso 6)
   - `WHATSAPP_NUMBER` → tu número de WhatsApp (con indicativo, sin el +, ej: `573001234567`) donde te escriben tus clientas para comprar
   - `APP_PRICE` → el precio que quieres mostrar (ya viene en `$19.900`)
   - `FB_PIXEL_ID` → el ID de tu Pixel de Facebook, si ya tienes uno creado para medir tus campañas (si no, déjalo vacío y lo agregas después)

4. Guarda el archivo.

---

## 5. Crear tu cuenta gratis de Groq (la inteligencia artificial) (5 minutos)

Groq es el servicio de IA que hace que el coach de nutrición y la personalización de menús funcionen. Tiene un nivel gratis generoso, suficiente para empezar.

1. Entra a [console.groq.com](https://console.groq.com) y crea una cuenta gratis.
2. Ve a **API Keys** y crea una nueva clave.
3. Copia esa clave y pégala en `GROQ_API_KEY` dentro de tu `config.php` (paso 4).

> Si por algún motivo no configuras esto todavía, **la app funciona igual**: el menú se genera con el algoritmo de recetas (sin el toque extra de personalización de la IA ni el chat del coach).

---

## 6. Instalar la base de datos (1 minuto, una sola vez)

1. Abre en tu navegador: `https://tudominio.com/install.php?key=LA_CLAVE_QUE_PUSISTE_EN_INSTALL_KEY`
2. Deberías ver una página que dice "✅ Instalación completada", con la lista de tablas creadas, las recetas cargadas y tu cuenta de administradora creada.
3. Si vuelves a entrar a esa URL después, no pasa nada malo: no borra ni duplica datos.

---

## 7. ¡Listo! Entra y genera tus primeros códigos

1. Ve a `https://tudominio.com/login.php` y entra con tu correo y contraseña de administradora.
2. Te llevará automáticamente al panel en `https://tudominio.com/admin/`.
3. Ahí generas los **códigos de activación** que le compartes por WhatsApp a cada clienta cuando te paga los `$19.900`.
4. Cuando lo necesites, revisa **Ajustes de seguridad**: activa el **SSL/HTTPS gratis** de Hostinger si aún no está activo (Hostinger → SSL), así el candado del navegador se ve correctamente.

---

## Cómo funciona de ahí en adelante

- Yo sigo mejorando la app y subiendo cambios a GitHub.
- Hostinger detecta esos cambios automáticamente (por el webhook del paso 3) y actualiza tu sitio solo — **tú no tienes que hacer nada**.
- Tu `config.php` con tus contraseñas nunca se toca ni se sobreescribe, porque vive solo en el servidor, no en GitHub.

## Si algo falla

- **Pantalla en blanco o error 500:** revisa que `config.php` exista en `includes/` y que los datos de la base de datos sean correctos.
- **"No pudimos crear tu cuenta" al registrar:** puede que la base de datos aún no esté instalada — repite el paso 6.
- **El coach o la IA no responden:** revisa que `GROQ_API_KEY` esté bien copiada en `config.php` (sin espacios de más).
