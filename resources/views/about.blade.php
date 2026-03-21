@extends('layouts.app')

@section('title', 'About HackGreenville')
@section('description', 'Discover the origins, mission, vision, and values of the HackGreenville community.')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="mb-12">
            <h1 class="text-3xl font-bold">About HackGreenville</h1>
            <p class="text-gray-500 mt-1 text-sm">The story, mission, and people behind Greenville's tech community hub</p>
        </div>

        {{-- History --}}
        <section class="mb-14">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">History</h2>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>
            <div class="max-w-3xl space-y-4">
                <p class="text-gray-700 leading-relaxed">
                    Our journey began as a humble <a href="/join-slack" class="text-primary underline hover:text-blue-600">Slack chat group</a>
                    back in March 2015, thanks to the efforts of Andrew Orr
                    <a class="text-gray-500 hover:text-gray-700 no-underline" href="https://github.com/Soulfire86" rel="nofollow">(@Soulfire86)</a>
                    and Dave Brothers
                    <a class="text-gray-500 hover:text-gray-700 no-underline" href="https://github.com/davebrothers" rel="nofollow">(@davebrothers)</a>.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    In Nov. 2023, HackGreenville established a relationship with
                    <a href="https://refactorgvl.com/" target="refactorgvl" class="text-primary underline hover:text-blue-600">RefactorGVL</a>,
                    a local 501(c)(3) non-profit, to further their mission of elevating the tech community in the Upstate.
                    This collaboration provides infrastructure, fiscal sponsorship, and other support to HackGreenville and the local tech workforce.
                </p>
            </div>
        </section>

        {{-- Initiatives --}}
        <section class="mb-14">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Initiatives</h2>
                <div class="h-px bg-gray-200 flex-1"></div>
                <span class="text-xs text-gray-300 font-medium tabular-nums">3</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Slack --}}
                <div class="group border border-gray-100 rounded-lg p-5 hover:border-gray-200 transition-colors">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 rounded-md bg-primary/5 flex items-center justify-center">
                            <x-lucide-hash aria-hidden="true" class="w-4 h-4 text-primary"/>
                        </div>
                        <h3 class="font-semibold text-gray-900">HG Slack</h3>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Our <a href="/join-slack" class="text-primary underline hover:text-blue-600">Slack community</a>
                        continues to be a daily hub for insightful conversations and discovery.
                    </p>
                </div>

                {{-- Nights --}}
                <div class="group border border-gray-100 rounded-lg p-5 hover:border-gray-200 transition-colors">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 rounded-md bg-primary/5 flex items-center justify-center">
                            <x-lucide-moon aria-hidden="true" class="w-4 h-4 text-primary"/>
                        </div>
                        <h3 class="font-semibold text-gray-900">HG Nights</h3>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Since 2023, we host quarterly-ish
                        <a class="text-primary underline hover:text-blue-600" href="/hg-nights">HackGreenville Nights</a>
                        gatherings with socializing, good food, and optional short talks.
                    </p>
                </div>

                {{-- Labs --}}
                <div class="group border border-gray-100 rounded-lg p-5 hover:border-gray-200 transition-colors">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 rounded-md bg-primary/5 flex items-center justify-center">
                            <x-lucide-flask-conical aria-hidden="true" class="w-4 h-4 text-primary"/>
                        </div>
                        <h3 class="font-semibold text-gray-900">HG Labs</h3>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        This website, the
                        <a href="/docs/ORGS_API.md" class="text-primary underline hover:text-blue-600">Organizations API</a>, and
                        <a href="/docs/EVENTS_API.md" class="text-primary underline hover:text-blue-600">Events API</a>
                        are developed by <a href="/labs" class="text-primary underline hover:text-blue-600">HackGreenville Labs</a>.
                    </p>
                </div>
            </div>

            <p class="text-sm text-gray-500 mt-4 leading-relaxed">
                The HackGreenville.com website was initially brought to life by the <em>SC Codes</em>
                pilot program, and was later nurtured and expanded by <em>Code For Greenville</em>.
            </p>
        </section>

        {{-- Why HG --}}
        <section class="mb-14">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Why HG?</h2>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>

            <p class="text-gray-600 mb-8 text-sm">A snapshot of our community's purpose, mission, vision, culture, and principles.</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Purpose</div>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Nurture personal growth among the vibrant community of
                        "hackers" in Greenville, SC and the surrounding Upstate area.
                    </p>
                </div>
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Mission</div>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Through our <a href="/join-slack" class="text-primary underline hover:text-blue-600">online community</a> and
                        <a href="/" class="text-primary underline hover:text-blue-600">discovery tools</a>,
                        spotlight local, non-commercial tech opportunities for learning, sharing, and connecting.
                    </p>
                </div>
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Vision</div>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Be the first point of call — the <span class="font-medium text-gray-900">"GO TO"</span> — for
                        tech enthusiasts exploring the area's vibrant "hacker" culture and opportunities.
                    </p>
                </div>
            </div>
        </section>

        {{-- Guiding Principles --}}
        <section class="mb-14">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Culture &amp; Guiding Principles</h2>
                <div class="h-px bg-gray-200 flex-1"></div>
                <span class="text-xs text-gray-300 font-medium tabular-nums">6</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div class="flex items-start gap-3 py-2">
                    <x-lucide-shield-check aria-hidden="true" class="w-4 h-4 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Everyone must abide by the <a href="/code-of-conduct" class="text-primary underline hover:text-blue-600">Code of Conduct</a> within our Slack and at in-person events.
                    </p>
                </div>
                <div class="flex items-start gap-3 py-2">
                    <x-lucide-sprout aria-hidden="true" class="w-4 h-4 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        We exist to nurture personal growth, not to bring people down. Constructive debate is welcome, but please do not harass or provoke.
                    </p>
                </div>
                <div class="flex items-start gap-3 py-2">
                    <x-lucide-users aria-hidden="true" class="w-4 h-4 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        We welcome all hackers, makers, and tinker types. Our community extends beyond just software.
                    </p>
                </div>
                <div class="flex items-start gap-3 py-2">
                    <x-lucide-heart aria-hidden="true" class="w-4 h-4 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Be respectful. Egos and biases have no place here.
                    </p>
                </div>
                <div class="flex items-start gap-3 py-2">
                    <x-lucide-arrow-right-left aria-hidden="true" class="w-4 h-4 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Pay it forward. Remember, your knowledge comes from others.
                    </p>
                </div>
                <div class="flex items-start gap-3 py-2">
                    <x-lucide-hand-helping aria-hidden="true" class="w-4 h-4 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Give more than you take. This isn't a community for selfish pursuits.
                    </p>
                </div>
            </div>
        </section>

        {{-- Committee --}}
        <section class="mb-8">
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Committee</h2>
                <div class="h-px bg-gray-200 flex-1"></div>
                <span class="text-xs text-gray-300 font-medium tabular-nums">3</span>
            </div>

            <p class="text-sm text-gray-600 mb-4">Our committee members help with strategic priorities and decisions across HackGreenville's initiatives.</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="https://www.linkedin.com/in/bogdan-kharchenko/"
                   rel="noopener"
                   class="group flex items-center gap-3 py-3 px-4 border border-gray-100 rounded-lg hover:border-gray-200 transition-colors no-underline">
                    <div class="w-8 h-8 rounded-full bg-primary/5 flex items-center justify-center">
                        <x-lucide-user aria-hidden="true" class="w-4 h-4 text-primary/40 group-hover:text-primary/60 transition-colors"/>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700 group-hover:text-primary transition-colors">Bogdan Kharchenko</div>
                        <div class="text-xs text-gray-400 flex items-center gap-1">
                            <x-lucide-linkedin aria-hidden="true" class="w-3 h-3"/> LinkedIn
                        </div>
                    </div>
                </a>

                <div class="flex items-center gap-3 py-3 px-4 border border-gray-100 rounded-lg">
                    <div class="w-8 h-8 rounded-full bg-primary/5 flex items-center justify-center">
                        <x-lucide-user aria-hidden="true" class="w-4 h-4 text-primary/40"/>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700">Eric Anderson</div>
                    </div>
                </div>

                <a href="https://www.linkedin.com/in/pamelawoodbrowne/"
                   rel="noopener"
                   class="group flex items-center gap-3 py-3 px-4 border border-gray-100 rounded-lg hover:border-gray-200 transition-colors no-underline">
                    <div class="w-8 h-8 rounded-full bg-primary/5 flex items-center justify-center">
                        <x-lucide-user aria-hidden="true" class="w-4 h-4 text-primary/40 group-hover:text-primary/60 transition-colors"/>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-700 group-hover:text-primary transition-colors">Pamela Wood Browne</div>
                        <div class="text-xs text-gray-400 flex items-center gap-1">
                            <x-lucide-linkedin aria-hidden="true" class="w-3 h-3"/> LinkedIn
                        </div>
                    </div>
                </a>
            </div>
        </section>
    </div>
@endsection
