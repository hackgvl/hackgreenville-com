<?php

namespace HackGreenville\SlackEventsBot\Models;

use Illuminate\Database\Eloquent\Model;

class SlackWorkspace extends Model
{
    protected $fillable = [
        'team_id',
        'team_name',
        'access_token',
        'bot_user_id',
    ];
}
