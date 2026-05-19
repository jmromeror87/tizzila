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


namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\AccountingSetting;
use App\Models\Accounting\ChartOfAccount;

class AccountingSettingController extends Controller
{
    public function index()
    {
        $settings = AccountingSetting::with('account')->get();
        $accounts = ChartOfAccount::where('is_posting', 1)->get();

        return view('accounting.settings.index', compact('settings', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key_name' => 'required|string',
            'account_id' => 'required|exists:chart_of_accounts,id'
        ]);

        AccountingSetting::updateOrCreate(
            ['key_name' => $request->key_name],
            ['account_id' => $request->account_id]
        );

        return back()->with('success', 'Configuración guardada');
    }

    public function destroy(AccountingSetting $setting)
    {
        $setting->delete();

        return back()->with('success', 'Eliminado correctamente');
    }
}