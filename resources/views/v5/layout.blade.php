<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title>@yield('title', 'Vista previa | FUJEIRA')</title>
  <meta name="description" content="@yield('description', 'Vista previa del catálogo nuevo de FUJEIRA. Sin precio y sin compra.')">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-site.png') }}">
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@300;400;500;700&family=League+Spartan:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}?v=v5preview">
</head>
<body class="@yield('body-class')">
  <a class="skip-to-content" href="#contenido-principal">Saltar al contenido</a>

  <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-fujeira">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ \App\Support\V5Urls::to('home') }}">
        <img src="{{ \App\Support\Site::image('assets/img/Logolima_transparente.png') }}" class="logo-fujeira" alt="FUJEIRA Logo" width="64" height="64">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#v5Nav" aria-controls="v5Nav" aria-expanded="false" aria-label="Abrir menú">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="v5Nav">
        <ul class="navbar-nav align-items-lg-center gap-lg-3">
          <li class="nav-item"><a class="nav-link" href="{{ \App\Support\V5Urls::to('home') }}">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ \App\Support\V5Urls::to('catalogo') }}">Catálogo</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ \App\Support\V5Urls::tiendaActual() }}">Tienda actual</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main id="contenido-principal" class="pt-5">
    @php
      $trozos = [];
      foreach (['Jugador', 'Retro', 'Fan'] as $categoriaAviso) {
          $visibles = $v5Resumen['en_vista'][$categoriaAviso] ?? 0;
          $total = $v5Resumen['totales'][$categoriaAviso] ?? $visibles;
          $trozos[] = $visibles.' de '.$total.' en '.$categoriaAviso;
      }
      $ultimo = array_pop($trozos);
      $listaMuestra = $trozos === [] ? (string) $ultimo : implode(', ', $trozos).' y '.$ultimo;
    @endphp
    <div class="container pt-3">
      <div class="v5-aviso p-3" role="status">
        <strong>Vista previa, sin compra.</strong>
        Estás viendo una muestra: {{ $listaMuestra }}. No es la carpeta completa del Drive y desde acá no se puede pagar.
        @if (! empty($v5Resumen['origen']))
          <a href="{{ $v5Resumen['origen'] }}">Abrir la carpeta</a>.
        @endif
        <a href="{{ \App\Support\V5Urls::tiendaActual() }}">Volver a la tienda actual</a>.
      </div>
    </div>
    @yield('content')
  </main>

  <footer class="footer-fujeira mt-5">
    <div class="container py-4">
      <div class="row g-4 align-items-center">
        <div class="col-md-4 text-center text-md-start">
          <img src="{{ \App\Support\Site::image('assets/img/Logo_negro.png') }}" class="footer-logo mb-2" alt="FUJEIRA Logo" width="64" height="64" loading="lazy" decoding="async">
          <p class="footer-claim small mt-2 mb-0">Vista previa del catálogo nuevo. La tienda actual sigue en su lugar.</p>
        </div>
        <div class="col-md-8 text-center text-md-end">
          <a href="{{ \App\Support\V5Urls::to('catalogo') }}" class="footer-link">Catálogo v5</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/tienda.js') }}?v=tienda2"></script>
</body>
</html>
