<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Backend\Content\SettingController;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            if (get_setting('cache_auto_clean_active') == 'enable') {
                (new SettingController)->cacheClearAll();
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
