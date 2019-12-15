<?php

namespace App\Console;

use App\Console\Commands\Sandbox;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Sandbox::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
//        $schedule->exec('imdb:ratings')
//            ->twiceDaily()
//            ->name('Fetch IMDB Ratings');
    }
}
