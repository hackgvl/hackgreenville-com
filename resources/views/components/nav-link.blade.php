@props(['route', 'icon', 'class' => ''])

<li class="tw-nav-item">
    <a class="tw-nav-link {{ Route::is($route) ? 'tw-active' : '' }} {{ $class }}" href="{{ route($route) }}">
        <i class="tw-d-md-none tw-d-lg-inline-block fa {{ $icon }}"></i>
        {{ $slot }}
    </a>
</li>
