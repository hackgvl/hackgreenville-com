<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $fillable
        = [
            'first_name',
            'last_name',
            'email',
            'password',
        ];

    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->active;
    }
}
