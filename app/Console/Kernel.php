<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Definisikan command schedule aplikasi.
     */
    protected function schedule(Schedule $schedule)
    {
        // Jalankan setiap jam
        $schedule->command('fingerspot:sync-users')->everyFiveMinutes();

        // atau jika ingin tiap hari jam 2 pagi:
        // $schedule->command('fingerspot:sync-users')->dailyAt('02:00');
    }

    /**
     * Daftarkan commands untuk aplikasi.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
