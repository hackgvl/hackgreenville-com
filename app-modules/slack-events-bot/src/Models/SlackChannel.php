<?php

namespace HackGreenville\SlackEventsBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SlackChannel extends Model
{
    protected $fillable = [
        'slack_channel_id',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(SlackMessage::class, 'channel_id');
    }
}
