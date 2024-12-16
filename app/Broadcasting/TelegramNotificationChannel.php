<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

/**
 * Class TelegramNotificationChannel
 * @package App\Broadcasting\TelegramNotificationChannel
 */
class TelegramNotificationChannel
{
    const TELEGRAM_URL = 'https://api.telegram.org/bot%s/sendMessage';

    /**
     * @param User $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send(User $notifiable, Notification $notification): void
    {
        $url = $this->getUrl($notification->toTelegram($notifiable));

        Http::get($url);
    }

    /**
     * @param array $queryParams
     * @return string
     */
    protected function getUrl(array $queryParams): string
    {
        $baseUrl = sprintf(self::TELEGRAM_URL, '7642896575:AAEEa4ixp1fLwqB0Mnyi0TEKU1unaTEP28A');

        return $baseUrl . '?' . http_build_query($queryParams);
    }
}
