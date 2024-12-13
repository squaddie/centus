<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory */
    use HasFactory;

    /** @use Notifiable */
    use Notifiable;

    /*** @var array $fillable */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /** @var array $hidden */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return BelongsToMany
     */
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'user_city');
    }

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
