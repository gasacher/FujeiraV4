@extends('layouts.store')

@section('title', 'Catálogo | FUJEIRA')
@section('description', 'Catálogo FUJEIRA: remeras Fujeira Retro Series y cuadros de arte futbolero. Buscá por nombre, diseño o código.')
@section('canonical', \App\Support\Site::url('/catalogo'))
@section('body-class', 'catalog-page')

@php
  $migas = [
      '@context' => 'https://schema.org',
      '@type' => 'BreadcrumbList',
      'itemListElement' => [
          ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => \App\Support\Site::url('/')],
          ['@type' => 'ListItem', 'position' => 2, 'name' => 'Catálogo', 'item' => \App\Support\Site::url('/catalogo')],
      ],
  ];
@endphp

@section('content')
<section class="py-5">
  <div class="container">
    <h1 class="section-title text-center mb-4">Catálogo</h1>
    <form class="catalog-search mb-4 d-flex justify-content-center" action="{{ route('catalogo') }}" method="get" role="search">
      @if ($categoria !== '')
        <input type="hidden" name="categoria" value="{{ $categoria }}">
      @endif
      <div class="search-field">
        <label class="visually-hidden" for="searchInput">Buscar en el catálogo</label>
        <div class="input-group">
          <span class="input-group-text bg-dark text-light border-secondary"><i class="bi bi-search"></i></span>
          <input type="search" id="searchInput" name="q" value="{{ $q }}" class="form-control bg-dark text-light border-secondary" placeholder="Buscar remera, cuadro, diseño..." autocomplete="off">
        </div>
      </div>
    </form>
    <div class="catalog-filters d-flex flex-wrap justify-content-center gap-2 mb-4">
      <a class="btn btn-sm btn-outline-light {{ $categoria === '' ? 'active' : '' }}" href="{{ route('catalogo') }}">Todos</a>
      <a class="btn btn-sm btn-outline-light btn-filter-retro-series {{ $categoria === 'Fujeira Retro Series' ? 'active' : '' }}" href="{{ route('catalogo', ['categoria' => 'Fujeira Retro Series']) }}">Retro Series</a>
      <a class="btn btn-sm btn-outline-light {{ $categoria === 'cuadros' ? 'active' : '' }}" href="{{ route('catalogo', ['categoria' => 'cuadros']) }}">Cuadros</a>
    </div>
    @if ($q !== '')
      <p class="text-center mb-4"><a href="{{ route('catalogo', $categoria !== '' ? ['categoria' => $categoria] : []) }}">Ver todo el catálogo</a></p>
    @endif
    <div class="row g-4" id="productGrid">
      @forelse ($productos as $producto)
        <div class="col-6 col-md-4 col-lg-3 js-product-col">@include('partials.product-card', ['producto' => $producto])</div>
      @empty
        <p class="text-center">No hay productos en esta categoría.</p>
      @endforelse
    </div>
    <p id="catalogEmpty" class="text-center d-none">No se encontraron productos.</p>
  </div>
</section>
<script type="application/ld+json">{!! json_encode($migas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection
