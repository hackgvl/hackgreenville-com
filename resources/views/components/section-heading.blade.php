@props(['level' => 2, 'align' => 'left'])

<div {{ $attributes->class([
    'text-center' => $align === 'center',
    'flex flex-wrap items-end justify-between gap-4' => isset($actions),
]) }}>
    <div>
        <div @class(['w-9 h-1 rounded-full bg-success mb-4', 'mx-auto' => $align === 'center']) aria-hidden="true"></div>
        <h{{ $level }} @class([
            'font-bold tracking-tight text-primary',
            'text-3xl' => $level == 1,
            'text-2xl sm:text-3xl' => $level != 1,
        ])>{{ $slot }}</h{{ $level }}>
    </div>
    @isset($actions)
        <div class="shrink-0 pb-1">
            {{ $actions }}
        </div>
    @endisset
</div>
