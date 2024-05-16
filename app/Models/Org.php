<?php

namespace App\Models;

use App\Enums\EventServices;
use App\Enums\OrganizationStatus;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Org
 *
 * @property int $id
 * @property int|null $category_id
 * @property string $title
 * @property string|null $path
 * @property string|null $city
 * @property string|null $focus_area
 * @property string|null $uri
 * @property string|null $primary_contact_person
 * @property string|null $organization_type
 * @property string|null $event_calendar_uri
 * @property EventServices|null $service
 * @property OrganizationStatus $status
 * @property string|null $service_api_key
 * @property array $cache
 * @property \Illuminate\Support\Carbon|null $established_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Category|null $category
 * @property-read mixed $home_page
 * @property-read mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\OrgFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Org newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Org newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Org onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Org query()
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereEstablishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereEventCalendarUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereFocusArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereOrganizationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org wherePrimaryContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereServiceApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Org withoutTrashed()
 * @mixin \Eloquent
 */
class Org extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'cache' => 'json',
        'status' => OrganizationStatus::class,
        'service' => EventServices::class,
        'established_at' => 'datetime',
        'inactive_at' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'organization_id');
    }

    public function isActive(): bool
    {
        return $this->status === OrganizationStatus::Active;
    }

    public function scopeHasConfiguredEventService($query): void
    {
        $query->where('status', OrganizationStatus::Active)
            ->whereIn('service', config('event-import-handlers.active_services'))
            ->whereNotNull('service')
            ->whereNotNull('service_api_key');
    }

    public function getEventHandler(): AbstractEventHandler
    {
        /** @var AbstractEventHandler $handler */
        $handler = collect(config('event-import-handlers.handlers'))
            ->firstOrFail(fn ($handler, $service) => $this->service->value === $service);

        return new $handler($this);
    }
}
