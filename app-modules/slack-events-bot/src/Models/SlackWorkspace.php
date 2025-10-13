<?php

namespace HackGreenville\SlackEventsBot\Models;

use HackGreenville\SlackEventsBot\Database\Factories\SlackWorkspaceFactory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class SlackWorkspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'team_name',
        'access_token',
        'bot_user_id',
    ];

    /**
     * Encrypt the access token when setting it.
     */
    public function setAccessTokenAttribute(string $value): void
    {
        $this->attributes['access_token'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the access token when getting it.
     */
    public function getAccessTokenAttribute(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            Log::warning('Could not decrypt access token for workspace. The token might be stored in plain text.', [
                'workspace_id' => $this->id,
                'team_id' => $this->team_id,
            ]);

            return $value;
        }
    }

    protected static function newFactory()
    {
        return SlackWorkspaceFactory::new();
    }
}
