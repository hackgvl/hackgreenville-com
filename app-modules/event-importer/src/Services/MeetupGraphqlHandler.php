<?php

namespace HackGreenville\EventImporter\Services;

use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use Illuminate\Support\Collection;

class MeetupGraphqlHandler extends AbstractEventHandler {
  
  protected function mapIntoEventData(array $data): EventData
  {
    return EventData::from([]);
  }

  protected function mapIntoVenueData(array $data): ?VenueData
  {
    return null;
  }

  protected function eventResults(int $page): Collection
  {
    return collect([]);
  }

  protected function determineNextPage(Response $response): void
  {
    $this->next_page_url = null;
  }
}
