<?php

namespace Tests\Unit\Notifications;

use App\Entities\WeatherDataEntity;
use App\Exceptions\EmailNotificationTemplateException;
use App\Exceptions\EmailNotificationWeatherException;
use App\Models\User;
use App\Notifications\EmailNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

/**
 * Class EmailNotificationTest
 * @package Tests\Unit\Notifications
 * @coversDefaultClass \App\Notifications\EmailNotification
 */
class EmailNotificationTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;

    /**
     * @test
     * @covers ::via
     */
    function it_should_return_email()
    {
        $expectedDriver = ['mail'];

        $userMock = $this->createMock(User::class);
        $notificatorMock = $this->getMockBuilder(EmailNotification::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals($expectedDriver, $notificatorMock->via($userMock));
    }

    /**
     * @test
     * @covers ::toMail
     */
    function it_should_prepare_mail_instance()
    {
        $city = $this->faker->city;
        $value = $this->faker->randomFloat();
        $views = ['email.precipitation', 'email.uv'];
        $template = $views[array_rand($views)];
        $expectedViewData = [
            'value' => $value,
            'city' => $city,
        ];

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notifiable = $this->createMock(User::class);
        $notification = $this->getMockBuilder(EmailNotification::class)
            ->setConstructorArgs([$weatherDataEntityMock])
            ->onlyMethods(['getNotificationTemplate', 'getNotificationWeatherValue'])
            ->getMock();

        $weatherDataEntityMock->expects($this->once())->method('getCity')->willReturn($city);
        $notification->expects($this->once())->method('getNotificationTemplate')->willReturn($template);
        $notification->expects($this->once())->method('getNotificationWeatherValue')->willReturn($value);

        $mailMessage = $notification->toMail($notifiable);

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        $this->assertEquals('Weather conditions', $mailMessage->subject);
        $this->assertEquals($template, $mailMessage->view);
        $this->assertEquals($expectedViewData, $mailMessage->viewData);
    }

    /**
     * @test
     * @covers ::getNotificationTemplate
     */
    function it_should_return_email_notification_template_for_precipitation()
    {
        $template = 'notifications.email.precipitation';

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(EmailNotification::class)
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
    function it_should_return_email_notification_template_for_uv()
    {
        $template = 'notifications.email.uv';

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(EmailNotification::class)
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
    function it_should_throw_exception_when_email_notification_template_not_found()
    {
        $this->expectException(EmailNotificationTemplateException::class);

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(EmailNotification::class)
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
    function it_should_return_email_notification_value_for_precipitation()
    {
        $precipitation = $this->faker->randomFloat();

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(EmailNotification::class)
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
    function it_should_return_email_notification_value_for_uv()
    {
        $uv = $this->faker->randomFloat();

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(EmailNotification::class)
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
    function it_should_throw_exception_when_email_notification_value_not_found()
    {
        $this->expectException(EmailNotificationWeatherException::class);

        $weatherDataEntityMock = $this->createMock(WeatherDataEntity::class);
        $notification = $this->getMockBuilder(EmailNotification::class)
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
