<?php

namespace App\Notifications;

use App\Broadcasting\TelegramNotificationChannel;
use App\Entities\WeatherDataEntity;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Class TelegramNotification
 * @package App\Notifications\TelegramNotification
 */
class TelegramNotification extends Notification
{
    use Queueable;

    /** @var WeatherDataEntity $weatherDataEntity */
    protected WeatherDataEntity $weatherDataEntity;

    /**
     * @param WeatherDataEntity $weatherDataEntity
     */
    public function __construct(WeatherDataEntity $weatherDataEntity)
    {
        $this->weatherDataEntity = $weatherDataEntity;
    }

    /**
     * @return array
     */
    public function via(): array
    {
        return [TelegramNotificationChannel::class];
    }

    /**
     * @param User $notifiable
     * @return array
     * @throws Exception
     */
    public function toTelegram(User $notifiable): array
    {
        $compliedView = view(
            $this->getNotificationTemplate(),
            [
                'value' => $this->getNotificationWeatherValue(),
            ])
            ->render();

        return [
            'chat_id' => $notifiable->getChatId(),
            'text' => $compliedView,
            'parse_mode' => 'Markdown',
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getNotificationTemplate(): string
    {

        if ($this->weatherDataEntity->getIsPrecipitationThresholdReachedFlag()) {
            return 'notifications.telegram.precipitation';
        }

        if ($this->weatherDataEntity->getIsUVThresholdReachedFlag()) {
            return 'notifications.telegram.uv';
        }

        throw new Exception();
    }

    /**
     * @return float
     * @throws Exception
     */
    protected function getNotificationWeatherValue(): float
    {
        if ($this->weatherDataEntity->getIsPrecipitationThresholdReachedFlag()) {
            return $this->weatherDataEntity->getPrecipitation();
        }

        if ($this->weatherDataEntity->getIsUVThresholdReachedFlag()) {
            return $this->weatherDataEntity->getUVIndex();
        }

        throw new Exception();
    }
}
