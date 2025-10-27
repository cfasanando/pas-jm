{{-- resources/views/layouts/navigation.blade.php --}}
@php
  $homeRoute = auth()->check() ? route('dashboard') : route('home');
  $brandName = $brand['name'] ?? config('app.name');
  $brandLogo = trim($brand['logo'] ?? '') ?: asset('img/logo-jm.svg'); // fallback local si no hay setting
@endphp

<div class="brandbar px-2 px-lg-4 d-flex align-items-center justify-content-between">
  <div class="d-flex align-items-center gap-2">
    @auth
      <button class="btn btn-sm btn-light d-md-none me-1"
              type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"
              aria-controls="mobileSidebar" aria-label="Abrir menú">
        <i class="bi bi-list"></i>
      </button>
    @endauth

    <a href="{{ $homeRoute }}" class="brand d-flex align-items-center text-white text-decoration-none">
      <img src="{{ $brandLogo }}" alt="{{ $brandName }}" width="22" height="22"
           class="me-2 rounded-1" onerror="this.style.display='none'">
      <span class="fw-semibold">{{ $brandName }}</span>
    </a>
  </div>

  @auth
    <form class="d-none d-lg-block" action="{{ route('dashboard') }}" method="GET" role="search" style="min-width:320px">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
        <input class="form-control border-0" name="q" placeholder="Buscar..." value="{{ request('q') }}">
      </div>
    </form>
  @endauth

  <div class="d-flex align-items-center gap-3">
    @auth
      <div class="dropdown">
        <a class="text-white text-decoration-none dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li class="dropdown-item text-muted small">{{ Auth::user()->email }}</li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">@csrf
              <button class="dropdown-item" type="submit">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
              </button>
            </form>
          </li>
        </ul>
      </div>
    @else
      <a class="btn btn-sm btn-light" href="{{ route('login') }}">Ingresar</a>
    @endauth
  </div>
</div>

{{-- Offcanvas móvil --}}
@auth
  <div class="offcanvas offcanvas-start offcanvas-sidebar" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header">
      <div class="d-flex align-items-center gap-2">
        <img src="{{ $brandLogo }}" alt="{{ $brandName }}" width="18" height="18" class="rounded-1"
             onerror="this.style.display='none'">
        <strong>{{ $brandName }}</strong>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
      @include('layouts.sidebar-menu') {{-- mismo menú que el sidebar desktop --}}
    </div>
  </div>
@endauth
