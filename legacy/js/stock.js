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

export function buildAvisameWaUrl(prod, talle) {
  const base = "https://wa.me/5491130124589?text=";
  const partes = [
    `Hola FUJEIRA! Me interesa la remera ${prod?.nombre || ""}`
  ];
  if (talle) partes.push(`talle ${talle}`);
  partes.push("y vi que está sin stock. ¿Me avisan cuando vuelva? 🙌");
  return base + encodeURIComponent(partes.filter(Boolean).join(" "));
}
