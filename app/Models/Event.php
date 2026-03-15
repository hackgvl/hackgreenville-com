<?php

namespace App\Models;

use App\Enums\EventServices;
use App\Enums\EventVisibility;
use App\Traits\HasUniqueIdentifier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends BaseModel
{
    use HasUniqueIdentifier;
    use SoftDeletes;

    protected $table = 'events';

    protected $casts = [
        'is_paid' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'active_at' => 'datetime',
        'expire_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'service_id' => 'string',
        'service' => EventServices::class,
        'visibility' => EventVisibility::class,
    ];

    public function getUniqueIdentifierAttribute(): bool|string
    {
        $service = $this->service;
        $service_id = $this->service_id;

        return json_encode(compact('service', 'service_id'));
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Org::class, 'organization_id');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('visibility', EventVisibility::Published);
    }

    public function scopeWithActiveOrganization(Builder $query): void
    {
        $query->whereHas('organization', fn (Builder $q) => $q->whereNull('deleted_at'));
    }

    public function scopeFilterByDateRange(Builder $query, ?Carbon $startDate, ?Carbon $endDate): void
    {
        $query
            ->when($startDate, fn (Builder $q) => $q->where('active_at', '>=', $startDate->startOfDay()))
            ->when($endDate, fn (Builder $q) => $q->where('active_at', '<=', $endDate->endOfDay()))
            ->when( ! $startDate && ! $endDate, fn (Builder $q) => $q->where('active_at', '>=', now()->subDays(config('events-api.default_days'))));
    }

    public function scopeFuture(Builder $query)
    {
        $query->where('active_at', '>=', now())
            ->orderBy('active_at', 'asc');
    }

    public function getUrlAttribute(): string
    {
        return $this->uri;
    }

    public function getStatusAttribute(): string
    {
        if ($this->cancelled_at) {
            return 'cancelled';
        }

        if ($this->active_at->isPast()) {
            return 'past';
        }

        return 'upcoming';
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->isCancelled()
            ? '[CANCELLED] ' . $this->event_name
            : $this->event_name;
    }

    public function toGoogleCalendarUrl(): string
    {
        $starts_at = $this->active_at->format('Ymd\THis');
        $ends_at = $this->expire_at->format('Ymd\THis');

        $query = http_build_query(array_filter([
            'text' => $this->event_name,
            'dates' => "{$starts_at}/{$ends_at}",
            'details' => strip_tags($this->description),
            'location' => $this->venue?->fullAddress(),
            'trp' => false,
        ]));

        return 'https://www.google.com/calendar/event?action=TEMPLATE&' . $query;
    }

    public function doesNotExistOnEventService(): bool
    {
        return ! $this->organization
            ->getEventHandler()
            ->eventExistsOnService($this);
    }

    public function isCancelled(): bool
    {
        return null !== $this->cancelled_at;
    }

    protected function expireAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value ?? $this->active_at->copy()->addHours(2)),
        );
    }
}
