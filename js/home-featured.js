import { catalogDataSources } from "./product-data-sources.js?v=20260508h3";
import { isProductOutOfStock } from "./stock.js?v=20260508h3";

const grid = document.getElementById("featuredGrid");

const SOURCES = catalogDataSources();

let allProductsPromise = null;

function loadAllProducts() {
  if (!allProductsPromise) {
    allProductsPromise = Promise.all(
      SOURCES.map(src =>
        fetch(src).then(r => {
          if (!r.ok) throw new Error(`Error cargando ${src}`);
          return r.json();
        })
      )
    ).then(responses => responses.flat());
  }

  return allProductsPromise;
}

async function loadFeatured() {
  if (!grid) return;

  try {
    const productos = await loadAllProducts();

    const destacados = productos
      .filter(
        p =>
          p.categoria === "Fujeira Retro Series" ||
          p.proveedor === "retro_series"
      )
      .slice(0, 4);

    if (!destacados.length) {
      grid.innerHTML =
        `<p class="text-light text-center">No hay productos destacados.</p>`;
      return;
    }

    grid.innerHTML = destacados.map(p => {
      const img = p.fotos?.[0] || "assets/img/placeholder.png";
      const sinStock = isProductOutOfStock(p);

      const badge = sinStock
        ? `<span class="catalog-card__badge catalog-card__badge--no-stock">Sin stock</span>`
        : p.preventa === true
          ? `<span class="catalog-card__badge catalog-card__badge--preventa">Preventa</span>`
          : "";

      return `
        <div class="col-6 col-md-3">
          <figure class="catalog-product-card featured-card ${p.preventa === true ? "catalog-product-card--preventa" : ""} ${sinStock ? "catalog-product-card--no-stock" : ""} h-100">

            <div class="catalog-img-wrapper">
              ${badge}
              <a href="producto.html?id=${p.codigo}">
                <img src="${img}" alt="${p.nombre}" loading="lazy" decoding="async">
              </a>
            </div>

            <figcaption class="catalog-info">
              <p class="catalog-product-name">${p.nombre}</p>
              <p class="catalog-product-category">
                ${p.categoria?.toUpperCase() || ""}
              </p>

              <a href="producto.html?id=${p.codigo}"
                 class="cta-fujeira-outline">
                VER MÁS
              </a>
            </figcaption>

          </figure>
        </div>
      `;
    }).join("");

  } catch (err) {
    console.error("Error al cargar destacados:", err);
    grid.innerHTML =
      `<p class="text-light text-center">Error al cargar destacados.</p>`;
  }
}

const gridCuadros = document.getElementById("featuredCuadrosGrid");

async function loadFeaturedCuadros() {
  if (!gridCuadros) return;

  try {
    const productos = await loadAllProducts();

    const cuadros = productos
      .filter(p => p.destacado === true && p.categoria === "cuadros")
      .slice(0, 4);

    if (!cuadros.length) {
      gridCuadros.innerHTML = "";
      return;
    }

    gridCuadros.innerHTML = cuadros.map(p => `
      <div class="col-6 col-md-3">
        <figure class="catalog-product-card cuadro-card h-100">

          <a href="producto.html?id=${p.codigo}">
            <img src="${p.fotos?.[0]}" alt="${p.nombre}" loading="lazy" decoding="async">
          </a>

          <figcaption class="catalog-info text-center">
            <p class="catalog-product-name">${p.nombre}</p>

            <a href="producto.html?id=${p.codigo}"
               class="cta-fujeira-outline mt-2">
              VER MÁS
            </a>
          </figcaption>

        </figure>
      </div>
    `).join("");

  } catch (err) {
    console.error("Error cargando cuadros destacados:", err);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  loadFeatured();
  loadFeaturedCuadros();
});
