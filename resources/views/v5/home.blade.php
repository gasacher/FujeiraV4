@extends('v5.layout')

@section('title', 'Vista previa | FUJEIRA')
@section('description', 'Muestra del catálogo nuevo de camisetas FUJEIRA: Jugador, Retro y Fan. Sin precio y sin compra.')
@section('body-class', 'home-page')

@section('content')
<header id="hero" class="hero-photo">
  <h1 class="visually-hidden">Vista previa del catálogo nuevo de FUJEIRA</h1>
  <button class="hero-nav hero-nav--prev" type="button" aria-label="Anterior">
    <i class="bi bi-chevron-left"></i>
  </button>
  <button class="hero-nav hero-nav--next" type="button" aria-label="Siguiente">
    <i class="bi bi-chevron-right"></i>
  </button>
  @foreach ($slides as $slide)
    <div class="hero-slide hero-slide--{{ $slide['modifier'] }}">
      <div class="hero-slide__media">
        <img
          class="hero-slide__backdrop"
          src="{{ $slide['producto']->fotoPublica() }}"
          alt=""
          width="1200"
          height="1600"
          @if ($slide['eager']) fetchpriority="high" @endif
          loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}"
          decoding="async"
        >
        <div class="hero-slide__mockup" aria-hidden="true">
          <figure class="hero-mockup__cell">
            <img src="{{ $slide['izquierda']->fotoPublica() }}" alt="" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
          </figure>
          <figure class="hero-mockup__cell hero-mockup__cell--feature">
            <img src="{{ $slide['producto']->fotoPublica() }}" alt="{{ $slide['producto']->nombre }}" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
          </figure>
          <figure class="hero-mockup__cell">
            <img src="{{ $slide['derecha']->fotoPublica() }}" alt="" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
          </figure>
        </div>
      </div>
      <div class="hero-slide__content">
        <div class="hero-slide__left">
          <span class="hero-slide__tag">Vista previa</span>
          <h2 class="hero-slide__title"><span>{{ $slide['titulo'] }}</span></h2>
          <p class="hero-slide__subtitle">Catálogo nuevo</p>
          <div class="hero-slide__divider"></div>
          <p class="hero-slide__desc">Una muestra para mirar las fotos. Sin precio y sin compra.</p>
        </div>
        <div class="hero-slide__right">
          <a href="{{ \App\Support\V5Urls::to('producto', ['codigo' => $slide['producto']->codigo]) }}" class="hero-slide__cta">Ver modelo →</a>
        </div>
      </div>
    </div>
  @endforeach
</header>

@foreach ($grupos as $categoria => $productos)
  <section class="section-destacados pt-4 pb-5">
    <div class="container">
      <h2 class="section-title text-center mb-2">{{ $categoria }}</h2>
      <p class="home-kicker text-center mb-4">Muestra de la carpeta</p>
      <div class="row g-4 mb-4">
        @foreach ($productos as $producto)
          <div class="col-6 col-md-3">@include('v5.partials.card', ['producto' => $producto])</div>
        @endforeach
      </div>
      <div class="text-center mt-3">
        <a href="{{ \App\Support\V5Urls::catalogo(['categoria' => $categoria]) }}" class="cta-fujeira">Ver {{ $categoria }}</a>
      </div>
    </div>
  </section>
@endforeach
@endsection
