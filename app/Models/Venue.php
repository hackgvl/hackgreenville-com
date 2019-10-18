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

    public function getStateAttribute()
    {
        return $this->state->abbr;
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
