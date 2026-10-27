@extends('layouts.store')

@section('title', 'Pago | FUJEIRA')
@section('description', 'Estado del pago en FUJEIRA. El envío se confirma por WhatsApp.')
@section('canonical', \App\Support\Site::url('/pago/'.$estado))

@section('content')
<section class="py-5">
  <div class="container text-center">
    @if ($estado === 'exitoso')
      <h1 class="section-title">Pago recibido</h1>
      <p>Mercado Pago registró el pago. Te vamos a escribir para coordinar el envío.</p>
    @elseif ($estado === 'pendiente')
      <h1 class="section-title">Pago pendiente</h1>
      <p>El pago quedó pendiente de acreditación. Cuando se confirme, seguimos el pedido.</p>
    @else
      <h1 class="section-title">El pago no se completó</h1>
      <p>Podés volver al carrito e intentarlo de nuevo.</p>
    @endif

    @if ($pedido)
      <p>Pedido <strong>{{ $pedido->numero }}</strong> — estado {{ str_replace('_', ' ', $pedido->estado) }}.</p>
      <p>Total ${{ number_format($pedido->total, 0, ',', '.') }}</p>
    @endif

    <a class="cta-fujeira" href="{{ route('catalogo') }}">Volver al catálogo</a>
  </div>
</section>
@endsection
