@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')
@section('description', 'Discover tech meetups, events, and organizations in Greenville, SC. Connect with local hackers, makers, and tinkerers through our Slack community and calendar of upcoming events.')

@section('content')
    <div id="homepage" class="overflow-x-hidden">
        {{-- Hero --}}
        <x-hero id="homepage-jumbotron" :image="asset('img/hackgreenville-banner.jpg')" eyebrow="Upstate SC Tech Community">
            Find Your People.<br class="hidden sm:block"/>
            Grow Your Career.<br class="hidden sm:block"/>
            Build the Upstate.

            <x-slot:subtitle>
                Join hundreds of Upstate hackers, makers, and tinkerers sharing meetups, talks, and projects &mdash; HackGreenville is your guide to getting connected, finding work, and giving back.
            </x-slot:subtitle>

            <x-slot:actions>
                <x-button href="/join-slack">Join Our Slack</x-button>
                <x-button href="{{ route('events.index') }}" variant="ghost">Browse Events</x-button>
            </x-slot:actions>

            <x-slot:footer>
                {{-- 15 events/month and 1,800 Slack members are manually maintained --}}
                <div class="flex flex-col gap-6 sm:flex-row sm:gap-0 sm:divide-x divide-white/15">
                    <x-stat :value="$stats['orgs']" label="active organizations" class="sm:pr-12"/>
                    <x-stat :value="15" label="events listed each month" class="sm:px-12"/>
                    <x-stat :value="1800" label="Slack members" class="sm:pl-12"/>
                </div>
            </x-slot:footer>
        </x-hero>

        {{-- About + Image --}}
        <div class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="text-center lg:text-left">
                        <img src="{{ url('img/meetup.jpeg') }}" alt="HackGreenville community meetup" class="max-w-full h-auto rounded-lg shadow-md" loading="lazy">
                    </div>
                    <div>
                        <x-section-heading class="mb-4">What is HackGreenville?</x-section-heading>
                        <p class="text-base text-gray-700 leading-relaxed mb-3">
                            HackGreenville is a community of "hackers" in and around Greenville, SC. We exist to foster personal growth through sharing and promoting local tech opportunities.
                        </p>
                        <p class="text-base text-gray-700 leading-relaxed">
                            We're the go-to resource for discovering and connecting with Upstate SC tech hackers, makers, and tinkerers. Explore the site for meetups and events, or join our active
                            <a href="/join-slack" class="text-primary hover:text-blue-600 underline">Slack community</a> to connect further.
                        </p>
                        @if ($categories->isNotEmpty())
                            <div class="flex flex-wrap gap-2 mt-5">
                                @foreach ($categories as $category)
                                    <span class="font-mono text-xs uppercase tracking-wide text-success border border-success/35 rounded-full px-3 py-1">{{ $category }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tech we cover --}}
                @php
                    $techLogos = collect([
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
                    $logoPool = $techLogos->slice(10)
                        ->map(fn ($label, $slug) => ['src' => asset("img/tech/$slug.svg"), 'alt' => $label])
                        ->values();
                @endphp
                <div class="mt-12 sm:mt-16">
                    <p class="font-mono text-xs tracking-[0.18em] uppercase text-success mb-3">Across the stack</p>
                    <p class="text-base text-gray-700 leading-relaxed max-w-xl mb-8 text-pretty">
                        Our groups cover numerous technology and design sectors &mdash; web, mobile, data, DevOps, design systems, open source, and more.
                    </p>
                    <div id="tech-logos"
                         class="grid grid-cols-5 gap-2 sm:gap-3"
                         aria-hidden="true"
                         data-pool="{{ $logoPool->toJson() }}">
                        @foreach ($techLogos->take(10) as $slug => $label)
                            <div class="flex items-center justify-center h-14 sm:h-20 rounded-xl border border-gray-950/5 bg-gray-50/60">
                                <img src="{{ asset("img/tech/$slug.svg") }}" alt="{{ $label }}"
                                     class="h-5 sm:h-8 w-auto max-w-[65%] transition duration-300" loading="lazy"/>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Events --}}
        <div class="bg-gray-50 py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <x-section-heading class="mb-8">
                    Upcoming Events
                    <x-slot:actions>
                        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-success hover:text-green-700 no-underline transition-colors">
                            View all events
                            <x-lucide-arrow-right aria-hidden="true" class="w-3.5 h-3.5"/>
                        </a>
                    </x-slot:actions>
                </x-section-heading>
                @if ($upcoming_events->isEmpty())
                    <p class="text-gray-500">
                        <strong class="font-bold">No</strong> events to display.
                    </p>
                @else
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden divide-y divide-gray-100">
                        @foreach ($upcoming_events as $event)
                            @include('events._item', ['event' => $event])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Explore the community --}}
        <div class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <x-section-heading class="mb-8">Explore the community</x-section-heading>
                <div class="grid grid-cols-1 md:grid-cols-[6fr_5fr] gap-4">
                    <a href="{{ route('hg-nights.index') }}" class="group relative rounded-2xl overflow-hidden min-h-56 flex items-end no-underline">
                        <img src="{{ asset('img/hg-nights-sm.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover" aria-hidden="true" loading="lazy"/>
                        <div class="absolute inset-0 bg-linear-to-t from-primary/90 via-primary/40 to-primary/10"></div>
                        <div class="relative p-6">
                            <p class="font-mono text-xs tracking-[0.14em] uppercase text-green-300 mb-1.5">Quarterly event</p>
                            <div class="flex items-center gap-1.5 text-white text-lg font-bold tracking-tight">
                                HG Nights
                                <x-lucide-arrow-up-right aria-hidden="true" class="w-4 h-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"/>
                            </div>
                            <p class="text-white/80 text-sm mt-1">Short talks, great food, good people.</p>
                        </div>
                    </a>
                    <a href="{{ route('labs.index') }}" class="group relative rounded-2xl overflow-hidden min-h-56 flex items-end bg-primary no-underline">
                        <svg viewBox="0 0 300 220" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="absolute inset-0 w-full h-full opacity-50" preserveAspectRatio="xMidYMid slice">
                            <g stroke="#6d63a8" stroke-width="0.6" fill="none">
                                <path d="M30 40 L90 70 L150 30 L210 80 L270 50"/>
                                <path d="M50 140 L110 100 L170 150 L240 110"/>
                                <path d="M90 70 L110 100 M150 30 L170 150 M210 80 L240 110"/>
                            </g>
                            <g fill="#9a90d6">
                                <circle cx="30" cy="40" r="2.5"/><circle cx="90" cy="70" r="2.5"/><circle cx="150" cy="30" r="2.5"/><circle cx="210" cy="80" r="2.5"/><circle cx="270" cy="50" r="2.5"/><circle cx="50" cy="140" r="2.5"/><circle cx="110" cy="100" r="2.5"/><circle cx="170" cy="150" r="2.5"/><circle cx="240" cy="110" r="2.5"/>
                            </g>
                        </svg>
                        <div class="relative p-6">
                            <p class="font-mono text-xs tracking-[0.14em] uppercase text-green-300 mb-1.5">Open source</p>
                            <div class="flex items-center gap-1.5 text-white text-lg font-bold tracking-tight">
                                HackGreenville Labs
                                <x-lucide-arrow-up-right aria-hidden="true" class="w-4 h-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"/>
                            </div>
                            <p class="text-white/80 text-sm mt-1">Open source tools &amp; public APIs.</p>
                        </div>
                    </a>
                </div>
                <a href="{{ route('events.index') }}" class="group mt-4 rounded-2xl bg-gray-50 border border-gray-950/5 flex flex-col sm:flex-row sm:items-center gap-6 p-6 no-underline">
                    @if ($upcoming_events->isNotEmpty())
                        <div class="flex gap-2 shrink-0">
                            @foreach ($upcoming_events->take(3) as $event)
                                <div class="w-12 rounded-lg border border-gray-950/10 overflow-hidden text-center bg-white">
                                    <div class="bg-success/10 font-mono text-[0.6875rem] font-medium uppercase tracking-wide text-success py-0.5">
                                        {{ $event->active_at->format('M') }}
                                    </div>
                                    <div class="text-lg font-semibold text-primary tabular-nums py-0.5">
                                        {{ $event->active_at->format('j') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="font-mono text-xs tracking-[0.14em] uppercase text-success mb-1.5">Events calendar</p>
                        <div class="flex items-center gap-1.5 text-primary text-lg font-bold tracking-tight">
                            Find an event or meetup
                            <x-lucide-arrow-up-right aria-hidden="true" class="w-4 h-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"/>
                        </div>
                        <p class="text-gray-600 text-sm mt-1">Browse every upcoming tech event across the Upstate &mdash; list or calendar view.</p>
                    </div>
                    <x-lucide-chevron-right aria-hidden="true" class="hidden sm:block w-5 h-5 text-gray-300 group-hover:text-primary transition-colors shrink-0"/>
                </a>
            </div>
        </div>

        {{-- Get Involved --}}
        <div class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <x-cta title="Get Involved">
                    HackGreenville is open source and community-driven. Contribute code, suggest features, or help improve the platform.

                    <x-slot:actions>
                        <x-button href="https://github.com/hackgvl/hackgreenville-com" rel="noopener" variant="outline">
                            <x-lucide-github aria-hidden="true" class="w-5 h-5"/>
                            View on GitHub
                        </x-button>
                        <x-button href="{{ route('contribute') }}" variant="outline">
                            <x-lucide-handshake aria-hidden="true" class="w-5 h-5"/>
                            Volunteer &amp; Sponsor
                        </x-button>
                    </x-slot:actions>
                </x-cta>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module">
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const counters = document.querySelectorAll('[data-countup]');
    if (counters.length && !prefersReducedMotion) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }
                observer.unobserve(entry.target);
                const el = entry.target;
                const target = parseInt(el.dataset.countup, 10);
                const start = performance.now();
                const duration = 1200;
                const tick = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(target * eased).toLocaleString('en-US');
                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    }
                };
                requestAnimationFrame(tick);
            });
        }, { threshold: 0.4 });
        counters.forEach((el) => observer.observe(el));
    }

    const grid = document.getElementById('tech-logos');
    if (grid && !prefersReducedMotion) {
        const pool = JSON.parse(grid.dataset.pool);
        const slots = [...grid.querySelectorAll('img')];
        const counts = slots.map(() => 0);
        if (pool.length && slots.length) {
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
        }
    }
    </script>
@endsection
