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


namespace App\Services\Accounting;

use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\JournalEntryLine;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AccountingService
{
    /**
     * 🚀 CREAR ASIENTO CONTABLE (CORE)
     */
    public static function createEntry(array $data)
    {
        // 🔒 Anti-duplicados (ANTES de transacción)
        if (!empty($data['module_source']) && !empty($data['module_id'])) {

            $exists = JournalEntry::where('module_source', $data['module_source'])
                ->where('module_id', $data['module_id'])
                ->exists();

            if ($exists) {
                return null;
            }
        }

        DB::beginTransaction();

        try {

            // 🔒 VALIDAR PERIODO CONTABLE
            if (\App\Models\Accounting\AccountingPeriod::isClosed($data['company_id'], $data['date'])) {
                throw new \Exception('El periodo contable está cerrado');
            }

            $totalDebit = 0;
            $totalCredit = 0;

            // 🧠 Validar líneas
            foreach ($data['lines'] as $line) {

                $account = ChartOfAccount::findOrFail($line['account_id']);

                if (!$account->is_posting) {
                    throw new \Exception("La cuenta {$account->name} no es posteable");
                }

                $debit = round($line['debit'] ?? 0, 2);
                $credit = round($line['credit'] ?? 0, 2);

                // ⚠️ Validar que no tenga ambos valores
                if ($debit > 0 && $credit > 0) {
                    throw new \Exception("La cuenta {$account->name} no puede tener débito y crédito al mismo tiempo");
                }

                $totalDebit += $debit;
                $totalCredit += $credit;
            }

            // 🔐 Validaciones contables
            if ($totalDebit <= 0 || $totalCredit <= 0) {
                throw new \Exception('El asiento no puede estar vacío');
            }

            if (round($totalDebit, 2) !== round($totalCredit, 2)) {
                throw new \Exception('El asiento no está balanceado');
            }

            // 🧾 Crear cabecera
            $entry = JournalEntry::create([
                'company_id' => $data['company_id'],
                'date' => $data['date'],
                'description' => $data['description'] ?? null,
                'reference' => $data['reference'] ?? null,
                'module_source' => $data['module_source'] ?? null,
                'module_id' => $data['module_id'] ?? null,
                'status' => $data['status'] ?? 'posted',
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
            ]);

            // 📄 Crear líneas + actualizar saldos
            foreach ($data['lines'] as $line) {

                $debit = round($line['debit'] ?? 0, 2);
                $credit = round($line['credit'] ?? 0, 2);

                if ($debit == 0 && $credit == 0) {
                    continue;
                }

                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'description' => $line['description'] ?? null,
                    'debit' => $debit,
                    'credit' => $credit,

                    // 🔥 TERCERO
                    'third_party_id'   => $line['third_party_id'] ?? null,
                    'third_party_type' => $line['third_party_type'] ?? null, // ✅ NUEVO
                ]);

                // 📊 Actualizar saldos correctamente según naturaleza
                self::updateAccountBalance(
                    $line['account_id'],
                    $debit,
                    $credit
                );
            }

            DB::commit();

            return $entry;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 🔁 REVERSIÓN CONTABLE
     */
    public static function reverseEntry(JournalEntry $entry)
    {
        DB::beginTransaction();

        try {

            if ($entry->status === 'locked') {
                throw new \Exception('No se puede reversar un asiento bloqueado');
            }

            // 🔒 Validar periodo
            if (\App\Models\Accounting\AccountingPeriod::isClosed($entry->company_id, now())) {
                throw new \Exception('El periodo contable está cerrado para reversión');
            }

            $lines = [];

            foreach ($entry->lines as $line) {
                $lines[] = [
                    'account_id'       => $line->account_id,
                    'debit'            => $line->credit,
                    'credit'           => $line->debit,
                    'description'      => 'Reverso',

                    // 🔥 TERCERO — se propaga en la reversión
                    'third_party_id'   => $line->third_party_id,
                    'third_party_type' => $line->third_party_type, // ✅ NUEVO
                ];
            }

            $reverse = self::createEntry([
                'company_id'    => $entry->company_id,
                'date'          => now(),
                'description'   => 'Reversión: ' . $entry->description,
                'reference'     => 'REV-' . $entry->id,
                'module_source' => 'reversal',
                'module_id'     => $entry->id,
                'status'        => 'posted',
                'lines'         => $lines
            ]);

            DB::commit();

            return $reverse;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 🔒 BLOQUEAR ASIENTO
     */
    public static function lockEntry(JournalEntry $entry)
    {
        if ($entry->status === 'locked') {
            return;
        }

        $entry->update([
            'status' => 'locked'
        ]);
    }

    /**
     * 📊 ACTUALIZAR SALDOS (CORRECTO SEGÚN NATURALEZA)
     */
    public static function updateAccountBalance($accountId, $debit, $credit)
    {
        $account = ChartOfAccount::find($accountId);

        $balance = DB::table('account_balances')
            ->where('account_id', $accountId)
            ->first();

        if (!$balance) {

            $newDebit = $debit;
            $newCredit = $credit;

        } else {

            $newDebit = $balance->debit_total + $debit;
            $newCredit = $balance->credit_total + $credit;
        }

        // 🧠 Balance según naturaleza
        if ($account->normal_balance === 'debit') {
            $finalBalance = $newDebit - $newCredit;
        } else {
            $finalBalance = $newCredit - $newDebit;
        }

        DB::table('account_balances')
            ->updateOrInsert(
                ['account_id' => $accountId],
                [
                    'debit_total'  => $newDebit,
                    'credit_total' => $newCredit,
                    'balance'      => $finalBalance,
                    'updated_at'   => now()
                ]
            );
    }

    /**
     * 🧠 OBTENER CUENTA DESDE CONFIGURACIÓN
     */
    public static function getAccount($key)
    {
        $account = \App\Models\Accounting\AccountingSetting::where('key_name', $key)
            ->value('account_id');

        if (!$account) {
            throw new \Exception("Falta configurar la cuenta: {$key}");
        }

        return $account;
    }

    // ============================
    // 💸 CONTABILIZAR GASTO (BLINDADO)
    // ============================
    public static function createExpenseEntry($expense)
    {
        if (!$expense) {
            throw new \Exception('Gasto inválido para contabilizar');
        }

        // ============================
        // 🔒 COMPANY ID
        // ============================
        $companyId = $expense->company_id ?? Auth::id()->company_id ?? 1;

        if (!$companyId) {
            throw new \Exception('El gasto no tiene company_id');
        }

        // ============================
        // 💰 CUENTA DE GASTO
        // ============================
        $expenseAccount = null;

        if ($expense->category && $expense->category->puc_code) {
            $expenseAccount = \App\Models\Accounting\ChartOfAccount::where('code', $expense->category->puc_code)
                ->where('company_id', $companyId)
                ->value('id');
        }

        if (!$expenseAccount) {
            $expenseAccount = self::getAccount('expense_default');
        }

        if (!$expenseAccount) {
            throw new \Exception('No se encontró cuenta contable para el gasto');
        }

        // ============================
        // 💰 OTRAS CUENTAS (SEGURAS)
        // ============================
        $cashAccount = self::getAccount('cash');

        if (!$cashAccount) {
            throw new \Exception('Falta configurar cuenta: cash');
        }

        $ivaAccount = null;
        if ($expense->iva > 0) {
            try {
                $ivaAccount = self::getAccount('iva_creditable');
            } catch (\Exception $e) {
                $ivaAccount = null;
            }
        }

        $reteAccount = null;
        if ($expense->retefuente > 0) {
            try {
                $reteAccount = self::getAccount('retefuente');
            } catch (\Exception $e) {
                $reteAccount = null;
            }
        }

        // ============================
        // 🧾 NORMALIZAR VALORES
        // ============================
        $taxBase = (float) ($expense->tax_base ?? 0);
        $iva     = (float) ($expense->iva ?? 0);
        $rete    = (float) ($expense->retefuente ?? 0);
        $total   = (float) ($expense->total ?? 0);

        // ============================
        // 🧾 LÍNEAS CONTABLES
        // ============================
        $lines = [];

        // Gasto base
        if ($taxBase > 0) {
            $lines[] = [
                'account_id'       => (int) $expenseAccount,
                'debit'            => $taxBase,
                'third_party_id'   => $expense->provider_id,
                'third_party_type' => 'provider', // ✅ NUEVO
            ];
        }

        // IVA
        if ($iva > 0 && $ivaAccount) {
            $lines[] = [
                'account_id'       => (int) $ivaAccount,
                'debit'            => $iva,
                'third_party_id'   => $expense->provider_id,
                'third_party_type' => 'provider', // ✅ NUEVO
            ];
        }

        // Retefuente
        if ($rete > 0 && $reteAccount) {
            $lines[] = [
                'account_id'       => (int) $reteAccount,
                'credit'           => $rete,
                'third_party_id'   => $expense->provider_id,
                'third_party_type' => 'provider', // ✅ NUEVO
            ];
        }

        // Pago (caja/banco — sin tercero, es cuenta propia)
        if ($total > 0) {
            $lines[] = [
                'account_id'       => (int) $cashAccount,
                'credit'           => $total,
                'third_party_id'   => $expense->provider_id,
                'third_party_type' => 'provider', // ✅ NUEVO
            ];
        }

        // ============================
        // 🔒 VALIDACIÓN FINAL
        // ============================
        if (empty($lines)) {
            throw new \Exception('No se generaron líneas contables');
        }

        // ============================
        // 🧾 CREAR ASIENTO
        // ============================
        return self::createEntry([
            'company_id'    => (int) $companyId,
            'date'          => $expense->expense_date,
            'description'   => 'Gasto #' . $expense->id,
            'reference'     => $expense->id,
            'module_source' => 'expense',
            'module_id'     => $expense->id,
            'lines'         => $lines
        ]);
    }
}