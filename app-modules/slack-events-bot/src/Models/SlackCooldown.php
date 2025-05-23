<?php

namespace HackGreenville\SlackEventsBot\Models;

use Illuminate\Database\Eloquent\Model;

class SlackCooldown extends Model
{
    protected $fillable = [
        'accessor',
        'resource',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
