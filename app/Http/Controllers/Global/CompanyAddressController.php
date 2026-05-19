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
 * Archivo  : CompanyAddressController.php
 * Función  : Gestión de direcciones de la compañía
 * ───────────────────────────────────────────────────────────────
 */

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Global\Company;
use App\Models\Global\CompanyAddress;
use App\Models\Global\Country;
use App\Models\Global\State;
use App\Models\Global\City;
use Illuminate\Http\Request;

class CompanyAddressController extends Controller
{
    /**
     * Mostrar direcciones de la compañía
     */
    public function index()
    {
        $company = Company::with([
            'addresses.city.state.country'
        ])->first();

        return view('global.company_addresses.index', compact('company'));
    }

    /**
     * Mostrar formulario para crear dirección
     */
    public function create()
    {
        $countries = Country::where('is_active', 1)->get();

        return view('global.company_addresses.create', compact('countries'));
    }

    /**
     * Guardar nueva dirección
     */
public function store(Request $request)
{
    $company = Company::first();

    // Desactivar dirección principal previa
    CompanyAddress::where('company_id', $company->id)
        ->update(['is_main' => 0]);

    CompanyAddress::create([
        'company_id' => $company->id,
        'city_id'    => $request->city_id,
        'address'    => $request->address,
        'is_main'    => 1,
    ]);

    return redirect()->route('setup.settings');
}


    /**
     * Mostrar formulario de edición
     */
    public function edit(CompanyAddress $companyAddress)
    {
        $countries = Country::where('is_active', 1)->get();

        return view(
            'global.company_addresses.edit',
            compact('companyAddress', 'countries')
        );
    }

    /**
     * Actualizar dirección
     */
    public function update(Request $request, CompanyAddress $companyAddress)
    {
        $companyAddress->update(
            $request->only([
                'city_id',
                'address',
                'neighborhood',
                'postal_code',
                'is_main',
            ])
        );

        return redirect()
            ->route('global.company_addresses.index')
            ->with('success', 'Dirección actualizada correctamente.');
    }
}
