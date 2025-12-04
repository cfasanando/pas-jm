{{-- resources/views/expedientes/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-0">Case files (Expedientes)</h5>
            <small class="text-muted">Control and monitoring of administrative case files.</small>
        </div>

        {{-- Filters --}}
        <form method="get" action="{{ route('expedientes') }}" class="d-flex gap-2 flex-wrap">
            <input
                type="text"
                name="q"
                value="{{ $q }}"
                class="form-control"
                placeholder="code, inspector, place"
                style="min-width: 220px;"
            >

            <select name="estado" class="form-select">
                <option value="">All statuses</option>
                <option value="abierto"    @selected($estado === 'abierto')>Open</option>
                <option value="en_tramite" @selected($estado === 'en_tramite')>In progress</option>
                <option value="concluido"  @selected($estado === 'concluido')>Closed</option>
                <option value="archivado"  @selected($estado === 'archivado')>Archived</option>
            </select>

            <button class="btn btn-primary">Search</button>

            @if($q || $estado)
                <a href="{{ route('expedientes') }}" class="btn btn-outline-secondary">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Flash / validation messages --}}
    @if (session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif
    @if (session('warn'))
        <div class="alert alert-warning">{{ session('warn') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- KPIs --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="small text-muted">Total</div>
                    <div class="fw-bold h5 mb-0">{{ $kpis['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="small text-muted">Open</div>
                    <div class="fw-bold h5 mb-0">{{ $kpis['open'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="small text-muted">In progress</div>
                    <div class="fw-bold h5 mb-0">{{ $kpis['inProgress'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="small text-muted">Closed</div>
                    <div class="fw-bold h5 mb-0">{{ $kpis['closed'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2">
                    <div class="small text-muted">Archived</div>
                    <div class="fw-bold h5 mb-0">{{ $kpis['archived'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- List --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <strong>List</strong>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Acta</th>
                        <th>Date</th>
                        <th>Inspector</th>
                        <th>Status</th>
                        <th class="text-end">Total fine</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($expedientes as $exp)
                    <tr>
                        <td>
                            <a href="{{ route('expedientes.show', $exp->id) }}">
                                {{ $exp->codigo }}
                            </a>
                        </td>
                        <td>
                            @if($exp->acta_numero)
                                {{ $exp->acta_numero }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($exp->acta_fecha)
                                {{ \Carbon\Carbon::parse($exp->acta_fecha.' '.$exp->acta_hora)->format('d/m/Y H:i') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $exp->inspector_name ?? '—' }}</td>
                        <td>
                            @php
                                $badgeClass = match ($exp->estado) {
                                    'abierto'    => 'bg-primary-subtle text-primary',
                                    'en_tramite' => 'bg-warning-subtle text-warning',
                                    'concluido'  => 'bg-success-subtle text-success',
                                    'archivado'  => 'bg-secondary text-light',
                                    default      => 'bg-secondary-subtle text-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $exp->estado)) }}
                            </span>
                        </td>
                        <td class="text-end">
                            S/ {{ number_format($exp->multa_total, 2) }}
                        </td>
                        <td class="text-end">
                            <a
                                href="{{ route('expedientes.show', $exp->id) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                View details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No case files found for the current filters.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($expedientes->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $expedientes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
