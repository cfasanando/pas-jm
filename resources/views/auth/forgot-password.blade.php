<x-guest-layout>
  <div class="card shadow auth-card overflow-hidden">
    <div class="row g-0">
      <div class="col-12 col-md-6 mx-auto">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="brand-square"></div>
            <div>
              <h1 class="h5 mb-0 fw-bold">¿Olvidaste tu contraseña?</h1>
              <div class="text-muted small">Te enviaremos un enlace para restablecerla.</div>
            </div>
          </div>

          @if (session('status'))
            <div class="alert alert-success small">{{ session('status') }}</div>
          @endif

          <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
            @csrf

            <div class="form-floating">
              <input type="email" name="email" class="form-control rounded-3" id="email" placeholder="correo@jm.gob.pe" value="{{ old('email') }}" required autofocus>
              <label for="email"><i class="bi bi-envelope me-2"></i>Correo electrónico</label>
            </div>

            <button class="btn btn-primary w-100 rounded-3 py-2">Enviar enlace</button>
          </form>

          <div class="text-center mt-3 small">
            <a href="{{ route('login') }}" class="text-decoration-none">Volver a ingresar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
