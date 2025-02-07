<?php

namespace App\Http\Requests;

use App\Models\Org;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;

class CalendarFeedRequest extends FormRequest
{
    protected $valid_organizations;

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->replace([
            'orgs' => $this->formattedOrganizationIds(),
        ]);
    }

    public function rules()
    {
        return [
            'orgs' => ['sometimes', 'array'],
        ];
    }

    public function validOrganizations(): Collection
    {
        return $this->valid_organizations ??= Org::query()->active()
            ->when($this->collect('orgs')->isNotEmpty(), fn(Builder $query) => $query->whereIn('id', $this->collect('orgs')))
            ->get();
    }

    protected function formattedOrganizationIds(): array
    {
        return collect(explode('-', $this->input('orgs')))
            // Only allow up to 150 orgs, could prevent unnecessary db lookups.
            ->take(150)
            ->filter(fn($id) => is_numeric($id) && (int) $id > 0)
            ->toArray();
    }
}
