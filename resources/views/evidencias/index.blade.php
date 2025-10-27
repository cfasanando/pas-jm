@extends('layouts.app')

@section('title','Evidencias')

@section('content')
@php
  // Fallback por si el controlador no pasó $evidencias
  $evidencias = $evidencias ?? ($acta->evidencias ?? collect());
@endphp

<div class="container py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="mb-0">Evidencias — Acta {{ $acta->numero ?? 'BORRADOR' }}</h5>
    <div class="d-flex gap-2">
      @if($acta->estado === 'borrador')
        <form class="d-inline" method="POST" action="{{ route('actas.emit',$acta) }}">
          @csrf
          <button class="btn btn-sm btn-primary">
            <i class="bi bi-check2-circle me-1"></i> Emitir acta
          </button>
        </form>
      @endif

      @if(empty($acta->boleta) && $acta->estado !== 'anulada')
        <form class="d-inline" method="POST" action="{{ route('boletas.fromActa',$acta) }}">
          @csrf
          <button class="btn btn-sm btn-success">
            <i class="bi bi-receipt me-1"></i> Generar boleta
          </button>
        </form>
      @endif
    </div>
  </div>

  @if (session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if (session('warn')) <div class="alert alert-warning">{{ session('warn') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="row g-3">
    {{-- LISTADO + SUBIDA --}}
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="fw-semibold"><i class="bi bi-paperclip me-2"></i>Evidencias</span>

          {{-- Subida múltiple (files[]) --}}
          <form method="POST" action="{{ route('evidencias.store',$acta) }}"
                enctype="multipart/form-data" class="d-flex gap-2">
            @csrf
            <input type="file" name="files[]" class="form-control form-control-sm" multiple
                   accept="image/*,video/mp4,video/quicktime,application/pdf">
            <button class="btn btn-sm btn-outline-primary">
              <i class="bi bi-upload me-1"></i> Subir
            </button>
          </form>
        </div>

        <div class="card-body">
          <div class="row g-3">
            @forelse($evidencias as $ev)
              @php
                $mime = strtolower($ev->mime ?? '');
                $isVideo = str_starts_with($mime, 'video/');
                $isPdf   = $mime === 'application/pdf';
                $url    = $ev->path ? \Storage::url($ev->path) : '';
                $thumb  = $ev->thumb_path ? \Storage::url($ev->thumb_path) : $url;
                $pesoKb = $ev->size ? number_format($ev->size / 1024, 0) : '0';
              @endphp

              <div class="col-6 col-md-4">
                <div class="border rounded h-100 d-flex flex-column">
                  <div class="p-2">
                    @if($isVideo)
                      <video class="w-100 rounded" controls src="{{ $url }}"></video>
                      <div class="small text-muted mt-1"><i class="bi bi-film"></i> {{ $ev->mime }}</div>
                    @elseif($isPdf)
                      <div class="d-flex align-items-center gap-2 small text-muted">
                        <i class="bi bi-filetype-pdf"></i> PDF
                      </div>
                    @else
                      <img class="img-fluid rounded" src="{{ $thumb }}" alt="evidencia"
                           onerror="this.style.display='none'">
                      @if(!$url)
                        <div class="small text-muted mt-1">Archivo no disponible</div>
                      @endif
                    @endif
                  </div>

                  <div class="px-2 pb-2 mt-auto d-flex justify-content-between align-items-center">
                    <small class="text-muted">{{ $pesoKb }} KB</small>
                    <div class="d-flex gap-1">
                      @if($url)
                        <a class="btn btn-sm btn-outline-secondary" href="{{ $url }}" target="_blank"
                           title="Ver/Descargar">
                          <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                      @endif
                      <form method="POST" action="{{ route('evidencias.destroy',$ev) }}"
                            onsubmit="return confirm('¿Eliminar evidencia?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="col-12">
                <div class="text-muted">Sin evidencias aún.</div>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- LATERAL: TIPIFICACIÓN + ADMINISTRADO --}}
    <div class="col-lg-4">
      {{-- Tipificación --}}
    <div class="card mb-3">
        <div class="card-header"><i class="bi bi-list-check me-2"></i>Tipificación</div>
        <div class="card-body">
           <form method="POST" action="{{ route('tipificaciones.store',$acta) }}" class="vstack gap-2">
            @csrf
            <select name="infraccion_id" class="form-select" required>
                <option value="">Seleccione infracción…</option>
                @foreach(\App\Models\Infraccion::orderBy('codigo')->get() as $inf)
                <option value="{{ $inf->id }}">{{ $inf->codigo }} — {{ $inf->descripcion }}</option>
                @endforeach
            </select>

            {{-- antes: name="monto" --}}
            <input name="multa" class="form-control" type="number" step="0.01" min="0" placeholder="Multa (S/)" required>

            {{-- antes: name="observaciones" --}}
            <textarea name="observacion" class="form-control" rows="2" placeholder="Observación (opcional)"></textarea>

            <button class="btn btn-primary">Agregar</button>
            </form>

            <hr>
            <ul class="list-group">
            @foreach($acta->tipificaciones as $t)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $t->infraccion->codigo }} — {{ $t->infraccion->descripcion }}</span>
                <div>
                    {{-- antes: $t->monto --}}
                    <span class="me-3">S/ {{ number_format($t->multa,2) }}</span>
                    <form class="d-inline" method="POST" action="{{ route('tipificaciones.destroy',$t) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Quitar">X</button>
                    </form>
                </div>
                </li>
            @endforeach
            </ul>

            <div class="text-end mt-2">
            {{-- antes: sum('monto') --}}
            <strong>Total: S/ {{ number_format($acta->tipificaciones->sum('multa'), 2) }}</strong>
            </div>
        </div>
    </div>

    {{-- Ubicación del acta --}}
