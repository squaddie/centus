<?php

namespace App\Models;

use App\Enums\ChannelsEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Models\User
 * @param int $chat_id
 */
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
        return $this->belongsToMany(City::class, UserCity::TABLE_NAME);
    }

    /**
     * @param float $currentIndex
     * @return bool
     */
    public function isUVThresholdReached(float $currentIndex): bool
    {
        return $this->original['pivot_threshold_uv'] >= $currentIndex;
    }

    /**
     * @param float $currentIndex
     * @return bool
     */
    public function isPrecipitationThresholdReached(float $currentIndex): bool
    {
        return $this->original['pivot_threshold_temperature'] >= $currentIndex;
    }

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chat_id;
    }

    /**
     * @return bool
     */
    public function isEmailChannel(): bool
    {
        return $this->channel === ChannelsEnum::EMAIL->value;
    }

    /**
     * @return bool
     */
    public function isTelegramChannel(): bool
    {
        return $this->channel === ChannelsEnum::TELEGRAM->value;
    }

    /**
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
