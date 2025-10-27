@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card">
    <div class="card-body">
      <h1 class="h5">Boleta de Infracción (vista HTML de prueba)</h1>
      <hr>
      <p><strong>Serie/Número:</strong> {{ $boleta['serie'] }}-{{ $boleta['numero'] }}</p>
      <p><strong>Administrado:</strong> {{ $boleta['admin'] }}</p>
      <p><strong>Monto:</strong> S/ {{ number_format($boleta['monto'],2) }}</p>
      <p><strong>Estado:</strong> {{ $boleta['estado'] }}</p>
      <div class="alert alert-info small mb-0">
        Instala <code>barryvdh/laravel-dompdf</code> para descargar PDF real.
      </div>
    </div>
  </div>
</div>
@endsection
