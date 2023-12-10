<?php

namespace HackGreenville\Api\Resources\Orgs\V0;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * This exists merely to handle legacy events api.
 */
class OrganizationsCollection extends ResourceCollection
{
    public $collects = OrgResource::class;

    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }
}
