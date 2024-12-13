<?php

namespace App\Services\Weather;

use App\Exceptions\WeatherApiRequestFailedException;
use App\ValueObjects\WeatherDataValueObject;
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
     * @return WeatherDataValueObject
     * @throws WeatherApiRequestFailedException
     */
    public function getWeather(string $city): WeatherDataValueObject
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
     * @return WeatherDataValueObject
     */
    protected function prepareResponse(array $response): WeatherDataValueObject
    {
        //precip_mm = осадки
        //uv = уровень ультрафиолета
        return new WeatherDataValueObject(
            $response['location']['name'] ?? null,
            $response['current']['temp_c'] ?? null,
            $response['current']['precip_mm'] ?? 0,
            $response['current']['uv'] ?? null
        );
    }
}
