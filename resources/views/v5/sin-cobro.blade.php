@extends('v5.layout')

@section('title', 'Vista previa, sin compra | FUJEIRA')
@section('body-class', 'catalog-page')

@section('content')
<section class="py-5">
  <div class="container">
    <h1 class="section-title">Vista previa, sin compra</h1>
    <p>En este subdominio se miran las fotos del catálogo nuevo. No hay carrito ni Mercado Pago, así que desde acá no se cobra.</p>
    <a class="cta-fujeira" href="{{ \App\Support\V5Urls::to('catalogo') }}">Volver al catálogo</a>
  </div>
</section>
@endsection
