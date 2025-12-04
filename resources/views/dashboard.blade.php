{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Dashboard de fiscalización</h5>

        <form method="get" class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm">
                <span class="input-group-text">Desde</span>
                <input type="date" name="from" class="form-control"
                       value="{{ $from }}">
            </div>

            <div class="input-group input-group-sm">
                <span class="input-group-text">Hasta</span>
                <input type="date" name="to" class="form-control"
                       value="{{ $to }}">
            </div>

            <button class="btn btn-sm btn-primary">Actualizar</button>
        </form>
    </div>

    {{-- KPIs principales --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Actas totales</p>
                    <h3 class="fw-bold mb-0">{{ number_format($totalActas) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Actas en el período</p>
                    <h3 class="fw-bold mb-0">{{ number_format($actasPeriodo) }}</h3>
                    <p class="text-muted mb-0 small">
                        {{ $from }} — {{ $to }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Recaudación total</p>
                    <h3 class="fw-bold mb-0">
                        S/ {{ number_format($recaudacionTotal, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Recaudación en el período</p>
                    <h3 class="fw-bold mb-0">
                        S/ {{ number_format($recaudacionPeriodo, 2) }}
                    </h3>
                    <p class="text-muted mb-0 small">
                        {{ $from }} — {{ $to }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Segunda fila: actas hoy + top días --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Actas emitidas hoy ({{ $today }})</p>
                    <h3 class="fw-bold mb-0">{{ number_format($actasHoy) }}</h3>
                    <p class="text-muted small mb-0">
                        Útil para ver actividad diaria en tiempo real.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">Días con más sanciones (Top 7)</h6>
                    <div class="table-responsive mb-0" style="max-height: 230px;">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th class="text-end">N.º de actas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($diasTop as $d)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($d->fecha)->format('d/m/Y') }}</td>
                                        <td class="text-end">{{ $d->total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted text-center">
                                            No hay datos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        Permite detectar días pico de fiscalización (útil para reforzar turnos).
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Lugares más recurrentes + recaudación por mes --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">Lugares con más sanciones en el período</h6>
                    <p class="text-muted small mb-2">
                        Top 5 lugares donde se levantan más actas ({{ $from }} — {{ $to }}).
                    </p>
                    <div class="table-responsive mb-0">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Lugar</th>
                                    <th class="text-end">Actas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lugaresTop as $l)
                                    <tr>
                                        <td>{{ $l->lugar ?? 'Sin dato' }}</td>
                                        <td class="text-end">{{ $l->total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted text-center">
                                            No hay datos en el período seleccionado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        Útil para priorizar operativos en zonas críticas.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">Recaudación por mes (últimos 6 meses)</h6>
                    <div class="table-responsive mb-0">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th class="text-end">Actas</th>
                                    <th class="text-end">Recaudación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($porMes as $m)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($m->periodo . '-01')->translatedFormat('F Y') }}</td>
                                        <td class="text-end">{{ $m->actas }}</td>
                                        <td class="text-end">S/ {{ number_format($m->monto, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted text-center">
                                            Aún no hay actas registradas en los últimos meses.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        Permite ver evolución mensual de las sanciones y la recaudación.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
