<?php

namespace HackGreenville\SlackEventsBot\Models;

use HackGreenville\SlackEventsBot\Database\Factories\SlackChannelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SlackChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'slack_channel_id',
        'slack_workspace_id',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(SlackMessage::class, 'channel_id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(SlackWorkspace::class, 'slack_workspace_id');
    }

    protected static function newFactory()
    {
        return SlackChannelFactory::new();
    }
}
