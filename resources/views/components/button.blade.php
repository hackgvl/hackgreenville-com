@props([
    'href' => null,
    'variant' => 'primary',
])

@php
    $classes = [
        'inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-sm text-center no-underline transition-colors',
        match ($variant) {
            'ghost' => 'bg-white/10 backdrop-blur text-white font-semibold border border-white/20 hover:bg-white/20',
            'outline' => 'border border-gray-200 text-gray-800 font-medium hover:border-gray-300 hover:bg-gray-50',
            default => 'bg-success text-white font-semibold hover:bg-green-600',
        },
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif
