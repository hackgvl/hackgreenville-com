<?php /** @var \App\Models\Event $event */ ?>

<div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
	<div class="flex items-center gap-3 md:grid md:grid-cols-12 md:gap-4">
		<div class="hidden md:block md:col-span-3">
			<a href="{{ $event->toGoogleCalendarUrl() }}"
			   rel="noopener"
			   class="group inline-block text-gray-700 hover:text-primary no-underline"
			   title="Add to Calendar"
			   aria-label="Add to Calendar">
				<div class="font-semibold text-sm mb-1">
					{{ $event->active_at->format('M jS Y') }}
				</div>
				<div class="text-xs text-gray-600 group-hover:text-primary">
					<x-lucide-calendar-plus class="w-3 h-3 mr-1 inline"/>
					{{ $event->active_at->format('l') }} • {{ $event->active_at->format('g:i A') }}
				</div>
			</a>
		</div>
		<div class="flex-1 min-w-0 md:col-span-7">
			<a href="{{ $event->url }}" rel="noopener" class="text-primary hover:underline text-sm font-medium">
				@if($event->cancelled_at)
					<span class="text-danger">[CANCELLED]</span>
				@endif
				{{ $event->event_name }}
			</a>
			<div class="text-xs text-gray-600 mt-0.5">
				<a href="{{ route('orgs.show',$event->organization) }}" class="text-gray-600 hover:text-gray-800">
					{{ $event->group_name }}
				</a>
			</div>
			<div class="text-xs text-gray-400 mt-0.5 md:hidden">
				<x-lucide-calendar-plus class="w-3 h-3 mr-0.5 inline"/>
				{{ $event->active_at->format('M j') }} • {{ $event->active_at->format('g:i A') }}
			</div>
		</div>
		<div class="shrink-0 md:col-span-2 md:text-right">
			@if(!$event->cancelled_at)
				<a href="{{ $event->url }}" class="inline-block bg-primary text-white px-3 py-1.5 md:px-4 md:py-2 rounded text-xs md:text-sm font-medium hover:bg-blue-700 transition-colors no-underline whitespace-nowrap">
					View Event
				</a>
			@else
				<span class="inline-block border border-gray-300 text-gray-400 px-3 py-1.5 md:px-4 md:py-2 rounded text-xs md:text-sm font-medium">
					Cancelled
				</span>
			@endif
		</div>
	</div>
</div>