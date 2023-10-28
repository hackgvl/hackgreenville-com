<?php

namespace HackGreenville\EventImporter\Console\Commands;

use App\Enums\OrganizationStatus;
use App\Models\Org;
use Glhd\ConveyorBelt\IteratesIdQuery;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Services\ImportEventForOrganization;
use Illuminate\Console\Command;

class ImportEventsCommand extends Command
{
    use IteratesIdQuery;

    public array $event_ids;

    protected $signature = 'import:events';

    public function beforeFirstRow()
    {
        $this->event_ids = [];
    }

    public function query()
    {
        return Org::query()
            ->where('status', OrganizationStatus::Active)
            ->whereIn('service', config('event-import-handlers.active_services'))
            ->whereNotNull('service')
            ->whereNotNull('service_api_key');
    }

    public function handleRow(Org $org)
    {
        $handler = $org->getEventImporterHandler();

        do {
            $current_page = 1;

            [$last_page, $events] = $handler->getPaginatedData($current_page);

            $this->info("Processing Page <{$current_page}> from {$org->service->name} for {$org->title}");

            /** @var EventData $event_data */
            foreach ($events as $event_data) {
                $this->info("Importing event: {$event_data->name}");

                $event = ImportEventForOrganization::process($event_data, $org);

                $this->event_ids[] = $event->id;
            }

        } while ($current_page++ < $last_page);
    }
}
