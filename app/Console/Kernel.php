<?php

namespace App\Console;

use App\Console\Commands\LogMessage;
use App\Console\Commands\RemoveOrderCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // Register the commands for Artisan.
    protected $commands = [
        LogMessage::class,
        RemoveOrderCommand::class,

    ];

    // Define the application's command schedule.
    protected function schedule(Schedule $schedule)
    {
        // Schedule the 'log:message' command to run every minute.
        $schedule->command('log:message')->everySecond();
    }

    // Register the commands that can be executed in Artisan.
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
