@extends('v5.layout')

@section('title', 'Catálogo v5 | FUJEIRA')
@section('description', 'Muestra del catálogo nuevo: Jugador, Retro y Fan. Sin precio y sin compra.')
@section('body-class', 'catalog-page')

@section('content')
<section class="py-5">
  <div class="container">
    <h1 class="section-title text-center mb-4">Catálogo nuevo</h1>
    <form class="catalog-search mb-4 d-flex justify-content-center" action="{{ \App\Support\V5Urls::to('catalogo') }}" method="get" role="search">
      @if ($categoria !== '')
        <input type="hidden" name="categoria" value="{{ $categoria }}">
      @endif
      <div class="search-field">
        <label class="visually-hidden" for="searchInput">Buscar en la muestra</label>
        <div class="input-group">
          <span class="input-group-text bg-dark text-light border-secondary"><i class="bi bi-search"></i></span>
          <input type="search" id="searchInput" name="q" value="{{ $q }}" class="form-control bg-dark text-light border-secondary" placeholder="Buscar por nombre o código" autocomplete="off">
        </div>
      </div>
    </form>
    <div class="catalog-filters d-flex flex-wrap justify-content-center gap-2 mb-4">
      <a class="btn btn-sm btn-outline-light {{ $categoria === '' ? 'active' : '' }}" href="{{ \App\Support\V5Urls::to('catalogo') }}">Todos</a>
      @foreach ($categorias as $opcion)
        <a class="btn btn-sm btn-outline-light {{ $categoria === $opcion ? 'active' : '' }}" href="{{ \App\Support\V5Urls::catalogo(['categoria' => $opcion]) }}">{{ $opcion }}</a>
      @endforeach
    </div>
    <div class="row g-4" id="productGrid">
      @forelse ($productos as $producto)
        <div class="col-6 col-md-4 col-lg-3 js-product-col">@include('v5.partials.card', ['producto' => $producto])</div>
      @empty
        <p class="text-center">No hay fotos de esta muestra para esa búsqueda.</p>
      @endforelse
    </div>
  </div>
</section>
@endsection
