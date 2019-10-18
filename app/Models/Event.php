<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events';

    protected $fillable
        = [
            'event_name',
            'group_name',
            'description',
            'rsvp_count',
            'active_at',
            'uri',
            'venue_id',
            'cache',
        ];

    protected $casts
        = [
            'cache' => 'json',
        ];

    protected $dates
        = [
            'created_at',
            'updated_at',
            'deleted_at',
            'active_at',
        ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function scopeGetActive($query)
    {
        return $query->where('active_at', '>=', DB::raw('UTC_TIME'));
    }


    public function getStateAttribute()
    {
        if($this->active_at->isPast()){
            return 'passed';
        }

        //TODO :: create the condition that returns the status of live
        if (false) {
            return 'live';
        }

        return 'upcoming';
    }
}
