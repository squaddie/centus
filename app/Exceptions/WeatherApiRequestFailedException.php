<?php

namespace App\Exceptions;

use Exception;

class WeatherApiRequestFailedException extends Exception
{
    public $message = 'Failed to fetch weather data from WeatherAPI.';
}
