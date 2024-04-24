<?php

namespace App\Models;

use App\Enums\EventServices;
use App\Http\SearchPipeline\Active;
use App\Http\SearchPipeline\Month;
use App\Traits\HasUniqueIdentifier;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Routing\Pipeline;
use RuntimeException;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property int|null $organization_id
 * @property EventServices $service
 * @property string $service_id
 * @property string $event_name
 * @property string $group_name
 * @property string $description
 * @property int|null $rsvp_count
 * @property \Illuminate\Support\Carbon|null $active_at
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property string $uri
 * @property int|null $venue_id
 * @property array $cache
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $expire_at
 * @property string|null $event_uuid
 * @property-read string $active_at_ftm
 * @property-read string $g_cal_url
 * @property-read \Carbon\Carbon|string $local_active_at
 * @property-read string $short_description
 * @property-read string $state
 * @property-read string $status
 * @property-read string $title
 * @property-read string|bool $unique_identifier
 * @property-read string $url
 * @property-read Org|null $organization
 * @property-read Venue|null $venue
 * @method static Builder|Event datesBetween($start, $end)
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static Builder|Event getActive()
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event onlyTrashed()
 * @method static Builder|Event query()
 * @method static Builder|Event search()
 * @method static Builder|Event startOfMonth()
 * @method static Builder|Event whereActiveAt($value)
 * @method static Builder|Event whereCache($value)
 * @method static Builder|Event whereCancelledAt($value)
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereDeletedAt($value)
 * @method static Builder|Event whereDescription($value)
 * @method static Builder|Event whereEventName($value)
 * @method static Builder|Event whereEventUuid($value)
 * @method static Builder|Event whereExpireAt($value)
 * @method static Builder|Event whereGroupName($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereOrganizationId($value)
 * @method static Builder|Event whereRsvpCount($value)
 * @method static Builder|Event whereService($value)
 * @method static Builder|Event whereServiceId($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereUri($value)
 * @method static Builder|Event whereVenueId($value)
 * @method static Builder|Event withTrashed()
 * @method static Builder|Event withoutTrashed()
 * @mixin \Eloquent
 */
class Event extends BaseModel
{
    use HasFactory;
    use HasUniqueIdentifier;
    use SoftDeletes;

    protected $table = 'events';

    protected $casts = [
        'cache' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'active_at' => 'datetime',
        'expire_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'service_id' => 'string',
        'service' => EventServices::class,
    ];

    protected $appends = [
        'short_description',
        'title',
        'active_at_ftm',
        'status',
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

    public function scopeGetActive(Builder $query): Builder
    {
        return $query
            ->where('active_at', '>=', DB::raw('NOW()'))
            ->orderBy('active_at', 'asc');
    }

    public function scopeStartOfMonth(Builder $query): Builder
    {
        return $query
            ->where('active_at', '>=', date('Y-m-1'))
            ->orderBy('active_at', 'asc');
    }

    public function scopeDatesBetween(Builder $query, $start, $end): Builder
    {
        return $query
            ->whereBetween(
                DB::raw('DATE(`active_at`)'),
                [
                    date('Y-m-d', strtotime($start)),
                    date('Y-m-d', strtotime($end)),
                ],
            );
    }

    public function scopeSearch(Builder $query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through(
                [
                    // Get the active events
                    Active::class,
                    Month::class,
                ],
            )
            ->thenReturn();
    }

    /**
     * accessor url to uri
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return $this->uri;
    }

    public function getStateAttribute(): string
    {
        if ($this->active_at->isPast()) {
            return 'passed';
        }

        return 'upcoming';
    }

    public function getStatusAttribute(): string
    {
        if ($this->cancelled_at) {
            return 'cancelled';
        }

        if ($this->active_at->isPast()) {
            return 'past';
        }

        if ($this->active_at->isFuture()) {
            return 'upcoming';
        }

        throw new RuntimeException('Unable to determine status');

    }

    /**
     * build out the link that adds this event to the users personal calendar
     *
     * @return string
     */
    public function getGCalUrlAttribute(): string
    {
        $event_time = $this->active_at->format('Y-m-d\TH:i:s\Z');

        $start_time = $this->active_at->format('Ymd\THis\Z');

        // Assume event is two hours long...
        $end_time = $this->active_at->addHours(2)->format('Ymd\THis\Z');

        $location = '';

        if (property_exists($this, 'venue') && ($this->venue !== null)) {
            $location .= $this->venue->name . ', ';
            $location .= $this->venue->address . ', ';
            $location .= $this->venue->city . ', ';
            $location .= $this->venue->state;
        }

        $calendar_url = "http://www.google.com/calendar/event?action=TEMPLATE&";
        $calendar_url .= 'text=' . urlencode($this->event_name) . '&';
        $calendar_url .= "dates={$start_time}/{$end_time}&";
        $calendar_url .= 'details=' . urlencode(strip_tags($this->description)) . '&';
        $calendar_url .= 'location=' . urlencode($location) . '&';
        $calendar_url .= "trp=false&";

        return $calendar_url;
    }

    public function getLocalActiveAtAttribute(): Carbon|string
    {
        return $this->active_at->tz(config('app.timezone'));
    }

    public function getShortDescriptionAttribute(): string
    {
        return str_limit($this->description);
    }

    public function getActiveAtFtmAttribute(): string
    {
        return $this->active_at->diffForHumans();
    }

    public function getTitleAttribute(): string
    {
        return $this->event_name;
    }

    public function doesNotExistOnEventService(): bool
    {
        return ! $this->organization
            ->getEventHandler()
            ->eventExistsOnService($this);
    }
}
