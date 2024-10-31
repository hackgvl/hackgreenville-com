<?php

namespace HackGreenville\EventImporter\Console\Commands;

use App\Enums\EventServices;
use App\Models\Org;
use Glhd\ConveyorBelt\IteratesIdQuery;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Services\ImportEventForOrganization;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImportEventsCommand extends Command
{
    use IteratesIdQuery;

    protected $signature = 'import:events';

    protected $description = 'Imports events from various sources such as Meetup etc';

    public function query()
    {
        return Org::query()->hasConfiguredEventService();
    }

    public function handleRow(Org $org)
    {
        DB::transaction(function () use ($org) {
            $handler = $org->getEventHandler();
            $current_page = 1;

            do {
                [$events, $last_page] = $handler->getPaginatedData($current_page);

                $this->info("Processing Page <{$current_page}> from {$org->service->name} for {$org->title}");

                $this->deleteDuplicateEvents($org, $events);

                /** @var EventData $event_data */
                foreach ($events as $event_data) {
                    $this->info("Importing event: {$event_data->name} @ {$event_data->starts_at->toDateTimeString()}");

                    ImportEventForOrganization::process($event_data, $org);
                }

            } while (++$current_page < $last_page);
        });
    }

    private function deleteDuplicateEvents(Org $org, Collection $events)
    {
        if ( ! $this->shouldDeleteDuplicateEvents($org)) {
            return;
        }

        $active_at_dates = $events->pluck('starts_at')->all();

        $events_to_delete = DB::table('events')
            ->where('organization_id', $org->id)
            ->whereIn('active_at', $active_at_dates)
            ->get();

        foreach ($events_to_delete as $event) {
            $this->info("Deleting duplicate event: {$event->event_name} @ {$event->active_at}");

            DB::table('events')->where('id', $event->id)->delete();
        }
    }

    private function shouldDeleteDuplicateEvents(Org $org): bool
    {
        return in_array($org->service, [EventServices::MeetupGraphql, EventServices::MeetupRest]);
    }
}
