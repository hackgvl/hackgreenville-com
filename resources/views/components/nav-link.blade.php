@props(['route', 'class' => ''])

<li>
    <a href="{{ route($route) }}"
       class="block py-2.5 nav-break:py-2 px-3 text-base nav-break:text-sm font-medium no-underline rounded-lg transition-colors {{ Route::is($route) ? 'text-white bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }} {{ $class }}">
        {{ $slot }}
    </a>
</li>
