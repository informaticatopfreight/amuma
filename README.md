# AMUMA · tema de WordPress

Tema a medida para **AMUMA**, la Asociación de Mujeres Afectadas de Cáncer de Mama y Ginecológico de Castilla-La Mancha, con sede en Ciudad Real.

El lazo rosa es el protagonista: se dibuja solo al entrar, flota, gira al hacer scroll y aparece en el menú, en las tarjetas y en el pie. Los textos aparecen letra a letra y la cinta rosa de palabras se acelera al bajar por la página. Las noticias de la portada se recorren en horizontal y las fotos se abren a pantalla completa.

![Portada](amuma/screenshot.png)

---

## 1. Instalación

1. Descarga el tema en `.zip`:
   - desde GitHub → pestaña **Actions** → última ejecución de "Empaquetar tema" → artefacto **amuma-tema**, o
   - en local: `./tools/build-zip.sh` (genera `amuma.zip`).
2. En WordPress: **Apariencia → Temas → Añadir nuevo → Subir tema**, elige `amuma.zip` y **Activar**.

Al activarlo, el tema:

- crea las categorías **Noticias** y **Actividades** si no existen;
- si la portada mostraba "Tus últimas entradas", crea una página **Inicio** como portada y otra **Actualidad** para el listado de noticias (*Ajustes → Lectura*). Si ya tenías una portada fija, no toca nada. En ese caso, ve a *Ajustes → Lectura* y elige como "Página de entradas" la página donde quieras el listado.

> Prueba primero en una copia de la web (staging). Las entradas actuales se aprovechan tal cual: el tema coge solo la primera foto de cada noticia y cuenta las fotos.

## 2. Primeros ajustes (10 minutos)

| Qué | Dónde |
|---|---|
| **Logo** | Apariencia → Personalizar → Identidad del sitio → Logo. Mientras no haya logo, se ve el lazo con "AMUMA". |
| **Textos de la portada** (título, botones, cifras, servicios, "Súmate al lazo") | Apariencia → Personalizar → **AMUMA** |
| **Dirección, teléfonos, email, horario y redes sociales** | Apariencia → Personalizar → AMUMA → Contacto y redes |
| **Menú** | Apariencia → Menús → crea un menú y asígnalo a "Menú principal" (y, si quieres, a "Menú del pie"). Puedes enlazar a secciones de la portada: `/#quienes-somos`, `/#que-hacemos`, `/#colabora`, `/#contacto`. |
| **Política de privacidad** | Ajustes → Privacidad (sale en el pie automáticamente). |

⚠️ **Revisar con la asociación**: los datos de contacto vienen del directorio del SESCAM (C/ Alarcos 12, 926 21 65 79, 616 10 36 08, info@amuma.org). Los cuatro servicios de "Qué hacemos" y las tres cifras son textos de ejemplo. Todo se cambia desde el Personalizador.

## 3. Publicar una noticia o actividad (lo que harán cada mes)

1. **Entradas → Añadir nueva**.
2. Escribe el **título** y el texto.
3. Para las fotos, añade un bloque **Galería** (botón `+` → Galería) y selecciona todas las fotos de una vez. El tema las monta en mosaico y se abren a pantalla completa al pulsarlas, con flechas y deslizando el dedo en el móvil.
4. A la derecha:
   - **Categoría**: *Noticias* o *Actividades*.
   - **Imagen destacada**: la foto de portada. Es opcional: si no la ponen, el tema usa la primera foto de la noticia.
   - **Extracto** (opcional): frase corta que sale bajo el título.
5. **Publicar**.

No hay que tocar nada más: la noticia aparece en el carrusel de la portada, en *Actualidad* (con filtros por categoría y botón "Ver más") y en "También te puede interesar".

**Consejo**: subid las fotos a un tamaño razonable (unos 2000 px de lado como máximo). WordPress crea solo los tamaños más pequeños.

## 4. Qué incluye

- **Portada**: intro con el lazo dibujándose (solo la primera vez por visita), titular animado, lazo 3D que sigue al ratón, cinta de palabras que reacciona al scroll, "Quiénes somos" con el texto que se ilumina al leer, cifras con contador, servicios con efecto de inclinación, **Actualidad en carrusel horizontal**, "Súmate al lazo" y contacto.
- **Actualidad** (listado): noticia destacada grande, rejilla tipo revista, filtros *Todo / Actividades / Noticias* y "Ver más" sin recargar la página.
- **Noticia**: cabecera grande, foto que se abre al entrar, galería en mosaico con visor a pantalla completa, botones para compartir (WhatsApp, Facebook, X, copiar enlace), anterior/siguiente y relacionadas.
- **Páginas** (Quiénes somos, Contacto, Aviso legal…) con el mismo estilo.
- **Accesibilidad**: si el sistema pide reducir el movimiento, la web se ve sin animaciones. Si el JavaScript falla, el contenido se muestra igualmente.
- **Rendimiento y RGPD**: fuentes (Fraunces y DM Sans) y librerías (GSAP, Lenis) incluidas en el propio tema, sin Google Fonts ni CDNs externos.
- Compatible con las galerías antiguas (`[gallery]`) de las noticias ya publicadas.

## 5. Estructura

```
amuma/                     ← el tema (esta carpeta es la que va en el .zip)
├── style.css              ← cabecera del tema
├── functions.php          ← configuración, scripts, categorías al activar
├── inc/customizer.php     ← textos editables (Apariencia → Personalizar → AMUMA)
├── inc/template-tags.php  ← funciones de apoyo (imagen automática, iconos, menú…)
├── front-page.php         ← portada
├── home.php / archive.php / search.php → template-parts/listing.php   ← Actualidad
├── single.php             ← noticia
├── page.php, 404.php
├── template-parts/        ← tarjeta de noticia, lazo SVG, listado
└── assets/
    ├── css/main.css       ← todos los estilos (colores en :root)
    ├── js/main.js         ← animaciones e interacción
    ├── fonts/  vendor/    ← fuentes y librerías incluidas
tools/build-zip.sh         ← genera amuma.zip
```

Colores principales (en `assets/css/main.css`, bloque `:root`): rosa `#e5267b`, ciruela `#2b0f22`, crema `#fff8f5`.

## Licencias

Tema GPL-2.0-or-later. GSAP (licencia estándar gratuita de GSAP), Lenis (MIT), Fraunces y DM Sans (SIL Open Font License).
