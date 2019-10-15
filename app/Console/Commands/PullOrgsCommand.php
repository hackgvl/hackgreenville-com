<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Org;
use Illuminate\Console\Command;

class PullOrgsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:orgs
                            {--a|active : only active organizations}
                            {--i|inactive : only inactive organizations}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and cache organizations in the database.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $activeOrgsCategories = [];
        $inactiveOrgs         = [];

        if (!$this->option('active') && !$this->option('inactive')) {
            $activeOrgsCategories = getActiveOrgs();
            $inactiveOrgs         = getInactiveOrgs();
        } elseif (!$this->option('active')) {
            $inactiveOrgs = getInactiveOrgs();
        } elseif (!$this->option('inactive')) {
            $activeOrgsCategories = getActiveOrgs();
        }

        $total_importing = count($activeOrgsCategories) + count($inactiveOrgs);

        $this->info("Importing {$total_importing} orgs");

        foreach ($activeOrgsCategories as $category_name => $activeOrgs) {
            $this->info('Importing active orgs category "' . $category_name . '"');
            $category = Category::firstOrCreate(['label' => $category_name,], ['label' => $category_name,]);

            foreach ($activeOrgs as $activeOrg) {
                $new_org = Org::firstOrCreate([
                    'title' => $activeOrg->title,
                    'city'  => $activeOrg->field_city,
                ], [
                    'title'                  => $activeOrg->title,
                    'city'                   => $activeOrg->field_city,
                    'category_id'            => $category->id,
                    'path'                   => $activeOrg->path,
                    'focus_area'             => $activeOrg->field_focus_area,
                    'uri'                    => $activeOrg->field_homepage,
                    'primary_contact_person' => $activeOrg->field_primary_contact_person,
                    'organization_type'      => $activeOrg->field_organization_type,
                    'event_calendar_uri'     => $activeOrg->field_event_calendar_homepage,
                    'cache'                  => $activeOrg,
                ]);
            }
        }

        $category = Category::firstOrCreate(['label' => 'Inactive'], ['label' => 'Inactive']);
        $this->info('Importing inactive orgs');

        foreach ($inactiveOrgs as $inactiveOrg) {
            $new_org = Org::firstOrCreate([
                'title' => $inactiveOrg->title,
                'city'  => $inactiveOrg->field_city,
            ], [
                'title'                  => $inactiveOrg->title,
                'city'                   => $inactiveOrg->field_city,
                'category_id'            => $category->id,
                'path'                   => $inactiveOrg->path,
                'focus_area'             => $inactiveOrg->field_focus_area,
                'uri'                    => $inactiveOrg->field_homepage,
                'primary_contact_person' => $inactiveOrg->field_primary_contact_person,
                'organization_type'      => $inactiveOrg->field_organization_type,
                'event_calendar_uri'     => $inactiveOrg->field_event_calendar_homepage,
                'cache'                  => $inactiveOrg,
            ]);

            $new_org->delete();
        }

        $this->info('Done Importing');

        return 0;
    }
}
