<?php

namespace HackGreenville\EventImporter\Console\Commands;

use App\Models\Org;
use Glhd\ConveyorBelt\IteratesIdQuery;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Services\ImportEventForOrganization;
use Illuminate\Console\Command;

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
        $handler = $org->getEventHandler();

        $current_page = 1;

        do {
            [$events, $last_page] = $handler->getPaginatedData($current_page);

            $this->info("Processing Page <{$current_page}> from {$org->service->name} for {$org->title}");

            /** @var EventData $event_data */
            foreach ($events as $event_data) {
                $this->info("Importing event: {$event_data->name} @ {$event_data->starts_at->toDateTimeString()}");

                ImportEventForOrganization::process($event_data, $org);
            }

        } while (++$current_page < $last_page);
    }
}
