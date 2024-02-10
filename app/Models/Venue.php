<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Venue
 *
 * @property int $id
 * @property string|null $slug
 * @property string $name
 * @property string|null $address
 * @property string|null $zipcode
 * @property string|null $phone
 * @property string|null $city
 * @property string|null $state_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read mixed $state_abbr
 * @property-read \App\Models\State|null $state
 * @method static \Database\Factories\VenueFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Venue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue query()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue whereZipcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Venue withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Venue withoutTrashed()
 * @mixin \Eloquent
 */
class Venue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'venues';

    protected $fillable
        = [
            'unique_venue_id',
            'slug',
            'name',
            'address',
            'zipcode',
            'phone',
            'city',
            'state_id',
            'country',
            'lat',
            'lng',
        ];

    public function __toString()
    {
        return "{$this->name} - {$this->address} {$this->city}, {$this->state_abbr} {$this->zipcode}";
    }

    public function getStateAbbrAttribute()
    {
        return $this->state->abbr;
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
