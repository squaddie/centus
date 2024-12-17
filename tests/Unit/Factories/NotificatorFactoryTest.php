<?php

namespace Tests\Unit\Factories;

use App\Entities\WeatherDataEntity;
use App\Factories\NotificatorFactory;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\TelegramNotification;
use Tests\TestCase;

/**
 * Class NotificatorFactoryTest
 * @package Tests\Unit\Factories
 * @coversDefaultClass \App\Factories\NotificatorFactory
 */
class NotificatorFactoryTest extends TestCase
{
    /**
     * @test
     * @covers ::getInstance
     */
    function it_should_return_email_notification_instance()
    {
        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isEmailChannel'])
            ->getMock();
        $notificatorFactoryMock = $this->getMockBuilder(NotificatorFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
        $weatherDataEntityStub = $this->createMock(WeatherDataEntity::class);

        $userMock->expects($this->once())->method('isEmailChannel')->willReturn(true);

        $this->assertInstanceOf(
            EmailNotification::class,
            $notificatorFactoryMock->getInstance($userMock, $weatherDataEntityStub)
        );
    }

    /**
     * @test
     * @covers ::getInstance
     */
    function it_should_return_telegram_notification_instance()
    {
        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isTelegramChannel'])
            ->getMock();
        $notificatorFactoryMock = $this->getMockBuilder(NotificatorFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
        $weatherDataValueObjectStub = $this->createMock(WeatherDataEntity::class);

        $userMock->expects($this->once())->method('isTelegramChannel')->willReturn(true);

        $this->assertInstanceOf(
            TelegramNotification::class,
            $notificatorFactoryMock->getInstance($userMock, $weatherDataValueObjectStub)
        );
    }
}
