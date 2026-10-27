@extends('v5.layout')

@section('title', 'Vista previa V5 | FUJEIRA')

@section('content')
<section class="py-5">
  <div class="container">
    <h1 class="section-title">Esta dirección no está en la vista previa</h1>
    <p class="v5-lead">En V5 se miran las camisetas. No hay carrito ni cobro con Mercado Pago.</p>
    <a class="cta-fujeira" href="{{ \App\Support\V5Urls::to('catalogo') }}">Volver al catálogo</a>
  </div>
</section>
@endsection
