<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string abbr
 * @property string name
 */
class State extends Model
{
    use SoftDeletes;

    protected $table = 'states';

    protected $fillable
        = [
            'abbr',
            'name',
        ];

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    public function get($abbr)
    {
        return $this->abbr($abbr)->first() ?: 0;
    }

    public function scopeAbbr($query, $abbr){
        return $query->where('abbr', 'like', $abbr);
    }
}
