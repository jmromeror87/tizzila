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


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateDispatchRoutes;

class RunDispatchRoutes extends Command
{
    protected $signature = 'dispatch:routes {--force : Ejecutar sin validar día ni hora}';

    protected $description = 'Ejecuta manualmente la generación de rutas de despacho';

    public function handle(): int
    {
        $force = $this->option('force');

        $this->info('🚀 Ejecutando GenerateDispatchRoutes...');
        $this->info('Force mode: ' . ($force ? 'YES' : 'NO'));

        GenerateDispatchRoutes::dispatch($force);

        $this->info('✅ Job despachado correctamente');

        return Command::SUCCESS;
    }
}
