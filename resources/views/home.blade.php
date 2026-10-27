@extends('layouts.store')

@section('title', 'FUJEIRA | Remeras de diseño propio, cuadros y arte futbolero')
@section('description', 'FUJEIRA: remeras de diseño propio de Fujeira Retro Series y cuadros de arte futbolero. Edición limitada, con envío que se confirma por WhatsApp.')
@section('canonical', \App\Support\Site::url('/'))
@section('og-image', \App\Support\Site::imageAbsolute('assets/catalogo/retro_series/diablos_rojos/diablos_rojos02.png'))
@section('body-class', 'home-page')

@push('head')
  <link rel="preload" as="image" href="{{ \App\Support\Site::image('assets/img/Logolima_transparente.png') }}">
  <link rel="preload" as="image" href="{{ \App\Support\Site::image('assets/catalogo/retro_series/diablos_rojos/diablos_rojos02.png') }}" fetchpriority="high">
@endpush

@section('content')
@php
  $slides = [
    [
      'modifier' => 'diablos',
      'codigo' => 'FU-01',
      'tag' => 'Nueva colección',
      'title' => 'LOS<br><span>DIABLOS</span><br>ROJOS',
      'desc' => 'Rey de Copas desde 1905. Edición limitada disponible.',
      'feature' => 'assets/catalogo/retro_series/diablos_rojos/diablos_rojos02.png',
      'featureAlt' => 'Remera Fujeira Retro Series Diablos Rojos',
      'left' => 'assets/catalogo/retro_series/los xeneixes/boca01.png',
      'leftAlt' => 'Remera Fujeira Retro Series Los Xeneixes',
      'right' => 'assets/catalogo/retro_series/cuervos/cuervos01.png',
      'rightAlt' => 'Remera Fujeira Retro Series Cuervos',
      'eager' => true,
    ],
    [
      'modifier' => 'xeneizes',
      'codigo' => 'FU-PREV-02',
      'tag' => 'Retro Series',
      'title' => 'LOS<br><span>XENEIZES</span>',
      'desc' => 'Azul y oro en el pecho. Diseño propio, mística de La Boca.',
      'feature' => 'assets/catalogo/retro_series/los xeneixes/boca01.png',
      'featureAlt' => 'Remera Fujeira Retro Series Los Xeneixes',
      'left' => 'assets/catalogo/retro_series/diablos_rojos/diablos_rojos02.png',
      'leftAlt' => 'Remera Fujeira Retro Series Diablos Rojos',
      'right' => 'assets/catalogo/retro_series/cuervos/cuervos01.png',
      'rightAlt' => 'Remera Fujeira Retro Series Cuervos',
      'eager' => false,
    ],
    [
      'modifier' => 'cuervos',
      'codigo' => 'FU-PREV-03',
      'tag' => 'Retro Series',
      'title' => '<span>CUERVOS</span>',
      'desc' => 'El Ciclón de Boedo. Azulgrana y alma de tribuna.',
      'feature' => 'assets/catalogo/retro_series/cuervos/cuervos01.png',
      'featureAlt' => 'Remera Fujeira Retro Series Cuervos',
      'left' => 'assets/catalogo/retro_series/diablos_rojos/diablos_rojos02.png',
      'leftAlt' => 'Remera Fujeira Retro Series Diablos Rojos',
      'right' => 'assets/catalogo/retro_series/los xeneixes/boca01.png',
      'rightAlt' => 'Remera Fujeira Retro Series Los Xeneixes',
      'eager' => false,
    ],
  ];
@endphp

<header id="hero" class="hero-photo">
  <h1 class="visually-hidden">FUJEIRA: remeras de diseño propio, cuadros y arte futbolero</h1>
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
          src="{{ \App\Support\Site::image($slide['feature']) }}"
          alt=""
          width="1200"
          height="1600"
          @if ($slide['eager']) fetchpriority="high" @endif
          loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}"
          decoding="async"
        >
        <div class="hero-slide__mockup" aria-hidden="true">
          <figure class="hero-mockup__cell">
            <img src="{{ \App\Support\Site::image($slide['left']) }}" alt="{{ $slide['leftAlt'] }}" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
          </figure>
          <figure class="hero-mockup__cell hero-mockup__cell--feature">
            <img src="{{ \App\Support\Site::image($slide['feature']) }}" alt="{{ $slide['featureAlt'] }}" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
          </figure>
          <figure class="hero-mockup__cell">
            <img src="{{ \App\Support\Site::image($slide['right']) }}" alt="{{ $slide['rightAlt'] }}" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
          </figure>
        </div>
      </div>
      <div class="hero-slide__content">
        <div class="hero-slide__left">
          <span class="hero-slide__tag">{{ $slide['tag'] }}</span>
          <h2 class="hero-slide__title">{!! $slide['title'] !!}</h2>
          <p class="hero-slide__subtitle">Fujeira Retro Series</p>
          <div class="hero-slide__divider"></div>
          <p class="hero-slide__desc">{{ $slide['desc'] }}</p>
        </div>
        <div class="hero-slide__right">
          <a href="{{ route('producto.show', $slide['codigo']) }}" class="hero-slide__cta">Ver modelo →</a>
        </div>
      </div>
    </div>
  @endforeach
