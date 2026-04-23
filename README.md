# FUJEIRA V4 — Sitio web optimizado para SEO

Proyecto de sitio web desarrollado para la materia **Programación con Entornos de Trabajo**, centrado en la aplicación práctica de criterios de SEO on-page, estructura semántica HTML5 y buenas prácticas técnicas para mejorar la visibilidad, el rendimiento y la accesibilidad del sitio.

🔗 **Sitio publicado:** [https://gasacher.github.io/FujeiraV4/](https://gasacher.github.io/FujeiraV4/)

---

## 1. Descripción general

FUJEIRA es una marca ficticia de remeras de diseño propio, cuadros y arte futbolero que se utilizó como caso de estudio para aplicar SEO sobre un sitio real. El desarrollo parte de un proyecto previo y lo refactoriza con foco en optimización on-page: metadatos, jerarquía de contenidos, semántica, naming de archivos, imágenes, performance y archivos técnicos (`robots.txt` y `sitemap.xml`).

El sitio está compuesto por múltiples páginas estáticas (home, catálogo, producto, nosotros, FAQs y contacto) y fue publicado en **GitHub Pages** para permitir la evaluación de performance, velocidad de carga y visibilidad en buscadores.

---

## 2. Objetivos del proyecto

- Implementar SEO on-page siguiendo los criterios fundamentales vistos en clase.
- Garantizar una estructura HTML5 semánticamente correcta y jerárquica.
- Mejorar la accesibilidad del sitio con atributos descriptivos y navegación asistida.
- Aplicar buenas prácticas en naming de archivos, carpetas y URLs.
- Optimizar el rendimiento de carga mediante técnicas de priorización y diferido.
- Configurar los archivos técnicos `robots.txt` y `sitemap.xml` para orientar el rastreo de los buscadores.
- Asegurar un diseño responsive para mobile, tablet y desktop.

---

## 3. Tecnologías utilizadas

- **HTML5** — estructura y semántica.
- **CSS3** — estilos personalizados sobre la base de Bootstrap.
- **Bootstrap 5.3.2** — sistema de grilla y componentes responsivos.
- **Bootstrap Icons 1.11.1** — iconografía vectorial.
- **Google Fonts** (Bebas Neue, Roboto, League Spartan) — tipografías de marca cargadas con `preconnect`.
- **GitHub Pages** — hosting y despliegue.

---

## 4. Implementación SEO

### Metaetiquetas
Cada página del sitio cuenta con sus propios bloques `<meta>` orientados a buscadores y a la lectura correcta del documento:

- `<meta charset="UTF-8">` y `<meta name="viewport" content="width=device-width, initial-scale=1.0">` en todas las páginas.
- `<title>` único y descriptivo por página, con estructura `Sección | Marca` para mejorar el CTR en SERP.
- `<meta name="description">` redactada específicamente por página, orientada a palabras clave del negocio (remeras, retro, diseño, arte futbolero, catálogo, envíos).
- `<html lang="es">` para indicar el idioma principal del contenido.
- `<link rel="icon">` con favicon propio de la marca.

### Jerarquía de headings
Se respeta un árbol de encabezados coherente en cada página:

- Un único `<h1>` por página, vinculado al tema principal.
- `<h2>` para secciones de primer nivel (destacados, cómo funciona, comunidad, FAQs, contacto).
- `<h3>` para subsecciones dentro de cada bloque (pasos del proceso de compra, preguntas individuales, etc.).

### Palabras clave
El contenido editorial fue redactado orientado a un conjunto de keywords del nicho: *remeras de diseño*, *Fujeira Retro Series*, *cuadros*, *arte futbolero*, *camiseta retro*, *envíos Argentina*. Se cuidó la densidad natural evitando sobre-repetición (keyword stuffing), distribuyendo los términos entre títulos, descripciones, encabezados y textos de contenido.

### Estructura semántica
Uso explícito de etiquetas HTML5 en lugar de `<div>` genéricos:

- `<header>` para encabezados de página y hero.
- `<nav>` para la navegación principal y los enlaces del footer.
- `<main>` como contenedor del contenido principal.
- `<section>` para cada bloque temático.
- `<article>` y `<figure>` donde corresponde.
- `<footer>` para el pie del sitio.

### Accesibilidad complementaria
- Enlace `skip-to-content` al inicio de cada página para saltar a la sección principal con el teclado.
- `aria-label` y `aria-hidden` aplicados a botones de navegación y elementos decorativos.
- Atributos `alt` descriptivos en todas las imágenes significativas.

---

## 5. Estructura del sitio

```
FujeiraV4/
├── index.html              → Home (hero, destacados, cómo funciona, comunidad)
├── catalogo.html           → Catálogo con filtros y buscador
├── producto.html           → Ficha de producto individual
├── nosotros.html           → Historia y línea Retro Series
├── faqs.html               → Preguntas frecuentes
├── contacto.html           → Canales de contacto
├── robots.txt              → Directivas para crawlers
├── sitemap.xml             → Mapa del sitio para buscadores
├── assets/
│   ├── img/                → Logos, favicon, imágenes generales
│   └── catalogo/           → Imágenes de productos por línea
├── css/
│   └── main.css            → Estilos compilados
└── js/                     → Scripts modulares por página
```

---

## 6. Buenas prácticas aplicadas

### Naming de archivos y URLs amigables
Las páginas siguen un naming claro, en minúsculas, sin acentos ni caracteres especiales, y descriptivo del contenido: `catalogo.html`, `nosotros.html`, `faqs.html`, `contacto.html`, `producto.html`. Esto facilita la lectura de URLs por parte de usuarios y buscadores.

### Atributos ALT en imágenes
Todas las imágenes de productos y de contenido cuentan con atributo `alt` descriptivo, orientado a la función de la imagen y al producto mostrado (por ejemplo, *"Remera Fujeira Retro Series Diablos Rojos"*). Las imágenes puramente decorativas del hero usan `aria-hidden="true"` para no generar ruido en lectores de pantalla.

### Enlaces externos seguros
Todos los enlaces externos (Instagram, WhatsApp) incluyen `target="_blank"` junto con `rel="noopener noreferrer"` para proteger la sesión del usuario y evitar filtrado de referrer.

### Contenido único por página
Cada página posee título, descripción y H1 diferenciados, evitando contenido duplicado que pueda penalizar el posicionamiento.

---

## 7. Archivos SEO técnicos

### `robots.txt`
Se configuró el archivo `robots.txt` en la raíz del sitio para orientar el comportamiento de los rastreadores:

- Permite el rastreo general del sitio con `Allow: /`.
- Bloquea el acceso a las páginas de retorno del flujo de pago (`/pago-exitoso.html`, `/pago-fallido.html`, `/pago-pendiente.html`), que no aportan valor SEO y pueden ensuciar el índice.
- Declara la ubicación del sitemap mediante `Sitemap: https://gasacher.github.io/FujeiraV4/sitemap.xml` para facilitar el descubrimiento.

### `sitemap.xml`
El mapa del sitio lista las URLs públicas prioritarias — home, catálogo, nosotros, FAQs, contacto y producto — usando el estándar `sitemaps.org` para que buscadores como Google puedan rastrear e indexar todo el contenido relevante en una sola lectura.

---

## 8. Responsive Design

El sitio está diseñado mobile-first y se adapta de forma fluida a tres breakpoints principales:

- **Mobile** (< 768px): navegación colapsada con toggler, grillas en una columna, tipografía y espaciados ajustados.
- **Tablet** (768px – 992px): grillas de 2 columnas, hero reestructurado.
- **Desktop** (> 992px): navegación horizontal completa, grillas de 3 y 4 columnas, hero con composición de mockups.

Esto se logra combinando el sistema de grilla de Bootstrap 5 con media queries propias en `main.css`. Todas las imágenes declaran atributos `width` y `height` para evitar saltos de layout (CLS) al cargarse.

---

## 9. Optimización de performance

Se aplicaron varias técnicas para reducir el tiempo de carga percibido y mejorar métricas de Core Web Vitals:

- **`preconnect`** hacia CDNs y Google Fonts para acelerar el handshake inicial.
- **`preload`** de la imagen principal del hero (`fetchpriority="high"`) para priorizar el LCP.
- **`loading="lazy"`** en imágenes fuera del viewport inicial (footer, Instagram grid, slides secundarios).
- **`decoding="async"`** para que el decode de imágenes no bloquee el renderizado.
- Dimensiones explícitas (`width`/`height`) en imágenes clave para evitar CLS.
- CSS y JS propios servidos con parámetro de versionado (`?v=...`) para control de caché.
- Carga de Bootstrap y Bootstrap Icons desde CDN con HTTPS.
- Scripts no críticos cargados con `type="module"` para diferir ejecución.

---

## 10. Conclusión y aprendizajes

El proyecto permitió llevar a la práctica, sobre un caso real, la mayoría de los conceptos trabajados en cursada: desde la redacción de metadatos orientados a búsqueda, pasando por la reestructuración semántica del HTML, hasta el trabajo fino de performance con atributos nativos del navegador.

Los principales aprendizajes fueron:

- El SEO on-page no es una capa decorativa: condiciona la forma en que se escribe el HTML desde el primer `<!DOCTYPE>`.
- La semántica HTML5 no solo suma en accesibilidad; también facilita el parseo del contenido por parte de los buscadores.
- Pequeñas decisiones técnicas (`preload`, `lazy`, `width`/`height`) tienen impacto directo en métricas como LCP y CLS.
- El naming consistente de archivos y carpetas es una tarea invisible pero crítica para URLs limpias y mantenibilidad a futuro.
- Publicar en GitHub Pages obliga a pensar el sitio como un deploy real, con rutas absolutas y comportamiento productivo, no como un entorno local.

El resultado es un sitio que combina una identidad de marca fuerte con una base técnica ordenada, lista para seguir creciendo y para ser auditada con herramientas como Lighthouse o PageSpeed Insights.
