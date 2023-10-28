<?php

namespace HackGreenville\EventImporter\Services\Concerns;

use App\Models\Org;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use Illuminate\Support\Collection;

abstract class AbstractEventHandler
{
    public int $page_count = 1;

    public function __construct(
        public Org $org
    ) {
    }

    abstract protected function mapIntoEventData(array $data): EventData;

    abstract protected function mapIntoVenueData(array $data): ?VenueData;

    abstract protected function eventResults(int $page): Collection;

    /** @return array{int, Collection<EventData>} */
    public function getPaginatedData(int $page): array
    {
        return [
            $this->page_count,
            $this->eventResults($page)->map(fn ($data) => $this->mapIntoEventData($data)),
        ];
    }
}
