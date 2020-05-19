<?php

namespace App\Console\Commands;

use App\Http\Clients\UpstateClient;
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
        $events = collect(UpstateClient::getEvents());

        if ($this->option('debug')) {
            dd($events[0]);
        }

        $bar = $this->output->createProgressBar(count($events));
        $bar->start();
        $events_missing_venue = [];

        $current_active_events = Event::getActive()->get();

        // Loop the current events, if they don't exist in the api response events then delete them.
	    foreach($current_active_events as $event){
	    	// TODO :: finish checking to see if the api response contains an active auction. If it does not then remove it from the database
	    	$found_count = $events
			    ->where('uuid', $event['uuid'])
			    ->get('*');

	    	print "Found count: " . $found_count . "\r\n";
	    }

	    dd('done');

        foreach ($events as $inc => $event) {
            $bar->advance();

            if ($this->option('one') && $inc > 0) {
                continue;
            }

            if (!$event['venue']) {
                $events_missing_venue[] = $event;
                continue;
            }

            // make sure to get a real state
            $event_state = array_get($event, 'venue.state') ?: 'SC';
            $state = State::where('abbr', 'like', $event_state)->first();

	        // make sure the venue exists in the system
            $venue = Venue::firstOrCreate([
                'address'  => array_get($event, 'venue.address'),
                'zipcode'  => array_get($event, 'venue.zip'),
                'state_id' => $state->id,
            ], [
                'address'  => array_get($event, 'venue.address'),
                'zipcode'  => array_get($event, 'venue.zip'),
                'state_id' => $state->id,
                'city'     => array_get($event, 'venue.city'),
                'name'     => array_get($event, 'venue.name'),
                'lat'      => array_get($event, 'venue.lat'),
                'lng'      => array_get($event, 'venue.lon'),
            ]);

            // insert or update the event
            $test2 = $venue->events()->updateOrCreate([
                'event_name'  => $event['event_name'],
                'group_name'  => $event['group_name'],
                'cache->uuid' => $event['uuid'],
            ], [
                'event_name'  => $event['event_name'],
                'group_name'  => $event['group_name'],
                'description' => $event['description'],
                'rsvp_count'  => $event['rsvp_count'],
                'active_at'   => $event['localtime'],
                'uri'         => $event['url'] ?: 'https://www.meetup.com/Hack-Greenville/events/',
                'cache'       => $event,
            ]);

            // TODO :: remove
            dd([
            	'venue' => $venue->id,
	            'event' => $test2->id,
            ]);
        }
        $bar->finish();

        $this->info('Done importing');

        $this->warn('Did not import ' . count($events_missing_venue) . ' because they are missing venue information.');

        return 0;
    }
}
