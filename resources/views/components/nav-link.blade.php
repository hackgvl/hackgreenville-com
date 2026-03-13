@props(['route', 'class' => ''])

<li>
    <a href="{{ route($route) }}"
       class="block py-3 nav-break:py-2 px-4 nav-break:px-2 text-sm font-medium no-underline transition-colors text-center nav-break:text-left {{ Route::is($route) ? 'text-white font-semibold' : 'text-white/70 hover:text-white' }} {{ $class }}">
        {{ $slot }}
    </a>
</li>
