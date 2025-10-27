{{-- resources/views/admin/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  // Mantener la pestaña activa: primero lo que venga del controlador (flash), si no, query ?tab=, y por defecto "users"
  $tab = session('tab', request('tab', 'users'));
@endphp

<div class="container py-3">
  <h5 class="mb-3">Administración</h5>

  @if (session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if (session('warn')) <div class="alert alert-warning">{{ session('warn') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  {{-- Tabs (links con ?tab= para poder compartir URL y para fallback si no hay flash) --}}
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"><a class="nav-link {{ $tab==='users' ? 'active' : '' }}" href="{{ route('admin', ['tab'=>'users']) }}">Usuarios</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='infracciones' ? 'active' : '' }}" href="{{ route('admin', ['tab'=>'infracciones']) }}">Infracciones</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='series' ? 'active' : '' }}" href="{{ route('admin', ['tab'=>'series']) }}">Series</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab==='settings' ? 'active' : '' }}" href="{{ route('admin', ['tab'=>'settings']) }}">Configuración</a></li>
  </ul>

  <div class="border border-top-0 p-3 bg-white">
    {{-- ===================== USERS ===================== --}}
    @if($tab==='users')
      <div class="row g-3">
        <div class="col-md-4">
          <form method="post" action="{{ route('admin.users.store') }}" class="card card-body vstack gap-2">
            @csrf
            <h6>Nuevo usuario</h6>
            <input class="form-control" name="name" placeholder="Nombre" required>
            <input class="form-control" name="email" type="email" placeholder="Correo" required>
            <input class="form-control" name="password" type="password" placeholder="Contraseña" required>
            <button class="btn btn-primary">Crear</button>
          </form>
        </div>
        <div class="col-md-8">
          <div class="table-responsive">
            <table class="table align-middle">
              <thead><tr><th>Nombre</th><th>Correo</th><th class="text-end">Acciones</th></tr></thead>
              <tbody>
                @foreach($users as $u)
                <tr>
                  <td>{{ $u->name }}</td>
                  <td>{{ $u->email }}</td>
                  <td class="text-end">
                    <form class="d-inline" method="post" action="{{ route('admin.users.destroy',$u) }}">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif

    {{-- ===================== INFRACCIONES ===================== --}}
    @if($tab==='infracciones')
      <div class="row g-3">
        <div class="col-md-5">
          <form method="post" action="{{ route('admin.catalogs.store') }}" class="card card-body vstack gap-2">
            @csrf
            <input type="hidden" name="type" value="infraccion">
            <h6>Nueva infracción</h6>

            <input class="form-control" name="codigo" placeholder="Código (ej: INF-001)" required>
            <input class="form-control" name="descripcion" placeholder="Descripción" required>
            <input class="form-control" name="base_legal" placeholder="Base legal">

            <div class="input-group">
              <span class="input-group-text">S/</span>
              <input class="form-control" name="multa" type="number" step="0.01" min="0" value="0.00" required>
            </div>

            {{-- Enviar boolean real: hidden 0 + checkbox 1 --}}
            <div class="form-check">
              <input type="hidden" name="activo" value="0">
              <input class="form-check-input" type="checkbox" id="chkActivo" name="activo" value="1" checked>
              <label class="form-check-label" for="chkActivo">Activo</label>
            </div>

            <button class="btn btn-primary">Guardar</button>
          </form>
        </div>

        <div class="col-md-7">
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Código</th><th>Descripción</th><th>Multa</th><th>Estado</th><th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($infracciones as $inf)
                <tr>
                  <td>{{ $inf->codigo }}</td>
                  <td>{{ $inf->descripcion }}</td>
                  <td>S/ {{ number_format($inf->multa,2) }}</td>
                  <td>
                    @if($inf->activo)
                      <span class="badge bg-success-subtle text-success">Activo</span>
                    @else
                      <span class="badge bg-secondary">Inactivo</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <form class="d-inline" method="post" action="{{ route('admin.catalogs.destroy',$inf->id) }}">
                      @csrf @method('DELETE')
                      <input type="hidden" name="type" value="infraccion">
                      <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif

    {{-- ===================== SERIES ===================== --}}
    @if($tab==='series')
      <div class="row g-3">
        <div class="col-md-4">
          <form method="post" action="{{ route('admin.catalogs.store') }}" class="card card-body vstack gap-2">
            @csrf
            <input type="hidden" name="type" value="serie">
            <h6>Nueva serie de Boleta</h6>

            <input class="form-control" name="serie" placeholder="Ej: A001" required>
            <input class="form-control" name="padding" type="number" min="3" max="10" value="6" required>

            <button class="btn btn-primary">Agregar serie</button>
          </form>
        </div>

        <div class="col-md-8">
          <ul class="list-group">
            @foreach($series as $s)

              <li class="list-group-item d-flex align-items-center justify-content-between gap-3">
  <div>
    {{-- Mostrar solo la serie (sin el prefijo boleta_) --}}
    <strong>{{ strtoupper(str_replace('boleta_','',$s->key)) }}</strong>
    <span class="text-muted ms-2">Correlativo: {{ $s->value }}</span>
    {{-- Si tu tabla no tiene 'padding', esta parte puede omitirse o mostrarse condicional --}}
    @if(isset($s->padding))
      <span class="text-muted ms-2">Padding: {{ $s->padding }}</span>
    @endif
  </div>

  <div class="d-flex gap-2">
    {{-- ACTUALIZAR --}}
    <form class="d-flex align-items-center gap-2" method="post"
          action="{{ route('admin.catalogs.update', strtoupper(str_replace('boleta_','',$s->key))) }}">
      @csrf @method('PUT')
      <input type="hidden" name="type" value="serie">

      <input class="form-control form-control-sm" type="number" name="current"
             min="0" value="{{ $s->value }}" placeholder="Nuevo Nº">

      <input class="form-control form-control-sm" type="number" name="padding"
             min="3" max="10" value="{{ data_get($s,'padding') }}" placeholder="Padding">
      <button class="btn btn-sm btn-outline-primary">Actualizar</button>
    </form>

    {{-- ELIMINAR --}}
    <form method="post"
          action="{{ route('admin.catalogs.destroy', strtoupper(str_replace('boleta_','',$s->key))) }}">
      @csrf @method('DELETE')
      <input type="hidden" name="type" value="serie">
      <button class="btn btn-sm btn-outline-danger">Eliminar</button>
    </form>
  </div>
</li>


            @endforeach
          </ul>
        </div>
      </div>
    @endif

    {{-- ====== SETTINGS ====== --}}
{{-- ====== SETTINGS ====== --}}
@if($tab==='settings')
  <form method="post" action="{{ route('admin.settings.update') }}" class="card card-body vstack gap-2">
    @csrf @method('PUT')
    <input type="hidden" name="tab" value="settings">

    <h6>Parámetros generales</h6>

    <label class="form-label small mb-1">Nombre del sistema</label>
    <input class="form-control"
           name="app[name]"
           placeholder="Nombre del sistema"
           value="{{ $settings['app.name'] ?? config('app.name') }}">

    <label class="form-label small mb-1 mt-2">Logo (URL) — puedes usar /img/logo-jm.svg</label>
    <input class="form-control"
           name="app[logo]"
           placeholder="https://... ó /img/logo.svg"
           value="{{ $settings['app.logo'] ?? '' }}">

    <div class="row g-2 mt-1">
      <div class="col-sm-6">
        <label class="form-label small mb-1">Color primario</label>
        <input type="color"
               name="app[primary]"
               class="form-control form-control-color"
               value="{{ $settings['app.primary'] ?? '#0d6efd' }}"
               title="Elige un color" />
      </div>
      <div class="col-sm-6">
        <label class="form-label small mb-1">Correo remitente</label>
        <input class="form-control"
               name="mail[from]"
               placeholder="notificaciones@jm.gob.pe"
               value="{{ $settings['mail.from'] ?? '' }}">
      </div>
    </div>

    <label class="form-label small mt-2">Pie de página PDF</label>
    <input class="form-control"
           name="pdf[footer]"
           placeholder="Pie de página"
           value="{{ $settings['pdf.footer'] ?? 'Municipalidad de Jesús María — PAS' }}">

    <button class="btn btn-primary mt-3">Guardar</button>
  </form>
@endif



  </div>
</div>
@endsection
