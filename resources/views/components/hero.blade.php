@props([
    'mode' => null,
    'image' => null,
    'eyebrow' => null,
    'canvasId' => 'hero-canvas',
])

@php
    // Background mode: 'image', 'interactive', or 'plain'. Defaults to
    // 'image' when an image is given, otherwise 'plain'.
    $mode = $mode ?? ($image ? 'image' : 'plain');
@endphp

<div {{ $attributes->class('w-full text-white bg-primary relative overflow-hidden min-h-[28rem] sm:min-h-[32rem] flex items-center') }}>
    @if ($mode === 'image' && $image)
        <img src="{{ $image }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center scale-105" aria-hidden="true"/>
        <div class="absolute inset-0 bg-linear-to-r from-primary/95 via-primary/65 to-primary/35 z-2"></div>
    @elseif ($mode === 'interactive')
        <canvas id="{{ $canvasId }}" class="absolute inset-0 w-full h-full pointer-events-none" aria-hidden="true"></canvas>
    @endif
    {{ $media ?? '' }}
    <div class="max-w-6xl mx-auto w-full relative z-10 py-16 sm:py-20 px-6 sm:px-8">
        <div class="max-w-2xl">
            @if ($eyebrow)
                <p class="font-mono text-xs sm:text-sm tracking-[0.18em] uppercase text-green-300 mb-4 sm:mb-6">{{ $eyebrow }}</p>
            @endif
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-white leading-tight">
                {{ $slot }}
            </h1>
            @isset($subtitle)
                <p class="text-lg sm:text-xl text-gray-300 mt-5 sm:mt-6 max-w-lg">
                    {{ $subtitle }}
                </p>
            @endisset
            @isset($actions)
                <div class="flex flex-col sm:flex-row gap-3 mt-8 sm:mt-10">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </div>
</div>
