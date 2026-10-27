<article class="v5-card h-100">
  <a href="{{ \App\Support\V5Urls::to('producto', ['codigo' => $producto->codigo]) }}" class="v5-card__link">
    <div class="v5-card-photo">
      @if ($producto->miniaturaPublica())
        <img src="{{ $producto->miniaturaPublica() }}" alt="{{ $producto->nombre }}" width="480" height="600" loading="lazy" decoding="async">
      @else
        @include('v5.partials.missing-photo', ['producto' => $producto])
      @endif
    </div>
    <div class="v5-card__body">
      <p class="v5-card__name">{{ $producto->nombre }}</p>
      <p class="v5-card__meta">{{ $producto->club }}</p>
      <p class="v5-card__meta">{{ $producto->region }} · {{ $producto->version }}</p>
      @if ($producto->precio !== null)
        <p class="v5-card__price">${{ number_format($producto->precio, 0, ',', '.') }}</p>
      @endif
    </div>
  </a>
</article>
