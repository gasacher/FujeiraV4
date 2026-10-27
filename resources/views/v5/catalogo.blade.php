@extends('v5.layout')

@section('title', 'Catálogo V5 | FUJEIRA')
@section('description', 'Vista previa del catálogo V5. Buscá y filtrá por club, región y versión.')

@section('content')
<section class="py-4">
  <div class="container">
    <h1 class="section-title">Catálogo</h1>
    <p class="v5-lead">{{ $total }} {{ $total === 1 ? 'camiseta' : 'camisetas' }}</p>

    <form class="v5-filters" action="{{ \App\Support\V5Urls::to('catalogo') }}" method="get" role="search">
      <div class="row g-2 align-items-end">
        <div class="col-lg-4">
          <label class="form-label" for="v5-q">Buscar</label>
          <input class="form-control bg-dark text-light border-secondary" id="v5-q" type="search" name="q" value="{{ $filtros['q'] }}" placeholder="Club, nombre o código" autocomplete="off">
        </div>
        <div class="col-md-6 col-lg-3">
          <label class="form-label" for="v5-club">Club</label>
          <select class="form-select bg-dark text-light border-secondary" id="v5-club" name="club">
            <option value="">Todos</option>
            @foreach ($clubes as $club)
              <option value="{{ $club }}" @selected($filtros['club'] === $club)>{{ $club }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 col-lg-3">
          <label class="form-label" for="v5-region">Región</label>
          <select class="form-select bg-dark text-light border-secondary" id="v5-region" name="region">
            <option value="">Todas</option>
            @foreach ($regiones as $region)
              <option value="{{ $region }}" @selected($filtros['region'] === $region)>{{ $region }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-2">
          <button class="cta-fujeira w-100" type="submit">Filtrar</button>
        </div>
      </div>
      @if ($filtros['version'] !== '')
        <input type="hidden" name="version" value="{{ $filtros['version'] }}">
      @endif
    </form>

    <div class="v5-version-row" role="group" aria-label="Versión">
      <a class="v5-chip {{ $filtros['version'] === '' ? 'is-active' : '' }}" href="{{ \App\Support\V5Urls::catalogo(array_merge($filtros, ['version' => ''])) }}">Todas</a>
      @foreach ($versiones as $clave => $etiqueta)
        <a class="v5-chip {{ $filtros['version'] === $clave ? 'is-active' : '' }}" href="{{ \App\Support\V5Urls::catalogo(array_merge($filtros, ['version' => $clave])) }}">{{ $etiqueta }}</a>
      @endforeach
    </div>

    <div class="row g-4">
      @forelse ($productos as $producto)
        <div class="col-6 col-md-4 col-lg-3">@include('v5.partials.card', ['producto' => $producto])</div>
      @empty
        <p class="text-center mt-4">No hay camisetas con esos filtros.</p>
      @endforelse
    </div>

    @include('v5.partials.pagination', ['productos' => $productos])
  </div>
</section>
@endsection
