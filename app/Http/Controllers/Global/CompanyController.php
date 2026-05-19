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
 * Archivo  : CompanyController.php
 * Función  : Gestión de información general de la compañía
 * ───────────────────────────────────────────────────────────────
 */

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Global\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Mostrar información de la compañía
     */
    public function show()
    {
        $company = Company::with([
            'documentType',
            'mainAddress.city.state',
            'settings',
            'activeTaxProfile',
        ])->first();

        return view('global.company.show', compact('company'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit()
    {
        $company = Company::with([
            'documentType',
            'mainAddress',
            'settings',
        ])->first();

        return view('global.company.edit', compact('company'));
    }

    /**
     * Actualizar información de la compañía
     */
    public function update(Request $request)
    {
        $company = Company::first();

        // Validación se agrega después
        $company->update($request->only([
            'legal_name',
            'trade_name',
            'email',
            'phone',
            'website',
        ]));

        return redirect()
            ->route('global.company.show')
            ->with('success', 'Información de la compañía actualizada correctamente.');
    }
}
