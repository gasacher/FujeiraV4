<article class="catalog-product-card h-100" data-search="{{ $producto->nombre }} {{ $producto->codigo }} {{ $producto->categoria }}">
  <a href="{{ \App\Support\V5Urls::to('producto', ['codigo' => $producto->codigo]) }}" class="catalog-card-link text-decoration-none">
    <figure class="catalog-img-wrapper mb-0">
      <img src="{{ $producto->fotoPublica() }}" alt="{{ $producto->nombre }}" width="800" height="1000" loading="lazy" decoding="async">
      <span class="catalog-card__badge catalog-card__badge--preventa">Vista previa</span>
    </figure>
    <div class="catalog-info">
      <p class="catalog-product-name">{{ $producto->nombre }}</p>
      <p class="catalog-product-category catalog-product-category--retro-series">{{ $producto->categoria }}</p>
      <p class="catalog-product-name">Sin precio</p>
    </div>
  </a>
</article>
