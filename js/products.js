import { addToCart, renderCart, showToast } from "./cart.js";
import { catalogDataSources } from "./product-data-sources.js";

const container = document.getElementById("productContainer");

const params = new URLSearchParams(window.location.search);
const id = params.get("id");

if (!id) {
  container.innerHTML = `
    <p class="text-light text-center mt-5">
      ❌ Producto no especificado.
    </p>
  `;
  throw new Error("ID no encontrado");
}

const SOURCES = catalogDataSources({ leadingDotSlash: true });

const RETRO_TALLES_STOCK = new Set(["L", "XL"]);

function isRetroSeriesProduct(prod) {
  return (
    prod?.categoria === "Fujeira Retro Series" ||
    prod?.proveedor === "retro_series"
  );
}

function formatPrice(precio) {
  if (!precio) return "Consultar";
  return `$${Number(precio).toLocaleString("es-AR")}`;
}

const SITE_ORIGIN = "https://fujeiraretrostore.com";

function absoluteAssetUrl(path) {
  if (!path) return `${SITE_ORIGIN}/assets/img/Logolima_transparente.png`;
  if (/^https?:\/\//i.test(path)) return path;
  const p = String(path).replace(/^\.\//, "").replace(/^\/+/, "");
  return `${SITE_ORIGIN}/${p}`;
}

function setProductPageSeo(prod) {
  const title = `${prod.nombre} | FUJEIRA`;
  document.title = title;

  const raw = getProductDescription(prod).replace(/\s+/g, " ").trim();
  const snippet =
    raw.length > 158 ? `${raw.slice(0, 155).trim()}…` : raw;
  const desc =
    snippet || `${prod.nombre} — FUJEIRA. Fotos, talles, Mercado Pago y envíos.`;

  const meta = document.getElementById("meta-desc");
  if (meta) meta.setAttribute("content", desc);

  const canonical = document.getElementById("seo-canonical");
  if (canonical) {
    try {
      const u = new URL(window.location.href);
      u.hash = "";
      canonical.setAttribute("href", u.toString());
    } catch {
      canonical.setAttribute(
        "href",
        `${SITE_ORIGIN}/producto.html?id=${encodeURIComponent(prod.codigo || "")}`
      );
    }
  }

  const shareUrl = (() => {
    try {
      const u = new URL(window.location.href);
      u.hash = "";
      return u.toString();
    } catch {
      return `${SITE_ORIGIN}/producto.html?id=${encodeURIComponent(prod.codigo || "")}`;
    }
  })();

  const imageUrl = absoluteAssetUrl(prod.fotos?.[0]);

  const setById = (id, content) => {
    const el = document.getElementById(id);
    if (el) el.setAttribute("content", content);
  };

  setById("seo-og-title", title);
  setById("seo-og-desc", desc);
  setById("seo-og-url", shareUrl);
  setById("seo-og-image", imageUrl);
  setById("seo-og-image-alt", prod.nombre);
  setById("seo-tw-title", title);
  setById("seo-tw-desc", desc);
  setById("seo-tw-image", imageUrl);
}

function getProductDescription(prod) {
  if (Number(prod.precio) === 80000) {
    return "Camisetas importadas calidad G5";
  }

  if (prod.descripcion) return prod.descripcion;

  if (prod.preventa && prod.preventa_detalle) {
    return prod.preventa_detalle;
  }

  if (prod.categoria === "Fujeira Retro Series") {
    return "Fujeira Retro Series. Diseños que nacen de nosotros. El apodo. La fecha. La mística que nunca se estampó.";
  }

  if (prod.categoria === "cuadros") {
    return "Una pieza decorativa con identidad futbolera, ideal para llevar la pasión a tu espacio.";
  }

  return "Una retro con historia, seleccionada por FUJEIRA.";
}

const RETRO_IDENTITY_FULL_TEXT =
  "Fujeira Retro Series. Diseños que nacen de nosotros. El apodo. La fecha. La mística que nunca se estampó.";

function escapeHtml(s) {
  if (s == null) return "";
  return String(s)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

function getProductDescriptionForPage(prod) {
  const plain = getProductDescription(prod).replace(/\s+/g, " ").trim();

  if (
    prod.categoria === "Fujeira Retro Series" &&
    plain === RETRO_IDENTITY_FULL_TEXT
  ) {
    return `
      <div class="product-desc product-desc--retro-identity">
        <p class="product-desc__brand">Fujeira Retro Series</p>
        <p class="product-desc__lead">Diseños que nacen de nosotros.</p>
        <p class="product-desc__tagline">El apodo. La fecha. La mística que nunca se estampó.</p>
      </div>
    `;
  }

  return `<p class="product-desc">${escapeHtml(plain)}</p>`;
}

function getMaterialNote(prod) {
  if (prod.categoria === "cuadros") return "";
  return `
    <div class="product-material-callout" role="note" aria-label="Composición de la prenda">
      <div class="product-material-callout__icon" aria-hidden="true">
        <i class="bi bi-droplet"></i>
      </div>
      <div class="product-material-callout__body">
        <span class="product-material-callout__label">Composición</span>
        <p class="product-material-callout__value">100% algodón</p>
      </div>
    </div>
  `;
}

function getSizeGuide(prod) {
  if (prod.categoria === "cuadros") {
    return `
      <div class="mt-3">
        <p class="fw-bold mb-1">Medidas</p>
        <p class="text-light small">
          ${prod.medidas?.join(" · ") || "Consultar"}
        </p>

        ${
          prod.marco?.length
            ? `
              <p class="fw-bold mt-3 mb-1">Marco</p>
              <ul class="text-light small ps-3">
                ${prod.marco.map(m => `<li>${m}</li>`).join("")}
              </ul>
            `
            : ""
        }
      </div>
    `;
  }

  if (prod.categoria === "Fujeira Retro Series") {
    return `
      <div class="mt-3">
        <p class="fw-bold mb-1">Guía de talles</p>
        <ul class="text-light small ps-3">
          <li>S: 48 cm ancho × 70 cm largo</li>
          <li>M: 50 cm ancho × 73 cm largo</li>
          <li>L: 54 cm ancho × 76 cm largo</li>
          <li>XL: 56 cm ancho × 79 cm largo</li>
          <li>XXL: 58 cm ancho × 82 cm largo</li>
        </ul>

        <p class="text-light small mt-2">
          Calce clásico. Tomá una remera que te quede bien y comparala con estas medidas.
        </p>
      </div>
    `;
  }

  return `
    <div class="mt-3">
      <p class="fw-bold mb-1">Guía de talles</p>
      <ul class="text-light small ps-3">
        <li>S: 49 cm ancho × 69 cm largo</li>
        <li>M: 50 cm ancho × 70 cm largo</li>
        <li>L: 52 cm ancho × 72 cm largo</li>
        <li>XL: 55 cm ancho × 75 cm largo</li>
        <li>XXL: 57 cm ancho × 76 cm largo</li>
      </ul>

      <p class="text-light small mt-2">
        Si estás entre dos talles, te recomendamos pedir uno más.
      </p>
    </div>
  `;
}

function getSelectionBlock(prod) {
  if (prod.categoria === "cuadros" && prod.marco?.length) {
    return `
      <div class="mt-4">
        <p class="fw-bold mb-1">Marco</p>
        <div class="sizes-list">
          ${prod.marco.map(m => `
            <button class="marco-btn" type="button" data-marco="${m}">
              ${m}
            </button>
          `).join("")}
        </div>
      </div>
    `;
  }

  if (prod.talles?.length) {
    const retro = isRetroSeriesProduct(prod);
    const waConsulta =
      "https://wa.me/5491130124589?text=" +
      encodeURIComponent(
        "Hola FUJEIRA — consulto por un talle que no está disponible en la web."
      );

    const botones = prod.talles
      .map(t => {
        const enStock = !retro || RETRO_TALLES_STOCK.has(t);
        const dis = enStock ? "" : " disabled";
        return `
            <button
              class="size-btn${enStock ? "" : " size-btn--no-stock"}"
              type="button"
              data-talle="${t}"
              data-disponible="${enStock ? "1" : "0"}"
              ${dis}
              title="${enStock ? "Elegir talle " + t : "Sin stock online — consultá por WhatsApp"}"
            >
              ${t}
            </button>`;
      })
      .join("");

    const notaRetro = retro
      ? `
        <p class="size-availability-note text-light small mt-3 mb-0">
          ¿Querés <strong>S, M o XXL</strong>? Escribinos por
          <a href="${waConsulta}" target="_blank" rel="noopener noreferrer" class="link-light text-decoration-underline">WhatsApp</a>
          y lo gestionamos.
        </p>`
      : "";

    return `
      <div class="mt-4">
        <p class="fw-bold mb-1">Talles disponibles</p>
        <div class="sizes-list">
          ${botones}
        </div>
        ${notaRetro}
      </div>
    `;
  }

  return "";
}

async function loadProduct() {
  try {
    const responses = await Promise.all(
      SOURCES.map(src =>
        fetch(src).then(r => {
          if (!r.ok) throw new Error(`Error cargando ${src}`);
          return r.json();
        })
      )
    );

    const productos = responses.flat();
    const prod = productos.find(p => p.codigo === id);

    if (!prod) {
      container.innerHTML = `
        <p class="text-light text-center mt-5">
          ❌ Producto no encontrado.
        </p>
      `;
      return;
    }

    renderProduct(prod, productos);
  } catch (err) {
    console.error(err);
    container.innerHTML = `
      <p class="text-light text-center mt-5">
        ❌ Error cargando el producto.
      </p>
    `;
  }
}

function renderProduct(prod, productos) {
  setProductPageSeo(prod);

  const hasImages = Array.isArray(prod.fotos) && prod.fotos.length > 0;
  let talleSeleccionado = null;
  let marcoSeleccionado = null;

  container.innerHTML = `
    <div class="row g-5">
      <!-- GALERÍA -->
      <div class="col-lg-6">
        <div class="product-gallery">
          ${
            hasImages
              ? `
                <div class="product-gallery__hero">
                  ${
                    prod.preventa
                      ? `<span class="catalog-card__badge catalog-card__badge--preventa">Preventa</span>`
                      : ""
                  }
                  <img
                    id="mainProductImg"
                    src="${prod.fotos[0]}"
                    alt="${prod.nombre}"
                    class="product-main-img"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                  >
                </div>
                <div class="product-thumbs mt-3">
                  ${prod.fotos.map((f, index) => `
                    <img
                      src="${f}"
                      alt="${prod.nombre} ${index + 1}"
                      class="product-thumb"
                      data-image="${f}"
                      loading="lazy"
                      decoding="async"
                    >
                  `).join("")}
                </div>
              `
              : `<div class="product-img-placeholder"></div>`
          }
        </div>
      </div>

      <!-- INFO -->
      <div class="col-lg-6 text-light">
        <h1 class="product-title">${prod.nombre}</h1>

        <p class="price-text fs-4 fw-bold text-warning">
          ${formatPrice(prod.precio)}
        </p>

        ${getProductDescriptionForPage(prod)}
        ${getMaterialNote(prod)}

        ${getSizeGuide(prod)}
        ${getSelectionBlock(prod)}

        <div class="mt-4 d-flex flex-wrap gap-2">
          <button id="addToCartBtn" class="cta-fujeira" type="button">
            Agregar al carrito
          </button>

          <button id="shareProductBtn" class="cta-fujeira-outline" type="button">
            <i class="bi bi-share"></i> Compartir
          </button>
        </div>
      </div>
    </div>

    <!-- RELACIONADOS -->
    <div class="related-section mt-5">
      <h3 class="text-light mb-3">También te puede interesar</h3>
      <div class="row g-4" id="relatedGrid"></div>
    </div>
  `;

  container.querySelectorAll(".product-thumb").forEach(thumb => {
    thumb.addEventListener("click", () => {
      const mainImg = document.getElementById("mainProductImg");
      if (mainImg) mainImg.src = thumb.dataset.image;
    });
  });

  container.querySelectorAll(".size-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      if (btn.disabled || btn.dataset.disponible === "0") return;
      container.querySelectorAll(".size-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      talleSeleccionado = btn.dataset.talle;
    });
  });

  container.querySelectorAll(".marco-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      container.querySelectorAll(".marco-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      marcoSeleccionado = btn.dataset.marco;
    });
  });

  window.resetTalleSeleccionado = () => {
    talleSeleccionado = null;
    marcoSeleccionado = null;

    container
      .querySelectorAll(".size-btn, .marco-btn")
      .forEach(btn => btn.classList.remove("active"));
  };

  document.getElementById("addToCartBtn")?.addEventListener("click", () => {
    if (prod.categoria === "cuadros" && !marcoSeleccionado) {
      showToast("Seleccioná un marco");
      return;
    }

    if (prod.talles?.length && !talleSeleccionado) {
      showToast("Seleccioná un talle");
      return;
    }

    addToCart({
      codigo: prod.codigo,
      nombre: prod.nombre,
      img: prod.fotos?.[0] || null,
      precio: prod.precio,
      talle: talleSeleccionado || null,
      marco: marcoSeleccionado || null,
      cantidad: 1,
      preventa: prod.preventa === true
    });

    renderCart();

    if (window.matchMedia("(max-width: 768px)").matches) {
      const offcanvasEl = document.getElementById("cartOffcanvas");
      if (offcanvasEl) {
        const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
        offcanvas.show();
      }
    }
  });

  renderRelated(prod, productos);
}

