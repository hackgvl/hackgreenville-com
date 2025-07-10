<?php

namespace HackGreenville\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Org;
use HackGreenville\Api\Http\Requests\OrgsApiV1Request;
use HackGreenville\Api\Resources\Orgs\V1\OrganizationCollection;
use Illuminate\Database\Eloquent\Builder;

class OrgsApiV1Controller extends Controller
{
    /**
     * Organizations API v1
     * 
     * This API provides access to organization data stored in the HackGreenville database.
     * 
     * @apiResource HackGreenville\Api\Resources\Orgs\V1\OrganizationCollection
     * @apiResourceModel App\Models\Org
     */
    public function __invoke(OrgsApiV1Request $request)
    {
        $query = Org::query()
            ->when($request->filled('tags'), function (Builder $query) use ($request) {
                $query->whereHas('tags', function (Builder $query) use ($request) {
                    $query->whereIn('id', $request->input('tags'));
                });
            })
            ->when($request->filled('title'), function (Builder $query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('title') . '%');
            })
            ->when($request->filled('city'), function (Builder $query) use ($request) {
                $query->where('city', 'like', '%' . $request->input('city') . '%');
            })
            ->when($request->filled('focus_area'), function (Builder $query) use ($request) {
                $query->where('focus_area', 'like', '%' . $request->input('focus_area') . '%');
            })
            ->when($request->filled('organization_type'), function (Builder $query) use ($request) {
                $query->where('organization_type', $request->input('organization_type'));
            })
            ->when($request->filled('status'), function (Builder $query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('established_from'), function (Builder $query) use ($request) {
                $query->whereYear('established_at', '>=', $request->input('established_from'));
            })
            ->when($request->filled('established_to'), function (Builder $query) use ($request) {
                $query->whereYear('established_at', '<=', $request->input('established_to'));
            })
            ->when($request->filled('sort_by') && in_array($request->input('sort_by'), [
                'title', 'city', 'established_at', 'updated_at', 'created_at'
            ]), function (Builder $query) use ($request) {
                $sortDirection = $request->input('sort_direction') === 'desc' ? 'desc' : 'asc';
                $query->orderBy($request->input('sort_by'), $sortDirection);
            }, function (Builder $query) {
                $query->orderBy('title', 'asc');
            });

        $perPage = $request->input('per_page', 15);
        $organizations = $query->paginate($perPage);

        return new OrganizationCollection($organizations);
    }
}
