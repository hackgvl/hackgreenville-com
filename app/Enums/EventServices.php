<?php

namespace App\Enums;

enum EventServices: string
{
    case MeetupRest = 'meetup';

    case EventBrite = 'eventbrite';

    case Nvite = 'nvite';

    case GetTogether = 'get-together';
}
