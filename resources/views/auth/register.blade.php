<x-guest-layout>
  <div class="card shadow auth-card overflow-hidden">
    <div class="row g-0 flex-md-row-reverse">
      <div class="col-12 col-md-5 d-none d-md-block auth-illustration"></div>
      <div class="col-12 col-md-7">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="brand-square"></div>
            <div>
              <h1 class="h4 mb-0 fw-bold">Crear cuenta</h1>
              <div class="text-muted small">Acceso para personal autorizado</div>
            </div>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger small"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
          @endif

          <form method="POST" action="{{ route('register') }}" class="w-100">
            @csrf
            <div class="form-floating mb-3">
              <input type="text" name="name" class="form-control rounded-3" id="name" placeholder="Nombres y apellidos" value="{{ old('name') }}" required autofocus>
              <label for="name"><i class="bi bi-person me-2"></i>Nombres y apellidos</label>
            </div>
            <div class="form-floating mb-3">
              <input type="email" name="email" class="form-control rounded-3" id="email" placeholder="correo@jm.gob.pe" value="{{ old('email') }}" required>
              <label for="email"><i class="bi bi-envelope me-2"></i>Correo institucional</label>
            </div>
            <div class="row g-3 w-100">
              <div class="col-12 col-md-6">
                <div class="form-floating">
                  <input type="password" name="password" class="form-control rounded-3" id="password_reg" placeholder="••••••••" required>
                  <label for="password_reg"><i class="bi bi-shield-lock me-2"></i>Contraseña</label>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-floating">
                  <input type="password" name="password_confirmation" class="form-control rounded-3" id="password_confirmation" placeholder="••••••••" required>
                  <label for="password_confirmation"><i class="bi bi-shield-check me-2"></i>Confirmación</label>
                </div>
              </div>
            </div>
            <button class="btn btn-primary w-100 rounded-3 py-2 mt-3">Crear cuenta</button>
            <div class="text-center mt-3 small"><a href="{{ route('login') }}" class="text-decoration-none">Ya tengo cuenta</a></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
