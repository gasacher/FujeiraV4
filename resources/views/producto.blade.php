@extends('layouts.store')

@section('title', $producto->nombreVisible().' | FUJEIRA')
@section('description', $producto->nombreVisible().'. '.$producto->descripcionPublica().' $'.number_format($producto->precio, 0, ',', '.').' ARS.')
@section('canonical', \App\Support\Site::url('/producto/'.$producto->codigo))
@section('og-type', 'product')
@section('og-image', $producto->fotoAbsoluta())
@section('body-class', 'product-page')

@php
  $disponible = $producto->esRemera() ? ! $producto->sinStock() : true;
  $jsonLd = [
      '@context' => 'https://schema.org',
      '@type' => 'Product',
      'name' => $producto->nombreVisible(),
      'sku' => $producto->codigo,
      'description' => $producto->descripcionPublica(),
      'image' => [$producto->fotoAbsoluta()],
      'brand' => ['@type' => 'Brand', 'name' => 'FUJEIRA'],
      'offers' => [
          '@type' => 'Offer',
          'url' => \App\Support\Site::url('/producto/'.$producto->codigo),
          'priceCurrency' => 'ARS',
          'price' => (string) $producto->precio,
          'availability' => $disponible
              ? 'https://schema.org/InStock'
              : 'https://schema.org/OutOfStock',
      ],
  ];
  $migas = [
      '@context' => 'https://schema.org',
      '@type' => 'BreadcrumbList',
      'itemListElement' => [
          ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => \App\Support\Site::url('/')],
          ['@type' => 'ListItem', 'position' => 2, 'name' => 'Catálogo', 'item' => \App\Support\Site::url('/catalogo')],
          ['@type' => 'ListItem', 'position' => 3, 'name' => $producto->nombreVisible(), 'item' => \App\Support\Site::url('/producto/'.$producto->codigo)],
      ],
  ];
@endphp

