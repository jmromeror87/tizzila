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


namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Global\Company;

class GlobalConfigurationController extends Controller
{
    public function show()
    {
        $company = Company::with([
            'mainAddress.city.state',
            'settings',
            'activeTaxProfile'
        ])->firstOrFail();

        return view('global.configuration.show', compact('company'));
    }
}
