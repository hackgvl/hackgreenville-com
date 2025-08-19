@extends('layouts.app')

@section('title', 'HackGreenville Labs')
@section('description', 'Building local Open Source & Open Data tools to support the tech community and beyond')

@section('content')
	<div class="flex flex-wrap text-black bg-white">
		<div class="w-full md:w-1/2 lg:w-5/12 md:ml-[8.333333%] py-20 flex flex-col justify-center text-center">
			<h1 class="text-4xl font-bold mb-4">{{ __('HackGreenville Labs') }}</h1>
			<p class="lead my-1">
				Supporting our HackGreenville tech projects
			</p>
		</div>
		<div id="jumbotron-image" class="m-0 p-0 w-full md:w-1/2 lg:w-6/12 hidden md:block"></div>
	</div>

	<div class="w-10/12 md:w-8/12 xl:w-6/12 mx-auto mt-12 flex flex-col justify-center">
		<h2 class="text-3xl font-bold mb-6">{{ __('Our Projects') }}</h2>
		<div class="flex flex-col my-3 p-0">
			@foreach($projects as $index=>$project)
				@include('labs._project', ['project' => (object) $project, 'index' => $index, 'total' => count($projects)])
			@endforeach
		</div>
	</div>

	<div class="p-20 flex flex-col justify-center text-center">
		<h3 class="text-2xl font-semibold mb-4">{{ __('Want to get involved?') }}</h3>
		<h5 class="text-xl">
		  Join the
			<a href="{{ route('join-slack') }}" class="text-success font-bold hover:underline">
				HackGreenville Slack
			</a>
			and our <em>#hg-labs</em> channel
		</h5>
	</div>
@endsection
