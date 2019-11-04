@extends('layouts.app')

@section('title', 'Events')

@section('content')
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
                                <option>Please select</option>
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
            <li>
                <a href="{{$event->url}}"><strong>{{ $event->event_name }}</strong></a> hosted by <strong>{{ $event->group_name }}</strong>
                </strong>
                <p>Time: </strong>{{ $event->active_at }} <br/>
                    [<a href="{{$event->url}}">RSVP</a> ||
                    <a href="{{ $event->gCalUrl }}" target="_blank">Add to Google Calendar</a>]
                </p>

            </li>
        @endforeach
    </ul>

    <p><small>This data is sourced from <a href="https://data.openupstate.org">a community-curated REST API</a>. To contribute or use the API connect with <a href="https://codeforgreenville.org">Code For Greenville.</a></small>
    </p>
@endsection

