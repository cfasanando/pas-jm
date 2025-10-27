@extends('layouts.app')
@section('title','Actas')

@section('content')
<div class="container-fluid">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
    <h5 class="mb-2 mb-lg-0">Actas</h5>
    <div class="d-flex gap-2">
      <form method="GET" class="d-flex">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input class="form-control" name="q" value="{{ $q }}" placeholder="Buscar por número, lugar...">
          <button class="btn btn-outline-secondary">Buscar</button>
        </div>
      </form>
      <a class="btn btn-primary" href="{{ route('actas.create') }}"><i class="bi bi-clipboard-plus me-1"></i> Nueva acta</a>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th style="width:120px">N°</th>
            <th>Fecha/Hora</th>
            <th>Lugar</th>
            <th>Inspector</th>
            <th>Administrado</th>
            <th class="text-center">Evidencias</th>
            <th class="text-center">Tipificaciones</th>
            <th>Estado</th>
            <th style="width:240px" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($actas as $a)
          <tr>
            <td class="fw-semibold">{{ $a->numero ?? '—' }}</td>
            <td>
              {{ optional($a->fecha)->format('d/m/Y') ?? '—' }}
              <div class="text-muted small">{{ $a->hora ?? '' }}</div>
            </td>
            <td class="text-truncate" style="max-width:260px;">{{ $a->lugar }}</td>
            <td>{{ $a->inspector->name ?? '—' }}</td>
            <td>
              @php
                $adm = $a->administrado;
                $nombreAdm = $adm?->razon_social ?: trim(($adm->nombres??'').' '.($adm->apellidos??''));
              @endphp
              {{ $nombreAdm ?: '—' }}
            </td>
            <td class="text-center">
              <span class="badge bg-secondary-subtle text-secondary">{{ $a->evidencias_count }}</span>
            </td>
            <td class="text-center">
              <span class="badge bg-secondary-subtle text-secondary">{{ $a->tipificaciones_count }}</span>
            </td>
            <td>
              @php
                $map = [
                  'borrador'=>'secondary',
                  'emitida'=>'primary',
                  'notificada'=>'success',
                  'anulada'=>'danger'
                ];
                $badge = $map[$a->estado] ?? 'secondary';
              @endphp
              <span class="badge bg-{{ $badge }}">{{ ucfirst($a->estado) }}</span>
            </td>
            <td class="text-end">
              <div class="btn-group">
                <a class="btn btn-sm btn-outline-primary"
                   href="{{ route('evidencias.index', $a) }}">
                   <i class="bi bi-images me-1"></i> Evidencias
                </a>
                <a class="btn btn-sm btn-outline-secondary"
                   href="{{ route('actas.show', $a) }}">
                   <i class="bi bi-eye me-1"></i> Ver
                </a>
                @if($a->estado === 'emitida')
                  <a class="btn btn-sm btn-outline-dark"
                     href="{{ route('boletas.fromActa', $a) }}"
                     onclick="event.preventDefault(); alert('Implementa acción crear boleta desde acta');">
                     <i class="bi bi-receipt me-1"></i> Boleta
                  </a>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="9" class="text-center text-muted py-4">No hay actas registradas.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($actas->hasPages())
      <div class="card-footer">
        {{ $actas->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
