<!doctype html>
<html lang="es">
<head>
  @php
    // Fallbacks seguros si aún no hay settings
    $brand = $brand ?? [
      'primary' => '#0d6efd',
      'name'    => config('app.name'),
      'logo'    => '',
    ];
    $primary = $brand['primary'] ?? '#0d6efd';
  @endphp

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $brand['name'] }} — @yield('title','Panel') </title>

  {{-- Bootstrap & icons (si ya los usas) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Tus CSS (opcional) --}}
  <link href="{{ asset('css/pas-pro.css') }}" rel="stylesheet">
  <link href="{{ asset('css/pas-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('css/pas-polish.css') }}" rel="stylesheet">
  <link href="{{ asset('css/pas-auth-extra.css') }}" rel="stylesheet">

  {{-- Assets de la app: Vite (Laravel 9+) ó Mix (Laravel 8) --}}
  @if (class_exists(\Illuminate\Foundation\Vite::class))
      @vite(['resources/css/app.css','resources/js/app.js'])
  @elseif (function_exists('mix')) {{-- fallback si usas Laravel Mix --}}
      <link rel="stylesheet" href="{{ mix('css/app.css') }}">
      <script src="{{ mix('js/app.js') }}" defer></script>
  @endif

  {{-- Tema dinámico desde settings --}}
<style>
:root{
  --jm-primary: {{ $brand['primary'] ?? '#0d6efd' }};
  --jm-primary-rgb:
    {{ sscanf($brand['primary'] ?? '#0d6efd', "#%02x%02x%02x")[0] ?? 13 }},
    {{ sscanf($brand['primary'] ?? '#0d6efd', "#%02x%02x%02x")[1] ?? 110 }},
    {{ sscanf($brand['primary'] ?? '#0d6efd', "#%02x%02x%02x")[2] ?? 253 }};
}

/* Botones BS 5.3 100% dinámicos */
.btn-primary{
  --bs-btn-color:#fff;
  --bs-btn-bg:var(--jm-primary);
  --bs-btn-border-color:var(--jm-primary);
  --bs-btn-hover-color:#fff;
  --bs-btn-hover-bg:color-mix(in srgb, var(--jm-primary) 90%, #000 10%);
  --bs-btn-hover-border-color:color-mix(in srgb, var(--jm-primary) 88%, #000 12%);
  --bs-btn-active-color:#fff;
  --bs-btn-active-bg:color-mix(in srgb, var(--jm-primary) 85%, #000 15%);
  --bs-btn-active-border-color:color-mix(in srgb, var(--jm-primary) 83%, #000 17%);
  --bs-btn-disabled-color:#fff;
  --bs-btn-disabled-bg:var(--jm-primary);
  --bs-btn-disabled-border-color:var(--jm-primary);
  background-color:var(--bs-btn-bg)!important;
  border-color:var(--bs-btn-border-color)!important;
}
.btn-outline-primary{
  --bs-btn-color:var(--jm-primary);
  --bs-btn-border-color:var(--jm-primary);
  --bs-btn-hover-color:#fff;
  --bs-btn-hover-bg:var(--jm-primary);
  --bs-btn-hover-border-color:var(--jm-primary);
  color:var(--jm-primary)!important;
  border-color:var(--jm-primary)!important;
}

/* Sidebar activo en primario (por si algún CSS interfiere) */
.sidebar .nav-link.active{
  background:var(--jm-primary)!important;
  color:#fff!important;
}
.sidebar .nav-link:not(.active):hover{
  background:rgba(var(--jm-primary-rgb), .08)!important;
  color:var(--jm-primary)!important;
}

/* === FIX: legibilidad en btn-outline-success (texto blanco en hover) === */


.btn-outline-primary:hover,
.btn-outline-primary:focus,
.btn-outline-primary:active{
  color: var(--bs-btn-hover-color) !important;
  background-color: var(--bs-btn-hover-bg) !important;
  border-color: var(--bs-btn-hover-border-color) !important;
}

/* Íconos dentro del botón también en blanco al hover */
.btn-outline-success:hover i,
.btn-outline-success:hover .bi{
  color:#fff !important;
  fill:#fff !important;
}

</style>

</head>

<body>
  <div class="app d-flex min-vh-100">

    {{-- Sidebar solo para usuarios autenticados --}}
    @auth
      @include('layouts.sidebar')
    @endauth

    <div class="flex-grow-1 d-flex flex-column">
      {{-- Top brandbar (usa color primario y logo dinámico) --}}
      @include('layouts.navigation')

      {{-- Contenido --}}
      <main class="app-content flex-grow-1 p-3 p-lg-4">
        @yield('content')
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
