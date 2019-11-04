<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Categories
 * @property int $id
 * @package App\Models
 */
class Category extends Model
{
    use SoftDeletes;

    protected $fillable
        = [
            'slug',
            'label',
        ];
}
