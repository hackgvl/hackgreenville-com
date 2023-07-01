<?php

namespace App\Console\Commands;

use App\Http\Clients\UpstateClient;
use App\Models\Category;
use App\Models\Org;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

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
                            {--d|debug : dump the first response from the orgs api}
                            {--org-cleanup : clean out duplicate deleted orgs}
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
     * @throws Throwable
     */
    public function handle(UpstateClient $upstateClient): int
    {
        $activeOrgsCategories = [];
        $inactiveOrgs         = [];


        try {
            if ( ! $this->option('active') && ! $this->option('inactive')) {
                $activeOrgsCategories = $upstateClient->getActiveOrgs();
                $inactiveOrgs         = $upstateClient->getInactiveOrgs();
            } elseif ( ! $this->option('active')) {
                $inactiveOrgs = $upstateClient->getInactiveOrgs();
            } elseif ( ! $this->option('inactive')) {
                $activeOrgsCategories = $upstateClient->getActiveOrgs();
            }

            $total_importing = $activeOrgsCategories->count() + $inactiveOrgs->count();

            throw_if($total_importing === 0, 'No orgs returned from api');
        } catch(Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->info("Importing {$total_importing} orgs");

        if($this->option('debug')) {
            dump(compact('activeOrgsCategories', 'inactiveOrgs'));
            return self::SUCCESS;
        }


        $active_counter = 0;
        $inactive_counter = 0;

        $this->importActiveOrgs($activeOrgsCategories, $active_counter);
        $this->handleImportInactiveOrgs($inactiveOrgs, $inactive_counter);

        $this->info('Done Importing. Imported ' . $active_counter . ' active and ' . $inactive_counter . ' orgs.');

        if ($this->option('org-cleanup')) {
            $this->handleCleanup();
        }

        return self::SUCCESS;
    }

    public function importActiveOrgs(Collection $active_orgs_by_group, &$inc): void
    {
        $active_orgs_by_group
            ->each(function ($orgs, $group_name) use (&$inc) {
                $category = Category::firstOrCreate(['label' => $group_name], ['label' => $group_name]);

                $this->info("Importing active orgs category \"{$group_name}\"");

                foreach ($orgs as $org) {
                    $this->handleUpdateOrCreateOrg($org, $category->id);
                    $inc++;
                }
            });
    }

    public function handleImportInactiveOrgs($inactiveOrgs, &$inc): void
    {
        $inactiveCategory = Category::firstOrCreate(['label' => 'Inactive'], ['label' => 'Inactive']);

        $inactiveOrgs->each(function ($inactiveOrg, $group_name) use ($inactiveCategory, &$inc) {
            $this->info("Importing inactive orgs {$group_name} as inactive category id {$inactiveCategory->id}");

            foreach ($inactiveOrg as $org) {
                $this->handleUpdateOrCreateOrg($org, $inactiveCategory->id);

                $inc++;
            }
        });
    }

    public function handleUpdateOrCreateOrg(array $org, $category_id): Model|Org
    {
        return Org::firstOrCreate([
            'title' => $org['title'],
            'city'  => $org['field_city'],
        ], [
            'title'                  => $org['title'],
            'city'                   => $org['field_city'],
            'category_id'            => $category_id,
            'path'                   => $org['path'],
            'focus_area'             => $org['field_focus_area'],
            'uri'                    => $org['field_homepage'],
            'primary_contact_person' => $org['field_primary_contact_person'],
            'organization_type'      => $org['field_organization_type'],
            'event_calendar_uri'     => $org['field_event_calendar_homepage'],
            'cache'                  => $org,
        ]);
    }

    public function handleCleanup(): void
    {
        $this->info('cleaning up the orgs table');

        // Find the first occurrence of a deleted org
        $first = Org::onlyTrashed()->first();

        if ($first) {
            // Find the start of duplicates
            $second = Org::onlyTrashed()
                ->where('id', '>', $first->id + 1)
                ->where('title', $first->title)
                ->first();

            if ($second) {
                // If there are any duplicates, delete starting at the duplicate line
                $deleted = Org::onlyTrashed()
                    ->where('id', '>=', $second->id)
                    ->forceDelete();
                $this->info("Cleaned out {$deleted} orgs");
            } else {
                $this->info('Nothing to clean out (a)');
            }
        } else {
            $this->info('Nothing to clean out (b)');
        }
    }
}
