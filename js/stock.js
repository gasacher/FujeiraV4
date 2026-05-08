export function getStockForTalle(prod, talle) {
  if (!prod?.stock) return Infinity;
  const value = prod.stock[talle];
  return typeof value === "number" ? value : 0;
}

export function isProductOutOfStock(prod) {
  if (!prod?.stock) return false;
  if (!Array.isArray(prod.talles) || !prod.talles.length) return false;
  return prod.talles.every(t => getStockForTalle(prod, t) <= 0);
}

/** Texto legible para mostrar en ficha de producto (solo talles con stock > 0). */
export function getStockSummaryText(prod) {
  if (!prod?.stock || !prod.talles?.length) return null;
  const parts = prod.talles
    .filter(t => {
      const n = prod.stock[t];
      return typeof n === "number" && n > 0;
    })
    .map(t => `${t}: ${prod.stock[t]} uds.`);
  if (!parts.length) return "Sin unidades disponibles por ahora.";
  return parts.join(" · ");
}

export function buildAvisameWaUrl(prod, talle) {
  const base = "https://wa.me/5491130124589?text=";
  const partes = [
    `Hola FUJEIRA! Me interesa la remera ${prod?.nombre || ""}`
  ];
  if (talle) partes.push(`talle ${talle}`);
  partes.push("y vi que está sin stock. ¿Me avisan cuando vuelva? 🙌");
  return base + encodeURIComponent(partes.filter(Boolean).join(" "));
}
