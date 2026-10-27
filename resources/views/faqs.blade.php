@extends('layouts.store')

@section('title', 'Preguntas frecuentes | FUJEIRA')
@section('body-class', 'faqs-page')

@section('content')
<section class="section-faqs">
  <div class="container">
    <h1 class="section-title text-center mb-2">Preguntas frecuentes</h1>
    <p class="text-center text-muted mb-5">Todo lo que necesitás saber antes de elegir tu próxima retro.</p>
    <div class="accordion" id="faqList">
      <div class="accordion-item">
        <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">¿Qué voy a encontrar en FUJEIRA?</button></h2>
        <div id="faq1" class="accordion-collapse collapse show"><div class="accordion-body">Dos líneas: <strong>Fujeira Retro Series</strong>, remeras de diseño propio, y <strong>cuadros</strong> de arte para el espacio del hincha.</div></div>
      </div>
      <div class="accordion-item mt-3">
        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">¿El catálogo muestra el stock?</button></h2>
        <div id="faq2" class="accordion-collapse collapse"><div class="accordion-body">Sí. En cada remera se habilitan solo los talles con unidades. La confirmación final del pedido se coordina antes del despacho.</div></div>
      </div>
      <div class="accordion-item mt-3">
        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">¿Cómo es la compra y el envío?</button></h2>
        <div id="faq3" class="accordion-collapse collapse"><div class="accordion-body">Elegís el producto, lo sumás al carrito, completás tus datos y pagás con Mercado Pago. Enviamos a CABA, Gran Buenos Aires y Provincia de Buenos Aires. El envío lo paga el comprador y suele demorar entre 5 y 7 días hábiles.</div></div>
      </div>
      <div class="accordion-item mt-3">
        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">¿Puedo cambiar una camiseta?</button></h2>
        <div id="faq4" class="accordion-collapse collapse"><div class="accordion-body">Sí, si está en las mismas condiciones en que fue entregada. El cambio de talle o modelo se coordina por WhatsApp.</div></div>
      </div>
    </div>
  </div>
</section>
@endsection
