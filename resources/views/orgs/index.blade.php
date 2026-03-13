@extends('layouts.app')

@section('title', 'Tech Organizations near Greenville, SC')
@section('description', 'A list of tech meetups, code schools, tech conferences / hack-a-thons near Greenville, SC, including inactive organizations.')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">

        <div class="mb-10">
            <h1 class="text-3xl font-bold">Local Tech Organizations</h1>
            <p class="text-gray-500 mt-1 text-sm">Groups, conferences, and programs in the Greenville, SC area</p>
        </div>

        @php
            $activeCategories = $activeOrgs->filter(fn($orgs) => !$orgs->first()->category->isInactive());
        @endphp

        <div class="space-y-10">
            @foreach($activeCategories as $organizations)
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">{{ $organizations->first()->category->label }}</h2>
                        <div class="h-px bg-gray-200 flex-1"></div>
                        <span class="text-xs text-gray-300 font-medium tabular-nums">{{ $organizations->count() }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-1">
                        @foreach($organizations as $org)
                            <div class="group flex items-center justify-between gap-2 py-2 border-b border-gray-100 last:border-b-0">
                                <a href="{{ route('orgs.show', $org) }}"
                                   title="{{ $org->title }}"
                                   class="text-gray-700 group-hover:text-primary text-sm truncate transition-colors">
                                    {{ $org->title }}
                                </a>
                                @if($org->event_calendar_uri)
                                    <a href="{{ $org->event_calendar_uri }}"
                                       rel="noopener"
                                       class="text-gray-200 group-hover:text-gray-400 transition-colors shrink-0"
                                       title="Events site">
                                        <x-lucide-external-link class="w-3 h-3"/>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        {{-- Inactive orgs --}}
        @foreach ($activeOrgs as $organizations)
            @if($organizations->first()->category->isInactive())
                <details class="mt-10 group">
                    <summary class="flex items-center gap-3 cursor-pointer select-none mb-4">
                        <h2 class="text-xs font-bold text-gray-300 uppercase tracking-widest whitespace-nowrap group-hover:text-gray-400 transition-colors">{{ $organizations->first()->category->label }}</h2>
                        <div class="h-px bg-gray-200 flex-1"></div>
                        <span class="text-xs text-gray-300 font-medium tabular-nums">{{ $organizations->count() }}</span>
                        <x-lucide-chevron-down class="w-3.5 h-3.5 text-gray-300 group-open:rotate-180 transition-transform"/>
                    </summary>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-1">
                        @foreach($organizations as $org)
                            <a href="{{ route('orgs.show', $org) }}"
                               title="{{ $org->title }}"
                               class="text-gray-400 hover:text-gray-600 text-sm truncate block py-2 border-b border-gray-50 transition-colors">
                                {{ $org->title }}
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif
        @endforeach

    </div>
@endsection
