<?php

namespace App\Services\Weather;

use App\Entities\WeatherDataEntity;

/**
 * Class WeatherService
 * @package App\Services\Weather\WeatherService
 */
abstract class WeatherService
{
    /** @var string $apiKey */
    protected string $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $city
     * @return WeatherDataEntity
     */
    abstract public function getWeather(string $city): WeatherDataEntity;

    /**
     * @param array $response
     * @return WeatherDataEntity
     */
    abstract protected function prepareResponse(array $response): WeatherDataEntity;
}

