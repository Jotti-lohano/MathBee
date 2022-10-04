<?php

namespace App\Console;

use App\Core\Commands\GenerateFilters;
use App\Core\Commands\CreateRepository;
use Illuminate\Console\Scheduling\Schedule;
use App\Core\Commands\CreateRepositoryContract;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        CreateRepository::class,
        CreateRepositoryContract::class,
        GenerateFilters::class,
        
    ];
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
