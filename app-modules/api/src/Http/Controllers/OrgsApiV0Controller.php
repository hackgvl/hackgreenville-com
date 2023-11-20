<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Org;
use HackGreenville\Api\Http\Requests\OrgsApiV0Request;
use HackGreenville\Api\Resources\Orgs\V0\OrganizationsCollection;
use Illuminate\Database\Eloquent\Builder;

class OrgsApiV0Controller extends Controller
{
    public function __invoke(OrgsApiV0Request $request)
    {
        return new OrganizationsCollection(
            resource: Org::query()
                ->when($request->filled('tags'), function (Builder $query) use ($request) {
                    $query->whereHas('tags', function ($query) use ($request) {
                        $query->where('id', $request->integer('tags'));
                    });
                })
                ->get()
        );
    }
}
