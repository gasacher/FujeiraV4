@extends('layouts.store')

@section('title', 'Catálogo | FUJEIRA')

@section('content')
<section class="py-5">
  <div class="container">
    <h1 class="section-title text-center mb-4">Catálogo</h1>
    <div class="catalog-filters d-flex flex-wrap justify-content-center gap-2 mb-4">
      <a class="btn btn-sm btn-outline-light" href="{{ route('catalogo') }}">Todos</a>
      <a class="btn btn-sm btn-outline-light btn-filter-retro-series" href="{{ route('catalogo', ['categoria' => 'Fujeira Retro Series']) }}">Retro Series</a>
      <a class="btn btn-sm btn-outline-light" href="{{ route('catalogo', ['categoria' => 'cuadros']) }}">Cuadros</a>
    </div>
    <div class="row g-4">
      @forelse ($productos as $producto)
        <div class="col-6 col-md-4 col-lg-3">@include('partials.product-card', ['producto' => $producto])</div>
      @empty
        <p class="text-center">No hay productos en esta categoría.</p>
      @endforelse
    </div>
  </div>
</section>
@endsection
