<?php

namespace HackGreenville\Api\Resources\Events\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->name,
            'address' => $this->resource->address,
            'city' => $this->resource->city,
            'state' => [
                'code' => $this->resource->state->abbr,
                'name' => $this->resource->state->name,
            ],
            'zipcode' => $this->resource->zipcode,
            'country' => $this->resource->country,
            'location' => [
                'latitude' => $this->resource->lat,
                'longitude' => $this->resource->lng,
            ],
        ];
    }
}
