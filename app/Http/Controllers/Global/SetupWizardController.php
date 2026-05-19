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
use App\Models\Global\State;
use App\Models\Global\CompanySetting;
use Illuminate\Http\Request;

class SetupWizardController extends Controller
{
      public function storeStep1(Request $request)
    {
        $request->validate([
            'legal_name' => 'required|string|max:255',
            'trade_name' => 'nullable|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:20',
        ]);

        $company = Company::first();

        if (!$company) {
            abort(500, 'No existe empresa base para el setup.');
        }

        $company->update([
            'legal_name' => $request->legal_name,
            'trade_name' => $request->trade_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
        ]);

        return redirect()->route('setup.address');
    }
    public function step1()
{
    $company = Company::first(); // empresa global única

    return view('global.wizard.step-1-company', [
        'progress' => 20,
        'company'  => $company,
    ]);
}

  public function step2()
{
    $company = Company::with('mainAddress.city.state')->first();

    $states = State::where('is_active', 1)
        ->orderBy('name')
        ->get();

    return view('global.wizard.step-2-address', [
        'progress' => 40,
        'company'  => $company,
        'states'   => $states,
    ]);
}

 public function step3()
{
    $company = Company::with('settings')->first();

    return view('global.wizard.step-3-settings', [
        'progress' => 60,
        'company'  => $company,
        'settings' => $company?->settings,
    ]);
}

   public function step4()
{
    $company = Company::with('activeTaxProfile')->first();

    return view('global.wizard.step-4-tax', [
        'progress' => 80,
        'company'  => $company,
        'profile'  => $company?->activeTaxProfile,
    ]);
}

public function complete()
{
    $company = Company::with('settings')->firstOrFail();

    // Si no existe settings, lo creamos
    if (!$company->settings) {
        $company->settings()->create([
            'default_currency'   => 'COP',
            'timezone'           => 'America/Bogota',
            'language'           => 'es',
            'fiscal_year_start'  => 1,
            'is_setup_completed' => 1,
        ]);
    } else {
        $company->settings->update([
            'is_setup_completed' => 1,
        ]);
    }

    return view('global.wizard.step-5-complete', [
        'progress' => 100,
        'company'  => $company,
    ]);
}
}
