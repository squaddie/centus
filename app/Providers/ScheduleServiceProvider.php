<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

/**
 * Class ScheduleServiceProvider
 * @package App\Providers\ScheduleServiceProvider
 */
class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command('app:check-weather-command')->dailyAt('09:00');
    }
}
