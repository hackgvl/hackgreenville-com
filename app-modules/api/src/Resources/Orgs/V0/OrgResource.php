<?php

namespace HackGreenville\Api\Resources\Orgs\V0;

use App\Models\Org;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrgResource extends JsonResource
{
    /** @var Org $resource */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'title' => $this->resource->title,
            'path' => $this->resource->path,
            'changed' => $this->resource->updated_at->toISOString(),
            'field_city' => $this->resource->city,
            'field_event_service' => $this->resource->service,
            'field_events_api_key' => $this->resource->service_api_key,
            'field_focus_area' => $this->resource->focus_area,
            'field_homepage' => $this->resource->uri,
            'field_event_calendar_homepage' => $this->resource->event_calendar_uri,
            'field_primary_contact_person' => $this->resource->primary_contact_person,
            'field_org_status' => $this->resource->status,
            'field_organization_type' => $this->resource->organization_type,
            'field_year_established' => $this->resource->established_at->year,
            'field_org_tags' => $this->resource->tags->first()?->id ?? '',
            'uuid' => $this->resource->id,
        ];
    }
}
