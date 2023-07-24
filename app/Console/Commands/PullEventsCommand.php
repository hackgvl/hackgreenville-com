<?php

namespace App\Console\Commands;

use App\Data\EventDataTransformer;
use App\Http\Clients\UpstateClient;
use App\Models\Event;
use Glhd\ConveyorBelt\IteratesEnumerable;
use Illuminate\Console\Command;
use Illuminate\Support\Enumerable;

class PullEventsCommand extends Command
{
    use IteratesEnumerable;

    protected $signature = 'pull:events';

    protected $description = 'Download events using a cron or console command and cache them in the database';

    public function collect(): Enumerable
    {
        $client = new UpstateClient;

        return $client
            ->getEvents()
            ->map(fn ($event_from_api) => EventDataTransformer::from($event_from_api));
    }

    public function handleRow(EventDataTransformer $data)
    {
        $this->progressMessage('Importing...');

        $this->progressSubMessage($data->event_name);

        Event::updateOrCreate($data->uniqueIdentifier(), [
            'event_uuid' => $data->uuid,
            'event_name' => $data->event_name,
            'group_name' => $data->group_name,
            'description' => $data->description,
            'rsvp_count' => $data->rsvp_count,
            'active_at' => $data->time,
            'cancelled_at' => $data->getCancelledAtOrNull(),
            'uri' => $data->url,
            'venue_id' => $data->hasVenue()
                ? $data->resolveVenue()->id
                : null,
            'cache' => [],
        ]);
    }

    public function afterLastRow()
    {
        // Clean up all events which not longer exist on the API.
        $event_uuids = $this->collect()->pluck('uuid');

        Event::query()
            ->whereNotIn('event_uuid', $event_uuids)
            ->where('active_at', '>', now())
            ->update([
                'cancelled_at' => now(),
            ]);
    }
}
