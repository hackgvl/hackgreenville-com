<?php

namespace HackGreenville\EventImporter\Services;

use App\Enums\EventServices;
use App\Enums\EventType;
use Carbon\Carbon;
use HackGreenville\EventImporter\Data\EventData;
use HackGreenville\EventImporter\Data\VenueData;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MeetupRestHandler extends AbstractEventHandler
{
    protected function mapIntoEventData(array $data): EventData
    {
        return EventData::from([
            'id' => $data['id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'url' => $data['link'],
            'starts_at' => Carbon::createFromTimestampMs($data['time']),
            'event_type' => match ($data['eventType']) {
                'ONLINE' => EventType::Online,
                'PHYSICAL' => EventType::Live,
                default => throw new RuntimeException("Unable to determine event type {$data['eventType']}"),
            },
            'rsvp' => $data['yes_rsvp_count'],
            'service' => EventServices::MeetupRest,
            'service_id' => $data['id'],
            'venue' => $this->mapIntoVenueData($data),
        ]);
    }

    protected function mapIntoVenueData(array $data): ?VenueData
    {
        if ( ! isset($data['venue'])) {
            return null;
        }

        return VenueData::from([
            'id' => $data['venue']['id'],
            'name' => $data['venue']['name'],
            'address' => $data['venue']['address_1'],
            'city' => $data['venue']['city'],
            'state' => $data['venue']['state'],
            'zip' => $data['venue']['zip'],
            'country' => $data['venue']['country'],
            'lat' => $data['venue']['lat'],
            'lon' => $data['venue']['lon'],
        ]);
    }

    protected function eventResults(int $page): Collection
    {
        return Http::baseUrl('https://api.meetup.com/')
            ->get("{$this->org->service_api_key}/events?&sign=true&photo-host=public")
            ->collect();
    }
}
