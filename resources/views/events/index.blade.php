@extends('layouts.app')

@section('title', 'List of Greenville, SC Area Tech Events')
@section('description', 'A list view of upcoming tech events happening in the Greenville, SC area.')
@section('canonical')
    <link rel="canonical" href="/events" />
@endsection

@section('content')
    <div class="container max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-bold mb-6">Upcoming Events</h1>

        <x-calendar-feed-promo />

        <div class="mb-8">
            <form method="get">

                <select class="block w-full md:w-3/4 px-3 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary" name="month" id="month">
                    <option value="">Filter by month</option>
                    @foreach( $months->keys() as $month )
                        <option value="{{$month}}" @if(request('month') == $month) selected="selected" @endif>
                            {{$month}}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @foreach($months as $month => $events)
            <div class="events mb-8" data-date="{{ $month }}">
                <h3 class="text-2xl font-bold mb-4">
                    {{ $month }}
                </h3>
                @foreach($events as $event)
                    @include('events._item', ['event' => $event])
                @endforeach
            </div>

        @endforeach

        <ul>
            <li>This data is sourced from <a href="https://data.openupstate.org" rel="external">a community-curated REST API</a>.</li>
            <li>To contribute to this project, please connect with <a href="https://codeforgreenville.org" rel="external">HackGreenville Labs.</a></li>
            <li>To suggest an addition or update to the data, please submit a <a href="https://data.openupstate.org/contact/suggestions">suggestion</a>.</li>
        </ul>

    </div>
@endsection

@section('js')
    <script type="module">
    $(function() {
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
    });
    </script>
@endsection
