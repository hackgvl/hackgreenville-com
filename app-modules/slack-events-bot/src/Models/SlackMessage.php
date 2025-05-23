<?php

namespace HackGreenville\SlackEventsBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlackMessage extends Model
{
    protected $fillable = [
        'week',
        'message_timestamp',
        'message',
        'sequence_position',
        'channel_id',
    ];

    protected $casts = [
        'week' => 'datetime',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(SlackChannel::class, 'channel_id');
    }
}
