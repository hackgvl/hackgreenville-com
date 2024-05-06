<?php /** @var \App\Models\Event $event */ ?>

<div class="list-group-item mb-3 hover-bg border-0 shadow-sm">
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-6">
			<a href="{{ $event->gCalUrl }}"
			   rel="external"
			   class="text-decoration-none"
			   title="Add to Calendar"
			   aria-label="Add to Calendar">
				<div class="font-weight-bold mr-2">
					{{ $event->active_at->format('M jS Y') }}
				</div>
				<div>
					<i class="fa fa-calendar-plus-o"></i>
					{{ $event->active_at->format('l') }} • {{ $event->active_at->format('g:i A') }}
				</div>
			</a>
		</div>
		<div class="col-md-6 col-xs-6 col-sm-6">
			<div class="d-flex">
				<div class="flex-row">
					<a href="{{ $event->url }}" rel="external">
						@if($event->cancelled_at)
							<span class="text-danger">
								[CANCELLED]
							</span>
						@endif
						{{ $event->event_name }}
					</a>

					<div class="text-muted">
                        <a href="{{ route('orgs.show',$event->organization) }}" class="text-muted">
                            {{ $event->group_name }}
                        </a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-12 text-center text-sm-center text-lg-right mt-sm-4 mt-lg-0">

			@if(!$event->cancelled_at)
				<a href="{{ $event->url }}" class="btn">
					View Event
				</a>
			@else
				<button class="btn btn-outline-primary disabled">
					Cancelled
				</button>
			@endif

		</div>
	</div>
</div>
