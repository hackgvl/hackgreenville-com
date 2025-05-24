<?php

namespace HackGreenville\Api\Resources\Orgs\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganizationCollection extends ResourceCollection
{
    public $collects = OrganizationResource::class;
}
