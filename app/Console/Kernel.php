<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        // Publish scheduled blog posts every minute
        $schedule->command('posts:publish-scheduled')->everyMinute();

        // LinkedIn scheduled posts
        $schedule->call(function () {
            \App\Models\LinkedInPost::query()
                ->due() // your scopeDue
                ->whereIn('status', ['draft', 'failed'])
                ->limit(100)
                ->get()
                ->each->queueForPublish();
        })->everyMinute();
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
