<?php

namespace App\Models;

use App\Enums\EventServices;
use App\Enums\OrganizationStatus;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Org extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
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

    public function scopeActive($query)
    {
        return $query->where('status', OrganizationStatus::Active);
    }

    public function scopeHasConfiguredEventService($query): void
    {
        $query->active()
            ->whereIn('service', config('event-import-handlers.active_services'))
            ->whereNotNull('service')
            ->whereNotNull('service_api_key');
    }

    public function scopeOrderByFieldSequence($query, string $column, array $sequence = []): void
    {
        if (empty($sequence)) {
            $query->orderBy($column);

            return;
        }

        $placeholders = implode(',', array_fill(0, count($sequence), '?'));

        $query->orderByRaw("
                CASE
                    WHEN {$column} IN ({$placeholders}) THEN 0
                    ELSE 999999
                END", $sequence);
    }

    public function getEventHandler(): AbstractEventHandler
    {
            $handler = collect(config('event-import-handlers.handlers'))
            ->firstOrFail(fn ($handler, $service) => $this->service->value === $service);

        return new $handler($this);
    }
}
