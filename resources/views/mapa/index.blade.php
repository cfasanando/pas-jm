@extends('layouts.app')
@section('title','Mapa')

@section('content')
@php
  // Preparamos datos mínimos para JS (uno por acta)
  $points = collect($markers)->map(function($a){
      $total = $a->tipificaciones->sum('multa');
      return [
          'id'        => $a->id,
          'numero'    => $a->numero ?? 'BORRADOR',
          'lat'       => (float) $a->lat,
          'lng'       => (float) $a->lng,
          'lugar'     => $a->lugar,
          'fecha'     => optional($a->fecha)->format('d/m/Y'),
          'hora'      => $a->hora,
          'estado'    => $a->estado,
          'inspector' => $a->inspector?->name,
          'total'     => (float) $total,
          'url'       => route('evidencias.index', $a),
      ];
  })->values();
@endphp

<div class="container py-3">
  <h5 class="mb-3">Mapa de intervenciones</h5>

  <div class="row g-3">
    <div class="col-lg-9">
      <div class="card">
        <div class="card-body p-0">
          <div id="map" style="height:520px;"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-3">
      <div class="card">
        <div class="card-header"><i class="bi bi-funnel me-2"></i>Filtros</div>
        <div class="card-body">
          <form method="get" class="vstack gap-2">
            <input type="date"   name="fecha"     class="form-control" value="{{ request('fecha') }}">
            <select name="inspector" class="form-select">
              <option value="">Inspector</option>
              @foreach($inspectores as $u)
                <option value="{{ $u->id }}" @selected(request('inspector')==$u->id)>{{ $u->name }}</option>
              @endforeach
            </select>
            <select name="estado" class="form-select">
              <option value="">Estado</option>
              @foreach($estados as $e)
                <option value="{{ $e }}" @selected(request('estado')==$e)>{{ ucfirst($e) }}</option>
              @endforeach
            </select>
            <button class="btn btn-success w-100">Aplicar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Leaflet CSS/JS vía CDN --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
  // Datos desde PHP
  const points = @json($points);

  // 1) Crear mapa (centro por defecto: Lima). Si hay puntos, luego ajustamos a su bounding box.
  const map = L.map('map', { zoomControl: true }).setView([-12.0621065, -77.046037], 12);

  // 2) Base layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  // 3) Agregar marcadores si existen
  if (points.length) {
    const layer = L.layerGroup().addTo(map);

    points.forEach(p => {
      if (!p.lat || !p.lng) return;

      const html = `
        <div class="small">
          <div><strong>${p.numero}</strong> — ${p.estado}</div>
          <div>${p.fecha || ''} ${p.hora || ''}</div>
          <div>${p.lugar || ''}</div>
          <div>Inspector: ${p.inspector || ''}</div>
          <div>Total: S/ ${Number(p.total).toFixed(2)}</div>
          <div class="mt-1"><a href="${p.url}" target="_blank">Abrir acta</a></div>
        </div>`;

      L.marker([p.lat, p.lng]).addTo(layer).bindPopup(html);
    });

    // Enfoca a los puntos
    const bounds = layer.getBounds();
    if (bounds.isValid()) map.fitBounds(bounds.pad(0.2));
  } else {
    // Sin puntos: dejamos el centro por defecto
  }
</script>
@endsection
