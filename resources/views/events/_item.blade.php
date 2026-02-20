<?php /** @var \App\Models\Event $event */ ?>

<div class="px-6 py-4 hover:bg-gray-50 transition-colors">
	<div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
		<div class="md:col-span-3">
			<a href="{{ $event->toGoogleCalendarUrl() }}"
			   rel="external"
			   class="group inline-block text-gray-700 hover:text-primary no-underline"
			   title="Add to Calendar"
			   aria-label="Add to Calendar">
				<div class="font-semibold text-sm mb-1">
					{{ $event->active_at->format('M jS Y') }}
				</div>
				<div class="text-xs text-gray-600 group-hover:text-primary">
					<i class="fa fa-calendar-plus-o mr-1"></i>
					{{ $event->active_at->format('l') }} • {{ $event->active_at->format('g:i A') }}
				</div>
			</a>
		</div>
		<div class="md:col-span-7">
			<div>
				<a href="{{ $event->url }}" rel="external" class="text-primary hover:underline text-sm font-medium">
					@if($event->cancelled_at)
						<span class="text-danger">
							[CANCELLED]
						</span>
					@endif
					{{ $event->event_name }}
				</a>

				<div class="text-xs text-gray-600 mt-1">
                    <a href="{{ route('orgs.show',$event->organization) }}" class="text-gray-600 hover:text-gray-800">
                        {{ $event->group_name }}
                    </a>
				</div>
			</div>
		</div>
		<div class="md:col-span-2 text-right">
			@if(!$event->cancelled_at)
				<a href="{{ $event->url }}" class="inline-block bg-primary text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700 transition-colors no-underline">
					View Event
				</a>
			@else
				<button class="inline-block border border-gray-300 text-gray-400 px-4 py-2 rounded text-sm font-medium cursor-not-allowed" disabled>
					Cancelled
				</button>
			@endif
		</div>
	</div>
</div>