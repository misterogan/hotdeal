<?php

namespace App\Console;

use App\Order;
use App\OrderPayment;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\CheckUnpaidOrder::class,
        Commands\CheckUnprocessedOrder::class,
        Commands\CheckOrderReview::class,
        Commands\CompletedOrder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:unpaid')->hourly();
        $schedule->command('command:unprocessed')->hourly();
        $schedule->command('command:review')->everyFiveMinutes();
        $schedule->command('command:complete')->everyFiveMinutes();
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
