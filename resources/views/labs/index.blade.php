@extends('layouts.app')

@section('title', 'HackGreenville Labs')
@section('description', 'Building local Open Source & Open Data tools to support the tech community and beyond')

@section('content')
	<div class="no-gutters row text-black bg-white">
		<div class="col-sm-12 offset-md-2 col-md-4 container py-5 d-flex flex-column justify-content-center text-center">
			<h1>{{ __('HackGreenville Labs') }}</h1>
			<p class="lead my-1">
				Supporting our HackGreenville tech projects
			</p>
		</div>
		<div id="jumbotron-image" class="m-0 p-0 col-md-6 d-none d-md-block"></div>
	</div>

	<div class="col-10 offset-2 col-sm-8 offset-sm-4 col-xl-6 offset-xl-6 mt-5 mx-auto d-flex flex-column justify-content-center">
		<h2 class="font-weight-bold">{{ __('Our Projects') }}</h2>
		<div class="list-group my-3 p-0">
			@foreach($projects as $index=>$project)
				@include('labs._project', ['project' => (object) $project, 'index' => $index, 'total' => count($projects)])
			@endforeach
		</div>
	</div>

	<div class="p-5 d-flex flex-column justify-content-center text-center">
		<h3>{{ __('Want to get involved?') }}</h3>
		<h5>
		  Join the
			<a href="{{ route('join-slack') }}" class="text-success font-weight-bold">
				HackGreenville Slack
			</a>
			and our <em>#hg-labs</em> channel
		</h5>
	</div>
@endsection
