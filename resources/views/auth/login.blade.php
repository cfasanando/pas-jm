<x-guest-layout>
  <div class="card shadow auth-card overflow-hidden">
    <div class="row g-0 flex-md-row-reverse">
      <div class="col-12 col-md-5 d-none d-md-block auth-illustration"></div>
      <div class="col-12 col-md-7">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="brand-square"></div>
            <div>
              <h1 class="h4 mb-0 fw-bold">Ingresar</h1>
              <div class="text-muted small">{{ $brand['name'] ?? config('app.name') }}</div>
            </div>
          </div>

          @if (session('status')) <div class="alert alert-success small">{{ session('status') }}</div> @endif
          @if ($errors->any())
            <div class="alert alert-danger small"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
          @endif

          <form method="POST" action="{{ route('login') }}" class="w-100 vstack gap-3">
            @csrf
            <div class="form-floating">
              <input type="email" name="email" class="form-control rounded-3" id="email" placeholder="correo@jm.gob.pe" value="{{ old('email') }}" required autofocus>
              <label for="email"><i class="bi bi-envelope me-2"></i>Correo electrónico</label>
            </div>
            <div class="form-floating position-relative">
              <input type="password" name="password" class="form-control rounded-3" id="password" placeholder="••••••••" required>
              <label for="password"><i class="bi bi-shield-lock me-2"></i>Contraseña</label>
              <button class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-decoration-none" type="button" data-toggle="password" data-target="#password">
                <i class="bi bi-eye-slash" id="toggleIcon"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
              </div>
              @if (Route::has('password.request'))
                <a class="small text-decoration-none" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
              @endif
            </div>
            <button class="btn btn-primary w-100 rounded-3 py-2"><i class="bi bi-box-arrow-in-right me-1"></i> Ingresar</button>
          </form>

          @if (Route::has('register'))
            <div class="text-center mt-3 small text-muted">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-decoration-none">Regístrate</a></div>
          @endif

          <div class="text-center mt-4 small text-muted">
          © {{ date('Y') }} {{ $brand['name'] ?? config('app.name') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
