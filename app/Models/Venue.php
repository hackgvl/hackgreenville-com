<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property State state
 */
class Venue extends Model
{
    use SoftDeletes;

    protected $table = 'venues';

    protected $fillable
        = [
            'slug',
            'name',
            'address',
            'zipcode',
            'phone',
            'city',
            'state_id',
            'lat',
            'lng',
        ];

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

    public function __toString()
    {
        return "{$this->name} - {$this->address} {$this->city}, {$this->state_abbr} {$this->zipcode}";
    }
}
