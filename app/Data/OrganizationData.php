<?php

namespace App\Data;

use App\Enums\EventServices;
use App\Enums\OrganizationStatus;
use App\Models\Category;
use Carbon\Carbon;
use RuntimeException;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class OrganizationData extends Data
{
    #[Computed]
    public Carbon $established_at;

    public function __construct(
        public string $title,
        public string $path,
        public string $field_org_status,
        public string $field_city,
        public string $field_focus_area,
        public string $field_homepage,
        public string $field_primary_contact_person,
        public string $field_organization_type,
        public string $field_year_established,
        public string $field_event_calendar_homepage,
        public string $field_event_service,
        public string $field_events_api_key,
        public string $field_org_tags,
    ) {
        $this->established_at = Carbon::create($field_year_established);
    }

    public function isOrganizationInactive(): bool
    {
        return 'Inactive' === $this->field_org_status;
    }

    public function resolveCategory(): Category
    {
        if ($this->isOrganizationInactive()) {
            return Category::firstOrCreate(['label' => 'Inactive'], ['label' => 'Inactive']);
        }

        return Category::firstOrCreate(['label' => $this->field_organization_type]);
    }

    public function mapStatus()
    {
        return match ($this->field_org_status) {
            'Inactive' => OrganizationStatus::InActive,
            'Active' => OrganizationStatus::Active,
            default => throw new RuntimeException("Invalid status {$this->field_org_status}")
        };
    }

    public function mapService()
    {
        return match ($this->field_event_service) {
            'Meetup.com' => EventServices::MeetupRest,
            'Eventbrite.com' => EventServices::EventBrite,
            'Nvite.com' => EventServices::Nvite,
            'GetTogether.community' => EventServices::GetTogether,
            'Unknown', '' => null,
            default => throw new RuntimeException("Invalid organization service {$this->field_event_service}")
        };
    }
}
