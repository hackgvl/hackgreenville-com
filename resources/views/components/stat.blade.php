@props([
    'value',
    'label',
    'suffix' => '+',
    'icon' => null,
    'variant' => 'dark',
])

<div {{ $attributes }}>
    <div @class([
        'font-mono text-3xl sm:text-4xl tabular-nums tracking-tight',
        'flex items-center gap-2' => $icon,
        'text-white' => $variant === 'dark',
        'text-primary' => $variant !== 'dark',
    ])>
        <span data-countup="{{ $value }}">{{ number_format($value) }}</span>@if ($icon)<x-dynamic-component :component="'lucide-' . $icon" aria-hidden="true" @class([
            'size-6 shrink-0',
            'text-green-300' => $variant === 'dark',
            'text-success' => $variant !== 'dark',
        ])/>@elseif ($suffix !== '')<span @class([
            'text-green-300' => $variant === 'dark',
            'text-success' => $variant !== 'dark',
        ])>{{ $suffix }}</span>@endif
    </div>
    <div @class([
        'text-sm mt-1.5',
        'text-white/60' => $variant === 'dark',
        'text-gray-500' => $variant !== 'dark',
    ])>{{ $label }}</div>
</div>
