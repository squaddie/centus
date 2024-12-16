<?php

namespace App\Jobs;

use App\Factories\NotificatorFactory;
use App\Models\City;
use App\Models\User;
use App\Services\Weather\WeatherAggregatorService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Class UsersWeatherJob
 * @package App\Jobs\UsersWeatherJob
 */
class UsersWeatherJob implements ShouldQueue
{
    /** @uses Queueable */
    use Queueable;

    /**
     * @param City $city
     */
    public function __construct(private readonly City $city)
    {

    }

    /**
     * @param WeatherAggregatorService $aggregatorService
     * @param NotificatorFactory $notificatorFactory
     * @return void
     * @throws Exception
     */
    public function handle(WeatherAggregatorService $aggregatorService, NotificatorFactory $notificatorFactory): void
    {
        $weatherData = $aggregatorService->getWeatherWithAverage($this->city->name);

        /** @var User $user */
        foreach ($this->city->users as $user) {
            if ($user->isUVThresholdReached($weatherData->getUVIndex())) {
                $wd = $weatherData;
                $wd->setUVThresholdReachedFlag();
                $user->notify($notificatorFactory->getInstance($user, $wd));
            }

            if ($user->isPrecipitationThresholdReached($weatherData->getPrecipitation())) {
                $wd = $weatherData;
                $wd->setPrecipitationThresholdReachedFlag();
                $user->notify($notificatorFactory->getInstance($user, $wd));
            }
        }
    }
}
