@props(['route', 'class' => ''])

<li class="tw-nav-item">
    <a class="tw-nav-link {{ Route::is($route) ? 'tw-active' : '' }} {{ $class }}" href="{{ route($route) }}">
        {{ $slot }}
    </a>
</li>
