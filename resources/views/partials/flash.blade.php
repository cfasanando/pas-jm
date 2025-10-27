@if ($errors->any())
  <div class="alert alert-danger shadow-sm" role="alert">
    <strong>Hay errores:</strong>
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@if (session('ok'))
  <div class="alert alert-success shadow-sm" role="alert">
    {{ session('ok') }}
  </div>
@endif

@if (session('warn'))
  <div class="alert alert-warning shadow-sm" role="alert">
    {{ session('warn') }}
  </div>
@endif
