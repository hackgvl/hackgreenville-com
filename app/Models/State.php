<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\State
 *
 * @property int $id
 * @property string $abbr
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $venues
 * @property-read int|null $venues_count
 * @method static \Illuminate\Database\Eloquent\Builder|State abbr($abbr)
 * @method static \Database\Factories\StateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|State withoutTrashed()
 * @mixin \Eloquent
 */
class State extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'states';

    protected $fillable
            = [
                'abbr',
                'name',
            ];

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    public function get($abbr)
    {
        return $this->abbr($abbr)->first() ?: 0;
    }

    public function scopeAbbr($query, $abbr)
    {
        return $query->where('abbr', 'like', $abbr);
    }
}
