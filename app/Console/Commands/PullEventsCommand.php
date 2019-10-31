<?php

namespace App\Console\Commands;

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
    protected $signature = 'pull:events';

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

        $bar = $this->output->createProgressBar(count($events));
        $bar->start();
        $events_missing_venue = [];

        foreach ($events as $event) {

            if(!$event->venue){
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

            $search_arr = [
                'venue_id'         => $venue->id,
                'active_at'        => new Carbon($event->time),
                'group_name' => $event->group_name,
            ];

            Event::firstOrCreate($search_arr, [
                'event_name'  => $event->event_name,
                'group_name'  => $event->group_name,
                'description' => $event->description,
                'rsvp_count'  => $event->rsvp_count,
                'active_at'   => new Carbon($event->time),
                'uri'         => $event->url ?: 'https://www.meetup.com/Hack-Greenville/events/',
                'venue_id'    => $venue->id,
                'cache'       => $event,
            ]);

            $bar->advance();
        }
        $bar->finish();

        $this->info('Done importint');

        $this->warn('Did not import ' . count($events_missing_venue) . ' because they are missing venue information');

        return 0;
    }
}
