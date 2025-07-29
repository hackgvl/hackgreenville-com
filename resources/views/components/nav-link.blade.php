@props(['route', 'icon', 'class' => ''])

<li class="nav-item">
    <a class="nav-link {{ Route::is($route) ? 'active' : '' }} {{ $class }}" href="{{ route($route) }}">
        <i class="md:hidden lg:inline-block fa {{ $icon }}"></i>
        {{ $slot }}
    </a>
</li>
