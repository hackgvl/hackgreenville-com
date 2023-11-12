@extends('layouts.app')

@section('title', 'HackGreenville Nights')
@section('description', 'An evening where Greenville\'s tech community gathers for fellowship and short-format talks')

@section('content')
    <div id="hg-nights-info" class="container">
        <h1>{{ __('HackGreenville Nights') }}</h1>
        <p>
            A "quarterly-ish" gathering of Greenville's local tech community for fellowship and learning.
        </p>
        <p>

        </p>

        <a href="https://www.meetup.com/hack-greenville/" type="button" class="btn btn-success" role="button" target="_blank">
            Join our Meetup group to be notified of future events!
        </a>

        <div class="col-10 offset-2 col-sm-8 offset-sm-4 col-xl-12 offset-xl-2 mt-5 mx-auto d-flex flex-column">
            <h2 class="font-weight-bold">{{ __('Past Talks') }}</h2>
            @foreach($events as $eventIndex => $event)
              <div class="row col-10">
                <div class="row col-md-4">
                  <h4 class="font-weight-bold">{{$event['title']}}</h4>
                </div>
                <div class="row col-md-6">
                  <h4 class="">{{$event['date']}} at {{$event['venue']}}</h4>
                </div>
              </div>
              <div class="accordion mb-3 p-0" id="accordion{{$eventIndex}}">
                @foreach($event['talks'] as $talkIndex => $talk)
                  @include('partials.event-card', [
                    'id' => $eventIndex . $talkIndex,
                    'parent_id' => "#accordion" . $eventIndex,
                    'talk' => [
                      'title' => $talk['title'],
                      'speaker' => $talk['speaker']
                    ]])
                @endforeach
              </div>
            @endforeach
        <div>
    </div>
@endsection
