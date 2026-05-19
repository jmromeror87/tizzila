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


namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\InvoicePaymentAllocation;
use Illuminate\Support\Facades\DB;
use App\Services\Accounting\AccountingService;

class InvoicePaymentService
{
    /**
     * Registrar un pago + CONTABILIDAD
     */
    public function registerPayment(array $data)
    {
        return DB::transaction(function () use ($data) {

            $payment = InvoicePayment::create([
                'company_id'     => $data['company_id'],
                'payment_date'   => $data['payment_date'],
                'amount'         => $data['amount'],
                'payment_method' => $data['payment_method'],
                'reference'      => $data['reference'] ?? null,
                'notes'          => $data['notes'] ?? null,
                'created_by'     => $data['created_by']
            ]);

            // ============================
            // 📌 APLICAR A FACTURAS
            // ============================
            $customerId = null;

            if (!empty($data['allocations'])) {

                foreach ($data['allocations'] as $allocation) {

                    $invoice = Invoice::lockForUpdate()->findOrFail($allocation['invoice_id']);

                    // ✅ VALIDAR SALDO — no puede pagar más de lo que debe
                    if ($invoice->balance <= 0) {
                        throw new \Exception("La factura #{$invoice->number} ya está completamente pagada");
                    }

                    $amountToApply = (float) $allocation['amount'];

                    // ✅ PROTECCIÓN — no aplicar más del saldo disponible
                    if ($amountToApply > (float) $invoice->balance) {
                        throw new \Exception(
                            "El monto $" . number_format($amountToApply, 0, ',', '.') .
                            " supera el saldo de la factura #{$invoice->number} " .
                            "($" . number_format($invoice->balance, 0, ',', '.') . ")"
                        );
                    }

                    InvoicePaymentAllocation::create([
                        'invoice_payment_id' => $payment->id,
                        'invoice_id'         => $invoice->id,
                        'amount_applied'     => $amountToApply
                    ]);

                    // ✅ ACTUALIZAR BALANCE Y ESTADO AUTOMÁTICAMENTE
                    $newBalance = round((float) $invoice->balance - $amountToApply, 2);

                    $invoice->balance = max(0, $newBalance);

                    if ($invoice->balance <= 0) {
                        $invoice->payment_status = 'paid';      // ✅ pagada completa
                    } elseif ($amountToApply > 0) {
                        $invoice->payment_status = 'partial';   // ✅ abono parcial
                    }

                    $invoice->save();

                    // Tomamos el customer_id de la primera factura
                    if (!$customerId) {
                        $customerId = $invoice->customer_id;
                    }
                }
            }

            // ============================
            // 💰 CONTABILIDAD DEL PAGO
            // ============================
            $cashAccount       = AccountingService::getAccount('cash');
            $receivableAccount = AccountingService::getAccount('accounts_receivable');

            AccountingService::createEntry([
                'company_id'    => $payment->company_id,
                'date'          => $payment->payment_date ?? now(),
                'description'   => 'Pago recibido #' . $payment->id,
                'reference'     => $payment->id,
                'module_source' => 'payment',
                'module_id'     => $payment->id,
                'lines'         => [
                    [
                        'account_id'       => $cashAccount,
                        'debit'            => $payment->amount,
                        'third_party_id'   => $customerId,
                        'third_party_type' => 'customer',
                    ],
                    [
                        'account_id'       => $receivableAccount,
                        'credit'           => $payment->amount,
                        'third_party_id'   => $customerId,
                        'third_party_type' => 'customer',
                    ]
                ]
            ]);

            return $payment;
        });
    }

    /**
     * Aplicar pago automáticamente a una factura
     */
    public function applyPaymentToInvoice($invoiceId, $paymentId, $amount)
    {
        return DB::transaction(function () use ($invoiceId, $paymentId, $amount) {

            $invoice = Invoice::lockForUpdate()->findOrFail($invoiceId);

            if ($invoice->balance <= 0) {
                throw new \Exception("La factura ya está completamente pagada");
            }

            if ($amount > $invoice->balance) {
                throw new \Exception("El monto supera el saldo de la factura");
            }

            $allocation = InvoicePaymentAllocation::create([
                'invoice_payment_id' => $paymentId,
                'invoice_id'         => $invoiceId,
                'amount_applied'     => $amount
            ]);

            // ✅ Actualizar balance y estado
            $invoice->balance = max(0, round((float) $invoice->balance - $amount, 2));
            $invoice->payment_status = $invoice->balance <= 0 ? 'paid' : 'partial';
            $invoice->save();

            return $allocation;
        });
    }

    /**
     * Obtener pagos de una factura
     */
    public function getInvoicePayments($invoiceId)
    {
        return InvoicePaymentAllocation::with(['payment'])
            ->where('invoice_id', $invoiceId)
            ->latest()
            ->get();
    }

    /**
     * Total pagado de una factura
     */
    public function getTotalPaid($invoiceId)
    {
        return InvoicePaymentAllocation::where('invoice_id', $invoiceId)
            ->sum('amount_applied');
    }

    /**
     * Balance pendiente
     */
    public function getBalance($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $paid    = $this->getTotalPaid($invoiceId);
        return max(0, $invoice->total - $paid);
    }

    /**
     * Eliminar pago + REVERSIÓN CONTABLE + restaurar balance
     */
    public function deletePayment($paymentId)
    {
        return DB::transaction(function () use ($paymentId) {

            $payment = InvoicePayment::with('allocations')->findOrFail($paymentId);

            // ✅ RESTAURAR BALANCE de cada factura afectada
            foreach ($payment->allocations as $allocation) {

                $invoice = Invoice::lockForUpdate()->find($allocation->invoice_id);

                if ($invoice) {
                    $invoice->balance = round(
                        (float) $invoice->balance + (float) $allocation->amount_applied, 2
                    );

                    // Recalcular estado
                    if ($invoice->balance >= $invoice->total) {
                        $invoice->payment_status = 'pending';
                    } elseif ($invoice->balance > 0) {
                        $invoice->payment_status = 'partial';
                    }

                    $invoice->save();
                }
            }

            // Eliminar allocations
            InvoicePaymentAllocation::where('invoice_payment_id', $payment->id)->delete();

            // Reversión contable
            $journalEntry = \App\Models\Accounting\JournalEntry::where('module_source', 'payment')
                ->where('module_id', $payment->id)
                ->first();

            if ($journalEntry) {
                AccountingService::reverseEntry($journalEntry);
            }

            return $payment->delete();
        });
    }
}