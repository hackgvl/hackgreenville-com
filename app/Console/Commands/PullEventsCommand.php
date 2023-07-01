<?php

namespace App\Console\Commands;

use App\Enums\EventStatuses;
use App\Http\Clients\UpstateClient;
use App\Models\Event;
use App\Models\State;
use App\Models\Venue;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Throwable;

class PullEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:events
                            {--d|debug : dump the first response from the events api}
                            {--0|dump : dump the response from the events api}
                            {--f|fix : update event keys <info>from event cache</info>}
    ';

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
    public function handle(UpstateClient $upstateClient): int
    {
        try {
            $events = $upstateClient->getEvents();

            throw_if(
                $events->isEmpty(),
                new Exception('No events returned from api'),
            );
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        } catch (Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        // get all upcoming events and set keys to uuid, so I can set status
        $dbEvents = Event::query()
            ->where('active_at', '>=', date('Y-m-d'))
            ->orderBy('active_at')
            ->get();

        if ($this->option('fix')) {
            $this->fixEvents($dbEvents);
        }

        $dbEventIdentifiers = $dbEvents
            ->keyBy('uniqueIdentifier')
            ->map(fn (Event $e) => false);

        /**
         * Debug output of an event returned from the api
         */
        if ($this->option('debug') || $this->option('dump')) {
            if ($this->option('dump')) {
                print_r($events->toArray());
                return self::SUCCESS;
            }

            print_r($events->first());
            return self::SUCCESS;
        }

        // create the progressbar object for cli
        $bar = $this->output->createProgressBar(count($events));
        $bar->start();

        foreach ($events as $event) {
            $bar->advance();

            // the event key is a json string of service id and service
            $eventKey = json_encode([
                'service'    => $event['service'],
                'service_id' => $event['service_id'],
            ]);
            $dbEventIdentifiers[$eventKey] = true;

            $this->saveEvent($event);
        }
        $bar->finish();

        $this->info(' Done importing');

        $this->processMissingResultsFromApi($dbEventIdentifiers);

        return self::SUCCESS;
    }

    private function fixEvents(Collection $dbEvents): void
    {
        // update mapping for service and service ids
        $this->info('fixing events');

        $fixed       = 0;
        $dup_removed = 0;

        $dbEvents
            ->filter(fn (Event $e) => $e->cache['service'])
            ->tap(function (Event $event) use (&$fixed, &$dup_removed) {
                // Try to find which service this event came from.

                if ($event->service) {
                    return;
                }

                $fixed++;
                $event->update(
                    [
                        'service'    => $event->cache['service'],
                        'service_id' => $event->cache['service_id'],
                    ],
                );
            })
            ->tap(function (Event $event) use (&$dup_removed) {
                // find and remove duplicates
                $removed = Event::where(
                    [
                        'service'    => $event->cache['service'],
                        'service_id' => $event->cache['service_id'],
                    ],
                )->where('id', '>', $event->id)->forceDelete();

                $dup_removed += $removed;
            });

        $this->info("fixed {$fixed} events");
        $this->info("remove {$dup_removed} duplicates");
    }

    private function processMissingResultsFromApi(Collection $dbEventIdentifiers): void
    {
        // get a list of uuids that are no longer in the database
        $dbEventIdentifiers
            ->filter(fn ($e) => $e === false)
            ->each(function ($value, $index) {
                $this->info("Marking event id {$index} cancelled in the database.");

                $find = json_decode($index, true);
                Event::where($find)->update(['cancelled_at' => new Carbon]);
            });
    }

    private function isEventCancelled($event): bool
    {
        // The api should always return "cancelled" if the event was canceled
        return $event['status'] === EventStatuses::CANCELLED->value;
    }

    private function getVenueFromEvent($event): Venue
    {
        // make sure to get a real state
        $event_state = Arr::get($event, 'venue.state') ?: 'SC';
        $state       = State::where('abbr', 'like', $event_state)->firstOrCreate([
            'abbr' => $event_state,
            'name' => $event_state,
        ]);

        // make sure the venue exists in the system
        return Venue::firstOrCreate(
            attributes: [
                'address'  => Arr::get($event, 'venue.address'),
                'zipcode'  => Arr::get($event, 'venue.zip'),
                'state_id' => $state->id,
            ],
            values: [
                'address'  => Arr::get($event, 'venue.address'),
                'zipcode'  => Arr::get($event, 'venue.zip'),
                'state_id' => $state->id,
                'city'     => Arr::get($event, 'venue.city'),
                'name'     => Arr::get($event, 'venue.name'),
                'lat'      => Arr::get($event, 'venue.lat'),
                'lng'      => Arr::get($event, 'venue.lon'),
            ],
        );
    }

    private function saveEvent($event): void
    {
        $service    = $event['service'];
        $service_id = $event['service_id'];
        $event_find = compact('service', 'service_id');

        // map api fields to the database
        $event_data = [
            'event_uuid'   => $event['uuid'],
            'event_name'   => $event['event_name'],
            'group_name'   => $event['group_name'],
            'description'  => $event['description'],
            'rsvp_count'   => $event['rsvp_count'] ?? 0,
            'active_at'    => $event['localtime'],
            'cancelled_at' => $this->isEventCancelled($event) ? new Carbon : null,
            'uri'          => $event['url'] ?: '#no-url',
            'cache'        => $event,
        ];


        if (Arr::get($event, 'venue')) {
            $venue      = $this->getVenueFromEvent($event);
            $event_data += ['venue_id' => $venue->id];
        }

        Event::updateOrCreate($event_find, $event_find + $event_data);
    }
}
