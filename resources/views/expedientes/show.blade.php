{{-- resources/views/expedientes/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-0">
                Case file {{ $expediente->codigo }}
            </h5>
            <small class="text-muted">
                Detailed view of the administrative case file and its related acta.
            </small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('expedientes') }}" class="btn btn-outline-secondary">
                ← Back to list
            </a>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                Print
            </button>
        </div>
    </div>

    {{-- Main info --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <strong>Case file information</strong>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Code</dt>
                        <dd class="col-sm-8">{{ $expediente->codigo }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            {{ ucfirst(str_replace('_', ' ', $expediente->estado)) }}
                        </dd>

                        <dt class="col-sm-4">Opened at</dt>
                        <dd class="col-sm-8">
                            @if($expediente->fecha_apertura)
                                {{ \Carbon\Carbon::parse($expediente->fecha_apertura)->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Closed at</dt>
                        <dd class="col-sm-8">
                            @if($expediente->fecha_cierre)
                                {{ \Carbon\Carbon::parse($expediente->fecha_cierre)->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Not closed</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Area / department</dt>
                        <dd class="col-sm-8">
                            {{ $expediente->derivado_a ?? '—' }}
                        </dd>

                        @if($expediente->observacion)
                            <dt class="col-sm-4">Notes</dt>
                            <dd class="col-sm-8">
                                {{ $expediente->observacion }}
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- Acta + administrado --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 mb-3">
                <div class="card-header bg-white border-0">
                    <strong>Related acta</strong>
                </div>
                <div class="card-body">
                    @if($expediente->acta_id)
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Acta number</dt>
                            <dd class="col-sm-8">{{ $expediente->acta_numero ?? '—' }}</dd>

                            <dt class="col-sm-4">Date / time</dt>
                            <dd class="col-sm-8">
                                @if($expediente->acta_fecha)
                                    {{ \Carbon\Carbon::parse($expediente->acta_fecha.' '.$expediente->acta_hora)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Place</dt>
                            <dd class="col-sm-8">{{ $expediente->acta_lugar ?? '—' }}</dd>

                            <dt class="col-sm-4">Acta status</dt>
                            <dd class="col-sm-8">
                                {{ ucfirst($expediente->acta_estado ?? '') ?: '—' }}
                            </dd>

                            <dt class="col-sm-4">Inspector</dt>
                            <dd class="col-sm-8">{{ $expediente->inspector_name ?? '—' }}</dd>
                        </dl>
                    @else
                        <p class="text-muted mb-0">
                            This case file is not linked to any acta.
                        </p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <strong>Citizen / company (administrado)</strong>
                </div>
                <div class="card-body">
                    @if($expediente->admin_razon_social)
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Name</dt>
                            <dd class="col-sm-8">{{ $expediente->admin_razon_social }}</dd>

                            <dt class="col-sm-4">Document</dt>
                            <dd class="col-sm-8">
                                {{ $expediente->admin_tipo_doc ?? '' }}
                                {{ $expediente->admin_numero_doc ?? '' }}
                            </dd>

                            <dt class="col-sm-4">Address</dt>
                            <dd class="col-sm-8">{{ $expediente->admin_direccion ?? '—' }}</dd>
                        </dl>
                    @else
                        <p class="text-muted mb-0">
                            No citizen / company information linked to this acta.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tipifications --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <strong>Tipifications and infractions</strong>
            <span class="fw-semibold">
                Total fine: S/ {{ number_format($multaTotal, 2) }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Observation</th>
                        <th class="text-end">Fine</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tipificaciones as $tip)
                    <tr>
                        <td>{{ $tip->codigo }}</td>
                        <td>{{ $tip->descripcion }}</td>
                        <td>{{ $tip->observacion ?? '—' }}</td>
                        <td class="text-end">
                            @if($tip->multa !== null)
                                S/ {{ number_format($tip->multa, 2) }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            No tipifications registered for this acta.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tickets / boletas --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <strong>Tickets (boletas) linked to this acta</strong>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Series</th>
                        <th>Number</th>
                        <th>Status</th>
                        <th class="text-end">Amount</th>
                        <th>Issued at</th>
                        <th>Notified at</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($boletas as $b)
                    <tr>
                        <td>{{ $b->serie }}</td>
                        <td>{{ $b->numero }}</td>
                        <td>{{ ucfirst($b->estado) }}</td>
                        <td class="text-end">
                            S/ {{ number_format($b->monto, 2) }}
                        </td>
                        <td>
                            @if($b->created_at)
                                {{ \Carbon\Carbon::parse($b->created_at)->format('d/m/Y H:i') }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($b->notified_at)
                                {{ \Carbon\Carbon::parse($b->notified_at)->format('d/m/Y H:i') }}
                            @else
                                <span class="text-muted">Not notified</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            No tickets associated with this acta.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
