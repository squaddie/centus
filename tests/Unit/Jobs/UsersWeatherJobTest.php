<?php

namespace Tests\Unit\Jobs;

use App\Entities\WeatherDataEntity;
use App\Factories\NotificatorFactory;
use App\Jobs\UsersWeatherJob;
use App\Models\City;
use App\Models\History;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\TelegramNotification;
use App\Services\Weather\WeatherAggregatorService;
use App\ValueObjects\HistoryValueObject;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class UsersWeatherJobTest
 * @package Tests\Unit\Jobs
 * @coversDefaultClass \App\Jobs\UsersWeatherJob
 */
class UsersWeatherJobTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;

    const TYPE_UV = 1;
    const TYPE_PRECIPITATION = 2;

    /**
     * @test
     * @covers ::handle
     */
    function it_should_notify_via_telegram_about_uv_index()
    {
        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isUVThresholdReached', 'isPrecipitationThresholdReached', 'notify'])
            ->getMock();
        $historyMock = $this->getMockBuilder(History::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['log'])
            ->getMock();
        $notificatorFactoryMock = $this->getMockBuilder(NotificatorFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getInstance'])
            ->getMock();
        $weatherAggregatorServiceMock = $this->getMockBuilder(WeatherAggregatorService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWeatherWithAverage'])
            ->getMock();

        $userId = $this->faker->randomDigitNotNull;
        $city = City::factory()->make();
        $city->users = collect([$userMock]);
        $weatherDataEntity = new WeatherDataEntity(
            $city->name,
            $this->faker->randomDigitNotNull,
            $this->faker->randomFloat(),
            $this->faker->randomFloat(),
            true,
            false
        );
        $historyValueObject = new HistoryValueObject(
            $userId,
            $city->id,
            $weatherDataEntity->getUVIndex(),
            self::TYPE_UV
        );
        $history = History::factory()->make($historyValueObject->toArray());

        $telegramNotificationStub = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntity])
            ->getMock();
        $jobMock = $this->getMockBuilder(UsersWeatherJob::class)
            ->setConstructorArgs([$city])
            ->onlyMethods(['getHistoryPayload'])
            ->getMock();

        $weatherAggregatorServiceMock
            ->expects($this->once())
            ->method('getWeatherWithAverage')
            ->with($city->name)
            ->willReturn($weatherDataEntity);
        $userMock
            ->expects($this->once())
            ->method('isUVThresholdReached')
            ->with($weatherDataEntity->getUVIndex())
            ->willReturn(true);
        $userMock
            ->expects($this->once())
            ->method('isPrecipitationThresholdReached')
            ->with($weatherDataEntity->getPrecipitation())
            ->willReturn(false);
        $notificatorFactoryMock
            ->expects($this->once())
            ->method('getInstance')
            ->with($userMock, $weatherDataEntity)
            ->willReturn($telegramNotificationStub);
        $userMock->expects($this->once())->method('notify')->with($telegramNotificationStub);
        $jobMock
            ->expects($this->once())
            ->method('getHistoryPayload')
            ->with($userMock, $weatherDataEntity, self::TYPE_UV)
            ->willReturn($historyValueObject);
        $historyMock->expects($this->once())->method('log')->with($historyValueObject->toArray())->willReturn($history);

        $this->assertNull($jobMock->handle($weatherAggregatorServiceMock, $notificatorFactoryMock, $historyMock));
    }

    /**
     * @test
     * @covers ::handle
     */
    function it_should_notify_via_telegram_about_precipitation()
    {
        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isUVThresholdReached', 'isPrecipitationThresholdReached', 'notify'])
            ->getMock();
        $historyMock = $this->getMockBuilder(History::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['log'])
            ->getMock();
        $notificatorFactoryMock = $this->getMockBuilder(NotificatorFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getInstance'])
            ->getMock();
        $weatherAggregatorServiceMock = $this->getMockBuilder(WeatherAggregatorService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWeatherWithAverage'])
            ->getMock();

        $userId = $this->faker->randomDigitNotNull;
        $city = City::factory()->make();
        $city->users = collect([$userMock]);
        $weatherDataEntity = new WeatherDataEntity(
            $city->name,
            $this->faker->randomDigitNotNull,
            $this->faker->randomFloat(),
            $this->faker->randomFloat(),
            false,
            true
        );
        $historyValueObject = new HistoryValueObject(
            $userId,
            $city->id,
            $weatherDataEntity->getPrecipitation(),
            self::TYPE_PRECIPITATION
        );
        $history = History::factory()->make($historyValueObject->toArray());

        $telegramNotificationStub = $this->getMockBuilder(TelegramNotification::class)
            ->setConstructorArgs([$weatherDataEntity])
            ->getMock();
        $jobMock = $this->getMockBuilder(UsersWeatherJob::class)
            ->setConstructorArgs([$city])
            ->onlyMethods(['getHistoryPayload'])
            ->getMock();

        $weatherAggregatorServiceMock
            ->expects($this->once())
            ->method('getWeatherWithAverage')
            ->with($city->name)
            ->willReturn($weatherDataEntity);
        $userMock
            ->expects($this->once())
            ->method('isUVThresholdReached')
            ->with($weatherDataEntity->getUVIndex())
            ->willReturn(false);
        $userMock
            ->expects($this->once())
            ->method('isPrecipitationThresholdReached')
            ->with($weatherDataEntity->getPrecipitation())
            ->willReturn(true);
        $notificatorFactoryMock
            ->expects($this->once())
            ->method('getInstance')
            ->with($userMock, $weatherDataEntity)
            ->willReturn($telegramNotificationStub);
        $userMock->expects($this->once())->method('notify')->with($telegramNotificationStub);
        $jobMock
            ->expects($this->once())
            ->method('getHistoryPayload')
            ->with($userMock, $weatherDataEntity, self::TYPE_PRECIPITATION)
            ->willReturn($historyValueObject);
        $historyMock->expects($this->once())->method('log')->with($historyValueObject->toArray())->willReturn($history);

        $this->assertNull($jobMock->handle($weatherAggregatorServiceMock, $notificatorFactoryMock, $historyMock));
    }

    /**
     * @test
     * @covers ::handle
     */
    function it_should_notify_via_email_about_uv_index()
    {
        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isUVThresholdReached', 'isPrecipitationThresholdReached', 'notify'])
            ->getMock();
        $historyMock = $this->getMockBuilder(History::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['log'])
            ->getMock();
        $notificatorFactoryMock = $this->getMockBuilder(NotificatorFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getInstance'])
            ->getMock();
        $weatherAggregatorServiceMock = $this->getMockBuilder(WeatherAggregatorService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWeatherWithAverage'])
            ->getMock();

        $userId = $this->faker->randomDigitNotNull;
        $city = City::factory()->make();
        $city->users = collect([$userMock]);
        $weatherDataEntity = new WeatherDataEntity(
            $city->name,
            $this->faker->randomDigitNotNull,
            $this->faker->randomFloat(),
            $this->faker->randomFloat(),
            true,
            false
        );
        $historyValueObject = new HistoryValueObject(
            $userId,
            $city->id,
            $weatherDataEntity->getUVIndex(),
            self::TYPE_UV
        );
        $history = History::factory()->make($historyValueObject->toArray());

        $emailNotificationStub = $this->getMockBuilder(EmailNotification::class)
            ->setConstructorArgs([$weatherDataEntity])
            ->getMock();
        $jobMock = $this->getMockBuilder(UsersWeatherJob::class)
            ->setConstructorArgs([$city])
            ->onlyMethods(['getHistoryPayload'])
            ->getMock();

        $weatherAggregatorServiceMock
            ->expects($this->once())
            ->method('getWeatherWithAverage')
            ->with($city->name)
            ->willReturn($weatherDataEntity);
        $userMock
            ->expects($this->once())
            ->method('isUVThresholdReached')
            ->with($weatherDataEntity->getUVIndex())
            ->willReturn(true);
        $userMock
            ->expects($this->once())
            ->method('isPrecipitationThresholdReached')
            ->with($weatherDataEntity->getPrecipitation())
            ->willReturn(false);
        $notificatorFactoryMock
            ->expects($this->once())
            ->method('getInstance')
            ->with($userMock, $weatherDataEntity)
            ->willReturn($emailNotificationStub);
        $userMock->expects($this->once())->method('notify')->with($emailNotificationStub);
        $jobMock
            ->expects($this->once())
            ->method('getHistoryPayload')
            ->with($userMock, $weatherDataEntity, self::TYPE_UV)
            ->willReturn($historyValueObject);
        $historyMock->expects($this->once())->method('log')->with($historyValueObject->toArray())->willReturn($history);

        $this->assertNull($jobMock->handle($weatherAggregatorServiceMock, $notificatorFactoryMock, $historyMock));
    }

    /**
     * @test
     * @covers ::handle
     */
    function it_should_notify_via_email_about_precipitation()
    {
        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isUVThresholdReached', 'isPrecipitationThresholdReached', 'notify'])
            ->getMock();
        $historyMock = $this->getMockBuilder(History::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['log'])
            ->getMock();
        $notificatorFactoryMock = $this->getMockBuilder(NotificatorFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getInstance'])
            ->getMock();
        $weatherAggregatorServiceMock = $this->getMockBuilder(WeatherAggregatorService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWeatherWithAverage'])
            ->getMock();

        $userId = $this->faker->randomDigitNotNull;
        $city = City::factory()->make();
        $city->users = collect([$userMock]);
        $weatherDataEntity = new WeatherDataEntity(
            $city->name,
            $this->faker->randomDigitNotNull,
            $this->faker->randomFloat(),
            $this->faker->randomFloat(),
            false,
            true
        );
        $historyValueObject = new HistoryValueObject(
            $userId,
            $city->id,
            $weatherDataEntity->getPrecipitation(),
            self::TYPE_PRECIPITATION
        );
        $history = History::factory()->make($historyValueObject->toArray());

        $emailNotificationStub = $this->getMockBuilder(EmailNotification::class)
            ->setConstructorArgs([$weatherDataEntity])
            ->getMock();
        $jobMock = $this->getMockBuilder(UsersWeatherJob::class)
            ->setConstructorArgs([$city])
            ->onlyMethods(['getHistoryPayload'])
            ->getMock();

        $weatherAggregatorServiceMock
            ->expects($this->once())
            ->method('getWeatherWithAverage')
            ->with($city->name)
            ->willReturn($weatherDataEntity);
        $userMock
            ->expects($this->once())
            ->method('isUVThresholdReached')
            ->with($weatherDataEntity->getUVIndex())
            ->willReturn(false);
        $userMock
            ->expects($this->once())
            ->method('isPrecipitationThresholdReached')
            ->with($weatherDataEntity->getPrecipitation())
            ->willReturn(true);
        $notificatorFactoryMock
            ->expects($this->once())
            ->method('getInstance')
            ->with($userMock, $weatherDataEntity)
            ->willReturn($emailNotificationStub);
        $userMock->expects($this->once())->method('notify')->with($emailNotificationStub);
        $jobMock
            ->expects($this->once())
            ->method('getHistoryPayload')
            ->with($userMock, $weatherDataEntity, self::TYPE_PRECIPITATION)
            ->willReturn($historyValueObject);
        $historyMock->expects($this->once())->method('log')->with($historyValueObject->toArray())->willReturn($history);

        $this->assertNull($jobMock->handle($weatherAggregatorServiceMock, $notificatorFactoryMock, $historyMock));
    }

    /**
     * @test
     * @covers ::getHistoryPayload
     */
    function it_should_return_history_log_payload_for_uv_index()
    {
        $userMock = $this->createMock(User::class);

        $user = User::factory()->make(['id' => $this->faker->randomDigitNotNull]);
        $city = City::factory()->make();
        $city->users = collect([$userMock]);
        $weatherDataEntity = new WeatherDataEntity(
            $city->name,
            $this->faker->randomDigitNotNull,
            $this->faker->randomFloat(),
            $this->faker->randomFloat(),
            $this->faker->boolean(),
            $this->faker->boolean()
        );
        $historyValueObject = new HistoryValueObject(
            $user->id,
            $city->id,
            $weatherDataEntity->getUVIndex(),
            self::TYPE_UV
        );

        $jobMock = $this->getMockBuilder(UsersWeatherJob::class)
            ->setConstructorArgs([$city])
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals(
            $historyValueObject,
            $this->invokeMethod($jobMock, 'getHistoryPayload', [$user, $weatherDataEntity, self::TYPE_UV])
        );
    }

    /**
     * @test
     * @covers ::getHistoryPayload
     */
    function it_should_return_history_log_payload_for_precipitation()
    {
        $userMock = $this->createMock(User::class);

        $user = User::factory()->make(['id' => $this->faker->randomDigitNotNull]);
        $city = City::factory()->make();
        $city->users = collect([$userMock]);
        $weatherDataEntity = new WeatherDataEntity(
            $city->name,
            $this->faker->randomDigitNotNull,
            $this->faker->randomFloat(),
            $this->faker->randomFloat(),
            $this->faker->boolean(),
            $this->faker->boolean()
        );
        $historyValueObject = new HistoryValueObject(
            $user->id,
            $city->id,
            $weatherDataEntity->getPrecipitation(),
            self::TYPE_PRECIPITATION
        );

        $jobMock = $this->getMockBuilder(UsersWeatherJob::class)
            ->setConstructorArgs([$city])
            ->onlyMethods([])
            ->getMock();

        $this->assertEquals(
            $historyValueObject,
            $this->invokeMethod($jobMock, 'getHistoryPayload', [$user, $weatherDataEntity, self::TYPE_PRECIPITATION])
        );
    }
}
