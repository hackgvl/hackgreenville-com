<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EventServices: string implements HasLabel
{
    case MeetupRest = 'meetup';

    case EventBrite = 'eventbrite';

    case Nvite = 'nvite';

    case GetTogether = 'get-together';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MeetupRest => 'Meetup.com',
            self::EventBrite => 'EventBrite.com',
            self::Nvite => 'Nvite (not implemented)',
            self::GetTogether => 'GetTogether (not implemented)',
        };
    }
}
