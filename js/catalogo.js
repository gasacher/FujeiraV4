import { catalogDataSources } from "./product-data-sources.js";

const GRID = document.getElementById("productGrid");
const FILTER_BUTTONS = document.querySelectorAll("[data-filter]");
const SEARCH_INPUT = document.getElementById("searchInput");

let currentFilter = "all";
let searchText = "";

const SOURCES = catalogDataSources();

let productos = [];

async function loadProducts() {
  productos = [];

  try {
    const responses = await Promise.all(
      SOURCES.map(async src => {
        const res = await fetch(src);
        if (!res.ok) throw new Error(`No se pudo cargar ${src}`);
        return res.json();
      })
    );

    productos = responses.flat();

    if (GRID) {
      renderCatalog(productos);
      applyCategoryFromUrl();
    }

  } catch (err) {
    console.error("Error cargando catálogo:", err);

    if (GRID) {
      GRID.innerHTML =
        `<p class="text-light text-center">Error al cargar productos.</p>`;
    }

  }
}

function applyCategoryFromUrl() {
  if (!GRID) return;

  const params = new URLSearchParams(window.location.search);
  const raw = params.get("categoria");
  if (!raw) return;

  const wanted = decodeURIComponent(raw);
  const btn = Array.from(FILTER_BUTTONS).find(b => b.dataset.filter === wanted);
  if (!btn) return;

  FILTER_BUTTONS.forEach(b => b.classList.remove("active"));
  btn.classList.add("active");
  currentFilter = wanted;
  applyFilters();
}

function renderCatalog(list) {
  GRID.innerHTML = "";

  if (!list.length) {
    GRID.innerHTML =
      `<p class="text-light text-center">No hay productos.</p>`;
    return;
  }

  list.forEach(prod => {
    const img = prod.fotos?.[0] || "assets/img/placeholder.png";
    const preventaBadge =
      prod.preventa === true
        ? `<span class="catalog-card__badge catalog-card__badge--preventa">Preventa</span>`
        : "";

    const categoryClass =
      prod.categoria === "Fujeira Retro Series"
        ? "catalog-product-category catalog-product-category--retro-series"
        : "catalog-product-category";

    GRID.insertAdjacentHTML(
      "beforeend",
      `
      <div class="col-6 col-md-4 col-lg-3">
        <div class="catalog-product-card ${prod.preventa === true ? "catalog-product-card--preventa" : ""} h-100">
          <div class="catalog-img-wrapper">
            ${preventaBadge}
            <img src="${img}" alt="${prod.nombre}" loading="lazy" decoding="async">
          </div>
          <div class="catalog-info">
            <p class="catalog-product-name">${prod.nombre}</p>
            <p class="${categoryClass}">
              ${prod.categoria?.toUpperCase() || ""}
            </p>
            <a href="producto.html?id=${prod.codigo}" class="cta-fujeira-outline">
              Ver más
            </a>
          </div>
        </div>
      </div>
      `
    );
  });
}

function applyFilters() {
  let filtered = productos.filter(prod => {
    const matchCategory =
      currentFilter === "all" || prod.categoria === currentFilter;

    const text = searchText.toLowerCase();

    const matchSearch =
      prod.nombre?.toLowerCase().includes(text) ||
      prod.codigo?.toLowerCase().includes(text) ||
      prod.categoria?.toLowerCase().includes(text);

    return matchCategory && matchSearch;
  });

  if (!filtered.length) {
    GRID.innerHTML =
      `<p class="text-light text-center">No se encontraron productos.</p>`;
    return;
  }

  renderCatalog(filtered);
}

SEARCH_INPUT?.addEventListener("input", e => {
  searchText = e.target.value;
  applyFilters();
});

FILTER_BUTTONS.forEach(btn => {
  btn.addEventListener("click", () => {
    FILTER_BUTTONS.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");

    currentFilter = btn.dataset.filter;
    applyFilters();
  });
});

document.addEventListener("DOMContentLoaded", loadProducts);
