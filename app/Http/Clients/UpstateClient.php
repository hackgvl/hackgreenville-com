<?php

namespace App\Http\Clients;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class UpstateClient
{
	public static function getEvents(): array
	{
		// Make the api call
		$event_url  = config('app.events_api_domain') . '/api/gtc';
		$event_data = Http::get($event_url);

		// Throw an error if the api is down.
		throw_unless($event_data->ok(), new Exception('api-down'));

		// Format the event data
		return array_map(function ($event) {
			// Convert utc time to whatever the server wants
			$displayTime        = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $event['time'], 'UTC');
			$event['localtime'] = $displayTime->tz(config('app.timezone'));

			return $event;
		}, $event_data->json());
	}
}
