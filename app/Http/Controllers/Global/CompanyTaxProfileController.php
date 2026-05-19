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
 * Archivo  : CompanyTaxProfileController.php
 * Función  : Gestión de perfiles tributarios de la compañía (DIAN)
 * ───────────────────────────────────────────────────────────────
 */

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Global\Company;
use App\Models\Global\CompanyTaxProfile;
use Illuminate\Http\Request;

class CompanyTaxProfileController extends Controller
{
    /**
     * Mostrar perfiles tributarios
     */
    public function index()
    {
        $company = Company::with('taxProfiles')->first();

        return view('global.company_tax_profiles.index', compact('company'));
    }

    /**
     * Mostrar formulario para crear perfil tributario
     */
    public function create()
    {
        return view('global.company_tax_profiles.create');
    }

    /**
     * Guardar nuevo perfil tributario
     */
    public function store(Request $request)
{
    $company = Company::first();

    // Desactivar perfiles previos
    CompanyTaxProfile::where('company_id', $company->id)
        ->update(['is_active' => 0]);

    CompanyTaxProfile::create([
        'company_id'            => $company->id,
        'tax_regime'            => $request->tax_regime,
        'responsibility_codes'  => $request->responsibility_codes
            ? explode(',', $request->responsibility_codes)
            : null,
        'dian_resolution'       => $request->dian_resolution,
        'resolution_date'       => $request->resolution_date,
        'prefix'                => $request->prefix,
        'from_number'           => $request->from_number,
        'to_number'             => $request->to_number,
        'is_active'             => 1,
    ]);

    return redirect()->route('setup.complete');
}


    /**
     * Mostrar formulario de edición
     */
    public function edit(CompanyTaxProfile $companyTaxProfile)
    {
        return view(
            'global.company_tax_profiles.edit',
            compact('companyTaxProfile')
        );
    }

    /**
     * Actualizar perfil tributario
     */
    public function update(Request $request, CompanyTaxProfile $companyTaxProfile)
    {
        $companyTaxProfile->update(
            $request->only([
                'tax_regime',
                'responsibility_codes',
                'dian_resolution',
                'resolution_date',
                'prefix',
                'from_number',
                'to_number',
                'is_active',
            ])
        );

        return redirect()
            ->route('global.company_tax_profiles.index')
            ->with('success', 'Perfil tributario actualizado correctamente.');
    }
}
