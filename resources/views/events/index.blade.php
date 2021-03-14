@extends('layouts.app')

@section('title', 'Events')

@section('content')
    <div class="container">
        <h1>Events</h1>
        <form method="get">
            <div class="col-md-8 offset-md-2">
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="row">
                                <label for="month" class="px-3 py-1">
                                    Choose a month to filter
                                </label>
                                <select class="form-control col-md-9" name="month" id="month">
                                    <option value="">Please select</option>
                                    @foreach( $months as $month )
                                        <option value="{{$month}}" @if(request('month') == $month) selected="selected" @endif>
                                            {{$month}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="pull-right">
                            <button class="btn btn-primary btn-round" type="submit">Submit</button>
                            <a href="{{explode('?', $_SERVER['REQUEST_URI'])[0]}}" class="btn btn-outline-primary btn-round" role="button">
                                Clear filter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <br>

        <ul>
            @foreach( $events as $event )
                <li class="events" data-date="{{$event->active_at->format('M Y')}}">
                    @if($event->cancelled_at)
                        <span class="text-danger">[CANCELLED] </span>
                        <strong>{{ $event->event_name }}</strong></a> hosted by <strong>{{ $event->group_name }}
                            @else
                                <a href="{{$event->url}}"><strong>{{ $event->event_name }}</strong></a> hosted by
                                <strong>{{ $event->group_name }}</strong>
                            @endif
                            <p>Time:</strong>{{ $event->active_at }} <br />
                        @unless($event->cancelled_at)
                            [<a href="{{$event->url}}">RSVP</a> ||
                            <a href="{{ $event->gCalUrl }}" target="_blank">Add to Google Calendar</a>]
                            @endunless
                            </p>

                </li>
            @endforeach
        </ul>

        <ul>
       	    <li>This data is sourced from <a href="https://data.openupstate.org" target="_blank">a community-curated REST API</a>.</li>
            <li>To contribute to this project, please connect with <a href="https://codeforgreenville.org" target="_blank">Code For Greenville.</a></li>
            <li>To suggest an addition or update to the data, please submit a <a href="https://data.openupstate.org/contact/suggestions">suggestion</a>.</li>
        </ul>

    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(function () {
            /**
             * on change filter the events. If there is nothing selected show all the events.
             **/
            $("#month").change(function () {
                const val = $(this).children('option:selected').val();
                const events = $(".events");
                if (val) {
                    events.hide();
                    $(".events[data-date='" + val + "']").show();

                    return true;
                }

                events.show();
            })
        });
    </script>
@endsection
