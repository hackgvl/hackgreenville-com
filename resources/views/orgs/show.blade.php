@extends('layouts.app')

@section('title', $org->title)
@section('description', 'Highlights of the '. $org->title . ' organization of '. $org->city . ', SC, including upcoming events, organizer, and history.')

@section('head')
    @php
        $orgSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $org->title,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $org->city ?? 'Greenville',
                'addressRegion' => 'SC',
                'addressCountry' => 'US',
            ],
            'memberOf' => [
                '@type' => 'Organization',
                'name' => 'HackGreenville',
                'url' => config('app.url'),
            ],
        ];
        if ($org->uri) $orgSchema['url'] = $org->uri;
        if ($org->description) $orgSchema['description'] = strip_tags($org->description);
        if ($org->established_at) $orgSchema['foundingDate'] = $org->established_at->format('Y');
    @endphp
    <script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">

        {{-- Back link --}}
        <a href="{{ route('orgs.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary no-underline transition-colors mb-6">
            <x-lucide-arrow-left aria-hidden="true" class="w-3.5 h-3.5"/>
            Organizations
        </a>

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 flex-wrap mb-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $org->title }}</h1>
                @if(!$org->isActive())
                    <span class="inline-block text-gray-400 text-xs font-semibold border border-gray-300 px-2 py-0.5 rounded">Inactive</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">
                {{ $org->focus_area }} &middot; {{ $org->city }} &middot; Est. {{ $org->established_at->year }}@if($org->inactive_at) &middot; Inactive {{ $org->inactive_at->year }}@endif
            </p>
        </div>

        {{-- Description --}}
        @if($org->description)
            <div class="text-gray-700 leading-relaxed mb-10">
                {!! $org->description !!}
            </div>
        @endif

        {{-- Details --}}
        <section class="mb-10">
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Details</h2>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>

            <div class="space-y-0 text-sm">
                @if($org->event_calendar_uri)
                    <div class="flex flex-col sm:flex-row sm:items-center py-2.5 border-b border-gray-100 gap-1 sm:gap-4">
                        <span class="text-gray-500 sm:w-36 shrink-0">Event Homepage</span>
                        <a href="{{ $org->event_calendar_uri }}" rel="noopener" class="text-primary hover:text-blue-600 no-underline truncate transition-colors">{{ $org->event_calendar_uri }}</a>
                    </div>
                @endif
                @if($org->uri)
                    <div class="flex flex-col sm:flex-row sm:items-center py-2.5 border-b border-gray-100 gap-1 sm:gap-4">
                        <span class="text-gray-500 sm:w-36 shrink-0">Homepage</span>
                        <a href="{{ $org->uri }}" rel="noopener" class="text-primary hover:text-blue-600 no-underline truncate transition-colors">{{ $org->uri }}</a>
                    </div>
                @endif
                @if($org->primary_contact_person)
                    <div class="flex flex-col sm:flex-row sm:items-center py-2.5 border-b border-gray-100 gap-1 sm:gap-4">
                        <span class="text-gray-500 sm:w-36 shrink-0">Contact</span>
                        <span class="text-gray-700">{{ $org->primary_contact_person }}</span>
                    </div>
                @endif
                <div class="flex flex-col sm:flex-row sm:items-center py-2.5 border-b border-gray-100 gap-1 sm:gap-4">
                    <span class="text-gray-500 sm:w-36 shrink-0">Type</span>
                    <span class="text-gray-700">{{ $org->organization_type }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center py-2.5 gap-1 sm:gap-4">
                    <span class="text-gray-500 sm:w-36 shrink-0">Subscribe</span>
                    <a href="{{ route('calendar-feed.index', ['orgs' => $org->id]) }}" class="text-gray-500 hover:text-primary no-underline inline-flex items-center gap-1.5 transition-colors">
                        <x-lucide-rss aria-hidden="true" class="w-3.5 h-3.5"/> Calendar feed
                    </a>
                </div>
            </div>
        </section>

        {{-- Upcoming Events --}}
        @if($org->events->isNotEmpty())
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Upcoming Events</h2>
                    <div class="h-px bg-gray-200 flex-1"></div>
                    <span class="text-xs text-gray-300 font-medium tabular-nums">{{ $org->events->count() }}</span>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden divide-y divide-gray-100">
                    @foreach($org->events as $event)
                        @include('events._item', ['event' => $event])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
