<?php

namespace App\Services\Weather;

use App\Exceptions\WeatherBitRequestFailedException;
use App\ValueObjects\WeatherDataValueObject;
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
     * @return WeatherDataValueObject
     * @throws Exception
     */
    public function getWeather(string $city): WeatherDataValueObject
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
     * @return WeatherDataValueObject
     */
    protected function prepareResponse(array $response): WeatherDataValueObject
    {
        return new WeatherDataValueObject(
            $response['data']['city'],
            $response['data']['temperature'],
            $response['data']['precip'],
            $response['data']['uv']
        );
    }
}
