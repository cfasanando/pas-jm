<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body{ font-family: DejaVu Sans, sans-serif; font-size:12px; }
    .title{ font-size:18px; font-weight:bold; margin-bottom:10px; }
    .box{ border:1px solid #ddd; padding:10px; margin-bottom:10px; }
    .grid{ display:flex; gap:10px; }
    .col{ flex:1; }
  </style>
</head>
<body>
  <div class="title">Boleta de Infracción — {{ $boleta->serie }}-{{ str_pad($boleta->numero,6,'0',STR_PAD_LEFT) }}</div>

  <div class="box">
    <strong>Administrado:</strong>
    {{ optional($boleta->acta->administrado)->razon_social ?? '—' }}<br>
    <strong>Documento:</strong>
    {{ optional($boleta->acta->administrado)->tipo_doc }} {{ optional($boleta->acta->administrado)->numero_doc }}<br>
    <strong>Lugar:</strong> {{ $boleta->acta->lugar }}
  </div>

  <div class="box">
    <strong>Acta N°:</strong> {{ $boleta->acta->numero }}<br>
    <strong>Fecha/Hora:</strong> {{ $boleta->acta->fecha }} {{ $boleta->acta->hora }}<br>
    <strong>Inspector:</strong> {{ $boleta->acta->inspector->name }}
  </div>

  <div class="box">
    <strong>Infracciones tipificadas</strong>
    <ul>
      @foreach($boleta->acta->tipificaciones as $t)
      <li>{{ $t->infraccion->codigo }} — {{ $t->infraccion->descripcion }} (S/ {{ number_format($t->multa,2) }})</li>
      @endforeach
    </ul>
    <strong>Total:</strong> S/ {{ number_format($boleta->monto, 2) }}
  </div>

  <div class="grid">
    <div class="col">
      {{-- QR simple como texto (SVG en base64) --}}
      <img src="data:image/svg+xml;base64,{{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->generate(route('boletas.show',$boleta))) }}">
    </div>
    <div class="col">
      <small>
        Verificación: {{ route('boletas.show',$boleta) }}<br>
        Hash: {{ $boleta->hash }}
      </small>
    </div>
  </div>

  <hr>
  <small>{{ $settings['pdf.footer'] ?? ($brand['name'] ?? config('app.name')) }}</small>
</body>
</html>
