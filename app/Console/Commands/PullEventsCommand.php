<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\State;
use App\Models\Venue;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class PullEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:events {--1|one : just import one event} {--d|debug : dump the first response from the events api}';

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
        $events = getEvents();

        if ($this->option('debug')) {
            dd($events[0]);
        }

        $bar = $this->output->createProgressBar(count($events));
        $bar->start();
        $events_missing_venue = [];

        foreach ($events as $inc => $event) {
            $bar->advance();

            if ($this->option('one') && $inc > 0) {
                continue;
            }

            if (!$event->venue) {
                $events_missing_venue[] = $event;
                continue;
            }

            $state = State::where('abbr', 'like', $event->venue->state ?: 'SC')->first();

            $venue = Venue::firstOrCreate([
                'address'  => $event->venue->address,
                'zipcode'  => $event->venue->zip,
                'state_id' => $state->id,
            ], [
                'address'  => $event->venue->address,
                'zipcode'  => $event->venue->zip,
                'state_id' => $state->id,
                'city'     => $event->venue->city,
                'name'     => $event->venue->name,
                'lat'      => $event->venue->lat,
                'lng'      => $event->venue->lon,
            ]);

            $venue->events()->updateOrCreate([
                'event_name'  => $event->event_name,
                'group_name'  => $event->group_name,
                'cache->uuid' => $event->uuid,
                'active_at'   => $event->localtime,
            ], [
                'event_name'  => $event->event_name,
                'group_name'  => $event->group_name,
                'description' => $event->description,
                'rsvp_count'  => $event->rsvp_count,
                'active_at'   => $event->localtime,
                'uri'         => $event->url ?: 'https://www.meetup.com/Hack-Greenville/events/',
                'cache'       => $event,
            ]);
        }
        $bar->finish();

        $this->info('Done importing');

        // clear out duplicates
        // For some reason duplicates are being imported. this piece of code is here to bandaid the problem
        $cleaupCount = 0;
        Event::get()
            ->groupBy(function (Event $e) {
                // group all elements by uuid and active date so we can remove them.
                return $e->cache['uuid'] . $e->active_at;
            })
            ->filter(function ($group) {
                // filter out events without duplicates
                return $group->count() > 1;
            })
            ->each(function (Collection $group) use (&$cleaupCount) {
                // remove the first element. We want to delete the others.
                $group->forget(0);

                if ($group->count() > 0) {
                    $group->each(function (Event $e) use (&$cleaupCount) {
                        $cleaupCount++;
                        $e->forceDelete();
                    });
                }
            });

        $this->warn('Did not import ' . count($events_missing_venue) . ' because they are missing venue information and cleaned up ' . $cleaupCount . ' duplicates');

        return 0;
    }
}
