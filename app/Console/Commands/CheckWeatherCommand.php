<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class CheckWeatherCommand
 * @package App\Console\Commands\CheckWeatherCommand
 */
class CheckWeatherCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'app:check-weather-command';
    /** @var string $description */
    protected $description = 'It will check the weather in users\' cities and notify them if the threshold is reached.';

    /**
     * @return void
     */
    public function handle(): void
    {
    }
}
