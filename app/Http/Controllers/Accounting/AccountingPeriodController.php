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
use App\Models\Accounting\AccountingPeriod;
use Illuminate\Support\Facades\Auth;

class AccountingPeriodController extends Controller
{
public function close(Request $request)
{
    $request->validate([
        'year' => 'required|integer|min:2000|max:2100',
        'month' => 'required|integer|min:1|max:12',
    ]);

    $companyId = 1;

    $existing = \App\Models\Accounting\AccountingPeriod::where('company_id', $companyId)
        ->where('year', $request->year)
        ->where('month', $request->month)
        ->first();

    if ($existing && $existing->status === 'closed') {
        return back()->withErrors('Este periodo ya está cerrado');
    }

    \App\Models\Accounting\AccountingPeriod::updateOrCreate(
        [
            'company_id' => $companyId,
            'year' => $request->year,
            'month' => $request->month,
        ],
        [
            'status' => 'closed',
            'closed_by' => Auth::id(),
            'closed_at' => now(),
        ]
    );

    // 🔥 AHORA SÍ REDIRIGE
    return redirect()->route('accounting.journal.index')
        ->with('success', 'Periodo cerrado correctamente');
}

public function reopen(Request $request)
{
    $request->validate([
        'year' => 'required|integer',
        'month' => 'required|integer',
        'reason' => 'nullable|string|max:1000',
    ]);

    $companyId = 1;

    $period = \App\Models\Accounting\AccountingPeriod::where('company_id', $companyId)
        ->where('year', $request->year)
        ->where('month', $request->month)
        ->first();

    if (!$period) {
        return back()->withErrors('El periodo no existe');
    }

    if ($period->status === 'open') {
        return back()->withErrors('El periodo ya está abierto');
    }

    $period->update([
        'status' => 'open',
        'reopened_by' => Auth::id(),
        'reopened_at' => now(),
        'reopen_reason' => $request->reason,
    ]);

    return back()->with('success', 'Periodo reabierto correctamente');
}
}