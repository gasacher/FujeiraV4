@extends('layouts.store')

@section('title', 'Contacto | FUJEIRA')
@section('description', 'Escribinos a FUJEIRA por WhatsApp o a fujeiraretrostore@gmail.com.')
@section('canonical', \App\Support\Site::url('/contacto'))
@section('body-class', 'contact-page')

@section('content')
<section class="section-contact py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7 text-center">
        <h1 class="section-title mb-3">Contacto</h1>
        <p class="text-muted mb-4 fs-5">¿Querés comunicarte con nosotros?</p>
        <a href="https://wa.me/5491130124589" target="_blank" rel="noopener noreferrer" class="cta-fujeira mb-4 d-inline-flex align-items-center gap-2">
          <i class="bi bi-whatsapp fs-5"></i> Escribinos por WhatsApp
        </a>
        <hr class="my-5">
        <p class="mb-2"><strong>¿Preferís escribir por mail?</strong></p>
        <p><a class="contact-mail" href="mailto:fujeiraretrostore@gmail.com">fujeiraretrostore@gmail.com</a></p>
      </div>
    </div>
  </div>
</section>
@endsection
