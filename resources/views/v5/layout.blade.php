<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title>@yield('title', 'Vista previa V5 | FUJEIRA')</title>
  <meta name="description" content="@yield('description', 'Vista previa del catálogo V5 de FUJEIRA. Desde acá no se cobra.')">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-site.png') }}">
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}?v=tienda2">
  <link rel="stylesheet" href="{{ asset('css/v5.css') }}?v=1">
</head>
<body class="v5-body">
  <a class="skip-to-content" href="#contenido-principal">Saltar al contenido</a>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-fujeira">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ \App\Support\V5Urls::to('home') }}">
        <img src="{{ \App\Support\Site::image('assets/img/Logolima_transparente.png') }}" class="logo-fujeira" alt="FUJEIRA" width="64" height="64">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#v5Nav" aria-controls="v5Nav" aria-expanded="false" aria-label="Abrir menú">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="v5Nav">
        <ul class="navbar-nav align-items-lg-center gap-lg-3">
          <li class="nav-item"><a class="nav-link" href="{{ \App\Support\V5Urls::to('home') }}">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ \App\Support\V5Urls::to('catalogo') }}">Catálogo</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main id="contenido-principal">
    <div class="v5-banner">Vista previa V5 · desde acá no se cobra</div>
    @yield('content')
  </main>

  <footer class="footer-fujeira mt-5">
    <div class="container py-4">
      <p class="mb-1">FUJEIRA V5 es una vista previa para mirar las camisetas.</p>
      @if (\App\Support\V5Host::matches())
        <p class="small mb-0">Este host muestra el catálogo nuevo. La tienda de fujeiraretrostore.com sigue como está.</p>
      @else
        <p class="small mb-0">Estás viendo /v5 dentro del proyecto. La tienda que está online no cambia con esta pantalla.</p>
      @endif
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
