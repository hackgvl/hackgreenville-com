
<div>
    <h3 class="text-center">
        {{ $title }}
    </h3>
    <ul class="{{ count($events) > 0 ? 'timeline' : '' }}">
        @if (!count($events))
            <li>
                <strong>No</strong> events to display.
            </li>
        @else
            @foreach ($events as $event)
                <li class="timeline-inverted">
                    <div class="timeline-badge bg-success">
                        <i class="fa fa-calendar" ></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title">
                                @if($event['cancelled_at'])
                                <span class="text-danger">
                                    [CANCELLED]
                                </span>
                                @endif
                                {{ $event['title'] }}
                            </h4>
                            <p class="timeline-subtitle h6">{{ $event['group_name'] }}</p>
                            <p>
                                <small class="text-muted">
                                    <i class="fa fa-calendar"  ></i> {{ \Carbon\Carbon::parse($event['active_at'])->format('M/D h:m A') }}
                                </small>
                            </p>
                        </div>
                        <div class="timeline-body">
                            <div>
                                <button
                                    onClick="showMoreTimeline('{{addslashes($event->toJson()) }}')"
                                    class="btn btn-secondary"
                                    type="button"
                                >
                                    READ MORE
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        @endif
    </ul>
</div>
