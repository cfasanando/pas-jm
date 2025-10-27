<x-guest-layout>
  <div class="card shadow auth-card overflow-hidden">
    <div class="row g-0">
      <div class="col-12 col-md-6 mx-auto">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="brand-square"></div>
            <div>
              <h1 class="h5 mb-0 fw-bold">Restablecer contraseña</h1>
              <div class="text-muted small">Ingresa tu nueva contraseña.</div>
            </div>
          </div>

          <form method="POST" action="{{ route('password.update') }}" class="vstack gap-3">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="form-floating">
              <input type="email" name="email" class="form-control rounded-3" id="email" placeholder="correo@jm.gob.pe" value="{{ request('email') }}" required>
              <label for="email"><i class="bi bi-envelope me-2"></i>Correo electrónico</label>
            </div>

            <div class="form-floating">
              <input type="password" name="password" class="form-control rounded-3" id="password" placeholder="••••••••" required>
              <label for="password"><i class="bi bi-shield-lock me-2"></i>Nueva contraseña</label>
            </div>

            <div class="form-floating">
              <input type="password" name="password_confirmation" class="form-control rounded-3" id="password_confirmation" placeholder="••••••••" required>
              <label for="password_confirmation"><i class="bi bi-shield-check me-2"></i>Confirmación</label>
            </div>

            <button class="btn btn-primary w-100 rounded-3 py-2">Guardar contraseña</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
