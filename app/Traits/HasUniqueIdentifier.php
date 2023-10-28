<?php

namespace App\Traits;

trait HasUniqueIdentifier
{
    public function uniqueIdentifier(): array
    {
        return [
            'service' => $this->service->value,
            'service_id' => $this->service_id,
        ];
    }

    public function uniqueIdentifierHash(): string
    {
        return hash(
            algo: 'md5',
            data: implode('', $this->uniqueIdentifier())
        );
    }
}
