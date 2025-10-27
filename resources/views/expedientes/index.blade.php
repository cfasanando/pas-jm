@extends('layouts.app')
@section('title','Expedientes')
@section('content')
<div class="container py-3">
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h5 class="mb-0">Expedientes</h5>
    <form class="d-flex gap-2" method="get">
      <input class="form-control" name="q" placeholder="código, inspector, lugar" value="{{ $q }}">
      <button class="btn btn-outline-secondary">Buscar</button>
    </form>
  </div>

  <div class="card">
    <div class="card-header">Lista</div>
    <div class="table-responsive">
      <table class="table mb-0 align-middle">
        <thead>
          <tr>
            <th>Número</th>
            <th>Fecha</th>
            <th>Inspector</th>
            <th>Estado</th>
            <th class="text-end">Multa total</th>
          </tr>
        </thead>
        <tbody>
          @forelse($actas as $a)
          <tr>
            <td>{{ $a->numero ?? '—' }}</td>
            <td>{{ optional($a->fecha)->format('d/m/Y') }} {{ $a->hora }}</td>
            <td>{{ $a->inspector?->name }}</td>
            <td>{{ ucfirst($a->estado) }}</td>
            <td class="text-end">
              S/ {{ number_format($a->tipificaciones->sum('multa'), 2) }}
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center text-muted py-4">Sin registros</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $actas->links() }}</div>
</div>
@endsection
