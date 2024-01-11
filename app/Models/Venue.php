<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property State state
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
