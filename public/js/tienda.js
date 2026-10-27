(function () {
  function normalizar(valor) {
    return String(valor || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");
  }

  const input = document.getElementById("searchInput");
  const cards = Array.from(document.querySelectorAll("[data-search]"));
  const live = document.getElementById("searchLive");
  const empty = document.getElementById("catalogEmpty");
  const indexNode = document.getElementById("catalogIndex");
  let catalogo = [];

  if (indexNode) {
    try {
      catalogo = JSON.parse(indexNode.textContent || "[]");
    } catch (error) {
      catalogo = [];
    }
  }

  function filtrar() {
    if (!input) return;
    const consulta = normalizar(input.value.trim());
    let visibles = 0;

    cards.forEach((card) => {
      const columna = card.closest(".js-product-col") || card.parentElement;
      const coincide = consulta === "" || normalizar(card.dataset.search).includes(consulta);
      if (columna) columna.classList.toggle("d-none", !coincide);
      if (coincide) visibles += 1;
    });

    if (empty) empty.classList.toggle("d-none", consulta === "" || visibles > 0);

    if (!live) return;

    if (consulta.length < 1 || !catalogo.length) {
      live.hidden = true;
      live.innerHTML = "";
      return;
    }

    const coincidencias = catalogo
      .filter((item) => normalizar(item.nombre + " " + item.codigo + " " + item.categoria).includes(consulta))
      .slice(0, 6);

    live.hidden = false;
    live.replaceChildren();

    if (!coincidencias.length) {
      const vacio = document.createElement("p");
      vacio.className = "search-live__empty";
      vacio.textContent = "No hay coincidencias. ";
      const enlace = document.createElement("a");
      enlace.href = "/catalogo";
      enlace.textContent = "Ver el catálogo";
      vacio.append(enlace);
      live.append(vacio);
      return;
    }

    coincidencias.forEach((item) => {
      const enlace = document.createElement("a");
      enlace.className = "search-live__item";
      enlace.href = String(item.url || "/catalogo");
      const nombre = document.createElement("span");
      nombre.textContent = String(item.nombre || "");
      const codigo = document.createElement("small");
      codigo.textContent = String(item.codigo || "");
      enlace.append(nombre, codigo);
      live.append(enlace);
    });
  }

  input?.addEventListener("input", filtrar);
  if (input && input.value) filtrar();

  document.querySelectorAll(".product-thumb-btn").forEach((boton) => {
    boton.addEventListener("click", () => {
      const principal = document.getElementById("mainProductImg");
      if (principal && boton.dataset.full) principal.src = boton.dataset.full;
      document.querySelectorAll(".product-thumb-btn").forEach((otro) => otro.classList.remove("is-active"));
      boton.classList.add("is-active");
    });
  });

  const hero = document.querySelector(".hero-photo");
  const slides = hero ? Array.from(hero.querySelectorAll(".hero-slide")) : [];
  if (hero && slides.length > 1) {
    let indice = 0;
    const mostrar = (siguiente) => {
      hero.classList.add("is-manual");
      indice = (siguiente + slides.length) % slides.length;
      slides.forEach((slide, posicion) => slide.classList.toggle("is-active", posicion === indice));
    };
    hero.querySelector(".hero-nav--prev")?.addEventListener("click", () => mostrar(indice - 1));
    hero.querySelector(".hero-nav--next")?.addEventListener("click", () => mostrar(indice + 1));
  }

  const drawer = document.getElementById("cartDrawer");
  if (drawer?.dataset.show && window.bootstrap?.Offcanvas) {
    window.bootstrap.Offcanvas.getOrCreateInstance(drawer).show();
  }
})();
