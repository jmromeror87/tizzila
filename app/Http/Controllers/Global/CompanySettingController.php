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


/**
 * ───────────────────────────────────────────────────────────────
 * Proyecto : Tizzila App
 * Módulo   : Configuración Global
 * Archivo  : CompanySettingController.php
 * Función  : Gestión de configuración operativa de la compañía
 * ───────────────────────────────────────────────────────────────
 */

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Global\Company;
use App\Models\Global\CompanySetting;
use Illuminate\Http\Request;

class CompanySettingController extends Controller
{
    /**
     * Mostrar configuración de la compañía
     */
    public function show()
    {
        $company = Company::with('settings')->first();

        return view('global.company_settings.show', compact('company'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit()
    {
        $company = Company::with('settings')->first();

        return view('global.company_settings.edit', compact('company'));
    }

    /**
     * Actualizar configuración
     */
    public function update(Request $request)
    {
        $company = Company::first();

        $settings = $company->settings;

        // Si no existe (caso raro), se crea
        if (!$settings) {
            $settings = CompanySetting::create([
                'company_id' => $company->id,
            ]);
        }

        $settings->update(
            $request->only([
                'default_currency',
                'timezone',
                'language',
                'fiscal_year_start',
            ])
        );

      return redirect()
    ->route('setup.tax')
    ->with('success', 'Configuración guardada correctamente.');

    }
}
