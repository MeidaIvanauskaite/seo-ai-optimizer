<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\FetchAnalyticsData;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new FetchAnalyticsData())->everyMinute();
        $schedule->call(function () {
            Log::info('Scheduler is running');
        })->everyMinute();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
