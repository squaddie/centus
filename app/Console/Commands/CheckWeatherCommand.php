<?php

namespace App\Console\Commands;

use App\Jobs\UsersWeatherJob;
use App\Models\City;
use Illuminate\Console\Command;

/**
 * Class CheckWeatherCommand
 * @package App\Console\Commands\CheckWeatherCommand
 */
class CheckWeatherCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'app:check-weather-command';
    /** @var string $description */
    protected $description = 'It will check the weather in users\' cities and notify them if the threshold is reached.';

    /**
     * @param City $city
     * @return void
     */
    public function handle(City $city): void
    {
        foreach ($city->getCitiesWithAttachedUsers() as $data) {
            dispatch(new UsersWeatherJob($data));
        }
    }
}
