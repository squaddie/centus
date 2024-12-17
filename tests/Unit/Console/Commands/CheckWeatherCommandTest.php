<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\CheckWeatherCommand;
use App\Jobs\UsersWeatherJob;
use App\Models\City;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class CheckWeatherCommandTest
 * @package Tests\Unit\Console\Commands
 * @coversDefaultClass \App\Console\Commands\CheckWeatherCommand
 */
class CheckWeatherCommandTest extends TestCase
{
    /**
     * @test
     * @covers ::handle
     */
    function it_should_fetch_cities_with_attached_users_and_schedule_notification_jobs()
    {
        Queue::fake();

        $cities = City::factory()->count(1)->make();

        $commandMock = $this->getMockBuilder(CheckWeatherCommand::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
        $cityMock = $this->getMockBuilder(City::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCitiesWithAttachedUsers'])
            ->getMock();

        $cityMock
            ->expects($this->once())
            ->method('getCitiesWithAttachedUsers')
            ->willReturn($cities);

        $commandMock->handle($cityMock);

        Queue::assertPushed(UsersWeatherJob::class);
    }
}
