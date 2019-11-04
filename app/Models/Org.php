<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Org extends Model
{
    use SoftDeletes;

    protected $table = 'orgs';
    protected $primaryKey = 'id';

    protected $appends
        = [
            'nid',
        ];

    protected $fillable
        = [
            'category_id',
            'title',
            'path',
            'city',
            'focus_area',
            'uri',
            'primary_contact_person',
            'organization_type',
            'event_calendar_uri',
            'cache',
        ];

    protected $casts
        = [
            'cache' => 'json',
        ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getHomePageAttribute()
    {
        return $this->uri ?: $this->path;
    }

    public function getNidAttribute()
    {
        return $this->cache['nid'];
    }
}
