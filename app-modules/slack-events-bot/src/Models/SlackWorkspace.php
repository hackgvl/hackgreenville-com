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

    protected $guarded = [];

    /**
     * Encrypt the access token when setting it.
     */
    public function setAccessTokenAttribute(string $value): void
    {
        $this->attributes['access_token'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the access token when getting it.
     *
     * Ciphertext from another APP_KEY raises "The MAC is invalid." Return null
     * and leave the row alone so a reinstall can overwrite it, and so restoring
     * the previous APP_KEY can still decrypt it.
     */
    public function getAccessTokenAttribute(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException) {
            // Info stays out of the Slack log webhook, which records warning and above.
            Log::info('Slack workspace access token could not be decrypted.', [
                'team_id' => $this->team_id,
            ]);

            return null;
        }
    }

    protected static function newFactory()
    {
        return SlackWorkspaceFactory::new();
    }
}
