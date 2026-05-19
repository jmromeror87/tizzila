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
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\ChartOfAccount;
use App\Services\Accounting\AccountingService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class JournalEntryController extends Controller
{
 /**
 * 📘 LIBRO DIARIO
 */
public function index(Request $request)
{
    // ✅ OPTIMIZADO: 4 queries fijos sin importar cuántos asientos
    $query = JournalEntry::with([
        'lines.account',
        'lines.customer',  // 1 query para todos los customers de la página
        'lines.provider',  // 1 query para todos los providers de la página
    ])
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc');

    if ($request->from_date) {
        $query->whereDate('date', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('date', '<=', $request->to_date);
    }

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('description', 'like', "%{$request->search}%")
              ->orWhere('reference', 'like', "%{$request->search}%");
        });
    }

    $entries = $query->paginate(20);

    // ✅ KPI corregido — suma desde líneas, no desde journal_entries
    $todayTotal = \App\Models\Accounting\JournalEntryLine::whereHas('journalEntry', function ($q) {
        $q->whereDate('date', today());
    })->sum('debit');

    $periods = \App\Models\Accounting\AccountingPeriod::where('company_id', 1)
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

    return view('accounting.journal.index', compact('entries', 'periods', 'todayTotal'));
}

    /**
     * 📝 FORM CREAR
     */
    public function create()
    {
        $accounts = ChartOfAccount::where('is_posting', 1)
            ->orderBy('code')
            ->get();

        return view('accounting.journal.create', compact('accounts'));
    }

    /**
     * 💾 GUARDAR (USANDO SERVICE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'date'                   => 'required|date',
            'lines'                  => 'required|array|min:2',
            'lines.*.account_id'     => 'required|exists:chart_of_accounts,id',
        ]);

        try {

            AccountingService::createEntry([
                'company_id'    => 1,
                'date'          => $request->date,
                'description'   => $request->description,
                'reference'     => $request->reference,
                'module_type'   => 'manual',
                'module_id'     => null,
                'status'        => 'draft',
                'lines'         => $request->lines
            ]);

            return redirect()->route('journal.index')
                ->with('success', 'Asiento creado correctamente');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

   /**
 * 👁️ DETALLE
 */
