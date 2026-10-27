@extends('layouts.store')

@section('title', $producto->nombre.' | FUJEIRA')
@section('description', $producto->nombre.' — FUJEIRA. '.$producto->categoria.'. $'.number_format($producto->precio, 0, ',', '.').'.')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6">
        <img src="{{ $producto->fotoPublica() }}" alt="{{ $producto->nombre }}" class="img-fluid">
        <div class="d-flex gap-2 mt-3 flex-wrap">
          @foreach ($producto->fotos as $index => $foto)
            <img src="{{ $producto->fotoPublica($index) }}" alt="" width="84" height="112">
          @endforeach
        </div>
      </div>
      <div class="col-md-6">
        <p class="catalog-product-category">{{ $producto->categoria }}</p>
        <h1 class="section-title">{{ $producto->nombre }}</h1>
        <p class="fs-3">${{ number_format($producto->precio, 0, ',', '.') }}</p>
        <p class="text-muted">Código {{ $producto->codigo }}</p>

        @if ($producto->sinStock())
          <p>Esta remera no tiene stock en este momento.</p>
          <a class="cta-fujeira" target="_blank" rel="noopener noreferrer" href="https://wa.me/5491130124589?text={{ rawurlencode('Hola FUJEIRA! Me interesa '.$producto->nombre.' y vi que está sin stock.') }}">Avisame cuando vuelva</a>
        @else
          <form method="POST" action="{{ route('carrito.add') }}" class="mt-4">
            @csrf
            <input type="hidden" name="codigo" value="{{ $producto->codigo }}">
            @if ($producto->esRemera())
              <label class="form-label" for="talle">Talle</label>
              <select class="form-select mb-3" id="talle" name="talle" required>
                <option value="">Elegí un talle</option>
                @foreach ($producto->talles as $talle)
                  @php $stock = $producto->stockDe($talle); @endphp
                  <option value="{{ $talle }}" @disabled($stock < 1)>{{ $talle }}{{ $stock < 1 ? ' — sin stock' : '' }}</option>
                @endforeach
              </select>
            @elseif ($producto->marcos !== [])
              <label class="form-label" for="marco">Marco</label>
              <select class="form-select mb-3" id="marco" name="marco" required>
                <option value="">Elegí un marco</option>
                @foreach ($producto->marcos as $marco)
                  <option value="{{ $marco }}">{{ $marco }}</option>
                @endforeach
              </select>
            @endif
            <label class="form-label" for="cantidad">Cantidad</label>
            <input class="form-control mb-3" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1" required>
            <button class="cta-fujeira" type="submit">Agregar al carrito</button>
          </form>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
