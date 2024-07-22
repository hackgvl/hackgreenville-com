<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EventServices: string implements HasLabel
{
    case ManuallyManaged = 'manually_managed';

    case MeetupRest = 'meetup';

    case MeetupGraphql = 'meetup_graphql';

    case Luma = 'luma';

    case EventBrite = 'eventbrite';

    case Nvite = 'nvite';

    case GetTogether = 'get-together';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ManuallyManaged => 'Managed Manually',
            self::MeetupRest => 'Meetup.com',
            self::MeetupGraphql => 'Meetup.com (GraphQL)',
            self::Luma => 'Luma',
            self::EventBrite => 'EventBrite.com',
            self::Nvite => 'Nvite (not implemented)',
            self::GetTogether => 'GetTogether (not implemented)',
        };
    }
}
