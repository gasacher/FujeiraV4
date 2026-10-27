@extends('v5.layout')

@section('title', $producto->nombre.' | Vista previa FUJEIRA')
@section('description', $producto->nombre.'. Vista previa del catálogo nuevo, sin precio y sin compra.')
@section('body-class', 'product-page')

@section('content')
<section class="py-5">
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
        <div class="product-gallery">
          <div class="product-gallery__hero">
            <img id="mainProductImg" src="{{ $producto->fotoPublica() }}" alt="{{ $producto->nombre }}" class="product-main-img" width="800" height="1000" fetchpriority="high" decoding="async">
          </div>
        </div>
      </div>
      <div class="col-md-6 text-light">
        <p class="catalog-product-category">{{ $producto->categoria }}</p>
        <h1 class="product-title">{{ $producto->nombre }}</h1>
        <p class="product-code">Código {{ $producto->codigo }}</p>
        @if ($producto->archivo !== '')
          <p class="small mb-3">Archivo en el Drive: {{ $producto->archivo }}</p>
        @endif
        <p class="product-desc">Foto de la muestra del catálogo nuevo. El nombre sale del archivo, sin el prefijo de WhatsApp ni el código suelto.</p>
        <div class="mt-3">
          <p class="fw-bold mb-1">Talle</p>
          <p class="mb-0">a definir</p>
        </div>
        <div class="mt-3">
          <p class="fw-bold mb-1">Precio</p>
          <p class="mb-0">a definir</p>
        </div>
        <p class="cta-fujeira-outline v5-aviso__estado mt-4" role="status">Vista previa, sin compra</p>
      </div>
    </div>

    <div class="related-section mt-5">
      <h2 class="text-light mb-3">También en {{ $producto->categoria }}</h2>
      <div class="row g-4">
        @forelse ($relacionados as $relacionado)
          <div class="col-6 col-md-3">@include('v5.partials.card', ['producto' => $relacionado])</div>
        @empty
          <p class="text-light">No hay más fotos de esta muestra en la categoría.</p>
        @endforelse
      </div>
    </div>
  </div>
</section>
@endsection
