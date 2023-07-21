<?php

namespace App\Data;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

class OrganizationData extends Data
{
    public function __construct(
        public string $title,
        public string $path,
        public string $field_org_status,
        public string $field_city,
        public string $field_focus_area,
        public string $field_homepage,
        public string $field_primary_contact_person,
        public string $field_organization_type,
        public string $field_event_calendar_homepage,
    ) {
    }

    public function isOrganizationInactive(): bool
    {
        return 'Inactive'=== $this->field_org_status;
    }

    public function resolveCategory(): Model|Category
    {
        if ($this->isOrganizationInactive()) {
            return $this->inactiveCategory();
        }

        return Category::firstOrCreate(['label' => $this->field_organization_type]);
    }

    protected function inactiveCategory()
    {
        return Category::firstOrCreate(['label' => 'Inactive'], ['label' => 'Inactive']);
    }
}
