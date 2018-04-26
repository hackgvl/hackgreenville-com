@extends('layouts.page')

@section('title', 'Events')

@section('content')
	<h1>Events</h1>
	<h3>Choose a month to filter</h3>
	
	<a href="<?= explode("?", $_SERVER['REQUEST_URI'])[0] ?>">
	  Clear filter
	</a>
	<br />
	
	@foreach( $months as $month )
	  <a href=<?= '?month=' . rawurlencode($month) ?>>
	    <?= $month ?>
    </a>
    <br />
	@endforeach
	
	<ul>
		@foreach( $events as $event )
			<li>
			  <strong>
			    {{ $event->event_name }} hosted by {{ $event->group_name }}
		    </strong>
		    
		    <a href=<?= build_cal_url( $event ); ?> target="_blank">
  				<ul>
  					<li><strong>Time:</strong> <?= DateTime::
  					  createFromFormat('Y-m-d\TH:i:s\Z', $event->time)
  					  ->format('g:i A, D j M y') ?></li>
  				</ul>
				</a>
			</li>
		@endforeach
	</ul>
@endsection