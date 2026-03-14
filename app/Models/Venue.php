<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'venues';

    protected $fillable = [
        'unique_venue_id',
        'slug',
        'name',
        'address',
        'zipcode',
        'phone',
        'city',
        'state',
        'country',
        'lat',
        'lng',
    ];

    public function fullAddress()
    {
        $location = collect([$this->city, $this->state])
            ->filter()
            ->join(', ');

        return collect([
            "{$this->name} - {$this->address}",
            mb_trim("{$location} {$this->zipcode}"),
        ])->filter()->join(' ');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