<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="bi bi-geo-alt me-2"></i>Ubicación del acta</span>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-sm btn-outline-secondary" id="btnUseGPS">
        <i class="bi bi-crosshair"></i> Mi ubicación
      </button>
      <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUbicacion">
        <i class="bi bi-map"></i> Elegir en mapa
      </button>
    </div>
  </div>
  <div class="card-body">
    <form class="row g-2" method="POST" action="{{ route('actas.ubicacion', $acta) }}">
      @csrf @method('PUT')
      <div class="col-6">
        <label class="form-label small">Latitud</label>
        <input class="form-control" name="lat" id="inpLat" value="{{ $acta->lat }}">
      </div>
      <div class="col-6">
        <label class="form-label small">Longitud</label>
        <input class="form-control" name="lng" id="inpLng" value="{{ $acta->lng }}">
      </div>
      <div class="col-12 text-end">
        <button class="btn btn-primary btn-sm">Guardar ubicación</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Picker de mapa --}}
<div class="modal fade" id="modalUbicacion" tabindex="-1" aria-labelledby="modalUbicacionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="modalUbicacionLabel">Seleccionar ubicación</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-0">
        <div id="mapPicker" style="height: 420px;"></div>
      </div>
      <div class="modal-footer">
        <div class="me-auto small text-muted">
          Click en el mapa para colocar el pin. Arrastra el pin si lo necesitas.
        </div>
        <button type="button" class="btn btn-primary btn-sm" id="btnSaveCoords">
          Usar estas coordenadas
        </button>
      </div>
    </div>
  </div>
</div>

@once
  {{-- Leaflet (solo aquí; si prefieres, muévelo a tu layout global) --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endonce

<script>
  (function(){
    let map, marker;
    const modalEl = document.getElementById('modalUbicacion');
    const latInput = document.getElementById('inpLat');
    const lngInput = document.getElementById('inpLng');
    const btnUseGPS = document.getElementById('btnUseGPS');
    const btnSaveCoords = document.getElementById('btnSaveCoords');

    // Centro por defecto (Jesús María, Lima aprox.)
    const FALLBACK = { lat: -12.081, lng: -77.049 };

    function currentCenter(){
      const lat = parseFloat(latInput.value);
      const lng = parseFloat(lngInput.value);
      if(!isNaN(lat) && !isNaN(lng)) return {lat, lng};
      return FALLBACK;
    }

    function setMarker(lat, lng){
      if(!map) return;
      if(!marker){
        marker = L.marker([lat, lng], {draggable:true}).addTo(map);
        marker.on('dragend', (e)=>{
          const p = e.target.getLatLng();
          latTemp = p.lat; lngTemp = p.lng;
        });
      } else {
        marker.setLatLng([lat, lng]);
      }
      latTemp = lat; lngTemp = lng;
    }

    let latTemp = null, lngTemp = null;

    // Inicializar/mostrar mapa al abrir modal
    modalEl.addEventListener('shown.bs.modal', function () {
      const center = currentCenter();

      if(!map){
        map = L.map('mapPicker').setView([center.lat, center.lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        setMarker(center.lat, center.lng);

        map.on('click', function(e){
          setMarker(e.latlng.lat, e.latlng.lng);
        });
      }else{
        map.setView([center.lat, center.lng], 16);
        setMarker(center.lat, center.lng);
        setTimeout(()=> map.invalidateSize(), 150);
      }
    });

    // Guardar coordenadas del modal a los inputs
    btnSaveCoords.addEventListener('click', function(){
      if(latTemp!=null && lngTemp!=null){
        latInput.value = latTemp.toFixed(6);
        lngInput.value = lngTemp.toFixed(6);
      }
      const modal = bootstrap.Modal.getInstance(modalEl);
      modal.hide();
    });

    // Tomar posición del navegador
    btnUseGPS.addEventListener('click', function(){
      if(!navigator.geolocation){
        alert('Geolocalización no soportada en este navegador.');
        return;
      }
      btnUseGPS.disabled = true;
      navigator.geolocation.getCurrentPosition(
        (pos)=>{
          btnUseGPS.disabled = false;
          const {latitude, longitude} = pos.coords;
          latInput.value = latitude.toFixed(6);
          lngInput.value = longitude.toFixed(6);

          if(map){
            map.setView([latitude, longitude], 17);
            setMarker(latitude, longitude);
          }
        },
        (err)=>{
          btnUseGPS.disabled = false;
          alert('No se pudo obtener ubicación: ' + err.message);
        },
        {enableHighAccuracy:true, timeout:8000, maximumAge:0}
      );
    });
  })();
</script>


      <div class="card">
        <div class="card-header"><i class="bi bi-person-badge me-2"></i>Administrado</div>
        <div class="card-body small">
          <div><strong>Doc:</strong> {{ optional($acta->administrado)->tipo_doc }} {{ optional($acta->administrado)->numero_doc }}</div>
          <div><strong>Razón social:</strong>
            {{ optional($acta->administrado)->razon_social
               ?? (optional($acta->administrado)->nombres ? optional($acta->administrado)->nombres.' '.optional($acta->administrado)->apellidos : '') }}
          </div>
          <div><strong>Email:</strong> {{ optional($acta->administrado)->email }}</div>
          <div><strong>Dirección:</strong> {{ optional($acta->administrado)->direccion }}</div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
