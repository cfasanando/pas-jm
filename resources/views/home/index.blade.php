@extends('layouts.app')

@section('title','Inicio')
@section('page_title','Inicio')
@section('page_subtitle','Bienvenido. Accesos rápidos y widgets.')

@section('content')
<div class="row g-3">
  <div class="col-12 col-xl-8">
    <div class="card">
      <div class="card-header"><i class="bi bi-grid me-2"></i> Accesos rápidos</div>
      <div class="card-body d-flex flex-wrap gap-2">
        <a class="btn btn-soft" href="{{ route('dashboard') }}">
          <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
        <a class="btn btn-soft" href="{{ route('actas.create') }}">
          <i class="bi bi-clipboard-plus me-1"></i> Registrar Acta
        </a>
        <a class="btn btn-soft" href="{{ route('expedientes') }}">
          <i class="bi bi-folder2 me-1"></i> Expedientes
        </a>
        <a class="btn btn-soft" href="{{ route('mapa') }}">
          <i class="bi bi-geo-alt me-1"></i> Mapa
        </a>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-info-circle me-2"></i> Estado</div>
      <div class="card-body">
        <div class="text-muted small">Hora del sistema</div>
        <div class="h5 mb-0" id="clock-now">--</div>
      </div>
    </div>
  </div>

  @for($i=0;$i<6;$i++)
    <div class="col-12 col-md-4">
      <div class="card"><div class="card-body">Widget {{ $i+1 }}</div></div>
    </div>
  @endfor
</div>
@endsection

@push('scripts')
<script>
  (function tick(){
    const el = document.getElementById('clock-now');
    if(el){ el.textContent = new Date().toLocaleString(); }
    setTimeout(tick, 1000);
  })();
</script>
@endpush
