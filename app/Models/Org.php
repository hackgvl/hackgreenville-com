<?php

namespace App\Models;

use App\Enums\EventServices;
use App\Enums\OrganizationStatus;
use HackGreenville\EventImporter\Services\Concerns\AbstractEventHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string uri
 */
class Org extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'cache' => 'json',
        'status' => OrganizationStatus::class,
        'service' => EventServices::class,
        'established_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getUrlAttribute()
    {
        return $this->uri;
    }

    public function getHomePageAttribute()
    {
        return $this->uri ?: $this->path;
    }

    public function getEventImporterHandler(): AbstractEventHandler
    {
        /** @var AbstractEventHandler $handler */
        $handler = collect(config('event-import-handlers.handlers'))
            ->firstOrFail(fn ($handler, $service) => $this->service->value === $service);

        return new $handler($this);
    }
}
