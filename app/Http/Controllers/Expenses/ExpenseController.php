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


namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expenses\Expense;
use App\Models\Expenses\ExpenseCategory;
use App\Models\Poultry\Provider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Accounting\AccountingService;
use App\Models\Accounting\ChartOfAccount;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['provider', 'category', 'user']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->to);
        }

        $expenses  = $query->latest()->paginate(20);
        $total     = Expense::sum('total');
        $thisMonth = Expense::thisMonth()->sum('total');

        // ✅ FIX: la vista necesita estas listas para los filtros
        $categories = ExpenseCategory::active()->orderBy('name')->get();
        $providers  = Provider::where('status', 'active')->orderBy('business_name')->get();

        return view('expenses.index', compact('expenses', 'total', 'thisMonth', 'categories', 'providers'));
    }

    public function create()
    {
        // ✅ FIX: solo categorías activas
        $categories = ExpenseCategory::active()->orderBy('name')->get();
        $providers  = Provider::where('status', 'active')->orderBy('business_name')->get();

        return view('expenses.create', compact('categories', 'providers'));
    }

    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'provider_id'      => 'nullable|exists:providers,id',
                'category_id'      => 'required|exists:expense_categories,id',
                'document_type'    => 'required|in:invoice,equivalent,support_doc',
                'document_number'  => 'nullable|string|max:100',
                'tax_base'         => 'required|numeric|min:0',
                'iva'              => 'nullable|numeric|min:0',
                'retefuente'       => 'nullable|numeric|min:0',
                'total'            => 'required|numeric|min:0',
                'expense_date'     => 'required|date',
                'payment_method'   => 'required|in:cash,transfer,card,other',
                'description'      => 'nullable|string|max:500',
                'support_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            if ($data['document_type'] === 'invoice' && empty($data['provider_id'])) {
                return back()->withInput()->with('error', 'Debe seleccionar proveedor');
            }

            $data['iva']        = $data['iva'] ?? 0;
            $data['retefuente'] = $data['retefuente'] ?? 0;

            $calculated = ($data['tax_base'] + $data['iva']) - $data['retefuente'];

            if (round($calculated, 2) != round($data['total'], 2)) {
                return back()->withInput()->with('error', 'El total no coincide');
            }

            if ($request->hasFile('support_document')) {
                $data['support_document'] = $request->file('support_document')
                    ->store('expenses_support', 'public');
            }

            DB::transaction(function () use ($data) {

                $companyId = Auth::user()->company_id ?? DB::table('companies')->value('id') ?? 1;

                $expense = Expense::create([
                    'company_id'       => $companyId,
                    'provider_id'      => $data['provider_id'] ?? null,
                    'category_id'      => $data['category_id'],
                    'document_type'    => $data['document_type'],
                    'document_number'  => $data['document_number'] ?? null,
                    'tax_base'         => $data['tax_base'],
                    'iva'              => $data['iva'],
                    'retefuente'       => $data['retefuente'],
                    'total'            => $data['total'],
                    'expense_date'     => $data['expense_date'],
                    'payment_method'   => $data['payment_method'],
                    'description'      => $data['description'] ?? null,
                    'support_document' => $data['support_document'] ?? null,
                    'created_by'       => Auth::id() ?? 1,
                    'status'           => 'approved',
                ]);

                // ============================
                // 💰 CONTABILIDAD
                // ============================
                $expenseAccount = null;

                if ($expense->category && $expense->category->puc_code) {
                    $expenseAccount = ChartOfAccount::where('code', $expense->category->puc_code)
                        ->where('company_id', $companyId)
                        ->value('id');
                }

                if (!$expenseAccount) {
                    $expenseAccount = AccountingService::getAccount('expense_default');
                }

                $cashAccount = AccountingService::getAccount('cash');

                $ivaAccount = $expense->iva > 0
                    ? AccountingService::getAccount('iva_creditable')
                    : null;

                $reteAccount = $expense->retefuente > 0
                    ? AccountingService::getAccount('retefuente')
                    : null;

                $providerId = $expense->provider_id ?? null;

                $lines = [];

                $lines[] = [
                    'account_id'       => (int) $expenseAccount,
                    'debit'            => (float) $expense->tax_base,
                    'third_party_id'   => $providerId,
                    'third_party_type' => 'provider',
                ];

                if ($ivaAccount) {
                    $lines[] = [
                        'account_id'       => (int) $ivaAccount,
                        'debit'            => (float) $expense->iva,
                        'third_party_id'   => $providerId,
                        'third_party_type' => 'provider',
                    ];
                }

                if ($reteAccount) {
                    $lines[] = [
                        'account_id'       => (int) $reteAccount,
                        'credit'           => (float) $expense->retefuente,
                        'third_party_id'   => $providerId,
                        'third_party_type' => 'provider',
                    ];
                }

                $lines[] = [
                    'account_id'       => (int) $cashAccount,
                    'credit'           => (float) $expense->total,
                    'third_party_id'   => $providerId,
                    'third_party_type' => 'provider',
                ];

                $entry = AccountingService::createEntry([
                    'company_id'    => $companyId,
                    'date'          => $expense->expense_date,
                    'description'   => 'Gasto #' . $expense->id,
                    'reference'     => $expense->id,
                    'module_source' => 'expense',
                    'module_id'     => $expense->id,
                    'lines'         => $lines
                ]);

                if ($entry) {
                    $expense->update(['journal_entry_id' => $entry->id]);
                }
            });

            return redirect()->route('expenses.index')
                ->with('success', 'Gasto registrado correctamente');

        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        // ✅ FIX: carga journalEntry para link al asiento en la vista
        $expense = Expense::with(['provider', 'category', 'user', 'journalEntry'])
            ->findOrFail($id);

        return view('expenses.show', compact('expense'));
    }

    public function edit($id)
    {
        $expense    = Expense::findOrFail($id);
        // ✅ FIX: solo activas
        $categories = ExpenseCategory::active()->orderBy('name')->get();
        $providers  = Provider::where('status', 'active')->orderBy('business_name')->get();

        return view('expenses.edit', compact('expense', 'categories', 'providers'));
    }

    public function update(Request $request, $id)
    {
        return back()->with('info', 'Actualizar gasto pendiente de lógica contable');
    }

    public function destroy($id)
    {
        // ✅ FIX: con transacción — si el reverso falla el gasto no se borra
        DB::beginTransaction();

        try {
            $expense = Expense::findOrFail($id);

            if ($expense->journalEntry) {
                AccountingService::reverseEntry($expense->journalEntry);
            }

            $expense->delete();

            DB::commit();

            return back()->with('success', 'Gasto eliminado correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $total     = Expense::sum('total');
        $thisMonth = Expense::thisMonth()->sum('total');

        // ✅ FIX: detectar gastos sin asiento contable
        $unaccounted = Expense::unaccounted()->count();

        $byCategory = Expense::selectRaw('category_id, SUM(total) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('expenses.dashboard', compact('total', 'thisMonth', 'byCategory', 'unaccounted'));
    }
}