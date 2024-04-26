<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrganizationStatus: string implements HasLabel
{
    case Active = 'active';

    case InActive = 'inactive';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'Active',
            self::InActive => 'In Active',
        };
    }
}
