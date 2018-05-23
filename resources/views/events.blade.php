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
    
    <?php dd( $_SERVER , $events ) ?>
    
    <button class="btn btn-primary btn-round" type="submit">Submit</button>
  	<a href="<?= explode("?", $_SERVER['REQUEST_URI'])[0] ?>" class="btn btn-primary btn-round btn-simple" role="button">
    		Clear filter
    </a>
  </form>
	
	<br>
	
	<ul>
		@foreach( $events as $event )
			<li>
			  <strong>
			    {{ $event->event_name }} hosted by {{ $event->group_name }}
		    </strong>
		    
		    <a href="<?= build_cal_url( $event ); ?>" target="_blank">
  				<ul>
  					<li><strong>Time:</strong> <?= DateTime::
  					  createFromFormat('Y-m-d\TH:i:s\Z', $event->time, new DateTimeZone('UTC'))
  					  ->setTimezone(new DateTimeZone(getenv('TZ')))
  					  ->format('g:i A, D j M y') ?></li>
  				</ul>
				</a>
			</li>
		@endforeach
	</ul>
@endsection

