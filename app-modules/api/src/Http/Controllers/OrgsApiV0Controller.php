<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Org;
use HackGreenville\Api\Resources\Orgs\V0\OrganizationsCollection;

class OrgsApiV0Controller extends Controller
{
    public function __invoke()
    {
        return new OrganizationsCollection(
            resource: Org::all()
        );
    }
}
