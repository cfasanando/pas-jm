@extends('layouts.app')

@section('title','Registrar Acta')
@section('page_title','Registrar Acta')

@section('content')
<div class="container py-3">
  @if (session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if (session('warn')) <div class="alert alert-warning">{{ session('warn') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form class="card card-body vstack gap-3" method="post" action="{{ route('actas.store') }}">
    @csrf
    <h6 class="mb-0">Datos del Acta</h6>
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Fecha</label>
        <input class="form-control" type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Hora</label>
        <input class="form-control" type="time" name="hora" value="{{ old('hora') }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Lugar</label>
        <input class="form-control" name="lugar" value="{{ old('lugar') }}" required>
      </div>

      {{-- Ubicación --}}
<div class="card mt-3">
  <div class="card-header">Ubicación</div>
  <div class="card-body">
    <div id="mapPick" style="height:320px; border-radius:12px;"></div>

    <div class="row g-2 mt-2">
      <div class="col-sm-4">
        <label class="form-label small">Latitud</label>
        <input class="form-control" id="lat" name="lat" value="{{ old('lat') }}" readonly>
      </div>
      <div class="col-sm-4">
        <label class="form-label small">Longitud</label>
        <input class="form-control" id="lng" name="lng" value="{{ old('lng') }}" readonly>
      </div>
      <div class="col-sm-4 d-flex align-items-end">
        <button type="button" id="btnLocate" class="btn btn-outline-primary w-100">
          Usar mi ubicación
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Leaflet (CDN) --}}
<link rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
  const map = L.map('mapPick').setView([-12.0621065, -77.046037], 13); // centro por defecto (JM)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom:19}).addTo(map);

  let marker = null;
  function setPos(lat, lng){
    if(!marker){
      marker = L.marker([lat, lng], {draggable:true}).addTo(map);
      marker.on('dragend', e => {
        const {lat, lng} = e.target.getLatLng();
        latInp.value = lat.toFixed(6);
        lngInp.value = lng.toFixed(6);
      });
    } else {
      marker.setLatLng([lat, lng]);
    }
    map.setView([lat, lng], 16);
    latInp.value = lat.toFixed(6);
    lngInp.value = lng.toFixed(6);
  }

  const latInp = document.getElementById('lat');
  const lngInp = document.getElementById('lng');

  // click en el mapa = fijar punto
  map.on('click', e => setPos(e.latlng.lat, e.latlng.lng));

  // botón "Usar mi ubicación"
  document.getElementById('btnLocate').onclick = () => {
    if (!navigator.geolocation) return alert('Geolocalización no soportada.');
    navigator.geolocation.getCurrentPosition(
      pos => setPos(pos.coords.latitude, pos.coords.longitude),
      ()  => alert('No se pudo obtener tu ubicación.')
    );
  };

  // si el form volvió con old() (error de validación), reponer
  @if(old('lat') && old('lng'))
    setPos({{ old('lat') }}, {{ old('lng') }});
  @endif
</script>


      <div class="col-12">
        <label class="form-label">Constatación</label>
        <textarea class="form-control" name="constatacion" rows="3">{{ old('constatacion') }}</textarea>
      </div>
    </div>

    <h6 class="mt-2 mb-0">Administrado</h6>
    <div class="row g-3">
      <div class="col-md-2">
        <label class="form-label">Tipo doc.</label>
        <select class="form-select" name="tipo_doc">
          <option value="">—</option>
          @foreach(['DNI','RUC','CE','PAS'] as $opt)
            <option value="{{ $opt }}" @selected(old('tipo_doc')===$opt)>{{ $opt }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">N° doc.</label>
        <input class="form-control" name="numero_doc" value="{{ old('numero_doc') }}">
      </div>
      <div class="col-md-7">
        <label class="form-label">Razón social / Nombres</label>
        <input class="form-control" name="razon_social" value="{{ old('razon_social') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Correo</label>
        <input class="form-control" name="email" value="{{ old('email') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Teléfono</label>
        <input class="form-control" name="telefono" value="{{ old('telefono') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Dirección</label>
        <input class="form-control" name="direccion" value="{{ old('direccion') }}">
      </div>
    </div>

    <h6 class="mt-2 mb-0">Tipificación (infracciones)</h6>
    <div class="row row-cols-1 row-cols-md-2 g-2">
      @foreach($infracciones as $inf)
        <div class="col">
          <label class="form-check">
            <input class="form-check-input" type="checkbox" name="infracciones[]" value="{{ $inf->id }}">
            <span class="form-check-label">
              <strong>{{ $inf->codigo }}</strong> — {{ $inf->descripcion }}
              <span class="text-muted"> (S/ {{ number_format($inf->multa,2) }})</span>
            </span>
          </label>
        </div>
      @endforeach
      @if($infracciones->isEmpty())
        <div class="col text-muted small">No hay infracciones registradas (admin).</div>
      @endif
    </div>

    <div class="d-flex gap-2 mt-2">
      <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Guardar borrador</button>
      <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">Cancelar</a>
    </div>
  </form>
</div>
@endsection