public function show($id)
{
    // ✅ OPTIMIZADO: terceros cargados en 2 queries fijos
    $entry = JournalEntry::with([
        'lines.account',
        'lines.customer',
        'lines.provider',
    ])->findOrFail($id);

    return view('accounting.journal.show', compact('entry'));
}
    /**
     * 🔒 BLOQUEAR ASIENTO
     */
    public function lock($id)
    {
        $entry = JournalEntry::findOrFail($id);

        AccountingService::lockEntry($entry);

        return back()->with('success', 'Asiento bloqueado');
    }

    /**
     * 🗑️ ELIMINAR (SOLO BORRADOR)
     */
    public function destroy($id)
    {
        $entry = JournalEntry::findOrFail($id);

        if ($entry->status !== 'draft') {
            return back()->withErrors('Solo se pueden eliminar asientos en borrador');
        }

        DB::beginTransaction();

        try {
            $entry->lines()->delete();
            $entry->delete();

            DB::commit();

            return redirect()->route('journal.index')
                ->with('success', 'Asiento eliminado');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * 📊 LIBRO MAYOR
     */
    public function ledger(Request $request)
    {
        $accounts = ChartOfAccount::where('is_posting', 1)
            ->orderBy('code')
            ->get();

        $selectedAccount = $request->account_id;

        $lines   = collect();
        $balance = 0;

        if ($selectedAccount) {

            $lines = \App\Models\Accounting\JournalEntryLine::with(['account', 'journalEntry'])
                ->where('account_id', $selectedAccount)
                ->orderBy('id')
                ->get();

            foreach ($lines as $line) {
                $balance += $line->debit - $line->credit;
                $line->running_balance = $balance;
            }
        }

        return view('accounting.ledger.index', compact('accounts', 'lines', 'selectedAccount'));
    }

    public function trialBalance(Request $request)
    {
        $from = $request->from_date;
        $to   = $request->to_date;

        $query = \App\Models\Accounting\JournalEntryLine::query()
            ->select(
                'account_id',
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
            ->groupBy('account_id');

        if ($from || $to) {
            $query->whereHas('journalEntry', function ($q) use ($from, $to) {
                if ($from) $q->whereDate('date', '>=', $from);
                if ($to)   $q->whereDate('date', '<=', $to);
            });
        }

        $lines = $query->with('account')->get();

        foreach ($lines as $line) {
            $line->balance = $line->total_debit - $line->total_credit;
        }

        $totalDebit  = $lines->sum('total_debit');
        $totalCredit = $lines->sum('total_credit');

        return view('accounting.trial_balance.index', compact(
            'lines', 'totalDebit', 'totalCredit', 'from', 'to'
        ));
    }

  public function incomeStatement(Request $request)
{
    $from = $request->from_date;
    $to   = $request->to_date;

    $query = \App\Models\Accounting\JournalEntryLine::query()
        ->select(
            'account_id',
            DB::raw('SUM(debit) as total_debit'),
            DB::raw('SUM(credit) as total_credit')
        )
        ->groupBy('account_id');

    if ($from || $to) {
        $query->whereHas('journalEntry', function ($q) use ($from, $to) {
            if ($from) $q->whereDate('date', '>=', $from);
            if ($to)   $q->whereDate('date', '<=', $to);
        });
    }

    $lines = $query->with('account')->get();

    // ============================
    // 📊 ESTRUCTURA PRO
    // ============================
    $revenues       = []; // income
    $costs          = []; // cost — costo de producción clase 6
    $expenses       = []; // expense — gastos operacionales clase 5

    $totalRevenue   = 0;
    $totalCost      = 0;
    $totalExpense   = 0;

    foreach ($lines as $line) {

        $account = $line->account;
        if (!$account) continue;

        switch ($account->type) {

            // 💰 INGRESOS
            case 'income':
                $amount       = $line->total_credit - $line->total_debit;
                $revenues[]   = ['account' => $account, 'amount' => $amount];
                $totalRevenue += $amount;
                break;

            // 🐔 COSTOS DE PRODUCCIÓN (clase 6)
            case 'cost':
                $amount     = $line->total_debit - $line->total_credit;
                $costs[]    = ['account' => $account, 'amount' => $amount];
                $totalCost += $amount;
                break;

            // 💸 GASTOS OPERACIONALES (clase 5)
            case 'expense':
                $amount       = $line->total_debit - $line->total_credit;
                $expenses[]   = ['account' => $account, 'amount' => $amount];
                $totalExpense += $amount;
                break;
        }
    }

    // ============================
    // 🧮 CÁLCULOS PRO
    // ============================
    $grossProfit     = $totalRevenue - $totalCost;       // Utilidad Bruta
    $operatingProfit = $grossProfit - $totalExpense;     // Utilidad Operacional
    $profit          = $operatingProfit;                 // Utilidad Neta (por ahora = operacional)

    return view('accounting.income_statement.index', compact(
        'revenues',
        'costs',
        'expenses',
        'totalRevenue',
        'totalCost',
        'totalExpense',
        'grossProfit',
        'operatingProfit',
        'profit',
        'from',
        'to'
    ));
}

    public function balanceSheet(Request $request)
    {
        $from = $request->from_date;
        $to   = $request->to_date;

        $query = \App\Models\Accounting\JournalEntryLine::query()
            ->select(
                'account_id',
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
            ->groupBy('account_id');

        if ($from || $to) {
            $query->whereHas('journalEntry', function ($q) use ($from, $to) {
                if ($from) $q->whereDate('date', '>=', $from);
                if ($to)   $q->whereDate('date', '<=', $to);
            });
        }

        $lines = $query->with('account')->get();

        $assets      = [];
        $liabilities = [];
        $equity      = [];

        $totalAssets      = 0;
        $totalLiabilities = 0;
        $totalEquity      = 0;

        foreach ($lines as $line) {

            $account = $line->account;
            if (!$account) continue;

            $balance = $line->total_debit - $line->total_credit;

            switch ($account->type) {
                case 'asset':
                    $assets[]      = ['account' => $account, 'balance' => $balance];
                    $totalAssets  += $balance;
                    break;
                case 'liability':
                    $balance         = $line->total_credit - $line->total_debit;
                    $liabilities[]   = ['account' => $account, 'balance' => $balance];
                    $totalLiabilities += $balance;
                    break;
                case 'equity':
                    $balance       = $line->total_credit - $line->total_debit;
                    $equity[]      = ['account' => $account, 'balance' => $balance];
                    $totalEquity  += $balance;
                    break;
            }
        }

        return view('accounting.balance_sheet.index', compact(
            'assets', 'liabilities', 'equity',
            'totalAssets', 'totalLiabilities', 'totalEquity',
            'from', 'to'
        ));
    }

    public function trialBalancePdf(Request $request)
    {
        $data = $this->trialBalance($request)->getData();
        $pdf  = Pdf::loadView('accounting.trial_balance.pdf', (array) $data)->setPaper('letter');
        return $pdf->download('balance_prueba.pdf');
    }

    public function incomeStatementPdf(Request $request)
    {
        $data = $this->incomeStatement($request)->getData();
        $pdf  = Pdf::loadView('accounting.income_statement.pdf', (array) $data)->setPaper('letter');
        return $pdf->download('estado_resultados.pdf');
    }

    public function balanceSheetPdf(Request $request)
    {
        $data = $this->balanceSheet($request)->getData();
        $pdf  = Pdf::loadView('accounting.balance_sheet.pdf', (array) $data)->setPaper('letter');
        return $pdf->download('balance_general.pdf');
    }

    public function dashboard(Request $request)
    {
        $year = $request->year ?? now()->year;

        $data = \App\Models\Accounting\JournalEntryLine::select(
            DB::raw('MONTH(journal_entries.date) as month'),
            DB::raw('SUM(CASE WHEN chart_of_accounts.type = "income" THEN (journal_entry_lines.credit - journal_entry_lines.debit) ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN chart_of_accounts.type IN ("expense","operational","administrative") THEN (journal_entry_lines.debit - journal_entry_lines.credit) ELSE 0 END) as expense')
        )
            ->join('journal_entries', 'journal_entry_lines.journal_entry_id', '=', 'journal_entries.id')
            ->join('chart_of_accounts', 'journal_entry_lines.account_id', '=', 'chart_of_accounts.id')
            ->whereYear('journal_entries.date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months      = [];
        $incomeData  = [];
        $expenseData = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[$i]      = date("M", mktime(0, 0, 0, $i, 1));
            $incomeData[$i]  = 0;
            $expenseData[$i] = 0;
        }

        foreach ($data as $row) {
            $incomeData[$row->month]  = (float) $row->income;
            $expenseData[$row->month] = (float) $row->expense;
        }

        $months      = array_values($months);
        $incomeData  = array_values($incomeData);
        $expenseData = array_values($expenseData);

        $totalIncome  = array_sum($incomeData);
        $totalExpense = array_sum($expenseData);
        $profit       = $totalIncome - $totalExpense;

        if ($request->ajax()) {
            return response()->json([
                'labels'  => $months,
                'income'  => $incomeData,
                'expense' => $expenseData,
                'kpis'    => [
                    'income'  => number_format($totalIncome,  0, ',', '.'),
                    'expense' => number_format($totalExpense, 0, ',', '.'),
                    'profit'  => number_format($profit,       0, ',', '.'),
                ]
            ]);
        }

        return view('accounting.dashboard.index', compact(
            'months', 'incomeData', 'expenseData',
            'totalIncome', 'totalExpense', 'profit', 'year'
        ));
    }

    /**
     * 🔁 REVERSAR ASIENTO
     */
    public function reverse(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();

        try {

            $entry = JournalEntry::with('lines')->lockForUpdate()->findOrFail($id);

            if (\App\Models\Accounting\AccountingPeriod::isClosed($entry->company_id, $entry->date)) {
                return back()->withErrors('No se puede anular en un periodo cerrado');
            }

            if ($entry->module_source === 'reversal') {
                return back()->withErrors('No se puede reversar un asiento de reversión');
            }

            if (!is_null($entry->reversed_entry_id) || !is_null($entry->reversed_at)) {
                return back()->withErrors('Este asiento ya fue anulado');
            }

            $alreadyReversed = JournalEntry::where('module_source', 'reversal')
                ->where('module_id', $entry->id)
                ->exists();

            if ($alreadyReversed) {
                return back()->withErrors('Este asiento ya tiene una reversión registrada');
            }

            $reverseEntry = AccountingService::createEntry([
                'company_id'    => $entry->company_id,
                'date'          => now()->toDateString(),
                'description'   => 'Reversión: ' . $entry->description,
                'reference'     => 'REV-' . $entry->id,
                'module_source' => 'reversal',
                'module_id'     => $entry->id,
                'status'        => 'posted',
                'lines'         => $entry->lines->map(function ($line) {
                    return [
                        'account_id'       => $line->account_id,
                        'debit'            => $line->credit,
                        'credit'           => $line->debit,
                        'description'      => $line->description,
                        'third_party_id'   => $line->third_party_id,   // ✅ propagado
                        'third_party_type' => $line->third_party_type, // ✅ propagado
                    ];
                })->toArray(),
            ]);

            if (!$reverseEntry) {
                throw new \Exception('No se pudo generar la reversión');
            }

            $entry->update([
                'reversed_entry_id' => $reverseEntry->id,
                'reversed_by'       => Auth::id(),
                'reversed_at'       => now(),
                'reversal_reason'   => $request->reason,
            ]);

            DB::commit();

            return back()->with('success', 'Asiento anulado correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }
}