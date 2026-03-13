@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Labs')
@section('description', 'Building local Open Source & Open Data tools to support the tech community and beyond')

@section('content')
	{{-- Hero --}}
	<div class="bg-primary text-white">
		<div class="max-w-5xl mx-auto px-4 py-16 sm:py-20">
			<div class="max-w-2xl">
				<p class="text-sm font-medium tracking-widest uppercase text-green-300 mb-4">Open Source</p>
				<h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-4">HackGreenville Labs</h1>
				<p class="text-lg text-blue-100 leading-relaxed max-w-lg">
					We build and maintain open source tools and public APIs that power the local tech community. Everything we make is free to use and open to contributors.
				</p>
			</div>
		</div>
	</div>

	{{-- Projects --}}
	<div class="max-w-5xl mx-auto px-4 py-16 sm:py-20">
		<div class="flex items-center gap-3 mb-8">
			<h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Projects</h2>
			<div class="h-px bg-gray-200 flex-1"></div>
			<span class="text-xs text-gray-300 font-medium tabular-nums">{{ count($projects) }}</span>
		</div>

		<div class="space-y-4">
			@foreach($projects as $project)
				@php $project = (object) $project; @endphp
				<div class="rounded-lg border border-gray-200 overflow-hidden">
					<a href="{{ $project->link }}" rel="noopener" target="_blank"
					   class="group flex items-start gap-4 p-5 hover:bg-blue-50/30 transition-colors no-underline">
						<div class="shrink-0 w-9 h-9 rounded-lg bg-gray-100 group-hover:bg-primary/10 flex items-center justify-center transition-colors">
							@if($project->linkType === 'github')
								<x-lucide-github class="w-4 h-4 text-gray-400 group-hover:text-primary transition-colors"/>
							@else
								<x-lucide-globe class="w-4 h-4 text-gray-400 group-hover:text-primary transition-colors"/>
							@endif
						</div>
						<div class="flex-1 min-w-0">
							<div class="flex items-center gap-2 mb-1">
								<span class="text-gray-900 font-semibold text-sm group-hover:text-primary transition-colors">{{ $project->name }}</span>
								@if($project->status === 'active')
									<span class="text-[0.65rem] font-bold uppercase tracking-wide text-green-600">Active</span>
								@elseif($project->status === 'considering')
									<span class="text-[0.65rem] font-bold uppercase tracking-wide text-yellow-600">Considering</span>
								@elseif($project->status === 'retired')
									<span class="text-[0.65rem] font-bold uppercase tracking-wide text-gray-400">Retired</span>
								@endif
							</div>
							<p class="text-sm text-gray-500 leading-snug">{{ $project->description }}</p>
						</div>
						<x-lucide-arrow-up-right class="w-4 h-4 text-gray-300 group-hover:text-primary shrink-0 mt-0.5 transition-colors"/>
					</a>

					@if(!empty($project->children))
						<div class="border-t border-gray-100 bg-gray-50/50">
							@foreach($project->children as $child)
								@php $child = (object) $child; @endphp
								<a href="{{ $child->link }}" rel="noopener" target="_blank"
								   class="group flex items-center gap-3 px-5 py-3 pl-[4.25rem] hover:bg-blue-50/40 transition-colors no-underline border-b border-gray-100 last:border-b-0">
									<div class="flex-1 min-w-0">
										<span class="text-sm text-gray-700 font-medium group-hover:text-primary transition-colors">{{ $child->name }}</span>
										<span class="text-gray-400 mx-1.5">&middot;</span>
										<span class="text-sm text-gray-400">{{ $child->description }}</span>
									</div>
									<x-lucide-arrow-up-right class="w-3.5 h-3.5 text-gray-300 group-hover:text-primary shrink-0 transition-colors"/>
								</a>
							@endforeach
						</div>
					@endif
				</div>
			@endforeach
		</div>
	</div>

	{{-- CTA --}}
	<div class="bg-gray-50">
		<div class="max-w-5xl mx-auto px-4 py-14 sm:py-16">
			<div class="flex flex-col sm:flex-row items-center justify-between gap-6">
				<div>
					<h2 class="text-xl font-semibold mb-1">Want to contribute?</h2>
					<p class="text-gray-600 text-sm">Join our Slack and hop into <em class="font-medium">#hg-labs</em> to get started.</p>
				</div>
				<a href="{{ route('join-slack') }}" class="inline-block bg-success text-white px-6 py-3 rounded-lg text-sm font-semibold no-underline hover:bg-green-600 transition-colors whitespace-nowrap">
					Join Slack
				</a>
			</div>
		</div>
	</div>
@endsection
