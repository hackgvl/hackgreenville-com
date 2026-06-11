@props(['route'])

<li>
    <a href="{{ route($route) }}" {{ $attributes->class('block px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-primary no-underline transition-colors') }}>
        {{ $slot }}
    </a>
</li>
