<?php /** @var App\Models\Event $event */ ?>

<div class="group/row px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50/80 transition-colors">
	<div class="flex items-start gap-3 md:items-center md:gap-4">
		{{-- Date: desktop --}}
		<div class="hidden md:flex items-center gap-3 w-40 shrink-0">
			<div class="w-12 shrink-0 rounded-lg border border-gray-950/10 overflow-hidden text-center bg-white">
				<div class="bg-success/10 font-mono text-[0.6875rem] font-medium uppercase tracking-wide text-success py-0.5">
					{{ $event->active_at->format('M') }}
				</div>
				<div class="text-lg font-semibold text-primary tabular-nums py-0.5">
					{{ $event->active_at->format('j') }}
				</div>
			</div>
			<div class="min-w-0">
				<div class="text-xs font-medium text-gray-700">
					{{ $event->active_at->format('l') }}
				</div>
				<div class="text-xs text-gray-500 mt-0.5">
					{{ $event->active_at->format('g:i A') }}
				</div>
			</div>
		</div>

		{{-- Event info --}}
		<div class="flex-1 min-w-0">
			<a href="{{ $event->url() }}" rel="noopener" class="text-primary hover:text-success text-sm font-medium no-underline transition-colors">
				@if($event->cancelled_at)
					<span class="text-danger font-semibold">[CANCELLED]</span>
				@endif
				{{ $event->event_name }}
			</a>
			<div class="text-xs text-gray-500 mt-0.5">
				<a href="{{ route('orgs.show', $event->organization) }}" class="text-gray-500 hover:text-gray-700 no-underline transition-colors">
					<span class="inline-block w-1.5 h-1.5 rounded-full bg-success align-middle mr-1.5" aria-hidden="true"></span>{{ $event->group_name }}
				</a>
			</div>
			{{-- Date: mobile --}}
			<div class="font-mono text-[0.6875rem] tracking-wide text-success mt-0.5 md:hidden">
				{{ $event->active_at->format('D, M j, Y') }} • {{ $event->active_at->format('g:i A') }}
			</div>
		</div>

		{{-- Actions --}}
		<div class="shrink-0 flex items-center gap-2">
			@if(!$event->cancelled_at)
				<a href="{{ $event->toGoogleCalendarUrl() }}"
				   rel="noopener"
				   class="text-gray-300 hover:text-primary transition-colors md:opacity-0 md:group-hover/row:opacity-100"
				   aria-label="Add {{ $event->event_name }} to Google Calendar">
					<x-lucide-calendar-plus aria-hidden="true" class="w-4 h-4"/>
				</a>
				<a href="{{ $event->url() }}"
				   rel="noopener"
				   class="text-gray-300 hover:text-primary transition-colors"
				   aria-label="View {{ $event->event_name }}">
					<x-lucide-chevron-right aria-hidden="true" class="w-5 h-5"/>
				</a>
			@else
				<span class="text-xs text-gray-300 font-medium">Cancelled</span>
			@endif
		</div>
	</div>
</div>
