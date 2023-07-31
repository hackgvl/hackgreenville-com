<?php

namespace App\Console\Commands;

use App\Data\OrganizationData;
use App\Http\Clients\UpstateClient;
use App\Models\Org;
use Glhd\ConveyorBelt\IteratesEnumerable;
use Illuminate\Console\Command;
use Illuminate\Support\Enumerable;

class PullOrgsCommand extends Command
{
    use IteratesEnumerable;

    protected $signature = 'pull:orgs {--org-cleanup : clean out duplicate deleted orgs}';

    protected $description = 'Download and cache organizations in the database.';

    public function collect(): Enumerable
    {
        return (new UpstateClient)
            ->getOrgs()
            ->transform(fn ($org_from_api) => OrganizationData::from($org_from_api));
    }

    public function handleRow(OrganizationData $data)
    {
        $this->progressMessage('Importing Organizations');
        $this->progressSubMessage($data->title);

        Org::updateOrCreate([
            'title' => $data->title,
            'city' => $data->field_city,
        ], [
            'title' => $data->title,
            'city' => $data->field_city,
            'category_id' => $data->resolveCategory()->id,
            'path' => $data->path,
            'focus_area' => $data->field_focus_area,
            'uri' => $data->field_homepage,
            'primary_contact_person' => $data->field_primary_contact_person,
            'organization_type' => $data->field_organization_type,
            'event_calendar_uri' => $data->field_event_calendar_homepage,
            'cache' => '',
        ]);
    }
}