document.addEventListener("click", async e => {
  const btn = e.target.closest("#shareProductBtn");
  if (!btn) return;

  const url = window.location.origin + window.location.pathname + window.location.search;
  const title =
    document.querySelector(".product-title")?.textContent?.trim() ||
    document.title ||
    "FUJEIRA";

  if (navigator.share) {
    try {
      await navigator.share({ title, url });
      showToast?.("Compartido ✅");
    } catch (err) {
    }
    return;
  }

  try {
    await navigator.clipboard.writeText(url);
    showToast?.("Link copiado ✅");
  } catch (err) {
    window.prompt("Copiá el link:", url);
  }
});

function shuffleArray(array) {
  return array
    .map(item => ({ item, sort: Math.random() }))
    .sort((a, b) => a.sort - b.sort)
    .map(({ item }) => item);
}

function renderRelated(prod, productos) {
  const grid = document.getElementById("relatedGrid");
  if (!grid) return;

  const relacionados = shuffleArray(
    productos.filter(p => {
      if (p.codigo === prod.codigo || !p.fotos?.length) return false;
      if (prod.preventa) return p.preventa === true;
      return p.categoria === prod.categoria;
    })
  ).slice(0, 4);

  if (!relacionados.length) {
    grid.innerHTML = `<p class="text-muted">No hay productos relacionados.</p>`;
    return;
  }

  grid.innerHTML = relacionados.map(p => `
    <div class="col-6 col-md-3">
      <a href="producto.html?id=${p.codigo}" class="related-card">
        <img src="${p.fotos[0]}" class="related-img" alt="${p.nombre}" loading="lazy" decoding="async">
        <p class="related-name">${p.nombre}</p>
      </a>
    </div>
  `).join("");
}

loadProduct();
