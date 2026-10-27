export const CATALOG_JSON_QUERY = "v=20260508h6";

/**
 * Origen del sitio con barra final (p. ej. https://…/FujeiraV4/).
 * Basado en location — sin import.meta (evita fallos en algunos WebKit y URLs de módulo con ?v=).
 */
export function catalogSiteBase() {
  const { origin, pathname } = window.location;
  let dir = pathname;
  if (/\.html?$/i.test(pathname)) {
    dir = pathname.replace(/[^/]+$/, "");
  } else if (!pathname.endsWith("/")) {
    dir = `${pathname}/`;
  }
  return new URL(dir, origin).href;
}

export function catalogDataSources() {
  const q = CATALOG_JSON_QUERY;
  const base = catalogSiteBase();
  return [
    new URL(`data/productos-retroseries.json?${q}`, base).href,
    new URL(`data/productos-cuadros.json?${q}`, base).href
  ];
}

export function retroSeriesDataUrl() {
  return new URL(
    `data/productos-retroseries.json?${CATALOG_JSON_QUERY}`,
    catalogSiteBase()
  ).href;
}
