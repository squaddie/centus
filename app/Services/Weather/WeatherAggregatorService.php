<?php

namespace App\Services\Weather;

use App\Entities\WeatherDataEntity;

/**
 * Class WeatherAggregatorService
 * @package App\Services\Weather\WeatherAggregatorService
 */
class WeatherAggregatorService
{
    /** @var array $providers */
    protected array $providers;

    /**
     * @param array $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @param string $city
     * @return WeatherDataEntity
     */
    public function getWeatherWithAverage(string $city): WeatherDataEntity
    {
        $weatherData = [];

        foreach ($this->providers as $provider) {
            $weatherData[] = $provider->getWeather($city);
        }

        return $this->calculateAverage($weatherData);
    }

    /**
     * @param array $weatherData
     * @return WeatherDataEntity
     */
    protected function calculateAverage(array $weatherData): WeatherDataEntity
    {
        $city = $weatherData[0]->city;

        $averageTemperature = $this->average(array_map(fn($data) => $data->temperature, $weatherData));
        $averagePrecipitation = $this->average(array_map(fn($data) => $data->precipitation, $weatherData));
        $averageUvIndex = $this->average(array_map(fn($data) => $data->uvIndex, $weatherData));

        return new WeatherDataEntity(
            $city,
            $averageTemperature,
            $averagePrecipitation,
            $averageUvIndex
        );
    }

    /**
     * @param array $values
     * @return float
     */
    protected function average(array $values): float
    {
        $filteredValues = array_filter($values, fn($value) => $value !== null);
        $count = count($filteredValues);

        return $count > 0 ? array_sum($filteredValues) / $count : 0.0;
    }
}

