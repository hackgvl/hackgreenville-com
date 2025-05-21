<?php

namespace HackGreenville\Api\Resources\Orgs\V1;

use App\Models\Org;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public $resource;

    public function toArray(Request $request): array
    {
        $this->resource->loadMissing('tags');

        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'path' => $this->resource->path,
            'city' => $this->resource->city,
            'service' => $this->resource->service,
            'service_api_key' => $this->resource->service_api_key,
            'focus_area' => $this->resource->focus_area,
            'website_url' => $this->resource->uri,
            'event_calendar_url' => $this->resource->event_calendar_uri,
            'primary_contact' => $this->resource->primary_contact_person,
            'status' => $this->resource->status,
            'organization_type' => $this->resource->organization_type,
            'established_year' => $this->resource->established_at ? $this->resource->established_at->year : null,
            'tags' => $this->resource->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            }),
            'created_at' => $this->resource->created_at->toISOString(),
            'updated_at' => $this->resource->updated_at->toISOString(),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
                'timestamp' => now()->toISOString(),
            ],
        ];
    }
}
