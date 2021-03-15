<?php

namespace App\Console\Commands;

use App\Http\Clients\UpstateClient;
use App\Models\Event;
use App\Models\State;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PullEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:events ' .
    '{--1|one : just import one event} ' .
    '{--d|debug : dump the first response from the events api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download events using a cron or console command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = collect(UpstateClient::getEvents());

        // get all upcoming events and set keys to uuid so I can set status
        $dbEvents     = Event::startOfmonth()->whereNull('cancelled_at')->get();
        $dbEventUUIDs = array_map(
                function ($e) {
                    return false;
                },
                array_flip($dbEvents->pluck('event_uuid')->toArray())
        );

        if ($this->option('debug')) {
            dd($events[0]);
        }

        $bar = $this->output->createProgressBar(count($events));
        $bar->start();

        foreach ($events as $inc => $event) {
            $bar->advance();

            if ($this->option('one') && $inc > 0) {
                continue;
            }

            // Start to format the event data. This array can be appended to in the venue conditional statement below
            $event_data = [
                    'event_uuid'   => $event['uuid'],
                    'event_name'   => $event['event_name'],
                    'group_name'   => $event['group_name'],
                    'description'  => $event['description'],
                    'rsvp_count'   => $event['rsvp_count'],
                    'active_at'    => $event['localtime'],
                    // The api should always return cancelled if the event was cancelled
                    'is_cancelled' => $event['status'] == 'cancelled' ? new Carbon() : null,
                    'uri'          => $event['url'] ?: 'https://www.meetup.com/Hack-Greenville/events/',
                    'cache'        => $event,
            ];

            $dbEventUUIDs[$event_data['event_uuid']] = true;

            if (array_get($event, 'venue')) {
                // make sure to get a real state
                $event_state = array_get($event, 'venue.state') ?: 'SC';
                $state       = State::where('abbr', 'like', $event_state)->first();

                // make sure the venue exists in the system
                $venue = Venue::firstOrCreate(
                        [
                                'address'  => array_get($event, 'venue.address'),
                                'zipcode'  => array_get($event, 'venue.zip'),
                                'state_id' => $state->id,
                        ],
                        [
                                'address'  => array_get($event, 'venue.address'),
                                'zipcode'  => array_get($event, 'venue.zip'),
                                'state_id' => $state->id,
                                'city'     => array_get($event, 'venue.city'),
                                'name'     => array_get($event, 'venue.name'),
                                'lat'      => array_get($event, 'venue.lat'),
                                'lng'      => array_get($event, 'venue.lon'),
                        ]
                );

                $event_data += ['venue_id' => $venue->id];
            }

            Event::updateOrCreate(['event_uuid' => $event['uuid']], $event_data);
        }
        $bar->finish();

        $this->info(' Done importing');

        // get a list of uuids that are no longer in the database
        $no_longer_in_api = array_filter(
                $dbEventUUIDs,
                function ($e) {
                    return $e == false;
                }
        );

        if (count($no_longer_in_api) > 0) {
            $ids = array_keys($no_longer_in_api);
            $this->info(
                    'Marking ' . count($no_longer_in_api) . ' ' .
                    Str::plural('event', count($no_longer_in_api)) .
                    ' cancelled in the database. These uuid ' .
                    implode(', ', $ids)
            );

            Event::whereIn('event_uuid', $ids)->update(['cancelled_at' => new Carbon()]);
        }

        return 0;
    }
}
