<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
*/


namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Generar gastos recurrentes automáticamente
        $schedule->command('expenses:generate-recurring')
            ->daily()
            ->withoutOverlapping()
            ->onOneServer();

        // Recordatorios de cobro por WhatsApp — lunes, miércoles y viernes a las 8am
        $schedule->command('collections:remind')
            ->weeklyOn(1, '08:00') // lunes
            ->weeklyOn(3, '08:00') // miércoles
            ->weeklyOn(5, '08:00') // viernes
            ->withoutOverlapping()
            ->onOneServer();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}