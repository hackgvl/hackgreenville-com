<?php /** @var \App\Models\Event $event */ ?>

<div class="bg-white p-4 mb-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
	<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
		<div class="md:col-span-3">
			<a href="{{ $event->toGoogleCalendarUrl() }}"
			   rel="external"
			   class="no-underline text-gray-700 hover:text-primary"
			   title="Add to Calendar"
			   aria-label="Add to Calendar">
				<div class="font-bold mb-1">
					{{ $event->active_at->format('M jS Y') }}
				</div>
				<div class="text-sm">
					<i class="fa fa-calendar-plus-o"></i>
					{{ $event->active_at->format('l') }} • {{ $event->active_at->format('g:i A') }}
				</div>
			</a>
		</div>
		<div class="md:col-span-6">
			<div>
				<a href="{{ $event->url }}" rel="external" class="text-primary hover:text-blue-600 underline">
					@if($event->cancelled_at)
						<span class="text-danger">
							[CANCELLED]
						</span>
					@endif
					{{ $event->event_name }}
				</a>

				<div class="text-gray-600 mt-1">
                    <a href="{{ route('orgs.show',$event->organization) }}" class="text-gray-600 hover:text-gray-800 underline">
                        {{ $event->group_name }}
                    </a>
				</div>
			</div>
		</div>
		<div class="md:col-span-3 text-center md:text-right mt-4 md:mt-0">

			@if(!$event->cancelled_at)
				<a href="{{ $event->url }}" class="btn btn-primary">
					View Event
				</a>
			@else
				<button class="btn btn-outline-primary opacity-50 cursor-not-allowed" disabled>
					Cancelled
				</button>
			@endif

		</div>
	</div>
</div>