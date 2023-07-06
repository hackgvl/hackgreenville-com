@extends('layouts.app')

@section('title', 'List of Greenville, SC Area Tech Events')
@section('description', 'A list view of upcoming tech events happening in the Greenville, SC area.')

@section('content')
	<div class="container">
		<h1>Upcoming Events</h1>

		<div class="mb-4">
			<form method="get">

				<select class="form-control col-md-9" name="month" id="month">
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
			<div class="events mb-4" data-date="{{ $month }}">
				<h3 class="font-weight-bolder">
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
