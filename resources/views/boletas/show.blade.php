@extends('layouts.app')

@php
  // número con padding a 6 dígitos
  $numPadded = str_pad((string)$boleta->numero, 6, '0', STR_PAD_LEFT);
@endphp

@section('title', "Boleta {$boleta->serie}-{$numPadded}")

@section('content')
<div class="container py-3">

  @if (session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if (session('warn')) <div class="alert alert-warning">{{ session('warn') }}</div> @endif

  <div class="d-flex justify-content-end gap-2 mb-2">
    <a class="btn btn-outline-success btn-sm" href="{{ route('boletas.pdf',$boleta) }}">PDF</a>
    <form method="post" action="{{ route('boletas.notify',$boleta) }}">@csrf
      <button class="btn btn-outline-success btn-sm">Notificar</button>
    </form>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col"><strong>Acta:</strong> {{ $boleta->acta?->numero ?? '—' }}</div>
        <div class="col text-end"><strong>Total:</strong> S/ {{ number_format($boleta->monto, 2) }}</div>
      </div>
      <div class="row mt-1">
        <div class="col"><strong>Inspector:</strong> {{ $boleta->acta?->inspector?->name }}</div>
        <div class="col text-end"><strong>Estado:</strong> {{ $boleta->estado }}</div>
      </div>
      <div class="row mt-1">
        <div class="col"><strong>Administrado:</strong>
          {{ $boleta->acta?->administrado?->razon_social
              ?? trim(($boleta->acta?->administrado?->nombres.' '.$boleta->acta?->administrado?->apellidos) ?? '') }}
        </div>
        <div class="col text-end"><strong>Hash:</strong> {{ $boleta->qr_hash ?: '—' }}</div>
      </div>

      <hr>
      <div class="fw-semibold mb-1">Infracciones</div>
      <ul class="mb-0">
        @forelse($boleta->acta?->tipificaciones as $t)
          <li>{{ $t->infraccion->codigo }} — {{ $t->infraccion->descripcion }}
            (S/ {{ number_format($t->multa, 2) }})
          </li>
        @empty
          <li class="text-muted">Sin tipificaciones</li>
        @endforelse
      </ul>
    </div>
  </div>
</div>
@endsection
