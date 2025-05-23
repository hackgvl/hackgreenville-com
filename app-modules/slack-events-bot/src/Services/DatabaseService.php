<?php

namespace HackGreenville\SlackEventsBot\Services;

use Carbon\Carbon;
use HackGreenville\SlackEventsBot\Models\SlackChannel;
use HackGreenville\SlackEventsBot\Models\SlackCooldown;
use HackGreenville\SlackEventsBot\Models\SlackMessage;
use Illuminate\Support\Collection;

class DatabaseService
{
    public function createMessage(
        Carbon $week,
        string $message,
        string $messageTimestamp,
        string $slackChannelId,
        int $sequencePosition
    ): SlackMessage {
        $channel = SlackChannel::where('slack_channel_id', $slackChannelId)->firstOrFail();

        return SlackMessage::create([
            'week' => $week,
            'message' => $message,
            'message_timestamp' => $messageTimestamp,
            'channel_id' => $channel->id,
            'sequence_position' => $sequencePosition,
        ]);
    }

    public function updateMessage(
        Carbon $week,
        string $message,
        string $messageTimestamp,
        string $slackChannelId
    ): int {
        $channel = SlackChannel::where('slack_channel_id', $slackChannelId)->firstOrFail();

        return SlackMessage::where('week', $week)
            ->where('message_timestamp', $messageTimestamp)
            ->where('channel_id', $channel->id)
            ->update(['message' => $message]);
    }

    public function getMessages(Carbon $week): Collection
    {
        return SlackMessage::with('channel')
            ->where('week', $week)
            ->orderBy('sequence_position')
            ->get()
            ->map(fn ($message) => [
                'message' => $message->message,
                'message_timestamp' => $message->message_timestamp,
                'slack_channel_id' => $message->channel->slack_channel_id,
                'sequence_position' => $message->sequence_position,
            ]);
    }

    public function getMostRecentMessageForChannel(string $slackChannelId): ?array
    {
        $channel = SlackChannel::where('slack_channel_id', $slackChannelId)->first();

        if ( ! $channel) {
            return null;
        }

        $message = SlackMessage::where('channel_id', $channel->id)
            ->orderBy('week', 'desc')
            ->orderBy('message_timestamp', 'desc')
            ->first();

        if ( ! $message) {
            return null;
        }

        return [
            'week' => $message->week->toIso8601String(),
            'message' => $message->message,
            'message_timestamp' => $message->message_timestamp,
        ];
    }

    public function getSlackChannelIds(): Collection
    {
        return SlackChannel::pluck('slack_channel_id');
    }

    public function addChannel(string $slackChannelId): SlackChannel
    {
        return SlackChannel::create(['slack_channel_id' => $slackChannelId]);
    }

    public function removeChannel(string $slackChannelId): int
    {
        return SlackChannel::where('slack_channel_id', $slackChannelId)->delete();
    }

    public function deleteOldMessages(int $daysBack = 90): void
    {
        $cutoffDate = Carbon::now()->subDays($daysBack);

        // Delete old messages
        SlackMessage::whereRaw('CAST(message_timestamp AS DECIMAL) < ?', [$cutoffDate->timestamp])
            ->delete();

        // Delete old cooldowns
        SlackCooldown::where('expires_at', '<', $cutoffDate)->delete();
    }

    public function createCooldown(string $accessor, string $resource, int $cooldownMinutes): SlackCooldown
    {
        return SlackCooldown::updateOrCreate(
            [
                'accessor' => $accessor,
                'resource' => $resource,
            ],
            [
                'expires_at' => Carbon::now()->addMinutes($cooldownMinutes),
            ]
        );
    }

    public function getCooldownExpiryTime(string $accessor, string $resource): ?Carbon
    {
        $cooldown = SlackCooldown::where('accessor', $accessor)
            ->where('resource', $resource)
            ->first();

        return $cooldown?->expires_at;
    }
}
