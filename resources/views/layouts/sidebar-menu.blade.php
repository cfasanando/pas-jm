@php
    // Returns 'active' if current route matches any of the given patterns
    $active = function (...$patterns) {
        foreach ($patterns as $p) {
            if (request()->routeIs($p)) {
                return 'active';
            }
        }

        return '';
    };
@endphp

@auth
<ul class="list-unstyled m-0 p-0">

    {{-- Common items for any authenticated user --}}
    <li class="mb-1">
        <a class="nav-link {{ $active('dashboard') }}" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
    </li>

    <li class="mb-1">
        <a class="nav-link {{ $active('mapa') }}" href="{{ route('mapa') }}">
            <i class="bi bi-geo-alt me-2"></i>Mapa
        </a>
    </li>

    <li class="mb-1">
        <a class="nav-link {{ $active('expedientes') }}" href="{{ route('expedientes') }}">
            <i class="bi bi-folder2-open me-2"></i>Expedientes
        </a>
    </li>

    {{-- Actas and tickets: admin + inspector --}}
    @if (auth()->user()->hasAnyRole(['admin', 'inspector']))
        <li class="mb-1">
            <a class="nav-link {{ $active('actas.index', 'actas.show', 'evidencias.*') }}" href="{{ route('actas.index') }}">
                <i class="bi bi-journal-text me-2"></i>Actas
            </a>
        </li>

        <li class="mb-1">
            <a class="nav-link {{ $active('actas.create') }}" href="{{ route('actas.create') }}">
                <i class="bi bi-clipboard-plus me-2"></i>Registrar acta
            </a>
        </li>

        <li class="mb-1">
            <a class="nav-link {{ $active('boletas', 'boletas.*') }}" href="{{ route('boletas') }}">
                <i class="bi bi-file-earmark-text me-2"></i>Boletas
            </a>
        </li>
    @endif

    {{-- Administration: admin only --}}
    @if (auth()->user()->hasRole('admin'))
        <li class="mb-1">
            <a class="nav-link {{ $active('admin') }}" href="{{ route('admin', ['tab' => request('tab', 'users')]) }}">
                <i class="bi bi-gear me-2"></i>Administración
            </a>
        </li>
    @endif

</ul>
@endauth
