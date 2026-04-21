import { catalogDataSources } from "./product-data-sources.js";

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
      const preventaBadge =
        p.preventa === true
          ? `<span class="catalog-card__badge catalog-card__badge--preventa">Preventa</span>`
          : "";

      return `
        <div class="col-6 col-md-3">
          <div class="catalog-product-card featured-card ${p.preventa === true ? "catalog-product-card--preventa" : ""} h-100">

            <div class="catalog-img-wrapper">
              ${preventaBadge}
              <a href="producto.html?id=${p.codigo}">
                <img src="${img}" alt="${p.nombre}" loading="lazy" decoding="async">
              </a>
            </div>

            <div class="catalog-info">
              <p class="catalog-product-name">${p.nombre}</p>
              <p class="catalog-product-category">
                ${p.categoria?.toUpperCase() || ""}
              </p>

              <a href="producto.html?id=${p.codigo}"
                 class="cta-fujeira-outline">
                VER MÁS
              </a>
            </div>

          </div>
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
        <div class="catalog-product-card cuadro-card h-100">

          <a href="producto.html?id=${p.codigo}">
            <img src="${p.fotos?.[0]}" alt="${p.nombre}" loading="lazy" decoding="async">
          </a>

          <div class="catalog-info text-center">
            <p class="catalog-product-name">${p.nombre}</p>

            <a href="producto.html?id=${p.codigo}"
               class="cta-fujeira-outline mt-2">
              VER MÁS
            </a>
          </div>

        </div>
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
