<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = '';

    protected $fillable
        = [
            'slug',
            'name',
            'description',
            'venue_id',
            'cache',
            'scheduled_at',
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
            'scheduled_at',
        ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function scopeGetActive($query)
    {
        return $query->where('scheduled_at', '>=', DB::raw('UTC_TIME'));
    }
}
