@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')
@section('description', 'Discover tech meetups, events, and organizations in Greenville, SC. Connect with local hackers, makers, and tinkerers through our Slack community and calendar of upcoming events.')

@section('content')
    <div id="homepage" class="overflow-x-hidden">
        {{-- Hero --}}
        <x-hero id="homepage-jumbotron" :image="asset('img/hackgreenville-banner.jpg')" image-position="center top" eyebrow="Upstate SC Tech Community">
            Find Your People.<br class="hidden sm:block"/>
            Grow Yourself.<br class="hidden sm:block"/>
            Build the Upstate.

            <x-slot:subtitle>
                Join hundreds of Upstate hackers, makers, and tinkerers sharing meetups, talks, and projects &mdash; HackGreenville is your guide to getting connected, personal growth, and giving back.
            </x-slot:subtitle>

            <x-slot:actions>
                <x-button href="/join-slack">Join Our Slack</x-button>
                <x-button href="{{ route('events.index') }}" variant="ghost">Browse Events</x-button>
            </x-slot:actions>

            <x-slot:footer>
                {{-- Individuals and Slack counts come from COMMUNITY_* env vars (config/community.php) --}}
                <div class="grid grid-cols-2 gap-6 lg:flex lg:gap-0 lg:divide-x divide-white/15">
                    <x-stat :value="$stats['orgs']" label="active organizations" icon="building" class="lg:pr-12"/>
                    <x-stat :value="$stats['events_this_month']" label="events this month" icon="calendar-days" class="lg:px-12"/>
                    @if($stats['active_individuals'])<x-stat :value="$stats['active_individuals']" label="active individuals" icon="user" class="lg:px-12"/>@endif
                    @if($stats['slack_members'])<x-stat :value="$stats['slack_members']" label="Slack members" class="lg:pl-12"/>@endif
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
                <x-section-heading class="mb-8">Explore the Community</x-section-heading>
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
            </div>
        </div>

        {{-- Get Involved --}}
        <div class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <x-call-to-action title="Get Involved">
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
                </x-call-to-action>
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
    </script>
@endsection
