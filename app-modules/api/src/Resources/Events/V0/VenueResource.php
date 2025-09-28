<?php

namespace HackGreenville\Api\Resources\Events\V0;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    /** @var Venue $resource */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->name,
            'address' => $this->resource->address,
            'city' => $this->resource->city,
            'state' => $this->resource->state,
            'zip' => $this->resource->zipcode,
            'country' => $this->resource->country,
            'lat' => $this->resource->lat,
            'lon' => $this->resource->lng,
        ];
    }
}
