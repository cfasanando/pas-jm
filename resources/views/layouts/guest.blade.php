<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Acceso · PAS JM</title>

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Estilos específicos del login -->
  <link href="{{ asset('css/pas-auth.css') }}" rel="stylesheet">
  <link href="{{ asset('css/pas-auth-extra.css') }}" rel="stylesheet">  {{-- ancho 1120px --}}

  {{-- Si quieres además cargar tu tema general: --}}
  {{-- <link href="{{ asset('css/pas-pro.css') }}" rel="stylesheet"> --}}
</head>
<body class="auth-bg d-flex align-items-center justify-content-center min-vh-100">
  <main class="container px-3">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8 col-xl-6">
        {{ $slot }}
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
