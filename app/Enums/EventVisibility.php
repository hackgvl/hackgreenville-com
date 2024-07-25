<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EventVisibility: string implements HasLabel
{
    case Private = 'private';

    case Published = 'published';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Private => 'Private',
            self::Published => 'Published',
        };
    }
}
