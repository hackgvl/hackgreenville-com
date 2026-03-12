@extends('layouts.app')

@section('title', 'HackGreenville Labs')
@section('description', 'Building local Open Source & Open Data tools to support the tech community and beyond')

@section('content')
	{{-- Hero --}}
	<div class="max-w-4xl mx-auto px-4 py-12 md:py-16">
		<div class="flex flex-col md:flex-row items-center gap-10">
			<div class="md:w-5/12 text-center md:text-left">
				<h1 class="text-3xl font-bold mb-3">{{ __('HackGreenville Labs') }}</h1>
				<p class="text-lg text-gray-600">
					Supporting our HackGreenville tech projects
				</p>
			</div>
			<div class="md:w-7/12 hidden md:block">
				<img src="{{ asset('img/labs.png') }}" alt="HackGreenville Labs" class="w-full h-auto rounded-lg" loading="lazy">
			</div>
		</div>
	</div>

	{{-- Projects --}}
	<div class="max-w-3xl mx-auto px-4 pb-12">
		<h2 class="text-2xl font-bold mb-4">{{ __('Our Projects') }}</h2>
		<div class="border border-gray-200 rounded-lg overflow-hidden divide-y divide-gray-200">
			@foreach($projects as $index => $project)
				@include('labs._project', ['project' => (object) $project])
			@endforeach
		</div>
	</div>

	{{-- CTA --}}
	<div class="max-w-3xl mx-auto px-4 pb-16 text-center">
		<p class="text-gray-600">
			Want to get involved? Join the
			<a href="{{ route('join-slack') }}" class="text-success font-semibold hover:underline">HackGreenville Slack</a>
			and our <em>#hg-labs</em> channel.
		</p>
	</div>
@endsection
