<?php

namespace App\Models;

use App\Enums\ChannelsEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @param int $chat_id
 * @param string $channel
 * @package App\Models\User
 */
class User extends Authenticatable
{
    /** @use HasFactory */
    use HasFactory;

    /** @use Notifiable */
    use Notifiable;

    /** @uses HasApiTokens */
    use HasApiTokens;

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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * @return BelongsToMany
     */
    public function cities(): BelongsToMany
    {
        return $this
            ->belongsToMany(City::class, UserCity::TABLE_NAME)
            ->latest()
            ->withPivot('threshold_uv', 'threshold_temperature');
    }

    /**
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this
            ->hasMany(History::class, 'u_id', 'id')
            ->latest();
    }

    /**
     * @return array
     */
    public function getUserHistory(): array
    {
        return $this
            ->with('history')
            ->with('history.city')
            ->find(auth()->id())
            ->toArray()['history'];
    }

    /**
     * @param float $currentIndex
     * @return bool
     */
    public function isUVThresholdReached(float $currentIndex): bool
    {
        return $this->original['pivot_threshold_uv'] <= $currentIndex;
    }

    /**
     * @param float $currentIndex
     * @return bool
     */
    public function isPrecipitationThresholdReached(float $currentIndex): bool
    {
        return $this->original['pivot_threshold_temperature'] <= $currentIndex;
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
    public function hasChatId(): bool
    {
        return !is_null($this->chat_id);
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
     * @param int $chatId
     * @return void
     */
    public function setChatId(int $chatId): void
    {
        $this->chat_id = $chatId;
        $this->save();
    }

    /**
     * @param string $channel
     * @return void
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
        $this->save();
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
