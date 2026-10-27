# Panel de Google (Apps Script)

El fuente del panel no está en este repositorio. No se puede cerrar desde GitHub.

La tienda Laravel no llama a ese script. El sitio viejo, en `legacy/js/orders.js`, enviaba pedidos a un Web App público de Apps Script (`action=addPedido`) sin una clave. Si ese Web App sigue publicado, cualquiera que tenga la URL puede escribir en la hoja o, si el script expone un `getAll` / `doGet`, leer pedidos.

## Qué hacer en Apps Script

1. En el proyecto del panel: Configuración del proyecto → Propiedades del script → agregar `FUJEIRA_PANEL_SECRET`.
2. El valor lo elegís vos y lo guardás solo ahí (y, si algún cliente todavía pega pedidos, en el servidor, nunca en GitHub).
3. Reemplazá el inicio de `doGet` y `doPost` por el chequeo de abajo. Si el secreto falta o no coincide, el script corta.

```javascript
function fujeiraSecretoOk(e) {
  var esperado = PropertiesService.getScriptProperties().getProperty("FUJEIRA_PANEL_SECRET");
  var recibido = e && e.parameter ? String(e.parameter.secret || "") : "";
  return Boolean(esperado) && recibido === esperado;
}

function doGet(e) {
  if (!fujeiraSecretoOk(e)) {
    return ContentService.createTextOutput("Forbidden");
  }
  // acá sigue el getAll que ya tengas
}

function doPost(e) {
  if (!fujeiraSecretoOk(e)) {
    return ContentService
      .createTextOutput(JSON.stringify({ ok: false }))
      .setMimeType(ContentService.MimeType.JSON);
  }
  // acá sigue el alta de pedidos que ya tengas
}
```

Un Web App de Apps Script no ve headers propios. El secreto viaja como parámetro `secret` en la URL o en el cuerpo. No lo pegues en el código ni en GitHub. Después de guardarlo, volvé a implementar el Web App (Implementar → Administrar implementaciones → Nueva versión). La tienda Laravel no manda ese parámetro: si el panel viejo sigue publicado, queda cerrado hasta que vos decidas cómo hablarle.
