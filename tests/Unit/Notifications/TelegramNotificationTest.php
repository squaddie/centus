<?php

namespace Tests\Unit\Notifications;

use App\Broadcasting\TelegramNotificationChannel;
use App\Entities\WeatherDataEntity;
use App\Exceptions\TelegramNotificationTemplateException;
use App\Exceptions\TelegramNotificationWeatherException;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class TelegramNotificationTest
 * @package Tests\Unit\Notifications
 * @coversDefaultClass \App\Notifications\TelegramNotification
 */
class TelegramNotificationTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;

    /**
     * @test
     * @covers ::via
     */
    function it_should_return_email()
    {
        $expectedDriver = [TelegramNotificationChannel::class];

        $notificatorMock = $this->getMockBuilder(TelegramNotification::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals($expectedDriver, $notificatorMock->via());
    }

    /**
     * @test
     * @covers ::toTelegram
     */
    function it_should_prepare_telegram_instance_with_precipitation_message()
    {
        $city = $this->faker->city;
        $chatId = $this->faker->randomDigitNotNull();
        $value = $this->faker->randomFloat();
        $template = 'notifications.telegram.precipitation';
        $expectedMessage = [
            'chat_id' => $chatId,
            'text' => "The current precipitation for the city of $city is {$value}mm.\n",
            'parse_mode' => 'Markdown',
        ];

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notifiable = $this->createMock(User::class);
        $notification = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods(['getNotificationTemplate', 'getNotificationWeatherValue'])
            ->getMock();

        $weatherDataEntityMock->expects($this->once())->method('getCity')->willReturn($city);
        $notifiable->expects($this->once())->method('getChatId')->willReturn($chatId);
        $notification->expects($this->once())->method('getNotificationTemplate')->willReturn($template);
        $notification->expects($this->once())->method('getNotificationWeatherValue')->willReturn($value);

        $telegramMessage = $notification->toTelegram($notifiable);

        $this->assertEquals($expectedMessage, $telegramMessage);
    }

    /**
     * @test
     * @covers ::toTelegram
     */
    function it_should_prepare_telegram_instance_with_uv_message()
    {
        $city = $this->faker->city;
        $chatId = $this->faker->randomDigitNotNull();
        $value = $this->faker->randomFloat();
        $template = 'notifications.telegram.uv';
        $expectedMessage = [
            'chat_id' => $chatId,
            'text' => "The UV index is $value for the city of $city.\n\n",
            'parse_mode' => 'Markdown',
        ];

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notifiable = $this->createMock(User::class);
        $notification = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods(['getNotificationTemplate', 'getNotificationWeatherValue'])
            ->getMock();

        $weatherDataEntityMock->expects($this->once())->method('getCity')->willReturn($city);
        $notifiable->expects($this->once())->method('getChatId')->willReturn($chatId);
        $notification->expects($this->once())->method('getNotificationTemplate')->willReturn($template);
        $notification->expects($this->once())->method('getNotificationWeatherValue')->willReturn($value);

        $telegramMessage = $notification->toTelegram($notifiable);

        $this->assertEquals($expectedMessage, $telegramMessage);
    }

    /**
     * @test
     * @covers ::getNotificationTemplate
     */
    function it_should_return_telegram_notification_template_for_precipitation()
    {
        $template = 'notifications.telegram.precipitation';

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods([])
            ->getMock();

        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsPrecipitationThresholdReachedFlag')
            ->willReturn(true);

        $this->assertEquals(
            $template,
            $this->invokeMethod($notification, 'getNotificationTemplate')
        );
    }

    /**
     * @test
     * @covers ::getNotificationTemplate
     */
    function it_should_return_telegram_notification_template_for_uv()
    {
        $template = 'notifications.telegram.uv';

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods([])
            ->getMock();

        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsPrecipitationThresholdReachedFlag')
            ->willReturn(false);
        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsUVThresholdReachedFlag')
            ->willReturn(true);

        $this->assertEquals(
            $template,
            $this->invokeMethod($notification, 'getNotificationTemplate')
        );
    }

    /**
     * @test
     * @covers ::getNotificationTemplate
     */
    function it_should_throw_exception_when_telegram_notification_template_not_found()
    {
        $this->expectException(TelegramNotificationTemplateException::class);

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods([])
            ->getMock();

        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsPrecipitationThresholdReachedFlag')
            ->willReturn(false);
        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsUVThresholdReachedFlag')
            ->willReturn(false);

        $this->invokeMethod($notification, 'getNotificationTemplate');
    }

    /**
     * @test
     * @covers ::getNotificationWeatherValue
     */
    function it_should_return_telegram_notification_value_for_precipitation()
    {
        $precipitation = $this->faker->randomFloat();

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods([])
            ->getMock();

        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsPrecipitationThresholdReachedFlag')
            ->willReturn(true);
        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getPrecipitation')
            ->willReturn($precipitation);

        $this->assertEquals(
            $precipitation,
            $this->invokeMethod($notification, 'getNotificationWeatherValue')
        );
    }

    /**
     * @test
     * @covers ::getNotificationWeatherValue
     */
    function it_should_return_telegram_notification_value_for_uv()
    {
        $uv = $this->faker->randomFloat();

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods([])
            ->getMock();

        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsPrecipitationThresholdReachedFlag')
            ->willReturn(false);
        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsUVThresholdReachedFlag')
            ->willReturn(true);
        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getUVIndex')
            ->willReturn($uv);

        $this->assertEquals(
            $uv,
            $this->invokeMethod($notification, 'getNotificationWeatherValue')
        );
    }

    /**
     * @test
     * @covers ::getNotificationWeatherValue
     */
    function it_should_throw_exception_when_telegram_notification_value_not_found()
    {
        $this->expectException(TelegramNotificationWeatherException::class);

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods([])
            ->getMock();

        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsPrecipitationThresholdReachedFlag')
            ->willReturn(false);
        $weatherDataEntityMock
            ->expects($this->once())
            ->method('getIsUVThresholdReachedFlag')
            ->willReturn(false);

        $this->invokeMethod($notification, 'getNotificationWeatherValue');
    }
}
