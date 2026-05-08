export const CATALOG_JSON_QUERY = "v=20260508h5";

/** URLs absolutas al origen del sitio, resueltas desde este módulo (evita fallos si el HTML está en otra ruta). */
export function catalogDataSources() {
  const q = CATALOG_JSON_QUERY;
  return [
    new URL(`../data/productos-retroseries.json?${q}`, import.meta.url).href,
    new URL(`../data/productos-cuadros.json?${q}`, import.meta.url).href
  ];
}

export function retroSeriesDataUrl() {
  return new URL(
    `../data/productos-retroseries.json?${CATALOG_JSON_QUERY}`,
    import.meta.url
  ).href;
}
