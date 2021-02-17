<?php

namespace App\Console\Commands;

use App\Http\Clients\UpstateClient;
use App\Models\Event;
use App\Models\State;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
                'is_cancelled' => $event['status'] == 'canceled' ? new Carbon() : null,
                'uri'          => $event['url'] ?: 'https://www.meetup.com/Hack-Greenville/events/',
                'cache'        => $event,
            ];

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

        $this->info('Done importing');

        return 0;
    }
}
