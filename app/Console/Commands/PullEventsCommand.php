<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\State;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Http\Clients\UpstateClient;

class PullEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:events ' .
    '{--1|one : just import one event} ' .
    '{--d|debug : dump the first response from the events api}' .
    '{--f|fix : update event keys <info>from event cache</info>}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download events using a cron or console command and cache them in the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = collect(UpstateClient::getEvents());

        // get all upcoming events and set keys to uuid so I can set status
        $dbEvents = Event::where('active_at', '>=', date('Y-m-d'))
            ->orderBy('active_at', 'asc')->get();

        if ($this->option('fix')) {
            // update mapping for service and service ids
            $this->info('fixing events');
            $fixed       = 0;
            $dup_removed = 0;

            $dbEvents->each(
                function ($e) use (&$fixed, &$dup_removed) {
                    if (!$e->cache['service']) {
                        return true;
                    }

                    if (!$e->service) {
                        $fixed++;
                        $e->update(
                            [
                                'service'    => $e->cache['service'],
                                'service_id' => $e->cache['service_id'],
                            ]
                        );
                    }


                    // find and remove duplicates
                    $removed = Event::where([
                                                'service'    => $e->cache['service'],
                                                'service_id' => $e->cache['service_id'],
                                            ]
                    )->where('id', '>', $e->id)->forceDelete();

                    $dup_removed += $removed;
                }
            );
            $this->info('fixed ' . $fixed . ' events');
            $this->info('remove ' . $dup_removed . ' duplicates');
        }

        $dbEventIdentifiers = $dbEvents->keyBy('uniqueIdentifier')->map(
            function (Event $e) {
                return false;
            }
        )->toArray();

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
            $service    = $event['service'];
            $service_id = $event['service_id'];

            $event_find = compact('service', 'service_id');

            // the event key is a json string of service id and service
            $eventKey = json_encode($event_find);

            $event_data = [
                'event_uuid'   => $event['uuid'],
                'event_name'   => $event['event_name'],
                'group_name'   => $event['group_name'],
                'description'  => $event['description'],
                'rsvp_count'   => $event['rsvp_count'],
                'active_at'    => $event['localtime'],
                // The api should always return cancelled if the event was cancelled
                'cancelled_at' => $event['status'] == 'cancelled' ? new Carbon() : null,
                'uri'          => $event['url'] ?: 'https://www.meetup.com/Hack-Greenville/events/',
                'cache'        => $event,
            ];

            $dbEventIdentifiers[$eventKey] = true;

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

            Event::updateOrCreate($event_find, $event_find + $event_data);
        }
        $bar->finish();

        $this->info(' Done importing');

        // get a list of uuids that are no longer in the database
        $no_longer_in_api = array_filter(
            $dbEventIdentifiers,
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

            foreach ($ids as $identifier) {
                $find = json_decode($identifier, true);
                Event::where($find)->update(['cancelled_at' => new Carbon()]);
            }
        }

        return 0;
    }
}
