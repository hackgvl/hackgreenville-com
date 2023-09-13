<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class VenueData extends Data
{
    public function __construct(
        public string $name,
        public ?string $address,
        public ?string $city,
        #[Computed]
        public ?string $state,
        public ?string $zip,
        public ?float $lat = 0,
        public ?float $lon = 0,
    ) {
        $this->state ??= 'SC';
    }
}
