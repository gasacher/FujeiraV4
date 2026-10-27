@extends('layouts.store')

@section('title', 'Checkout | FUJEIRA')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-7">
        <h1 class="section-title mb-4">Tus datos</h1>
        <form method="POST" action="{{ route('checkout.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="nombre">Nombre</label>
              <input class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="apellido">Apellido</label>
              <input class="form-control" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="email">Email</label>
              <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="telefono">Teléfono</label>
              <input class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
            </div>
            <div class="col-12">
              <label class="form-label" for="direccion">Dirección de envío</label>
              <input class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
            </div>
          </div>
          <button class="cta-fujeira mt-4" type="submit">Continuar a Mercado Pago</button>
        </form>
      </div>
      <div class="col-md-5">
        <h2 class="h4">Pedido</h2>
        <ul class="list-unstyled">
          @foreach ($lineas as $linea)
            <li class="mb-2">{{ $linea['cantidad'] }} × {{ $linea['product']->nombre }} — ${{ number_format($linea['subtotal'], 0, ',', '.') }}</li>
          @endforeach
        </ul>
        <p class="fs-4">Total: ${{ number_format($total, 0, ',', '.') }}</p>
        <p class="small text-muted">El precio lo define el catálogo del servidor. El envío se confirma por WhatsApp.</p>
      </div>
    </div>
  </div>
</section>
@endsection
