<?php

namespace HackGreenville\SlackEventsBot\Models;

use HackGreenville\SlackEventsBot\Database\Factories\SlackWorkspaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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
     */
    public function getAccessTokenAttribute(string $value): string
    {
        return Crypt::decryptString($value);
    }

    protected static function newFactory()
    {
        return SlackWorkspaceFactory::new();
    }
}
