<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class MapLayer extends BaseModel
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'center_latitude' => 'decimal:6',
            'center_longitude' => 'decimal:6',
            'zoom_level' => 'integer',
            'maintainers' => 'array',
        ];
    }
}
