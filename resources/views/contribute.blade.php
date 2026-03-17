@extends('layouts.app')

@section('title', 'Contribute Around Greenville, SC')
@section('description', "Opportunities to sponsor, volunteer, and donate with Upstate, SC tech, maker, and tinker non-profits or open-source projects.")

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="mb-14">
            <h1 class="text-3xl sm:text-4xl font-bold mb-3">Help Build Something That Matters</h1>
            <p class="text-gray-600 text-base max-w-2xl leading-relaxed">
                The Greenville tech community thrives because people like you show up — with time, resources, and heart.
                Every contribution strengthens the network that connects us all.
            </p>
        </div>

        {{-- Ways to help --}}
        <section class="mb-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="relative border border-gray-100 rounded-lg p-6 hover:border-success/30 transition-colors overflow-hidden">
                    <x-lucide-hand-helping aria-hidden="true" class="absolute -right-3 -top-3 w-24 h-24 text-success/[0.12]"/>
                    <h2 class="font-semibold text-gray-900 mb-2 relative">Volunteer</h2>
                    <p class="text-sm text-gray-600 leading-relaxed relative">
                        Give your time or skills to a project that's shaping the next generation of builders in the Upstate.
                    </p>
                </div>

                <div class="relative border border-gray-100 rounded-lg p-6 hover:border-warning/30 transition-colors overflow-hidden">
                    <x-lucide-megaphone aria-hidden="true" class="absolute -right-3 -top-3 w-24 h-24 text-warning/[0.07]"/>
                    <h2 class="font-semibold text-gray-900 mb-2 relative">Sponsor</h2>
                    <p class="text-sm text-gray-600 leading-relaxed relative">
                        Put your brand in front of the community. Connect with <a href="/join-slack" class="text-primary underline hover:text-blue-600">#community-organizers</a> to get started.
                    </p>
                </div>

                <div class="relative border border-gray-100 rounded-lg p-6 hover:border-primary/20 transition-colors overflow-hidden">
                    <x-lucide-heart-handshake aria-hidden="true" class="absolute -right-3 -top-3 w-24 h-24 text-primary/[0.07]"/>
                    <h2 class="font-semibold text-gray-900 mb-2 relative">Donate</h2>
                    <p class="text-sm text-gray-600 leading-relaxed relative">
                        Every dollar goes directly to local non-profits fueling tech education, events, and community spaces.
                    </p>
                </div>

            </div>
        </section>

        {{-- Organization columns --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-12">

            {{-- Volunteer --}}
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Volunteer With</h3>
                    <div class="h-px bg-gray-200 flex-1"></div>
                    <span class="text-xs text-gray-300 font-medium tabular-nums">6</span>
                </div>
                <div>
                    <a href="/orgs/agile-learning-institute" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Agile Learning Institute
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/orgs/carolina-code-conf" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Carolina Code Conference
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/orgs/code-with-the-carolinas" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Code with the Carolinas
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/labs" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        HackGreenville Labs
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/hg-nights" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        HackGreenville Nights
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/orgs/synergy-mill" class="group flex items-center justify-between gap-2 py-2.5 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Synergy Mill
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                </div>
            </section>

            {{-- Sponsor --}}
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Sponsor</h3>
                    <div class="h-px bg-gray-200 flex-1"></div>
                    <span class="text-xs text-gray-300 font-medium tabular-nums">6</span>
                </div>
                <div>
                    <a href="/orgs" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Local Meetup Groups
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/orgs/build-carolina" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Build Carolina
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/hg-nights" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        HackGreenville Nights
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="https://refactorgvl.com" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        RefactorGVL
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/orgs/synergy-mill" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Synergy Mill
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/orgs/upwise" class="group flex items-center justify-between gap-2 py-2.5 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        UpWiSE
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                </div>
            </section>

            {{-- Donate --}}
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Donate To</h3>
                    <div class="h-px bg-gray-200 flex-1"></div>
                    <span class="text-xs text-gray-300 font-medium tabular-nums">4</span>
                </div>
                <div>
                    <a href="/orgs/agile-learning-institute" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Agile Learning Institute
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/orgs/build-carolina" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Build Carolina
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="https://refactorgvl.com" class="group flex items-center justify-between gap-2 py-2.5 border-b border-gray-100 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        RefactorGVL
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                    <a href="/orgs/synergy-mill" class="group flex items-center justify-between gap-2 py-2.5 text-sm text-gray-700 hover:text-primary transition-colors no-underline">
                        Synergy Mill
                        <x-lucide-arrow-up-right aria-hidden="true" class="w-3 h-3 text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"/>
                    </a>
                </div>
            </section>

        </div>

        {{-- Closing CTA --}}
        <div class="mt-16 border-t border-gray-100 pt-10 text-center">
            <p class="text-gray-500 text-sm mb-5">Not sure where to start? Join the conversation and find your fit.</p>
            <a href="/join-slack" class="inline-flex items-center gap-2 bg-success text-white px-6 py-3 rounded-lg text-sm font-semibold no-underline hover:bg-green-600 transition-colors">
                <x-lucide-message-circle aria-hidden="true" class="w-4 h-4"/>
                Join Our Slack
            </a>
        </div>

    </div>
@endsection
