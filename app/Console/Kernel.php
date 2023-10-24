<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\BuatTagihanSimpananPerbulan::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // jalankan setiap tanggal 1, jam 00 malam
        $schedule->command('buat-tagihan-simpanan-perbulan:cron')->monthly();

        // jalankan setiap hari jam 00
        $schedule->command('angsuranPinjamanOtomatisUpdate:cron')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
