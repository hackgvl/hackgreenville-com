<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Org;
use HackGreenville\Api\Http\Requests\OrgsApiV0Request;
use HackGreenville\Api\Resources\Orgs\V0\OrganizationsCollection;
use Illuminate\Database\Eloquent\Builder;

class OrgsApiV0Controller extends Controller
{
    /**
     * Organizations API v0
     *
     * This API provides access to organization data stored in the HackGreenville database.
     * 
     * Please see the [Organization API docs](https://github.com/hackgvl/hackgreenville-com/blob/develop/ORGS_API.md) for more information about the organization API.
     *
     * @apiResource HackGreenville\Api\Resources\Orgs\V0\OrganizationsCollection
     * @apiResourceModel App\Models\Org states=forDocumentation
     */
    public function __invoke(OrgsApiV0Request $request)
    {
        return new OrganizationsCollection(
            resource: Org::query()
                ->with('tags')
                ->when($request->filled('tags'), function (Builder $query) use ($request) {
                    $query->whereHas('tags', function ($query) use ($request) {
                        $query->where('id', $request->integer('tags'));
                    });
                })
                ->get()
        );
    }
}
