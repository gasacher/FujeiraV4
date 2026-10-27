@extends('v5.layout')

@section('title', $producto->nombre.' | Vista previa V5')
@section('description', $producto->nombre.'. '.$producto->club.'. '.$producto->region.'. '.$producto->version.'. Vista previa, sin cobro.')

@section('content')
<section class="py-4">
  <div class="container">
    <nav class="product-breadcrumb mb-4" aria-label="Migas de pan">
      <a href="{{ \App\Support\V5Urls::to('home') }}">Inicio</a>
      <span aria-hidden="true">/</span>
      <a href="{{ \App\Support\V5Urls::to('catalogo') }}">Catálogo</a>
      <span aria-hidden="true">/</span>
      <span>{{ $producto->nombre }}</span>
    </nav>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="v5-hero-photo">
          @if ($producto->tieneFoto())
            <img src="{{ $producto->fotoPublica() }}" alt="{{ $producto->nombre }}" width="800" height="1000" fetchpriority="high" decoding="async">
          @else
            @include('v5.partials.missing-photo', ['producto' => $producto])
          @endif
        </div>
      </div>
      <div class="col-md-6 text-light">
        <p class="v5-kicker">Vista previa</p>
        <h1 class="product-title">{{ $producto->nombre }}</h1>
        <dl class="v5-facts">
          <div>
            <dt>Club</dt>
            <dd>{{ $producto->club }}</dd>
          </div>
          <div>
            <dt>Región</dt>
            <dd>{{ $producto->region }}</dd>
          </div>
          <div>
            <dt>Versión</dt>
            <dd>{{ $producto->version }}</dd>
          </div>
          <div>
            <dt>Código</dt>
            <dd>{{ $producto->codigo }}</dd>
          </div>
          @if ($producto->talles !== [])
            <div>
              <dt>Talles</dt>
              <dd>{{ implode(', ', $producto->talles) }}</dd>
            </div>
          @endif
        </dl>

        @if ($producto->precio !== null)
          <p class="v5-price">${{ number_format($producto->precio, 0, ',', '.') }}</p>
          <p class="v5-price-note">Precio del catálogo. En esta vista previa no se cobra.</p>
        @else
          <p class="v5-price-note">Este modelo no trae precio en el catálogo.</p>
        @endif

        @if ($producto->porEncargo())
          <p class="mb-0">Por encargo. Todavía no se puede comprar desde esta ficha.</p>
        @endif

        <p class="mt-4 mb-0">
          <a href="{{ \App\Support\V5Urls::catalogo(['club' => $producto->club]) }}">Ver más de {{ $producto->club }}</a>
        </p>
      </div>
    </div>

    @if ($relacionados->isNotEmpty())
      <div class="related-section mt-5">
        <h2 class="h4">Otras de {{ $producto->club }}</h2>
        <div class="row g-4">
          @foreach ($relacionados as $relacionado)
            <div class="col-6 col-md-3">@include('v5.partials.card', ['producto' => $relacionado])</div>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</section>
@endsection
