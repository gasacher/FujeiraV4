# FUJEIRA

Tienda de remeras de diseño propio y cuadros de arte futbolero. La Retro Series toma un apodo, una fecha o un color de tribuna y lo convierte en una prenda corta. Los cuadros ocupan la pared del hincha, con marco a elección.

**Sitio:** [fujeiraretrostore.com](https://fujeiraretrostore.com)

Este repositorio es el portfolio del desarrollo. La marca, los diseños, las fotos y los textos son de FUJEIRA.

## La tienda

El recorrido es el de un local chico. Se entra por la pieza destacada, se recorre el catálogo, se elige talle o marco y se paga. El pedido queda a nombre de quien compra y la marca confirma el envío.

La edición publicada tiene cinco remeras de Fujeira Retro Series y diecinueve cuadros.

## Qué muestra el trabajo

- Inicio con la pieza de portada y una selección del catálogo.
- Catálogo y ficha, con fotos, talle y stock en las remeras, y marco en los cuadros.
- Carrito y checkout con los datos de envío.
- Cobro con Mercado Pago y página de regreso según el resultado.
- Nosotros, preguntas frecuentes y contacto.

## Decisiones

La página la arma el servidor. El precio y el stock salen del catálogo de la marca y se vuelven a comprobar al armar el carrito y al cobrar. El navegador no fija el monto.

El catálogo es una edición curada. Cada pieza está descripta con su nombre, sus fotos y su variante, en lugar de pasar por un administrador genérico.

El pedido se registra antes de salir a pagar. El pago se acepta cuando la moneda, el monto y el pedido coinciden. Las credenciales de cobro quedan fuera del repositorio.

La interfaz usa la tipografía y la paleta de la marca, en una grilla que se acomoda al teléfono y al escritorio.

## Panel de Google

El código del panel (Apps Script / fujeira-panel) no está en este repositorio. La tienda Laravel no lo llama. El sitio viejo, en `legacy/js/orders.js`, pegaba pedidos a un Web App de Google sin secreto. Cómo cerrarlo está en `docs/panel-google.md`. El secreto no se commitea.

## Stack

Laravel 11 · PHP · Blade · SQLite · Mercado Pago Checkout Pro · Bootstrap 5

## Vista previa v5

El catálogo publicado no cambia. La muestra del catálogo nuevo está en `/v5`, con fotos y datos aparte (`database/data/productos-v5.json`). No tiene precio ni checkout. El subdominio se arma después, con los clics de `docs/v5-subdominio.md`.
