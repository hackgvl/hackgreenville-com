{{--
    Logo cloud section: a mono headline, supporting copy, and a grid of logo
    tiles that rotate through a larger pool of marks.

    Usage:
        <x-logo-list/>

        <x-logo-list headline="Powered by" :visible="5"
                     :logos="['laravel' => 'Laravel', 'vuedotjs' => 'Vue.js', 'tailwindcss' => 'Tailwind CSS']">
            Custom supporting copy for this instance.
        </x-logo-list>

    - `headline`  — the small mono uppercase line above the copy.
    - default slot — the supporting copy paragraph; omit to use the default text.
    - `logos`     — [slug => label] map; each slug resolves to public/img/tech/{slug}.svg.
                    Defaults to the full built-in tech list below.
    - `visible`   — how many tiles show at once (default 10). The first `visible`
                    entries render immediately; the rest rotate in one at a time
                    every 1.5s, replacing a random least-recently-swapped tile.
                    Rotation is skipped for visitors who prefer reduced motion,
                    and a visible logo never appears twice at the same time.
--}}
@props([
    'headline' => 'Across the stack',
    'logos' => null,
    'visible' => 10,
])

@php
    $logos = collect($logos ?? [
        'php' => 'PHP',
        'ruby' => 'Ruby',
        'python' => 'Python',
        'laravel' => 'Laravel',
        'linux' => 'Linux',
        'javascript' => 'JavaScript',
        'nodedotjs' => 'Node.js',
        'react' => 'React',
        'vuedotjs' => 'Vue.js',
        'docker' => 'Docker',
        'git' => 'Git',
        'mysql' => 'MySQL',
        'postgresql' => 'PostgreSQL',
        'tailwindcss' => 'Tailwind CSS',
        'typescript' => 'TypeScript',
        'wordpress' => 'WordPress',
        'rust' => 'Rust',
        'go' => 'Go',
        'kotlin' => 'Kotlin',
        'swift' => 'Swift',
        'flutter' => 'Flutter',
        'angular' => 'Angular',
        'svelte' => 'Svelte',
        'astro' => 'Astro',
        'vite' => 'Vite',
        'kubernetes' => 'Kubernetes',
        'graphql' => 'GraphQL',
        'redis' => 'Redis',
        'sass' => 'Sass',
        'html5' => 'HTML5',
        'css' => 'CSS',
        'figma' => 'Figma',
        'github' => 'GitHub',
        'alpinedotjs' => 'Alpine.js',
    ]);
    $logoPool = $logos->slice($visible)
        ->map(fn ($label, $slug) => ['src' => asset("img/tech/$slug.svg"), 'alt' => $label])
        ->values();
@endphp

<div {{ $attributes->class('py-16 sm:py-20') }}>
    <div class="max-w-6xl mx-auto px-4">
        <p class="font-mono text-xs tracking-[0.18em] uppercase text-success mb-3">{{ $headline }}</p>
        <p class="text-base text-gray-700 leading-relaxed max-w-xl mb-8 text-pretty">
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                Our communities include individuals from numerous technology and design sectors &mdash; web, mobile, data, DevOps, design systems, open source, and more.
            @endif
        </p>
        <div data-logo-list
             class="grid grid-cols-5 gap-2 sm:gap-3"
             aria-hidden="true"
             data-pool="{{ $logoPool->toJson() }}">
            @foreach ($logos->take($visible) as $slug => $label)
                <div class="flex items-center justify-center h-14 sm:h-20 rounded-xl border border-gray-950/5 bg-gray-50/60">
                    <img src="{{ asset("img/tech/$slug.svg") }}" alt="{{ $label }}"
                         class="h-5 sm:h-8 w-auto max-w-[65%] transition duration-300" loading="lazy"/>
                </div>
            @endforeach
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script type="module">
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.querySelectorAll('[data-logo-list]').forEach((grid) => {
                const pool = JSON.parse(grid.dataset.pool);
                const slots = [...grid.querySelectorAll('img')];
                const counts = slots.map(() => 0);
                if (!pool.length || !slots.length) {
                    return;
                }
                setInterval(() => {
                    if (document.hidden) {
                        return;
                    }
                    const min = Math.min(...counts);
                    const candidates = counts.flatMap((count, i) => count === min ? [i] : []);
                    const slot = candidates[Math.floor(Math.random() * candidates.length)];
                    counts[slot]++;
                    const img = slots[slot];
                    const next = pool.shift();
                    pool.push({ src: img.src, alt: img.alt });
                    img.classList.add('opacity-0', 'translate-y-1', 'scale-95');
                    setTimeout(() => {
                        img.src = next.src;
                        img.alt = next.alt;
                        img.classList.remove('opacity-0', 'translate-y-1', 'scale-95');
                    }, 300);
                }, 1500);
            });
        }
        </script>
    @endpush
@endonce
