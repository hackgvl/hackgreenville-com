<?php

namespace App\Http\Clients;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Throwable;

class UpstateClient
{
    /**
     * @throws Throwable
     */
    public function getEvents(): Collection
    {
        // Make the api call
        $event_data = Http::get(config('app.events_api_domain') . '/api/gtc');

        // Throw an error if the api is down.
        throw_unless($event_data->ok(), new Exception('api-down'));

        return collect($event_data->json())
            ->map(function ($event) {
                // Convert utc time to whatever the server wants
                $displayTime        = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $event['time'], 'UTC');
                $event['localtime'] = $displayTime->tz(config('app.timezone'));

                return $event;
            });
    }

    /**
     * @throws Throwable
     */
    public function getOrgs(string $status = null)
    {
        $org_url = config('app.orgs_api_domain') . '/rest/organizations?_format=json';

        if ($status) {
            $org_url .= '&org_status=' . $status;
        }

        $data = Http::get($org_url);

        // Throw an error if the api is down.
        throw_unless($data->ok(), new Exception('api-down'));

        // Put the data into JSON format.
        return collect($data->json());
    }

    /**
     * @throws Throwable
     */
    public function getInactiveOrgs()
    {
        return $this->getOrgs('inactive');
    }

    /**
     * @throws Throwable
     */
    public function getActiveOrgs()
    {
        return $this->getOrgs('active');
    }
}
