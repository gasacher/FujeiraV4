@extends('layouts.store')

@section('title', 'Preguntas frecuentes | FUJEIRA')
@section('description', 'Preguntas frecuentes de FUJEIRA: qué hay en el catálogo, cómo se ve el stock, cómo es el pago con Mercado Pago y el envío, y cómo cambiar una camiseta.')
@section('canonical', \App\Support\Site::url('/faqs'))
@section('body-class', 'faqs-page')

@php
  $faqs = [
      ['q' => '¿Qué voy a encontrar en FUJEIRA?', 'a' => 'Dos líneas: Fujeira Retro Series, remeras de diseño propio, y cuadros de arte para el espacio del hincha.'],
      ['q' => '¿El catálogo muestra el stock?', 'a' => 'Sí. En cada remera se habilitan solo los talles con unidades. La confirmación final del pedido se coordina antes del despacho.'],
      ['q' => '¿Cómo es la compra y el envío?', 'a' => 'Elegís el producto, lo sumás al carrito, completás tus datos y pagás con Mercado Pago. Enviamos a CABA, Gran Buenos Aires y Provincia de Buenos Aires. El envío lo paga el comprador y suele demorar entre 5 y 7 días hábiles.'],
      ['q' => '¿Puedo cambiar una camiseta?', 'a' => 'Sí, si está en las mismas condiciones en que fue entregada. El cambio de talle o modelo se coordina por WhatsApp.'],
  ];
  $faqLd = [
      '@context' => 'https://schema.org',
      '@type' => 'FAQPage',
      'mainEntity' => array_map(fn ($faq) => [
          '@type' => 'Question',
          'name' => $faq['q'],
          'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
      ], $faqs),
  ];
@endphp

@section('content')
<section class="section-faqs">
  <div class="container">
    <h1 class="section-title text-center mb-2">Preguntas frecuentes</h1>
    <p class="text-center mb-5">Todo lo que necesitás saber antes de elegir tu próxima retro.</p>
    <div class="accordion" id="faqList">
      @foreach ($faqs as $index => $faq)
        <div class="accordion-item {{ $index > 0 ? 'mt-3' : '' }}">
          <h2 class="accordion-header">
            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index + 1 }}">{{ $faq['q'] }}</button>
          </h2>
          <div id="faq{{ $index + 1 }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}">
            <div class="accordion-body">{{ $faq['a'] }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
<script type="application/ld+json">{!! json_encode($faqLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection
