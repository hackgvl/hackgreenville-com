<?php

namespace HackGreenville\EventImporter\Services\Concerns;

use App\Models\Event;
use App\Models\Org;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

abstract class AbstractEventHandler
{
    public int $page_count = 1;

    protected int $max_days_in_past;

    protected int $max_days_in_future;

    public function __construct(
        public Org $org
    ) {
        $this->max_days_in_past = config('event-import-handlers.max_days_in_past');
        $this->max_days_in_future = config('event-import-handlers.max_days_in_future');
    }

    abstract protected function mapIntoEventData(array $data): EventData;

    abstract protected function mapIntoVenueData(array $data): ?VenueData;

    abstract protected function eventResults(int $page): Collection;

    public function eventExistsOnService(Event $event): bool
    {
        return Http::get($event->url)->successful();
    }

    /** @return array{int, Collection<EventData>} */
    public function getPaginatedData(int $page): array
    {
        return [
            $this->eventResults($page)->map(fn ($data) => $this->mapIntoEventData($data)),
            $this->page_count,
        ];
    }
}
