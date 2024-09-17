<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ScrapeNews;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ScrapeNews::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Schedule your scraping command
        $schedule->command('scrape:news')->hourly();
    }
}
