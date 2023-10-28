<?php

namespace HackGreenville\Api\Resources\Events\V0;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /** @var Event $resource */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'event_name' => $this->resource->event_name,
            'group_name' => $this->resource->group_name,
            'group_url' => 'TBD', // TODO
            'url' => $this->resource->url,
            'time' => $this->resource->active_at->toISOString(),
            'tags' => '1', // TODO,
            'nid' => '1', // TODO,
            'status' => $this->resource->getStatusAttribute(),
            'rsvp_count' => $this->resource->rsvp_count,
            'description' => $this->resource->description,
            'uuid' => $this->resource->event_uuid,
            'data_as_of' => now()->toISOString(),
            'service_id' => $this->resource->service_id,
            'service' => $this->resource->service,
            'venue' => new VenueResource($this->resource->venue),
            'created_at' => $this->resource->created_at->toISOString(),
        ];
    }
}
