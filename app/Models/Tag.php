<?php

namespace App\Models;

class Tag extends BaseModel
{
    public function orgs()
    {
        return $this->belongsToMany(Org::class);
    }
}
