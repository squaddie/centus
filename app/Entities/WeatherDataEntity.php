<?php

namespace App\Entities;

/**
 * Class WeatherDataEntity
 * @package App\Entities\WeatherDataEntity
 */
class WeatherDataEntity
{
    /**
     * @param string $city
     * @param float $temperature
     * @param float $precipitation
     * @param float $uvIndex
     * @param bool $isUVThresholdReached
     * @param bool $isPrecipitationThresholdReached
     */
    public function __construct(
        private string $city,
        private float $temperature,
        private float $precipitation,
        private float $uvIndex,
        private bool $isUVThresholdReached = false,
        private bool $isPrecipitationThresholdReached = false,
    )
    {

    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return  $this->city;
    }

    /**
     * @return float
     */
    public function getTemperature(): float
    {
        return  $this->temperature;
    }

    /**
     * @return float
     */
    public function getUVIndex(): float
    {
        return  $this->uvIndex;
    }

    /**
     * @return float
     */
    public function getPrecipitation(): float
    {
        return $this->precipitation;
    }

    /**
     * @return void
     */
    public function setPrecipitationThresholdReachedFlag(): void
    {
        $this->isPrecipitationThresholdReached = true;
    }

    /**
     * @return void
     */
    public function setUVThresholdReachedFlag(): void
    {
        $this->isUVThresholdReached = true;
    }

    /**
     * @return bool
     */
    public function getIsUVThresholdReachedFlag(): bool
    {
        return $this->isUVThresholdReached;
    }

    /**
     * @return bool
     */
    public function getIsPrecipitationThresholdReachedFlag(): bool
    {
        return $this->isPrecipitationThresholdReached;
    }
}
