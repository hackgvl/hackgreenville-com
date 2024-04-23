<?php

namespace HackGreenville\EventImporter\Console\Commands;

use App\Models\Event;
use Glhd\ConveyorBelt\IteratesIdQuery;
use Illuminate\Console\Command;

class PruneMissingEventsCommand extends Command
{
    use IteratesIdQuery;

    protected $signature = 'events:prune';

    protected $description = 'Find events in the future which do not exist on an event service and delete them from our system.';

    public function query()
    {
        return Event::query()
            ->where('active_at', '>', now()->startOfDay())
            ->whereNull('cancelled_at')
            ->whereHas('organization', function ($query) {
                $query->hasConfiguredEventService();
            });
    }

    public function handleRow(Event $event)
    {
        $this->progressMessage("Checking: {$event->event_name}");

        sleep(1);
        
        if ($event->doesNotExistOnEventService()) {
            $this->progressMessage("Event missing: {$event->event_name}");

            $event->delete();
        }
    }
}
