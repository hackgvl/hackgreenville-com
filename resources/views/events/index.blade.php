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
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Upcoming Events</h1>

        <div class="mb-6">
            <x-calendar-feed-promo />
        </div>

        <div class="mb-6">
            <form method="get">
                <select class="block w-full md:w-auto px-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary bg-white" name="month" id="month">
                    <option value="">Filter by month</option>
                    @foreach( $months->keys() as $month )
                        <option value="{{$month}}" @if(request('month') == $month) selected="selected" @endif>
                            {{$month}}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @foreach($months as $month => $events)
                <div class="events" data-date="{{ $month }}">
                    <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $month }}
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($events as $event)
                            @include('events._item', ['event' => $event])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 text-sm text-gray-600">
            <ul class="list-disc pl-5 space-y-2">
                <li>This data is sourced from <a href="https://data.openupstate.org" rel="noopener" class="text-primary hover:underline">a community-curated REST API</a>.</li>
                <li>To contribute to this project, please connect with <a href="https://codeforgreenville.org" rel="noopener" class="text-primary hover:underline">HackGreenville Labs.</a></li>
                <li>To suggest an addition or update to the data, please submit a <a href="https://data.openupstate.org/contact/suggestions" class="text-primary hover:underline">suggestion</a>.</li>
            </ul>
        </div>

    </div>
@endsection

@section('js')
    <script>
    /**
     * on change filter the events. If there is nothing selected show all the events.
     **/
    $('#month').change(function() {
        const val = $(this).children('option:selected').val();
        const events = $('.events');
        if (val) {
            events.hide();
            $('.events[data-date=\'' + val + '\']').show();

            return true;
        }

        events.show();
    });
    </script>
@endsection
