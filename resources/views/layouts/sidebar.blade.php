<aside class="app-sidebar d-none d-md-flex flex-column p-3 border-end bg-white">
  <div class="mb-3 d-flex align-items-center gap-2">
    <span class="brand-square"></span><strong>Fiscalización</strong>
  </div>
  @include('layouts.sidebar-menu')
  <div class="mt-auto small text-muted">
    {{ now()->format('d M Y, H:i') }}<br>Lima, PE
  </div>
</aside>
