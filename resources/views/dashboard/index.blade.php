@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="container py-3">
  <h5 class="mb-3">Dashboard PAS — KPIs</h5>

  <form class="mb-3 d-flex gap-2" method="get">
    <input type="date" class="form-control" name="day" value="{{ $day }}">
    <button class="btn btn-primary">Actualizar</button>
  </form>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">Tiempo de emisión</div>
        <div class="display-5 fw-bold">{{ $avgMin }}</div><span class="ms-1">min</span>
        <div class="small text-muted">Periodo: {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</div>
      </div></div>
    </div>

    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">% evidencias válidas</div>
        <div class="display-5 fw-bold">{{ $pctEvid }}</div><span class="ms-1">%</span>
        <div class="small text-muted">Periodo: {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</div>
      </div></div>
    </div>

    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">% notif &lt; 5m</div>
        <div class="display-5 fw-bold">{{ $pctNotifFast }}</div><span class="ms-1">%</span>
        <div class="small text-muted">Periodo: {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</div>
      </div></div>
    </div>

    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">Reincidencia</div>
        <div class="display-5 fw-bold">{{ $pctReinc }}</div><span class="ms-1">%</span>
        <div class="small text-muted">Periodo: {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</div>
      </div></div>
    </div>

    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">Recaudación</div>
        <div class="display-5 fw-bold">{{ number_format($recaudacion,2) }}</div><span class="ms-1">S/</span>
        <div class="small text-muted">Periodo: {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</div>
      </div></div>
    </div>

    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">Actas hoy</div>
        <div class="display-5 fw-bold">{{ $actasHoy }}</div>
        <div class="small text-muted">Periodo: {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</div>
      </div></div>
    </div>
  </div>
</div>
@endsection
