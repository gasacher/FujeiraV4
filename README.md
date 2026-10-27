# FUJEIRA

Tienda de remeras de diseño propio y cuadros de arte futbolero. El sitio publicado es [fujeiraretrostore.com](https://fujeiraretrostore.com). El código corre en Hostinger y el repositorio está en GitHub.

## Cómo está hecha

Es una aplicación **Laravel 11** (PHP 8.2 o superior). Las páginas las arma el servidor con Blade. El diseño usa Bootstrap 5.3.2, Bootstrap Icons y los estilos de `public/css/main.css`.

El catálogo no está en una tabla. Vive en dos archivos JSON. El carrito vive en la sesión del visitante. Los pedidos se guardan en SQLite. El cobro lo hace Mercado Pago Checkout Pro.

El sitio estático anterior (HTML, JavaScript y el PHP viejo de Mercado Pago) quedó en `legacy/`. La tienda actual no lo usa.

### Páginas

| Ruta | Qué muestra |
| --- | --- |
| `/` | Inicio, con destacados, remeras y cuadros |
| `/catalogo` | Catálogo. `?categoria=` filtra |
| `/producto/{codigo}` | Ficha de un producto |
| `/carrito` | Carrito |
| `/checkout` | Datos de envío y paso a Mercado Pago |
| `/pago/exitoso`, `/pago/pendiente`, `/pago/fallido` | Vuelta desde Mercado Pago |
| `/nosotros`, `/faqs`, `/contacto` | Páginas de la marca |
| `POST /webhooks/mercadopago` | Aviso de Mercado Pago cuando cambia un pago |

### Catálogo

`app/Catalog/Catalog.php` lee estos archivos:

- `database/data/productos-retroseries.json` — 5 remeras de Fujeira Retro Series.
- `database/data/productos-cuadros.json` — 19 cuadros.

Cada producto tiene código, nombre, categoría, precio en pesos y fotos. Las remeras tienen talles (S a XXL) y stock por talle. Los cuadros tienen marcos (madera natural, negro, blanco) y no usan stock. Las fotos están en `public/assets/`.

El precio que se cobra sale de esos JSON. El navegador no puede cambiarlo. Un talle sin stock no entra al carrito.

Para cambiar un precio, un nombre o el stock, se edita el JSON correspondiente.

### Carrito

`app/Cart/CartSession.php` guarda las líneas en la sesión, con la clave `fujeira.cart`. Cada línea tiene código, cantidad, talle o marco. Al mostrar el carrito, el servidor vuelve a leer el producto del catálogo y recorta la cantidad si el stock no alcanza.

### Pedido y Mercado Pago

El checkout pide nombre, apellido, email, teléfono y dirección. Con eso se crea un pedido en la tabla `orders`, con número `FJ-` y el id rellenado a 6 cifras (por ejemplo `FJ-000001`). El estado inicial es `pendiente_pago`.

`app/Services/MercadoPagoClient.php` crea una preferencia en `https://api.mercadopago.com/checkout/preferences`. Los ítems van en pesos (ARS), con el precio del catálogo. Mercado Pago devuelve al sitio en `/pago/exitoso`, `/pago/pendiente` o `/pago/fallido`.

El Access Token no está en el código. Se lee de la variable `MP_ACCESS_TOKEN` del archivo `.env` del servidor. Un token que empieza con `TEST-` usa el checkout de prueba. Un token de producción usa el checkout real. Si el token falta, el pedido queda en `error_mp` y el carrito no se vacía.

Cuando `APP_URL` empieza con `https://`, Mercado Pago también avisa a `POST /webhooks/mercadopago`. Esa ruta no lleva el token de formulario de Laravel. El pedido pasa a `aprobado` solo si el pago está aprobado, la moneda es ARS, el monto coincide con el total del pedido y la referencia es el id de ese pedido.

### Dónde está cada parte

```
FujeiraV4/
├── app/Catalog/          Catálogo leído de los JSON
├── app/Cart/             Carrito de la sesión
├── app/Http/Controllers/ Páginas, carrito, checkout y webhook
├── app/Models/Order.php  Pedido y confirmación del pago
├── app/Services/         Cliente de la API de Mercado Pago
├── database/data/        JSON del catálogo
├── database/migrations/  Tablas, incluida orders
├── public/               CSS, imágenes y el front controller de Laravel
├── resources/views/      HTML de la tienda
├── routes/web.php        Rutas
├── index.php             Entrada cuando todo el proyecto está en public_html
├── .htaccess             Impide entrar por HTTP a .env, app, vendor, etc.
├── .env.example          Nombres de las variables, sin secretos
└── legacy/               Sitio estático anterior
```

En Hostinger el dominio no puede apuntar solo a `public/`. Por eso la raíz del proyecto también tiene `index.php` y `.htaccess`: el proyecto entero se sube a `public_html` y esa entrada manda el tráfico a Laravel. Las direcciones que buscan archivos ocultos, como `/.env`, responden 404.

## Secretos

`.env` no se sube a GitHub. El repositorio solo tiene `.env.example`, con `APP_KEY` y `MP_ACCESS_TOKEN` vacíos. El token nuevo se escribe en el `.env` del servidor, después de revocar en Mercado Pago el token que estuvo publicado en el sitio viejo. Ese token viejo sigue en el historial de Git.

## Cómo correrla en una computadora

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

En `.env`, `MP_ACCESS_TOKEN` es el Access Token de Mercado Pago. Sin esa línea el checkout no abre el pago.

Los tests están en `tests/Feature/StoreCheckoutTest.php` y se corren con `php artisan test`.

## Hostinger

El ZIP de la versión publicada en GitHub es:

https://github.com/gasacher/FujeiraV4/archive/refs/heads/main.zip

Se sube a `public_html`, se extrae y se mueve el contenido de la carpeta que crea el ZIP hasta `public_html`. Hay que borrar el `index.html` viejo. El `.env` se crea en el servidor copiando `.env.example`. Los comandos de Composer, la clave, la base SQLite y los permisos están en `SUBIR-A-HOSTINGER.txt`.
