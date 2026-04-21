function normalizeItems(payload) {
  const items = Array.isArray(payload?.items) ? payload.items : [];
  return items
    .map((it) => ({
      image: typeof it?.image === "string" ? it.image : "",
      alt: typeof it?.alt === "string" ? it.alt : "",
      href: typeof it?.href === "string" ? it.href : "",
    }))
    .filter((it) => it.image);
}

function applyInstaGrid(items) {
  const grid = document.querySelector(".insta-grid");
  if (!grid) return;

  const cards = Array.from(grid.querySelectorAll(".insta-item"));
  if (!cards.length) return;

  for (let i = 0; i < cards.length; i++) {
    const item = items[i];
    if (!item) break;

    const card = cards[i];
    const img = card.querySelector("img");
    if (!img) continue;

    img.src = item.image;
    img.alt = item.alt || img.alt || "FUJEIRA";
    img.loading = "lazy";
    img.decoding = "async";

    if (item.href) {
      const existingLink = card.querySelector("a.insta-link");
      if (existingLink) {
        existingLink.href = item.href;
        continue;
      }

      const link = document.createElement("a");
      link.className = "insta-link";
      link.href = item.href;
      link.target = "_blank";
      link.rel = "noopener noreferrer";
      link.setAttribute("aria-label", img.alt || "Abrir en Instagram");

      while (card.firstChild) link.appendChild(card.firstChild);
      card.appendChild(link);
    }
  }
}

async function loadInstaGrid() {
  try {
    const res = await fetch("data/instagram-grid.json?v=20260407h3", { cache: "no-store" });
    if (!res.ok) return;
    const payload = await res.json();
    const items = normalizeItems(payload);
    if (!items.length) return;
    applyInstaGrid(items);
  } catch {
  }
}

document.addEventListener("DOMContentLoaded", loadInstaGrid);
