@php
    // Marca 'active' si la ruta actual coincide con alguno de los patrones
    $active = function (...$patterns) {
        foreach ($patterns as $p) {
            if (request()->routeIs($p)) return 'active';
        }
        return '';
    };
@endphp

<ul class="list-unstyled m-0 p-0">

  <li class="mb-1">
    <a class="nav-link {{ $active('dashboard') }}" href="{{ route('dashboard') }}">
      <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
  </li>

  <li class="mb-1">
    <a class="nav-link {{ $active('mapa') }}" href="{{ route('mapa') }}">
      <i class="bi bi-geo-alt me-2"></i>Mapa
    </a>
  </li>

  <li class="mb-1">
    <a class="nav-link {{ $active('expedientes') }}" href="{{ route('expedientes') }}">
      <i class="bi bi-folder2-open me-2"></i>Expedientes
    </a>
  </li>

  {{-- NUEVO: listado de actas --}}
  <li class="mb-1">
    <a class="nav-link {{ $active('actas.index','actas.show','evidencias.*') }}" href="{{ route('actas.index') }}">
      <i class="bi bi-journal-text me-2"></i>Actas
    </a>
  </li>

  {{-- Registrar acta (formulario) --}}
  <li class="mb-1">
    <a class="nav-link {{ $active('actas.create') }}" href="{{ route('actas.create') }}">
      <i class="bi bi-clipboard-plus me-2"></i>Registrar acta
    </a>
  </li>

  <li class="mb-1">
    <a class="nav-link {{ $active('boletas','boletas.*') }}" href="{{ route('boletas') }}">
      <i class="bi bi-file-earmark-text me-2"></i>Boletas
    </a>
  </li>

  <li class="mb-1">
    <a class="nav-link {{ $active('admin') }}" href="{{ route('admin', ['tab' => request('tab','users')]) }}">
      <i class="bi bi-gear me-2"></i>Administración
    </a>
  </li>

</ul>
