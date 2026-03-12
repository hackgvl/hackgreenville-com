@extends('layouts.app')

@section('title', $org->title)
@section('description', 'Highlights of the '. $org->title . ' organization of '. $org->city . ', SC, including upcoming events, organizer, and history.')

@section('head')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": {{ \Illuminate\Support\Js::from($org->title) }},
        @if($org->uri)
        "url": {{ \Illuminate\Support\Js::from($org->uri) }},
        @endif
        @if($org->description)
        "description": {{ \Illuminate\Support\Js::from(strip_tags($org->description)) }},
        @endif
        "address": {
            "@type": "PostalAddress",
            "addressLocality": {{ \Illuminate\Support\Js::from($org->city ?? 'Greenville') }},
            "addressRegion": "SC",
            "addressCountry": "US"
        },
        @if($org->established_at)
        "foundingDate": "{{ $org->established_at->format('Y') }}",
        @endif
        "memberOf": {
            "@type": "Organization",
            "name": "HackGreenville",
            "url": "{{ config('app.url') }}"
        }
    }
    </script>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-3 flex-wrap mb-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $org->title }}</h1>
                @if($org->isActive())
                    <span class="inline-block bg-success text-white text-xs font-semibold px-2 py-0.5 rounded">Active</span>
                @else
                    <span class="inline-block bg-gray-400 text-white text-xs font-semibold px-2 py-0.5 rounded">Inactive</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">
                {{ $org->focus_area }} &middot; {{ $org->city }} &middot; Est. {{ $org->established_at->year }}@if($org->inactive_at) &middot; Inactive {{ $org->inactive_at->year }}@endif
            </p>
        </div>

        {{-- Description --}}
        @if($org->description)
            <div class="text-gray-700 leading-relaxed mb-6">
                {!! $org->description !!}
            </div>
        @endif

        {{-- Details --}}
        <div class="border border-gray-200 rounded-lg divide-y divide-gray-100 mb-8 text-sm">
            @if($org->event_calendar_uri)
                <div class="flex flex-col sm:flex-row sm:items-center px-4 py-2.5 gap-1 sm:gap-4">
                    <span class="text-gray-500 sm:w-40 shrink-0 font-medium">Event Homepage</span>
                    <a href="{{ $org->event_calendar_uri }}" rel="noopener" class="text-primary hover:underline truncate">{{ $org->event_calendar_uri }}</a>
                </div>
            @endif
            @if($org->uri)
                <div class="flex flex-col sm:flex-row sm:items-center px-4 py-2.5 gap-1 sm:gap-4">
                    <span class="text-gray-500 sm:w-40 shrink-0 font-medium">Homepage</span>
                    <a href="{{ $org->uri }}" rel="noopener" class="text-primary hover:underline truncate">{{ $org->uri }}</a>
                </div>
            @endif
            @if($org->primary_contact_person)
                <div class="flex flex-col sm:flex-row sm:items-center px-4 py-2.5 gap-1 sm:gap-4">
                    <span class="text-gray-500 sm:w-40 shrink-0 font-medium">Contact</span>
                    <span class="text-gray-800">{{ $org->primary_contact_person }}</span>
                </div>
            @endif
            <div class="flex flex-col sm:flex-row sm:items-center px-4 py-2.5 gap-1 sm:gap-4">
                <span class="text-gray-500 sm:w-40 shrink-0 font-medium">Type</span>
                <span class="text-gray-800">{{ $org->organization_type }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center px-4 py-2.5 gap-1 sm:gap-4">
                <span class="text-gray-500 sm:w-40 shrink-0 font-medium">Subscribe</span>
                <a href="{{ route('calendar-feed.index', ['orgs' => $org->id]) }}" class="text-primary hover:underline inline-flex items-center gap-1">
                    <x-lucide-calendar class="w-3.5 h-3.5 inline"/> Calendar feed
                </a>
            </div>
        </div>

        {{-- Upcoming Events --}}
        @if($org->events->isNotEmpty())
            <h2 class="text-xl font-semibold mb-4">Upcoming Events</h2>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                @foreach($org->events as $event)
                    @include('events._item', ['event' => $event])
                @endforeach
            </div>
        @endif
    </div>
@endsection
