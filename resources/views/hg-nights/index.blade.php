@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Nights')
@section('description', 'A quarterly event with social gathering and short talks for Greenville SC tech, hacker, tinkerer, maker, and DIY community members.')

@section('content')
	{{-- Hero --}}
	<x-hero :image="asset('img/hg-nights-sm.jpg')" eyebrow="Quarterly Community Event">
		HackGreenville Nights

		<x-slot:subtitle>
			A gathering of Greenville's tech, hacker, tinkerer, maker, and DIY community. Great food, short talks, and good people.
		</x-slot:subtitle>

		<x-slot:actions>
			<x-button href="https://www.meetup.com/hack-greenville/" rel="noopener" target="_blank">
				Join our Meetup Group
			</x-button>
			<x-button href="https://forms.gle/oz4vDwrwG9c4h5Bo6" rel="nofollow noopener" target="_blank" variant="ghost">
				Submit a Talk
			</x-button>
		</x-slot:actions>
	</x-hero>

	{{-- Submit a Talk + Get Involved --}}
	<div class="max-w-6xl mx-auto px-4 py-14 sm:py-16">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
			<div>
				<h2 class="text-xl font-semibold mb-3">Submit a Talk</h2>
				<p class="text-sm text-gray-600 leading-relaxed mb-2">
					Talks are typically 5, 10, or 15 minutes on tech or tech-adjacent topics that don't fit the format of existing local meetups or conferences.
				</p>
				<p class="text-sm text-gray-600 leading-relaxed mb-4">
					Thinking about starting a new group? Pitch the topic here and get a feel for the level of interest.
				</p>
				<a href="https://forms.gle/oz4vDwrwG9c4h5Bo6"
				   rel="nofollow noopener" target="_blank"
				   class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-blue-700 no-underline transition-colors">
					Submit a talk proposal
					<x-lucide-arrow-right aria-hidden="true" class="w-3.5 h-3.5"/>
				</a>
			</div>
			<div>
				<h2 class="text-xl font-semibold mb-3">Get Involved</h2>
				<ul class="space-y-2 text-sm text-gray-600">
					<li class="flex items-start gap-2">
						<x-lucide-megaphone aria-hidden="true" class="w-4 h-4 text-gray-400 shrink-0 mt-0.5"/>
						<span>Spread the word and invite others to <a href="https://forms.gle/oz4vDwrwG9c4h5Bo6" rel="nofollow noopener" target="_blank" class="text-primary hover:text-blue-700 no-underline">pitch a talk</a></span>
					</li>
					<li class="flex items-start gap-2">
						<x-lucide-calendar aria-hidden="true" class="w-4 h-4 text-gray-400 shrink-0 mt-0.5"/>
						<span>Join our <a href="https://www.meetup.com/hack-greenville/" rel="noopener" target="_blank" class="text-primary hover:text-blue-700 no-underline">Meetup group</a> to receive updates</span>
					</li>
					<li class="flex items-start gap-2">
						<x-lucide-message-square aria-hidden="true" class="w-4 h-4 text-gray-400 shrink-0 mt-0.5"/>
						<span>Hop into the <a href="/join-slack" class="text-primary hover:text-blue-700 no-underline">HackGreenville Slack</a> <em>#community-organizers</em> channel to volunteer</span>
					</li>
					<li class="flex items-start gap-2">
						<x-lucide-heart aria-hidden="true" class="w-4 h-4 text-gray-400 shrink-0 mt-0.5"/>
						<span><a href="/contact" class="text-primary hover:text-blue-700 no-underline">Become a HG Nights sponsor</a></span>
					</li>
				</ul>
			</div>
		</div>
	</div>

	{{-- Past Events --}}
	<div class="bg-gray-50">
		<div class="max-w-6xl mx-auto px-4 py-14 sm:py-16">
			<div class="flex items-center gap-3 mb-8">
				<h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Past Events</h2>
				<div class="h-px bg-gray-200 flex-1"></div>
				<span class="text-xs text-gray-300 font-medium tabular-nums">{{ count($events) }}</span>
			</div>

			<div class="space-y-6">
				@foreach($events as $event)
					@php $event = (object) $event; @endphp
					<div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
						{{-- Event header --}}
						<div class="p-5 sm:p-6">
							<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
								<div>
									<span class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $event->label }}</span>
									<h3 class="text-lg font-bold text-gray-900 mt-0.5">&ldquo;{{ $event->theme }}&rdquo;</h3>
								</div>
								<div class="flex items-center gap-2 shrink-0">
									<a href="{{ $event->recap_url }}" rel="noopener" target="_blank"
									   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors no-underline">
										<x-lucide-external-link aria-hidden="true" class="w-3 h-3"/>
										Recap
									</a>
									@if($event->videos_url)
										<a href="{{ $event->videos_url }}" rel="noopener" target="_blank"
										   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors no-underline">
											<x-lucide-play aria-hidden="true" class="w-3 h-3"/>
											Videos
										</a>
									@endif
								</div>
							</div>

							{{-- Sponsor --}}
							<a href="{{ $event->sponsor_url }}" rel="noopener" target="_blank"
							   class="inline-flex items-center gap-3 px-3 py-2 rounded-md border border-gray-100 no-underline hover:border-gray-200 hover:bg-gray-50 transition-colors group mb-4">
								<span class="text-[0.65rem] font-medium uppercase tracking-wide text-gray-500">Sponsor</span>
								@if($event->sponsor_logo)
									<img src="{{ asset('img/sponsors/' . $event->sponsor_logo) }}" alt="{{ $event->sponsor }}" class="h-6 w-auto max-w-[100px] object-contain" loading="lazy"/>
								@else
									<span class="text-sm font-semibold text-gray-700 group-hover:text-primary transition-colors">{{ $event->sponsor }}</span>
								@endif
							</a>

							{{-- Credits --}}
							<div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
								@if($event->host)
									<span>Hosted by <a href="{{ $event->host_url }}" rel="noopener" target="_blank" class="text-gray-500 hover:text-primary no-underline transition-colors">{{ $event->host }}</a></span>
								@endif
								@if($event->video_by)
									<span>Video by <a href="{{ $event->video_by_url }}" rel="noopener" target="_blank" class="text-gray-500 hover:text-primary no-underline transition-colors">{{ $event->video_by }}</a></span>
								@endif
							</div>
						</div>

						{{-- Speakers --}}
						<div class="border-t border-gray-100">
							@foreach($event->speakers as $speaker)
								@php $speaker = (object) $speaker; @endphp
								<div class="flex items-start gap-3 px-5 sm:px-6 py-2.5 border-b border-gray-50 last:border-b-0 group {{ isset($speaker->url) ? 'hover:bg-blue-50/30' : '' }}">
									<x-lucide-mic aria-hidden="true" class="w-3.5 h-3.5 text-gray-300 shrink-0 mt-1"/>
									<div class="flex-1 min-w-0 text-sm">
										<span class="text-gray-500">{{ $speaker->name }}</span>
										<span class="text-gray-300 mx-1">&mdash;</span>
										@if(isset($speaker->url))
											<a href="{{ $speaker->url }}" rel="noopener" target="_blank"
											   class="text-gray-700 hover:text-primary no-underline transition-colors"><em>{{ $speaker->title }}</em></a>
										@else
											<em class="text-gray-600">{{ $speaker->title }}</em>
										@endif
									</div>
									@if(isset($speaker->url))
										<a href="{{ $speaker->url }}" rel="noopener" target="_blank"
										   class="text-gray-200 group-hover:text-red-500 shrink-0 mt-1 transition-colors"
										   aria-label="Watch {{ $speaker->title }} on YouTube">
											<x-lucide-youtube aria-hidden="true" class="w-3.5 h-3.5"/>
										</a>
									@endif
								</div>
							@endforeach
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>

	{{-- CTA --}}
	<div class="max-w-6xl mx-auto px-4 py-14 sm:py-16">
		<x-cta title="Want to sponsor or speak?">
			Reach out via the contact form or hop into our Slack.

			<x-slot:actions>
				<x-button href="{{ route('contact') }}" variant="outline" class="whitespace-nowrap">
					Contact Us
				</x-button>
				<x-button href="{{ route('join-slack') }}" class="whitespace-nowrap">
					Join Slack
				</x-button>
			</x-slot:actions>
		</x-cta>
	</div>
@endsection
