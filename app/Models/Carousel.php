<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string name
 * @property string slug
 * @property mixed  slides
 */
class Carousel extends Model
{
    use SoftDeletes;

    protected $table = 'carousels';
    protected $primaryKey = 'id';

    protected $fillable
        = [
            'name',
            'slug',
            'meta',
            'name',
            'md5_sum',
            'position',
            'slides',
        ];

    protected $casts = ['slides' => 'json'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('position', 'asc');
        });
    }
}
