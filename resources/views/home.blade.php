@extends('layouts.store')

@section('body-class', 'home-page')

@section('content')
<header class="hero-photo">
  <h1 class="visually-hidden">FUJEIRA: remeras de diseño propio, cuadros y arte futbolero</h1>
  <div class="hero-slide hero-slide--diablos">
    <div class="hero-slide__media">
      <img class="hero-slide__backdrop" src="{{ asset('assets/catalogo/retro_series/diablos_rojos/diablos_rojos02.png') }}" alt="" width="1200" height="1600">
    </div>
  </div>
</header>

<section class="section-destacados pt-4 pb-5">
  <div class="container">
    <h2 class="section-title text-center mb-2">Destacados</h2>
    <p class="text-center text-secondary mb-4">Fujeira Retro Series y cuadros</p>
    <div class="row g-4">
      @foreach ($destacados as $producto)
        <div class="col-6 col-md-3">@include('partials.product-card', ['producto' => $producto])</div>
      @endforeach
    </div>
    <div class="text-center mt-4">
      <a href="{{ route('catalogo', ['categoria' => 'Fujeira Retro Series']) }}" class="cta-fujeira">Ver Retro Series</a>
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
