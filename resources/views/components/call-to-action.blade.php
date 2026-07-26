@props(['title'])

<div {{ $attributes->class('flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6') }}>
    <div class="max-w-xl">
        <x-section-heading class="mb-4">{{ $title }}</x-section-heading>
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
