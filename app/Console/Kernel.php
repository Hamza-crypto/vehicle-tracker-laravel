<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\GuruRefreshToken::class,
        Commands\GuruFetchNewJobs::class
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('guru:token-refresh')
            ->hourly();

        $schedule->command('guru:fetch-new-jobs')
            ->everyFiveMinutes();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
