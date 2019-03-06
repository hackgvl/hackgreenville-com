@extends('layouts.page')

@section('title', 'Events')

@section('content')
	<h1>Events</h1>
	<h3>Choose a month to filter</h3>
	
	<form>
    <select class="form-control" name="month">
	    @foreach( $months as $month )
	      <option value="<?= $month ?>">
	      	<?= $month ?>
	      </option>
	    @endforeach
    </select>
    
    <button class="btn btn-primary btn-round" type="submit">Submit</button>
  	<a href="<?= explode('?', $_SERVER['REQUEST_URI'])[0] ?>" class="btn btn-outline-primary btn-round" role="button">
    		Clear filter
    </a>
  </form>
	
	<br>
	
	<ul>
		@foreach( $events as $event )
			<li>
			    <a href="{{$event->url}}"><strong>{{ $event->event_name }}</strong></a> hosted by <strong>{{ $event->group_name }}</strong>
		</strong>
		<p>Time: </strong>{{ $event->localtime->format('g:i A, D j M y') }} <br />
			[<a href="{{$event->url}}">RSVP</a> ||
			<a href="{{ build_cal_url( $event ) }}" target="_blank">Add to Google Calendar</a>]
		</p>
	
		</li>
		@endforeach
	</ul>

<p><small>This data is sourced from <a href="https://data.openupstate.org">a community-curated REST API</a>. To contribute or use the API connect with <a href="https://codeforgreenville.org">Code For Greenville.</a></small></p>
@endsection

