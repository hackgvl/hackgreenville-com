@props(['route', 'icon', 'class' => ''])

<li class="nav-menu-item">
    <a class="nav-menu-link {{ Route::is($route) ? 'active' : '' }} {{ $class }}" href="{{ route($route) }}">
        <i class="md:hidden lg:inline-block fa {{ $icon }}"></i>
        {{ $slot }}
    </a>
</li>
