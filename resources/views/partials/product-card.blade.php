<article class="catalog-product-card {{ $cardClass ?? '' }} h-100 {{ $producto->sinStock() ? 'catalog-product-card--no-stock' : '' }}" data-search="{{ $producto->nombreVisible() }} {{ $producto->codigo }} {{ $producto->categoria }}">
  <a href="{{ route('producto.show', $producto->codigo) }}" class="catalog-card-link text-decoration-none">
    <figure class="catalog-img-wrapper mb-0">
      <img src="{{ $producto->fotoPublica() }}" alt="{{ $producto->nombreVisible() }}" width="800" height="1000" loading="lazy" decoding="async">
      @if ($producto->sinStock())
        <span class="catalog-card__badge catalog-card__badge--no-stock">Sin stock</span>
      @elseif ($producto->preventa)
        <span class="catalog-card__badge catalog-card__badge--preventa">Preventa</span>
      @endif
    </figure>
    <div class="catalog-info">
      <p class="catalog-product-name">{{ $producto->nombreVisible() }}</p>
      <p class="catalog-product-category {{ $producto->esRemera() ? 'catalog-product-category--retro-series' : '' }}">{{ $producto->categoria }}</p>
      <p class="catalog-product-name">${{ number_format($producto->precio, 0, ',', '.') }}</p>
    </div>
  </a>
</article>
