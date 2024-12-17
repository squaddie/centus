<?php

namespace Tests\Unit\Broadcasting;

use App\Broadcasting\TelegramNotificationChannel;
use App\Entities\WeatherDataEntity;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Class TelegramNotificationChannelTest
 * @package Tests\Unit\Broadcasting
 * @coversDefaultClass \App\Broadcasting\TelegramNotificationChannel
 */
class TelegramNotificationChannelTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;

    const TELEGRAM_URL = 'https://api.telegram.org/bot%s/sendMessage?chat_id=%s&text=%s';

    /**
     * @test
     * @covers ::send
     */
    function it_should_send_a_notification_to_the_telegram_channel()
    {
        Http::fake();

        $user = User::factory()->make();
        $weatherDataEntity = new WeatherDataEntity('New York', 11, 1.0, 1.0);
        $chatId = $this->faker->randomDigitNotNull();
        $text = 'test';
        $expectedUrl = sprintf(self::TELEGRAM_URL, config('services.telegram.key'), $chatId, $text);
        $parameters = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        $telegramNotificationChannelMock = $this->getMockBuilder(TelegramNotificationChannel::class)
            ->onlyMethods(['getUrl'])
            ->getMock();
        $notificationMock = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntity])
            ->onlyMethods(['toTelegram'])
            ->getMock();

        $notificationMock->expects($this->once())->method('toTelegram')->with($user)->willReturn($parameters);
        $telegramNotificationChannelMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($parameters)
            ->willReturn($expectedUrl);
        $telegramNotificationChannelMock->send($user, $notificationMock);

        Http::assertSent(function ($request) use ($expectedUrl) {
            return $request->url() === $expectedUrl;
        });
    }

    /**
     * @test
     * @covers ::getUrl
     */
    function it_should_return_proper_telegram_url()
    {
        $chatId = $this->faker->randomDigitNotNull();
        $text = 'test';
        $expectedUrl = sprintf(self::TELEGRAM_URL, config('services.telegram.key'), $chatId, $text);
        $parameters = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        $telegramNotificationChannelMock = $this->createMock(TelegramNotificationChannel::class);

        $this->assertEquals(
            $expectedUrl,
            $this->invokeMethod($telegramNotificationChannelMock, 'getUrl', [$parameters])
        );
    }
}
