@props(['route', 'icon', 'class' => ''])

<li>
    <a class="nav-link {{ Route::is($route) ? 'active' : '' }} {{ $class }}" href="{{ route($route) }}">
        <i class="d-md-none d-lg-inline-block fa {{ $icon }}"></i>
        {{ $slot }}
    </a>
</li>
