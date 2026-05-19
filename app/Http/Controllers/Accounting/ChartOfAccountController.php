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
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterType = $request->input('type');

        $accounts = ChartOfAccount::with('parent')
            ->when($search, fn($q) => $q->where('code', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%"))
            ->when($filterType, fn($q) => $q->where('type', $filterType))
            ->orderBy('code')
            ->get();

        // Siempre sobre el total, sin filtros
        $countByType = ChartOfAccount::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $allParents = ChartOfAccount::orderBy('code')->get(['id', 'code', 'name', 'level']);

        $types = ['asset' => 'Activo', 'liability' => 'Pasivo', 'equity' => 'Patrimonio',
                  'income' => 'Ingresos', 'expense' => 'Gastos', 'cost' => 'Costos'];

        return view('accounting.chart.index', compact('accounts', 'allParents', 'types', 'search', 'filterType', 'countByType'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'                  => 'required|string|max:20|unique:chart_of_accounts,code',
            'name'                  => 'required|string|max:255',
            'type'                  => 'required|in:asset,liability,equity,income,expense,cost',
            'parent_id'             => 'nullable|exists:chart_of_accounts,id',
            'normal_balance'        => 'required|in:debit,credit',
            'is_posting'            => 'boolean',
            'requires_third_party'  => 'boolean',
            'requires_cost_center'  => 'boolean',
            'is_active'             => 'boolean',
        ]);

        $data['level'] = $this->resolveLevel($data['parent_id'] ?? null);
        $data['is_posting']            = $request->boolean('is_posting');
        $data['requires_third_party']  = $request->boolean('requires_third_party');
        $data['requires_cost_center']  = $request->boolean('requires_cost_center');
        $data['is_active']             = $request->boolean('is_active', true);

        ChartOfAccount::create($data);

        return back()->with('success', 'Cuenta creada correctamente.');
    }

    public function update(Request $request, ChartOfAccount $account)
    {
        $data = $request->validate([
            'code'                  => 'required|string|max:20|unique:chart_of_accounts,code,' . $account->id,
            'name'                  => 'required|string|max:255',
            'type'                  => 'required|in:asset,liability,equity,income,expense,cost',
            'parent_id'             => 'nullable|exists:chart_of_accounts,id',
            'normal_balance'        => 'required|in:debit,credit',
            'is_posting'            => 'boolean',
            'requires_third_party'  => 'boolean',
            'requires_cost_center'  => 'boolean',
            'is_active'             => 'boolean',
        ]);

        $data['level'] = $this->resolveLevel($data['parent_id'] ?? null);
        $data['is_posting']            = $request->boolean('is_posting');
        $data['requires_third_party']  = $request->boolean('requires_third_party');
        $data['requires_cost_center']  = $request->boolean('requires_cost_center');
        $data['is_active']             = $request->boolean('is_active', true);

        $account->update($data);

        return back()->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy(ChartOfAccount $account)
    {
        if ($account->children()->exists()) {
            return back()->withErrors(['delete' => 'No se puede eliminar una cuenta que tiene subcuentas.']);
        }

        if ($account->lines()->exists()) {
            return back()->withErrors(['delete' => 'No se puede eliminar una cuenta con movimientos contables.']);
        }

        $account->delete();

        return back()->with('success', 'Cuenta eliminada.');
    }

    private function resolveLevel(?int $parentId): int
    {
        if (!$parentId) return 1;
        $parent = ChartOfAccount::find($parentId);
        return $parent ? $parent->level + 1 : 1;
    }
}
