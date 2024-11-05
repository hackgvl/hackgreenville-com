<?php

namespace App\Traits;

trait HasSlug
{
    public static bool $update_slug = false;

    public static function bootHasSlug()
    {
        static::creating(function ($model) {
            if ( ! $model[self::$slug_column ?? 'slug']) {
                $model[self::$slug_column ?? 'slug'] = $model->generateSlug($model[static::$slug_from ?? 'title']);
            }
        });

        if (static::$update_slug) {
            static::updating(function ($model) {
                $model[self::$slug_column ?? 'slug'] = $model->generateSlug($model[static::$slug_from ?? 'title']);
            });
        }
    }

    public function generateSlug($slug)
    {
        $slug ??= $this[static::$slug_from ?? 'title'];
        $slug = str_slug($slug);

        $count = 1;
        $originalSlug = $slug;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function slugExists($slug)
    {
        return static::where('slug', $slug)->exists();
    }
}
