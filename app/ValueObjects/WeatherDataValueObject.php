<?php

namespace App\ValueObjects;

class WeatherDataValueObject
{
    /**
     * @param string $city
     * @param float $temperature
     * @param float $precipitation
     * @param float $uvIndex
     */
    public function __construct(
        public string $city,
        public float $temperature,
        public float $precipitation,
        public float $uvIndex
    ) {}

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'city' => $this->city,
            'temperature' => $this->temperature,
            'precipitation' => $this->precipitation,
            'uv_index' => $this->uvIndex,
        ];
    }
}
