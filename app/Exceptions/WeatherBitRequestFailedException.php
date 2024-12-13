<?php

namespace App\Exceptions;

use Exception;

class WeatherBitRequestFailedException extends Exception
{
    public $message = 'Failed to fetch weather data from Weatherbit.';
}
