@extends('layouts.store')

@section('body-class', 'home-page')

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
      'left' => 'assets/catalogo/retro_series/los%20xeneixes/boca01.png',
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
      'feature' => 'assets/catalogo/retro_series/los%20xeneixes/boca01.png',
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
      'right' => 'assets/catalogo/retro_series/los%20xeneixes/boca01.png',
      'rightAlt' => 'Remera Fujeira Retro Series Los Xeneixes',
      'eager' => false,
    ],
  ];
@endphp

<header id="hero" class="hero-photo">
  <h1 class="visually-hidden">FUJEIRA: remeras de diseño propio, cuadros y arte futbolero</h1>
  @foreach ($slides as $slide)
    <div class="hero-slide hero-slide--{{ $slide['modifier'] }}">
      <div class="hero-slide__media">
        <img
          class="hero-slide__backdrop"
          src="{{ asset($slide['feature']) }}"
          alt=""
          width="1200"
          height="1600"
          @if ($slide['eager']) fetchpriority="high" @endif
          loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}"
          decoding="async"
        >
        <div class="hero-slide__mockup" aria-hidden="true">
          <figure class="hero-mockup__cell">
            <img src="{{ asset($slide['left']) }}" alt="{{ $slide['leftAlt'] }}" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
          </figure>
          <figure class="hero-mockup__cell hero-mockup__cell--feature">
            <img src="{{ asset($slide['feature']) }}" alt="{{ $slide['featureAlt'] }}" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
          </figure>
          <figure class="hero-mockup__cell">
            <img src="{{ asset($slide['right']) }}" alt="{{ $slide['rightAlt'] }}" width="1200" height="1600" loading="{{ $slide['eager'] ? 'eager' : 'lazy' }}" decoding="async">
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
    <p class="text-center text-secondary mb-4">Fujeira Retro Series</p>
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
@endsection
