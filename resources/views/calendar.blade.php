@extends('layouts.page')

@section('head')
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

@endsection

@section('title', 'Calendar')

@section('content')
  <h1>Calendar</h1>
  {!! $calendar->calendar() !!}
  {!! $calendar->script() !!}

<p><small>This data is sourced from <a href="https://data.openupstate.org">a community-curated REST API</a>. To contribute or use the API connect with <a href="https://codeforgreenville.org">Code For Greenville.</a></small></p>
@endsection
