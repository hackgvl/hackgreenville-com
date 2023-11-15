<?php

namespace App\Console\Commands;

use App\Data\OrganizationData;
use App\Http\Clients\UpstateClient;
use App\Models\Org;
use App\Models\Tag;
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

        $org = Org::updateOrCreate([
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
            'established_at' => $data->established_at,
            'event_calendar_uri' => $data->field_event_calendar_homepage,
            'cache' => '',
            'status' => $data->mapStatus(),
            'service' => $data->mapService(),
            'service_api_key' => $data->field_events_api_key,
        ]);

        if ( ! empty($data->field_org_tags)) {
            // Tags
            $tag = Tag::updateOrCreate([
                'id' => $data->field_org_tags,
            ], [
                'name' => "Unknown - {$data->field_org_tags}",
            ]);

            $org->tags()->sync($tag);
        }
    }
}
