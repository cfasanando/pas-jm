@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h5 class="mb-3">Boletas de Infracción</h5>

  @if (session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if (session('warn')) <div class="alert alert-warning">{{ session('warn') }}</div> @endif

  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Serie/Número</th>
            <th>Administrado</th>
            <th>Monto</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($boletas as $b)
          <tr>
            <td class="fw-semibold">{{ $b->serie }}-{{ str_pad($b->numero,6,'0',STR_PAD_LEFT) }}</td>
            <td>{{ optional($b->acta->administrado)->razon_social ?? '—' }}</td>
            <td>S/ {{ number_format($b->monto,2) }}</td>
            <td>
              <span class="badge {{ $b->estado==='notificada'?'bg-success':'bg-secondary' }}">{{ ucfirst($b->estado) }}</span>
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('boletas.pdf',$b) }}">PDF</a>
              <a class="btn btn-sm btn-outline-secondary" href="{{ route('boletas.show',$b) }}">Ver</a>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-muted">Sin registros</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">{{ $boletas->links() }}</div>
</div>
@endsection
