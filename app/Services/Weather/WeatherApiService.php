<?php

namespace App\Services\Weather;

use App\Exceptions\WeatherApiRequestFailedException;
use App\Entities\WeatherDataEntity;
use Illuminate\Support\Facades\Http;

/**
 * Class WeatherApiService
 * @package App\Services\Weather\WeatherApiService
 */
class WeatherApiService extends WeatherService
{
    /** @var string $weatherUrl */
    private string $weatherUrl = 'https://api.weatherapi.com/v1/current.json';

    /**
     * @param string $city
     * @return WeatherDataEntity
     * @throws WeatherApiRequestFailedException
     */
    public function getWeather(string $city): WeatherDataEntity
    {
        $response = Http::get($this->weatherUrl, [
            'key' => $this->apiKey,
            'q' => $city,
        ]);

        if ($response->failed()) {
            throw new WeatherApiRequestFailedException();
        }

        return $this->prepareResponse($response->json());
    }

    /**
     * @param array $response
     * @return WeatherDataEntity
     */
    protected function prepareResponse(array $response): WeatherDataEntity
    {
        return new WeatherDataEntity(
            $response['location']['name'] ?? null,
            $response['current']['temp_c'] ?? null,
            $response['current']['precip_mm'] ?? 0,
            $response['current']['uv'] ?? null
        );
    }
}
