<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'FUJEIRA | Remeras de diseño propio, cuadros y arte futbolero')</title>
  <meta name="description" content="@yield('description', 'FUJEIRA: remeras de diseño propio, cuadros y arte futbolero. Fujeira Retro Series y envíos en Argentina.')">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon_fujeira.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@300;400;500;700&family=League+Spartan:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}?v=laravel1">
</head>
<body class="@yield('body-class')">
  <a class="skip-to-content" href="#contenido-principal">Saltar al contenido</a>

  <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-fujeira">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
        <img src="{{ asset('assets/img/Logolima_transparente.png') }}" class="logo-fujeira" alt="FUJEIRA Logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="mainNav">
        <ul class="navbar-nav align-items-lg-center gap-lg-3">
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('catalogo') }}">Catálogo</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('nosotros') }}">Nosotros</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('faqs') }}">FAQs</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('contacto') }}">Contacto</a></li>
          <li class="nav-item ms-lg-2">
            <a class="cta-fujeira-outline" href="{{ route('carrito.show') }}">
              Carrito <span class="badge bg-warning text-dark">{{ $cartCount }}</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main id="contenido-principal" class="pt-5">
    @if ($errors->any())
      <div class="container mt-4">
        <div class="alert alert-warning" role="alert">{{ $errors->first() }}</div>
      </div>
    @endif
    @if (session('ok'))
      <div class="container mt-4">
        <div class="alert alert-success" role="alert">{{ session('ok') }}</div>
      </div>
    @endif
    @yield('content')
  </main>

  <footer class="footer-fujeira mt-5">
    <div class="container py-4">
      <div class="row g-4 align-items-center">
        <div class="col-md-3 text-center text-md-start">
          <img src="{{ asset('assets/img/Logo_negro.png') }}" class="footer-logo mb-2" alt="FUJEIRA Logo">
          <p class="footer-claim small mt-2 mb-0">“FUJEIRA. Como lo dijo el Diego.<br>Si así lo dijo, así queda.”</p>
        </div>
        <div class="col-md-6 text-center">
          <nav class="footer-links-line d-flex justify-content-center align-items-center gap-4 flex-wrap">
            <a href="{{ route('catalogo') }}" class="footer-link">Catálogo</a>
            <a href="{{ route('faqs') }}" class="footer-link">Preguntas frecuentes</a>
            <a href="{{ route('contacto') }}" class="footer-link">Contacto</a>
          </nav>
        </div>
        <div class="col-md-3 text-center">
          <p class="small mb-1">fujeiraretrostore@gmail.com</p>
          <div class="d-flex justify-content-center gap-3 mt-2">
            <a href="https://instagram.com/fujeirastore" target="_blank" rel="noopener noreferrer" class="footer-social"><i class="bi bi-instagram"></i></a>
            <a href="https://wa.me/5491130124589" target="_blank" rel="noopener noreferrer" class="footer-social"><i class="bi bi-whatsapp"></i></a>
          </div>
        </div>
      </div>
      <div class="footer-copy">© {{ date('Y') }} FUJEIRA Retro Store — Hecho con alma de potrero.</div>
    </div>
  </footer>

  <a href="https://wa.me/5491130124589" class="btn-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
    <i class="bi bi-whatsapp"></i>
  </a>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
