<?php

namespace App\Providers;

use App\Services\Weather\WeatherBitService;
use App\Services\Weather\WeatherAggregatorService;
use App\Services\Weather\WeatherApiService;
use Illuminate\Support\ServiceProvider;

/**
 * Class WeatherServiceProvider
 * @package App\Providers\WeatherServiceProvider
 */
class WeatherServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(WeatherAggregatorService::class, function ($app) {
            return new WeatherAggregatorService([
                new WeatherBitService(config('services.weather.weatherbit.key')),
                new WeatherApiService(config('services.weather.weatherapi.key')),
            ]);
        });
    }
}
