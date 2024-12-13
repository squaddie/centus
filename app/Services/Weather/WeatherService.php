<?php

namespace App\Services\Weather;

use App\ValueObjects\WeatherDataValueObject;

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
     * @return WeatherDataValueObject
     */
    abstract public function getWeather(string $city): WeatherDataValueObject;

    /**
     * @param array $response
     * @return WeatherDataValueObject
     */
    abstract protected function prepareResponse(array $response): WeatherDataValueObject;
}

