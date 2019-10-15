<?php

namespace App\Console\Commands;

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
        $this->warn('This command fails because the api does not return anything.');

//        return 1; // TODO :: remove this once the command is working

        $events = getEvents();

        dd($events);

        // Needed when sorting by OrgType.
        // $orgs = getOrgs();

        // Sort the events by date.
        usort($events, 'compareTime');

//        $months = getEventMonths($events);

        // Not currently needed, as only one OrgType hosts meetups.
        // $orgTypes = getOrgTypes( $orgs );

        if (isset($_GET['month'])) {
            $events = filterOnMonth($events, $_GET['month']);
        }
    }
}
