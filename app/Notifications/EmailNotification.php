<?php

namespace App\Notifications;

use App\Entities\WeatherDataEntity;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class EmailNotification
 * @package App\Notifications\EmailNotification
 */
class EmailNotification extends Notification
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
     * @param User $notifiable
     * @return array
     */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    /**
     * @param User $notifiable
     * @return MailMessage
     * @throws Exception
     */
    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('weather conditions')
            ->view(
                $this->getNotificationTemplate(), [
                'value' => $this->getNotificationWeatherValue(),
            ]);
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getNotificationTemplate(): string
    {
        if ($this->weatherDataEntity->getIsPrecipitationThresholdReachedFlag()) {
            return 'notifications.email.precipitation';
        }

        if ($this->weatherDataEntity->getIsUVThresholdReachedFlag()) {
            return 'notifications.email.uv';
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
