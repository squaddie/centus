<?php

namespace App\Jobs;

use App\Entities\WeatherDataEntity;
use App\Enums\WeatherTypeEnum;
use App\Factories\NotificatorFactory;
use App\Models\City;
use App\Models\History;
use App\Models\User;
use App\Services\Weather\WeatherAggregatorService;
use App\ValueObjects\HistoryValueObject;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     * @param History $history
     * @return void
     * @throws Exception
     */
    public function handle(
        WeatherAggregatorService $aggregatorService,
        NotificatorFactory $notificatorFactory,
        History $history
    ): void
    {
        $weatherData = $aggregatorService->getWeatherWithAverage($this->city->name);

        /** @var User $user */
        foreach ($this->city->users as $user) {
            if ($user->isUVThresholdReached($weatherData->getUVIndex())) {
                $wd = $weatherData;
                $wd->setUVThresholdReachedFlag();
                $user->notify($notificatorFactory->getInstance($user, $wd));
                $historyPayload = $this->getHistoryPayload($user, $wd, WeatherTypeEnum::TYPE_UV->value);
                $history->log($historyPayload->toArray());
            }

            if ($user->isPrecipitationThresholdReached($weatherData->getPrecipitation())) {
                $wd = $weatherData;
                $wd->setPrecipitationThresholdReachedFlag();
                $user->notify($notificatorFactory->getInstance($user, $wd));
                $historyPayload = $this->getHistoryPayload($user, $wd, WeatherTypeEnum::TYPE_PRECIPITATION->value);
                $history->log($historyPayload->toArray());
            }
        }
    }

    /**
     * @param User $user
     * @param WeatherDataEntity $weatherData
     * @param int $weatherType
     * @return HistoryValueObject
     */
    protected function getHistoryPayload(
        User $user,
        WeatherDataEntity $weatherData,
        int $weatherType
    ): HistoryValueObject
    {
        $value = $weatherType === WeatherTypeEnum::TYPE_UV->value ?
            $weatherData->getUVIndex() : $weatherData->getPrecipitation();

        return new HistoryValueObject(
            $user->id,
            $this->city->id,
            $value,
            $weatherType
        );
    }
}