@section('content')
<section class="py-5">
  <div class="container">
    <nav class="product-breadcrumb mb-4" aria-label="Migas de pan">
      <a href="{{ route('home') }}">Inicio</a>
      <span aria-hidden="true">/</span>
      <a href="{{ route('catalogo') }}">Catálogo</a>
      <span aria-hidden="true">/</span>
      <span>{{ $producto->nombreVisible() }}</span>
    </nav>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="product-gallery">
          <div class="product-gallery__hero">
            @if ($producto->preventa)
              <span class="catalog-card__badge catalog-card__badge--preventa">Preventa</span>
            @endif
            <img id="mainProductImg" src="{{ $producto->fotoPublica() }}" alt="{{ $producto->nombreVisible() }}" class="product-main-img" width="800" height="1000" fetchpriority="high" decoding="async">
          </div>
          @if (count($producto->fotos) > 1)
            <div class="product-thumbs mt-3">
              @foreach ($producto->fotos as $index => $foto)
                <button class="product-thumb-btn" type="button" data-full="{{ $producto->fotoPublica($index) }}" aria-label="Ver foto {{ $index + 1 }} de {{ $producto->nombreVisible() }}">
                  <img src="{{ $producto->fotoPublica($index) }}" alt="" class="product-thumb" width="84" height="84" loading="lazy" decoding="async">
                </button>
              @endforeach
            </div>
          @endif
        </div>
      </div>
      <div class="col-md-6 text-light">
        <p class="catalog-product-category">{{ $producto->categoria }}</p>
        <h1 class="product-title">{{ $producto->nombreVisible() }}</h1>
        <p class="price-text">${{ number_format($producto->precio, 0, ',', '.') }}</p>
        <p class="product-code">Código {{ $producto->codigo }}</p>

        @if ($producto->descripcionEsIdentidadRetro())
          <div class="product-desc product-desc--retro-identity">
            <p class="product-desc__brand">Fujeira Retro Series</p>
            <p class="product-desc__lead">Diseños que nacen de nosotros.</p>
            <p class="product-desc__tagline">El apodo. La fecha. La mística que nunca se estampó.</p>
          </div>
        @else
          <p class="product-desc">{{ $producto->descripcionPublica() }}</p>
        @endif

        @unless ($producto->esCuadro())
          <div class="product-material-callout" role="note" aria-label="Composición de la prenda">
            <div class="product-material-callout__icon" aria-hidden="true"><i class="bi bi-droplet"></i></div>
            <div class="product-material-callout__body">
              <span class="product-material-callout__label">Composición</span>
              <p class="product-material-callout__value">100% algodón</p>
            </div>
          </div>
        @endunless

        @if ($producto->esCuadro())
          <div class="mt-3">
            <p class="fw-bold mb-1">Medidas</p>
            <p class="mb-0">{{ $producto->medidasLegibles() }}</p>
          </div>
        @elseif ($producto->esRemera())
          <div class="mt-3">
            <p class="fw-bold mb-1">Guía de talles</p>
            <ul class="small ps-3 mb-2">
              <li>S: 48 cm ancho × 70 cm largo</li>
              <li>M: 50 cm ancho × 73 cm largo</li>
              <li>L: 54 cm ancho × 76 cm largo</li>
              <li>XL: 56 cm ancho × 79 cm largo</li>
              <li>XXL: 58 cm ancho × 82 cm largo</li>
            </ul>
            <p class="small mb-0">Calce clásico. Tomá una remera que te quede bien y comparala con estas medidas.</p>
          </div>
        @endif

        @if ($producto->sinStock())
          <p class="mt-4">Esta remera no tiene stock en este momento.</p>
          <p class="size-availability-note small">
            <a href="{{ $producto->whatsappTalle() }}" target="_blank" rel="noopener noreferrer">¿No ves tu talle disponible?</a>
          </p>
          <a class="cta-fujeira" target="_blank" rel="noopener noreferrer" href="https://wa.me/5491130124589?text={{ rawurlencode('Hola FUJEIRA! Me interesa '.$producto->nombre.' y vi que está sin stock.') }}">Avisame cuando vuelva</a>
        @else
          <form method="POST" action="{{ route('carrito.add') }}" class="mt-4">
            @csrf
            <input type="hidden" name="codigo" value="{{ $producto->codigo }}">
            @if ($producto->esRemera())
              <label class="form-label" for="talle">Talle</label>
              <select class="form-select mb-2" id="talle" name="talle" required>
                <option value="">Elegí un talle</option>
                @foreach ($producto->talles as $talle)
                  @php $stock = $producto->stockDe($talle); @endphp
                  <option value="{{ $talle }}" @disabled($stock < 1)>{{ $talle }}{{ $stock < 1 ? ' — sin stock' : '' }}</option>
                @endforeach
              </select>
              <p class="size-availability-note small mb-3">
                <a href="{{ $producto->whatsappTalle() }}" target="_blank" rel="noopener noreferrer">¿No ves tu talle disponible?</a>
              </p>
            @elseif ($producto->marcos !== [])
              <label class="form-label" for="marco">Marco</label>
              <select class="form-select mb-3" id="marco" name="marco" required>
                <option value="">Elegí un marco</option>
                @foreach ($producto->marcos as $marco)
                  <option value="{{ $marco }}">{{ $marco }}</option>
                @endforeach
              </select>
            @endif
            <label class="form-label" for="cantidad">Cantidad</label>
            <input class="form-control mb-3" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1" required>
            <button class="cta-fujeira" type="submit">Agregar al carrito</button>
          </form>
        @endif
      </div>
    </div>

    <div class="related-section mt-5">
      <h2 class="text-light mb-3">También te puede interesar</h2>
      <div class="row g-4">
        @forelse ($relacionados as $relacionado)
          <div class="col-6 col-md-3">
            <a href="{{ route('producto.show', $relacionado->codigo) }}" class="related-card">
              <img src="{{ $relacionado->fotoPublica() }}" class="related-img" alt="{{ $relacionado->nombreVisible() }}" width="600" height="750" loading="lazy" decoding="async">
              <p class="related-name">{{ $relacionado->nombreVisible() }}</p>
            </a>
          </div>
        @empty
          <p class="text-light">No hay productos relacionados.</p>
        @endforelse
      </div>
    </div>
  </div>
</section>
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($migas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection
