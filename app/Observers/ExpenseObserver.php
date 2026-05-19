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


namespace App\Observers;

use App\Models\Expenses\Expense;
use App\Services\Accounting\AccountingService;

class ExpenseObserver
{
    public function created(Expense $expense): void
    {
        $entry = app(AccountingService::class)
            ->createExpenseEntry($expense);

        $expense->journal_entry_id = $entry->id;
        $expense->saveQuietly();
    }
}