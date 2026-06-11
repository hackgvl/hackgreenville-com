@props(['title'])

<div {{ $attributes->class('flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6') }}>
    <div class="max-w-xl">
        <div class="w-9 h-1 rounded-full bg-success mb-4" aria-hidden="true"></div>
        <h2 class="text-2xl font-bold tracking-tight text-primary mb-2">{{ $title }}</h2>
        @if ($slot->isNotEmpty())
            <p class="text-base text-gray-600">{{ $slot }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex flex-col sm:flex-row gap-3 shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>
