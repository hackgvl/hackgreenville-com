<?php /** @var \App\Models\Event $event */ ?>

<div class="group/row px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50/80 transition-colors">
	<div class="flex items-start gap-3 md:grid md:grid-cols-12 md:gap-4 md:items-center">
		{{-- Date: desktop --}}
		<div class="hidden md:block md:col-span-3">
			<div class="font-semibold text-sm text-gray-700">
				{{ $event->active_at->format('M jS Y') }}
			</div>
			<div class="text-xs text-gray-400 mt-0.5">
				{{ $event->active_at->format('l') }} • {{ $event->active_at->format('g:i A') }}
			</div>
		</div>

		{{-- Event info --}}
		<div class="flex-1 min-w-0 md:col-span-7">
			<a href="{{ $event->url }}" rel="noopener" class="text-gray-700 hover:text-primary text-sm font-medium no-underline transition-colors">
				@if($event->cancelled_at)
					<span class="text-danger font-semibold">[CANCELLED]</span>
				@endif
				{{ $event->event_name }}
			</a>
			<div class="text-xs text-gray-400 mt-0.5">
				<a href="{{ route('orgs.show', $event->organization) }}" class="text-gray-400 hover:text-gray-600 no-underline transition-colors">
					{{ $event->group_name }}
				</a>
			</div>
			{{-- Date: mobile --}}
			<div class="text-xs text-gray-400 mt-0.5 md:hidden">
				{{ $event->active_at->format('M j') }} • {{ $event->active_at->format('g:i A') }}
			</div>
		</div>

		{{-- Actions --}}
		<div class="shrink-0 md:col-span-2 flex items-center justify-end gap-2">
			@if(!$event->cancelled_at)
				<a href="{{ $event->toGoogleCalendarUrl() }}"
				   rel="noopener"
				   class="text-gray-200 hover:text-primary transition-colors md:opacity-0 md:group-hover/row:opacity-100"
				   title="Add to Google Calendar"
				   aria-label="Add to Google Calendar">
					<x-lucide-calendar-plus class="w-4 h-4"/>
				</a>
				<a href="{{ $event->url }}"
				   rel="noopener"
				   class="text-gray-200 hover:text-primary transition-colors"
				   title="View event">
					<x-lucide-arrow-up-right class="w-4 h-4"/>
				</a>
			@else
				<span class="text-xs text-gray-300 font-medium">Cancelled</span>
			@endif
		</div>
	</div>
</div>
