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


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Global\Company;

class EnsureGlobalSetupCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $company = Company::with('settings')->first();

        if (
            !$company ||
            !$company->settings ||
            !$company->settings->is_setup_completed
        ) {
            return redirect()->route('setup.company');
        }

        return $next($request);
    }
}
