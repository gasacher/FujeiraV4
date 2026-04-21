export const CATALOG_JSON_QUERY = "v=20260413h52";

export function catalogDataSources(opts = {}) {
  const { leadingDotSlash = false } = opts;
  const q = CATALOG_JSON_QUERY;
  const paths = [
    `data/productos-retroseries.json?${q}`,
    `data/productos-cuadros.json?${q}`
  ];
  return leadingDotSlash ? paths.map(p => `./${p}`) : paths;
}

export function retroSeriesDataUrl() {
  return `data/productos-retroseries.json?${CATALOG_JSON_QUERY}`;
}
