@props([
    'label',
    'href' => null,
    'align' => 'left',
    'active' => false,
])

@php
    $triggerClasses = [
        'flex items-center gap-1 py-2 px-3 text-sm font-medium no-underline rounded-lg transition-colors',
        $active ? 'text-white bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5',
    ];
@endphp

<li class="hidden nav-break:block relative group">
    @if ($href)
        <a href="{{ $href }}" aria-haspopup="true" @class($triggerClasses)>
            {{ $label }}
            <x-lucide-chevron-down aria-hidden="true" class="size-3.5 transition-transform group-hover:rotate-180"/>
        </a>
    @else
        <button aria-haspopup="true" @class($triggerClasses)>
            {{ $label }}
            <x-lucide-chevron-down aria-hidden="true" class="size-3.5 transition-transform group-hover:rotate-180"/>
        </button>
    @endif
    <div @class([
        'invisible group-hover:visible group-focus-within:visible opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition-all duration-150 absolute top-full pt-2 z-50',
        'right-0' => $align === 'right',
        'left-0' => $align !== 'right',
    ])>
        <ul role="list" class="bg-white rounded-xl shadow-lg ring-1 ring-black/5 p-1 min-w-40 list-none pl-0 mb-0">
            {{ $slot }}
        </ul>
    </div>
</li>
