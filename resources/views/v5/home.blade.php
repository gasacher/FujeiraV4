@extends('v5.layout')

@section('title', 'Vista previa V5 | FUJEIRA')
@section('description', 'Vista previa del catálogo de camisetas importadas. '.$total.' modelos para revisar, sin cobro.')

@section('content')
<section class="v5-hero">
  <div class="container">
    <p class="v5-kicker">Catálogo importadas</p>
    <h1 class="section-title">Fujeira V5</h1>
    <p class="v5-lead">{{ $total }} camisetas para mirar de cerca: foto, club, región y versión. Esta pantalla no arma un pago.</p>
    <div class="d-flex flex-wrap gap-2 mt-4">
      <a class="cta-fujeira" href="{{ \App\Support\V5Urls::to('catalogo') }}">Ver el catálogo</a>
      @foreach ($versiones as $clave => $etiqueta)
        <a class="btn btn-sm btn-outline-light" href="{{ \App\Support\V5Urls::catalogo(['version' => $clave]) }}">{{ $etiqueta }}</a>
      @endforeach
    </div>
  </div>
</section>

<section class="pb-4">
  <div class="container">
    <h2 class="section-title h3">Por región</h2>
    <div class="row g-3">
      @foreach ($regiones as $fila)
        <div class="col-6 col-md-4 col-lg-2">
          <a class="v5-region-card" href="{{ \App\Support\V5Urls::catalogo(['region' => $fila['region']]) }}">
            <span class="v5-region-card__total">{{ $fila['total'] }}</span>
            <span class="v5-region-card__name">{{ $fila['region'] }}</span>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="pb-5">
  <div class="container">
    <h2 class="section-title h3">Para empezar a mirar</h2>
    <div class="row g-4">
      @foreach ($destacados as $producto)
        <div class="col-6 col-md-4 col-lg-3">@include('v5.partials.card', ['producto' => $producto])</div>
      @endforeach
    </div>
  </div>
</section>
@endsection
