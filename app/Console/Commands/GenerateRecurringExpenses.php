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


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Expenses\RecurringExpense;
use App\Models\Expenses\Expense;
use Illuminate\Support\Facades\DB;
use App\Services\Accounting\AccountingService;

class GenerateRecurringExpenses extends Command
{
    protected $signature = 'expenses:generate-recurring';
    protected $description = 'Generar gastos automáticos desde gastos recurrentes';

    public function handle()
    {
        $this->info("🔄 Procesando gastos recurrentes...");

        // ✅ FIX 1: usa scopeDue() — filtra en DB, no carga todo en memoria
        // scopeDue() ya valida: is_active, next_run_date <= hoy, end_date no vencido
        $recurrings = RecurringExpense::due()->with('category')->get();

        if ($recurrings->isEmpty()) {
            $this->info("✅ No hay gastos pendientes.");
            return Command::SUCCESS;
        }

        $generated = 0;
        $skipped   = 0;
        $errors    = 0;

        foreach ($recurrings as $recurring) {

            DB::beginTransaction();

            try {

                $companyId = $recurring->company_id ?? 1;

                // ============================
                // 🔒 EVITAR DUPLICADOS
                // ============================
                $exists = Expense::where('recurring_expense_id', $recurring->id)
                    ->whereDate('expense_date', $recurring->next_run_date)
                    ->exists();

                if ($exists) {
                    $this->warn("⚠️ Ya existe: {$recurring->name}");

                    // ✅ FIX 3: usa advanceToNextRun() — no duplica lógica
                    $recurring->advanceToNextRun()->save();

                    DB::commit();
                    $skipped++;
                    continue;
                }

                // ============================
                // 💸 CREAR GASTO
                // ============================
                $expense = Expense::create([
                    'company_id'           => $companyId,
                    'provider_id'          => $recurring->provider_id ?? null,
                    'category_id'          => $recurring->expense_category_id,
                    'document_type'        => 'support_doc',
                    'document_number'      => null,
                    'tax_base'             => $recurring->amount,
                    'iva'                  => 0,
                    'retefuente'           => 0,
                    'total'                => $recurring->amount,
                    'expense_date'         => $recurring->next_run_date,
                    'payment_method'       => 'transfer',
                    'description'          => '[AUTO] ' . $recurring->name,
                    'recurring_expense_id' => $recurring->id,
                    'created_by'           => 1,
                    'status'               => 'approved',
                ]);

                // ============================
                // 💰 CONTABILIDAD
                // ============================
                $entry = AccountingService::createExpenseEntry($expense);

                // ✅ FIX 2: guardar journal_entry_id en el gasto
                if ($entry) {
                    $expense->update(['journal_entry_id' => $entry->id]);
                }

                // ============================
                // 🔄 AVANZAR AL SIGUIENTE CICLO
                // ============================
                // ✅ FIX 3: usa advanceToNextRun() del modelo
                $recurring->advanceToNextRun()->save();

                DB::commit();

                $this->info("✅ Generado: {$recurring->name} — Próximo: {$recurring->next_run_date}");
                $generated++;

            } catch (\Throwable $e) {

                DB::rollBack();

                $this->error("❌ Error en {$recurring->name}: " . $e->getMessage());
                $errors++;
            }
        }

        // ✅ MEJORA: resumen final
        $this->newLine();
        $this->info("🎯 Proceso finalizado — Generados: {$generated} | Omitidos: {$skipped} | Errores: {$errors}");

        return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}