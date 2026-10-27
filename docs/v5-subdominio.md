# Vista previa v5

La tienda que está en [fujeiraretrostore.com](https://fujeiraretrostore.com) no se reemplaza. El inicio, `/catalogo`, `/producto/{codigo}`, el carrito y el checkout siguen leyendo `productos-retroseries.json` y `productos-cuadros.json`.

La vista previa del catálogo nuevo está al lado:

- `https://fujeiraretrostore.com/v5`
- `https://fujeiraretrostore.com/v5/catalogo`
- `https://fujeiraretrostore.com/v5/producto/{codigo}`

Las fotos salen de la carpeta pública [CATALOGO CAMISETAS](https://drive.google.com/drive/folders/1Y8-OmFwyOhwF7SIT_iGJOJoAJRHfDJ5s). En el Drive hay 256 en Jugador, 292 en Retro y 202 en Fan. Acá hay una muestra de 18 por categoría, en `database/data/productos-v5.json` y `public/assets/v5/`. No es la carpeta completa. No hay precio, talle ni stock: el talle figura como «a definir» y la ficha dice «Vista previa, sin compra». Esas piezas no entran al carrito de la tienda actual.

## Subdominio en Hostinger

Cuando quieras ver la misma muestra en la raíz de un subdominio, sin otro código:

1. Entrá a hPanel.
2. Abrí Dominios y después Subdominios.
3. Creá el subdominio `v5` para `fujeiraretrostore.com`.
4. En la carpeta, no dejes el directorio nuevo que Hostinger propone (`public_html/v5`). Elegí la misma `public_html` donde ya está la tienda.
5. Hostinger crea el DNS del subdominio. No hace falta otro registro, ni SSH, ni tocar el token de Mercado Pago.
6. Cuando propague, abrí `https://v5.fujeiraretrostore.com/`.

Ese host ya está en `config/fujeira.php` (`FUJEIRA_V5_HOSTS`, por defecto `v5.fujeiraretrostore.com`). Ahí `/`, `/catalogo` y `/producto/{codigo}` muestran la v5. El checkout, el carrito y el webhook de ese host responden que no se cobra. `fujeiraretrostore.com` sigue igual.

No hace falta activar nada en el `.env` para el paso de arriba.
