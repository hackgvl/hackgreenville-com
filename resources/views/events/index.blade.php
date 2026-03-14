@extends('layouts.app')

@section('title', 'List of Greenville, SC Area Tech Events')
@section('description', 'A list view of upcoming tech events happening in the Greenville, SC area.')

@section('head')
    @php
        $allEvents = $months->flatten();
        $jsonLdItems = $allEvents->map(function ($event, $i) {
            $item = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'item' => [
                    '@type' => 'Event',
                    'name' => $event->event_name,
                    'startDate' => $event->active_at->toIso8601String(),
                    'eventStatus' => 'https://schema.org/' . ($event->cancelled_at ? 'EventCancelled' : 'EventScheduled'),
                    'url' => $event->url,
                    'eventAttendanceMode' => 'https://schema.org/MixedEventAttendanceMode',
                ],
            ];
            if ($event->expire_at) {
                $item['item']['endDate'] = $event->expire_at->toIso8601String();
            }
            if ($event->organization) {
                $item['item']['organizer'] = [
                    '@type' => 'Organization',
                    'name' => $event->group_name,
                    'url' => route('orgs.show', $event->organization),
                ];
            }
            return $item;
        })->values();
    @endphp
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Upcoming Tech Events in Greenville, SC',
        'itemListElement' => $jsonLdItems,
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-3xl font-bold">Upcoming Events</h1>
            <p class="text-gray-500 mt-1 text-sm">
                Aggregated from meetup groups, conferences, and community organizations across the Greenville, SC area
            </p>
        </div>

        {{-- Calendar feed promo + filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <form method="get" class="m-0">
                <label for="month" class="sr-only">Filter by month</label>
                <select class="block w-full sm:w-auto px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary bg-white" name="month" id="month">
                    <option value="">All months</option>
                    @foreach( $months->keys() as $month )
                        <option value="{{$month}}" @if(request('month') == $month) selected="selected" @endif>
                            {{$month}}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('calendar-feed.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary no-underline transition-colors">
                <x-lucide-calendar-check aria-hidden="true" class="w-3.5 h-3.5"/>
                Subscribe to calendar feed
            </a>
        </div>

        {{-- Events list --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            @foreach($months as $month => $events)
                <div class="events" data-date="{{ $month }}">
                    <div class="flex items-center gap-3 px-4 sm:px-6 py-3 bg-gray-50/80 border-b border-gray-200">
                        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">{{ $month }}</h2>
                        <div class="h-px bg-gray-200 flex-1"></div>
                        <span class="text-xs text-gray-300 font-medium tabular-nums">{{ $events->count() }}</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($events as $event)
                            @include('events._item', ['event' => $event])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Footer notes --}}
        <div class="mt-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Sources &amp; Data</span>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="flex items-start gap-2.5">
                    <x-lucide-database aria-hidden="true" class="w-3.5 h-3.5 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Aggregated from <a href="https://meetup.com" rel="noopener" class="text-primary hover:text-blue-600 underline">Meetup</a>, <a href="https://lu.ma" rel="noopener" class="text-primary hover:text-blue-600 underline">Luma</a>, and <a href="https://eventbrite.com" rel="noopener" class="text-primary hover:text-blue-600 underline">Eventbrite</a>
                    </p>
                </div>
                <div class="flex items-start gap-2.5">
                    <x-lucide-git-pull-request aria-hidden="true" class="w-3.5 h-3.5 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Contribute via <a href="/labs" class="text-primary hover:text-blue-600 underline">HackGreenville Labs</a>
                    </p>
                </div>
                <div class="flex items-start gap-2.5">
                    <x-lucide-message-square-plus aria-hidden="true" class="w-3.5 h-3.5 text-gray-300 shrink-0 mt-0.5"/>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        <a href="{{ route('contact') }}" class="text-primary hover:text-blue-600 underline">Suggest</a> an addition or update
                    </p>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>
    document.getElementById('month').addEventListener('change', function() {
        const val = this.value;
        document.querySelectorAll('.events').forEach(function(el) {
            el.style.display = val ? (el.dataset.date === val ? '' : 'none') : '';
        });
    });
    </script>
@endsection
