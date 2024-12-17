<?php

namespace App\Enums;

/**
 * Enum ChannelsEnum
 * @package App\Enums\ChannelsEnum
 */
enum ChannelsEnum: string
{
    case EMAIL = 'email';
    case TELEGRAM = 'telegram';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::EMAIL => 'Email',
            self::TELEGRAM => 'Telegram Bot',
        };
    }

    /**
     * @return array
     */
    public static function options(): array
    {
        return array_combine(
            array_map(fn ($case) => $case->value, self::cases()),
            array_map(fn ($case) => $case->label(), self::cases())
        );
    }
}
