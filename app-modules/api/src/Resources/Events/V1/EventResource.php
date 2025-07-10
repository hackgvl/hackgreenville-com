<?php

namespace HackGreenville\Api\Resources\Events\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public $resource;

    public function toArray(Request $request): array
    {
        $this->loadMissing(['organization.tags', 'venue.state']);

        return [
            'id' => $this->resource->event_uuid,
            'name' => $this->resource->event_name,
            'description' => $this->resource->description,
            'url' => $this->resource->url,
            'starts_at' => $this->resource->active_at->toISOString(),
            'ends_at' => $this->resource->expire_at->toISOString(),
            'rsvp_count' => $this->resource->rsvp_count,
            'status' => $this->resource->getStatusAttribute(),
            'organization' => [
                'id' => $this->resource->organization->id,
                'name' => $this->resource->group_name,
                'url' => $this->resource->organization->uri,
                'tags' => $this->resource->organization->tags->map(fn ($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]),
            ],
            'venue' => new VenueResource($this->resource->venue),
            'service' => [
                'name' => $this->resource->service,
                'id' => $this->resource->service_id,
            ],
            'created_at' => $this->resource->created_at->toISOString(),
            'updated_at' => $this->resource->updated_at->toISOString(),
            'is_paid' => $this->resource->is_paid,
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
