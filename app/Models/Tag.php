<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends BaseModel
{
    use SoftDeletes;

    public function orgs()
    {
        return $this->belongsToMany(Org::class);
    }
}
