<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        Commands\ClearCacheCommand::class,
        \App\Console\Commands\DeleteOldRequestLogs::class,
        \App\Console\Commands\CleanupOnlineUsers::class,
    ];
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('notifications:clear')->daily(); // Chạy mỗi ngày
        $schedule->command('vip:revert')->daily();
        $schedule->command('cache:clear-custom')->everyThirtyMinutes(); // Chạy mỗi 30 phút
        $schedule->command('logs:delete-old')->daily(); // Xóa log mỗi ngày
        $schedule->command('online-users:cleanup')->everyFiveMinutes();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
