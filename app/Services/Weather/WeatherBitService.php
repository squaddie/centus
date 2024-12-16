<?php

namespace App\Services\Weather;

use App\Exceptions\WeatherBitRequestFailedException;
use App\Entities\WeatherDataEntity;
use Exception;
use Illuminate\Support\Facades\Http;

/**
 * Class WeatherBitService
 * @package App\Services\Weather\WeatherBitService
 */
class WeatherBitService extends WeatherService
{
    /** @var string $weatherUrl */
    protected string $weatherUrl = 'https://api.weatherbit.io/v2.0/current';

    /**
     * @param string $city
     * @return WeatherDataEntity
     * @throws Exception
     */
    public function getWeather(string $city): WeatherDataEntity
    {
        $response = Http::get($this->weatherUrl, [
            'city' => $city,
            'key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            throw new WeatherBitRequestFailedException();
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
            $response['data'][0]['city_name'],
            $response['data'][0]['app_temp'],
            $response['data'][0]['precip'],
            $response['data'][0]['uv']
        );
    }
}
