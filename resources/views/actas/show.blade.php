@extends('layouts.app')

@section('title','Acta '.$acta->numero ?? $acta->id)
@section('content')
@php
  $total = $acta->tipificaciones->sum('monto');
@endphp

<div class="container py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="mb-0">
      Acta {{ $acta->numero ?? ('#'.$acta->id) }}
      <span class="badge text-bg-{{ $acta->estado === 'emitida' ? 'primary' : ($acta->estado === 'notificada' ? 'success' : ($acta->estado === 'anulada' ? 'secondary' : 'warning')) }}">
        {{ ucfirst($acta->estado) }}
      </span>
    </h5>
    <div class="d-flex gap-2">
      <a href="{{ route('actas.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
      <a href="{{ route('evidencias.index', $acta) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-paperclip"></i> Evidencias
      </a>
      @if(!$acta->boleta && $acta->estado !== 'anulada')
        <form method="POST" action="{{ route('boletas.fromActa',$acta) }}" class="d-inline">
          @csrf
          <button class="btn btn-success btn-sm">
            <i class="bi bi-receipt"></i> Generar boleta
          </button>
        </form>
      @endif
      @if($acta->estado==='borrador')
        <form method="POST" action="{{ route('actas.emit',$acta) }}" class="d-inline">
          @csrf
          <button class="btn btn-primary btn-sm">
            <i class="bi bi-check2-circle"></i> Emitir
          </button>
        </form>
      @endif
    </div>
  </div>

  @if (session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if (session('warn')) <div class="alert alert-warning">{{ session('warn') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="row g-3">
    {{-- Columna principal --}}
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header"><i class="bi bi-clipboard-check me-2"></i>Resumen del acta</div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="small text-muted">Fecha</div>
              <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($acta->fecha)->format('d/m/Y') }}</div>
            </div>
            <div class="col-md-6">
              <div class="small text-muted">Hora</div>
              <div class="fw-semibold">{{ $acta->hora }}</div>
            </div>
            <div class="col-12">
              <div class="small text-muted">Lugar</div>
              <div class="fw-semibold">{{ $acta->lugar }}</div>
            </div>
            <div class="col-12">
              <div class="small text-muted">Constatación</div>
              <div>{{ $acta->constatacion ?: '—' }}</div>
            </div>
            @if($acta->lat && $acta->lng)
              <div class="col-12">
                <div class="small text-muted">Ubicación</div>
                <div>
                  <a class="text-decoration-none" target="_blank"
                     href="https://maps.google.com/?q={{ $acta->lat }},{{ $acta->lng }}">
                    <i class="bi bi-geo-alt"></i> {{ $acta->lat }}, {{ $acta->lng }}
                  </a>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Tipificaciones --}}
      <div class="card mt-3">
        <div class="card-header"><i class="bi bi-list-check me-2"></i>Tipificaciones</div>
        <div class="card-body">
          @if($acta->tipificaciones->isEmpty())
            <div class="text-muted">Sin tipificaciones registradas.</div>
          @else
            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th class="text-end">Monto</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($acta->tipificaciones as $t)
                    <tr>
                      <td>{{ $t->infraccion->codigo }}</td>
                      <td>{{ $t->infraccion->descripcion }}</td>
                      <td class="text-end">S/ {{ number_format($t->monto,2) }}</td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="2" class="text-end">Total</th>
                    <th class="text-end">S/ {{ number_format($total,2) }}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- Evidencias (mini galería) --}}
      <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
          <span><i class="bi bi-paperclip me-2"></i>Evidencias (reciente)</span>
          <a href="{{ route('evidencias.index',$acta) }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
        </div>
        <div class="card-body">
          @php
            $evs = $acta->evidencias->take(6);
          @endphp
          @if($evs->isEmpty())
            <div class="text-muted">Sin evidencias aún.</div>
          @else
            <div class="row g-2">
              @foreach($evs as $ev)
                @php
                  $isVideo = str_starts_with(strtolower($ev->tipo ?? ''), 'video/');
                  $url = $ev->path ? Storage::url($ev->path) : '';
                  $thumb = $ev->thumb_path ? Storage::url($ev->thumb_path) : $url;
                @endphp
                <div class="col-4 col-md-3">
                  <a href="{{ route('evidencias.index',$acta) }}" class="d-block border rounded overflow-hidden">
                    @if($isVideo)
                      <div class="ratio ratio-16x9 bg-dark d-grid place-items-center text-white small">Video</div>
                    @else
                      <img src="{{ $thumb }}" class="w-100" style="object-fit:cover;height:100px" onerror="this.style.display='none'">
                    @endif
                  </a>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Columna lateral --}}
    <div class="col-lg-4">
      {{-- Administrado --}}
      <div class="card">
        <div class="card-header"><i class="bi bi-person-badge me-2"></i>Administrado</div>
        <div class="card-body small">
          <div><strong>Doc:</strong> {{ optional($acta->administrado)->tipo_doc }} {{ optional($acta->administrado)->numero_doc }}</div>
          <div><strong>Razón social:</strong>
            {{ optional($acta->administrado)->razon_social
               ?? trim((optional($acta->administrado)->nombres.' '.optional($acta->administrado)->apellidos) ?? '') }}
          </div>
          <div><strong>Email:</strong> {{ optional($acta->administrado)->email }}</div>
          <div><strong>Dirección:</strong> {{ optional($acta->administrado)->direccion }}</div>
        </div>
      </div>

      {{-- Boleta --}}
      <div class="card mt-3">
        <div class="card-header"><i class="bi bi-file-earmark-text me-2"></i>Boleta</div>
        <div class="card-body">
          @if($acta->boleta)
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="small text-muted">Número</div>
                <div class="fw-semibold">{{ $acta->boleta->numero }}</div>
              </div>
              <div class="d-flex gap-2">
                <a href="{{ route('boletas.show', $acta->boleta) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                <a href="{{ route('boletas.pdf',  $acta->boleta) }}" class="btn btn-sm btn-outline-secondary" target="_blank">PDF</a>
              </div>
            </div>
          @else
            <div class="text-muted">Aún no se ha generado boleta.</div>
          @endif
        </div>
      </div>

      {{-- Inspector --}}
      <div class="card mt-3">
        <div class="card-header"><i class="bi bi-person-check me-2"></i>Inspector</div>
        <div class="card-body small">
          <div class="fw-semibold">{{ optional($acta->inspector)->name ?? '—' }}</div>
          <div class="text-muted">{{ optional($acta->inspector)->email }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
