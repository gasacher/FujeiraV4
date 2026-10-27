@extends('layouts.store')

@section('title', 'Carrito | FUJEIRA')

@section('content')
<section class="py-5">
  <div class="container">
    <h1 class="section-title mb-4">Tu carrito</h1>
    @if ($lineas === [])
      <p>Tu carrito está vacío.</p>
      <a class="cta-fujeira" href="{{ route('catalogo') }}">Ver catálogo</a>
    @else
      @foreach ($lineas as $index => $linea)
        <div class="cart-item d-flex gap-3 align-items-center mb-3">
          <img src="{{ $linea['product']->fotoPublica() }}" alt="" width="72" class="cart-item-img">
          <div class="flex-grow-1">
            <p class="cart-item-name mb-1">{{ $linea['product']->nombre }}</p>
            <p class="mb-1">
              @if ($linea['marco']) Marco: {{ $linea['marco'] }} @elseif ($linea['talle']) Talle: {{ $linea['talle'] }} @endif
            </p>
            <p class="mb-0">${{ number_format($linea['subtotal'], 0, ',', '.') }}</p>
          </div>
          <form method="POST" action="{{ route('carrito.update', $index) }}" class="d-flex gap-2">
            @csrf
            @method('PATCH')
            <input class="form-control" style="width:5rem" type="number" name="cantidad" min="0" max="10" value="{{ $linea['cantidad'] }}">
            <button class="btn btn-outline-light" type="submit">Actualizar</button>
          </form>
          <form method="POST" action="{{ route('carrito.remove', $index) }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger" type="submit">Quitar</button>
          </form>
        </div>
      @endforeach
      <p class="fs-4 text-end">Total: ${{ number_format($total, 0, ',', '.') }}</p>
      <div class="text-end">
        <a class="cta-fujeira" href="{{ route('checkout.show') }}">Pagar con Mercado Pago</a>
      </div>
    @endif
  </div>
</section>
@endsection
