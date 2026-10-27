@extends('layouts.store')

@section('title', 'Nosotros | FUJEIRA')
@section('body-class', 'inner-page nosotros-page')

@section('content')
<section class="py-5">
  <div class="container container-nosotros">
    <header class="nosotros-hero">
      <div class="nosotros-hero__panel">
        <h1 class="section-title nosotros-hero__title text-center">Nosotros</h1>
        <p class="nosotros-hero__lead text-center mb-3">Diseños que nacen de nosotros.</p>
        <p class="nosotros-hero__tagline text-center mb-0 mx-auto">El apodo. La fecha. La mística que nunca se estampó.</p>
      </div>
    </header>
    <div class="row g-4 mt-4 align-items-center">
      <div class="col-md-6">
        <img src="{{ asset('assets/img/retroseries_serie01.png') }}" alt="Fujeira Retro Series" class="img-fluid">
      </div>
      <div class="col-md-6">
        <p>FUJEIRA es una marca de remeras de diseño propio y cuadros de arte futbolero. La línea Retro Series toma apodos, fechas y colores de la tribuna y los convierte en prendas de edición limitada.</p>
        <p>Los cuadros son para el espacio del hincha: piezas para colgar, con marco a elección.</p>
        <a class="cta-fujeira" href="{{ route('catalogo') }}">Ver el catálogo</a>
      </div>
    </div>
  </div>
</section>
@endsection
