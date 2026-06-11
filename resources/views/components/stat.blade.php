@props([
    'value',
    'label',
    'suffix' => '+',
    'variant' => 'dark',
])

<div {{ $attributes }}>
    <div @class([
        'font-mono text-3xl sm:text-4xl tabular-nums tracking-tight',
        'text-white' => $variant === 'dark',
        'text-primary' => $variant !== 'dark',
    ])>
        <span data-countup="{{ $value }}">{{ number_format($value) }}</span><span @class([
            'text-green-300' => $variant === 'dark',
            'text-success' => $variant !== 'dark',
        ])>{{ $suffix }}</span>
    </div>
    <div @class([
        'text-sm mt-1.5 truncate',
        'text-white/60' => $variant === 'dark',
        'text-gray-500' => $variant !== 'dark',
    ])>{{ $label }}</div>
</div>
