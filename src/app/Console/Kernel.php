<?php

namespace App\Console;

use App\Console\Commands\VerifyAdvertPrice;
use App\Console\Commands\DeleteExpiredDeviceSessions;
use App\Repositories\DeviceSessionRepository;
use Exception;
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
        VerifyAdvertPrice::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     * @throws Exception
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            (new DeleteExpiredDeviceSessions())->handle(new DeviceSessionRepository());
        })->hourly();
    }
}
