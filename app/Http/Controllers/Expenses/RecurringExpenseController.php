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
use App\Models\Expenses\RecurringExpense;
use App\Models\Expenses\Expense;
use App\Models\Expenses\ExpenseCategory;
use App\Models\Poultry\Provider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Accounting\AccountingService;

class RecurringExpenseController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id ?? 1;

        $recurrings = RecurringExpense::with(['category', 'provider'])
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(15);

        return view('expenses.recurring.index', compact('recurrings'));
    }

    public function create()
    {
        // ✅ FIX: solo activas con modelo completo — pluck pierde datos para la vista
        $categories = ExpenseCategory::active()->orderBy('name')->get();
        $providers  = Provider::where('status', 'active')->orderBy('business_name')->get();

        return view('expenses.recurring.create', compact('categories', 'providers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:150',
            'provider_id'         => 'nullable|exists:providers,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount'              => 'required|numeric|min:0',
            'frequency'           => 'required|in:daily,weekly,biweekly,monthly',
            'start_date'          => 'required|date',
            'end_date'            => 'nullable|date|after:start_date', // ✅ nuevo
            'description'         => 'nullable|string|max:500',        // ✅ nuevo
        ]);

        $companyId = Auth::user()->company_id ?? 1;

        $data['company_id']    = $companyId;
        $data['next_run_date'] = $data['start_date'];
        $data['last_run_date'] = null;
        $data['is_active']     = true;

        RecurringExpense::create($data);

        return redirect()->route('recurring-expenses.index')
            ->with('success', 'Gasto recurrente programado con éxito');
    }

    public function edit(RecurringExpense $recurringExpense)
    {
        $this->authorizeCompany($recurringExpense);

        // ✅ FIX: solo activas con modelo completo
        $categories = ExpenseCategory::active()->orderBy('name')->get();
        $providers  = Provider::where('status', 'active')->orderBy('business_name')->get();

        return view('expenses.recurring.edit', compact('recurringExpense', 'categories', 'providers'));
    }

    public function update(Request $request, RecurringExpense $recurringExpense)
    {
        $this->authorizeCompany($recurringExpense);

        $data = $request->validate([
            'name'                => 'required|string|max:150',
            'provider_id'         => 'nullable|exists:providers,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount'              => 'required|numeric|min:0',
            'frequency'           => 'required|in:daily,weekly,biweekly,monthly',
            'start_date'          => 'required|date',
            'end_date'            => 'nullable|date|after:start_date', // ✅ nuevo
            'description'         => 'nullable|string|max:500',        // ✅ nuevo
            'is_active'           => 'sometimes',
        ]);

        $data['is_active'] = $request->has('is_active');

        // Si cambia start_date, reiniciar ciclo
        if ($recurringExpense->start_date != $data['start_date']) {
            $data['next_run_date'] = $data['start_date'];
            $data['last_run_date'] = null;
        }

        $recurringExpense->update($data);

        return redirect()->route('recurring-expenses.index')
            ->with('success', 'Programación actualizada correctamente');
    }

    public function destroy(RecurringExpense $recurringExpense)
    {
        $this->authorizeCompany($recurringExpense);

        $recurringExpense->delete();

        return back()->with('success', 'Gasto recurrente eliminado');
    }

    // ========================================
    // 🔥 GENERAR GASTO MANUAL + CONTABILIDAD
    // ========================================
    public function generate(RecurringExpense $recurring)
    {
        $this->authorizeCompany($recurring);

        // ✅ FIX: try/catch fuera de la transacción para mostrar el error al usuario
        try {
            DB::transaction(function () use ($recurring) {

                // Evitar duplicados
                $exists = Expense::where('recurring_expense_id', $recurring->id)
                    ->whereDate('expense_date', $recurring->next_run_date)
                    ->exists();

                if ($exists) {
                    throw new \Exception('Este gasto ya fue generado para esta fecha');
                }

                // Crear gasto
                $expense = Expense::create([
                    'company_id'           => $recurring->company_id,
                    'provider_id'          => $recurring->provider_id ?? null,
                    'category_id'          => $recurring->expense_category_id,
                    'document_type'        => 'support_doc',
                    'tax_base'             => $recurring->amount,
                    'iva'                  => 0,
                    'retefuente'           => 0,
                    'total'                => $recurring->amount,
                    'expense_date'         => $recurring->next_run_date,
                    'payment_method'       => 'transfer',
                    'description'          => $recurring->name,
                    'recurring_expense_id' => $recurring->id,
                    'created_by'           => Auth::id(),
                    'status'               => 'approved',
                ]);

                // 💰 Contabilidad
                $entry = AccountingService::createExpenseEntry($expense);

                // ✅ FIX: guardar journal_entry_id en el gasto
                if ($entry) {
                    $expense->update(['journal_entry_id' => $entry->id]);
                }

                // ✅ FIX: usar advanceToNextRun() — no duplicar lógica del modelo
                $recurring->advanceToNextRun()->save();
            });

            return back()->with('success', 'Gasto generado y contabilizado correctamente');

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ========================================
    // 📅 CALENDARIO
    // ========================================
    public function calendar()
    {
        // ✅ FIX: Auth::id() retorna int, no objeto — usar Auth::user()
        $companyId = Auth::user()->company_id ?? 1;

        $events = RecurringExpense::where('company_id', $companyId)
            ->where('is_active', true)
            ->with('category')
            ->get()
            ->map(function ($r) {

                $icon = 'fa-file-invoice-dollar';
                $name = strtolower($r->name);

                if (str_contains($name, 'arriendo') || str_contains($name, 'alquiler')) {
                    $icon = 'fa-building';
                }

                if (str_contains($name, 'luz') || str_contains($name, 'agua') || str_contains($name, 'servicio')) {
                    $icon = 'fa-bolt';
                }

                if (str_contains($name, 'nomina') || str_contains($name, 'sueldo')) {
                    $icon = 'fa-users';
                }

                if (str_contains($name, 'internet') || str_contains($name, 'software')) {
                    $icon = 'fa-wifi';
                }

                return [
                    'title' => $r->name . ' ($' . number_format($r->amount, 0, ',', '.') . ')',
                    'start' => $r->next_run_date,
                    'extendedProps' => [
                        'icon'     => $icon,
                        'amount'   => $r->amount,
                        'category' => $r->category->name ?? 'General',
                    ]
                ];
            });

        return view('expenses.recurring.calendar', compact('events'));
    }

    // ========================================
    // 🔒 SEGURIDAD
    // ========================================
    private function authorizeCompany($model)
    {
        $companyId = Auth::user()->company_id ?? 1;

        if ($model->company_id != $companyId) {
            abort(403, 'No autorizado');
        }
    }
}