</header>

<section id="destacados" class="section-destacados pt-4 pb-5">
  <div class="container">
    <h2 class="section-title text-center mb-2">Destacados</h2>
    <p class="home-kicker text-center mb-4">Fujeira Retro Series</p>
    <form class="catalog-search mb-4 d-flex justify-content-center" action="{{ route('catalogo') }}" method="get" role="search">
      <div class="search-field">
        <label class="visually-hidden" for="searchInput">Buscar en la tienda</label>
        <div class="input-group">
          <span class="input-group-text bg-dark text-light border-secondary"><i class="bi bi-search"></i></span>
          <input type="search" id="searchInput" name="q" class="form-control bg-dark text-light border-secondary" placeholder="Buscar remera, cuadro, diseño..." autocomplete="off">
        </div>
        <div id="searchLive" class="search-live" hidden></div>
      </div>
    </form>
    <script type="application/json" id="catalogIndex">{!! json_encode($indice, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    <div class="row g-4 mb-4">
      @foreach ($remeras as $producto)
        <div class="col-6 col-md-3">@include('partials.product-card', ['producto' => $producto, 'cardClass' => 'featured-card'])</div>
      @endforeach
    </div>
    <div class="text-center mt-3">
      <a href="{{ route('catalogo', ['categoria' => 'Fujeira Retro Series']) }}" class="cta-fujeira">Ver Retro Series</a>
    </div>
  </div>
</section>

<section class="section-featured-cuadros py-5">
  <div class="container">
    <h2 class="section-title text-light mb-4">Cuadros destacados</h2>
    <div class="row g-4">
      @foreach ($cuadros as $producto)
        <div class="col-6 col-md-3">@include('partials.product-card', ['producto' => $producto, 'cardClass' => 'cuadro-card'])</div>
      @endforeach
    </div>
  </div>
</section>

<section id="como-funciona" class="section-how">
  <div class="container">
    <div class="howto-header"><h2 class="howto-title">Cómo funciona</h2></div>
    <div class="howto-grid">
      <div class="howto-step"><span class="step-number">01</span><h3 class="step-title">Elegís la camiseta</h3><p class="step-caption">No cualquiera. La que te encuentra.</p></div>
      <div class="howto-step"><span class="step-number">02</span><h3 class="step-title">La sumás al carrito</h3><p class="step-caption">Abonás por Mercado Pago.</p></div>
      <div class="howto-step"><span class="step-number">03</span><h3 class="step-title">Nos WhatsAppeamos</h3><p class="step-caption">Confirmamos los detalles de tu pedido.</p></div>
      <div class="howto-step"><span class="step-number">04</span><h3 class="step-title">Llega tu Fujeira</h3><p class="step-caption">Recibís tu pedido con el sello Retro Series.</p></div>
    </div>
  </div>
</section>

<section class="instagram-strip">
  <div class="container">
    <div class="fj-pill">Comunidad FUJEIRA</div>
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
      <h2 class="section-title mb-0">Seguinos en Instagram</h2>
      <a href="https://instagram.com/fujeirastore" target="_blank" rel="noopener noreferrer" class="cta-fujeira d-inline-flex align-items-center gap-2">@fujeirastore</a>
    </div>
    <div class="insta-grid">
      @foreach ([
        ['assets/img/ig1.png', 'Post de FUJEIRA: remera Retro Series Diablos Rojos'],
        ['assets/img/ig2.png', 'Post de FUJEIRA: detalle de diseño Retro Series'],
        ['assets/img/ig3.png', 'Post de FUJEIRA: comunidad de hinchas con sus remeras'],
        ['assets/img/ig4.png', 'Post de FUJEIRA: nueva colección de cuadros'],
        ['assets/img/ig5.png', 'Post de FUJEIRA: remera Retro Series Los Xeneizes'],
        ['assets/img/ig6.png', 'Post de FUJEIRA: detrás de escena del proceso de diseño'],
      ] as [$foto, $alt])
        <div class="insta-item">
          <a class="insta-link" href="https://instagram.com/fujeirastore" target="_blank" rel="noopener noreferrer" aria-label="{{ $alt }}">
            <img src="{{ \App\Support\Site::image($foto) }}" alt="{{ $alt }}" width="600" height="600" loading="lazy" decoding="async">
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
