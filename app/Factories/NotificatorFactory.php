<?php

namespace App\Factories;

use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\TelegramNotification;
use App\Entities\WeatherDataEntity;
use Exception;
use Illuminate\Notifications\Notification;

/**
 * Class NotificatorFactory
 * @package App\Factories\NotificatorFactory
 */
class NotificatorFactory
{
    /**
     * @param User $user
     * @param WeatherDataEntity $weatherDataValueObject
     * @return Notification
     * @throws Exception
     */
    public function getInstance(User $user, WeatherDataEntity $weatherDataValueObject): Notification
    {
        if ($user->isEmailChannel()) {
            return new EmailNotification($weatherDataValueObject);
        }

        if ($user->isTelegramChannel()) {
            return new TelegramNotification($weatherDataValueObject);
        }

        throw new Exception();
    }
}
