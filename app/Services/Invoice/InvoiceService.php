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
use App\Models\Invoice\InvoiceItem;
use App\Models\Invoice\InvoiceAudit;
use App\Models\Invoice\TaxCategory;
use App\Models\Customer\Customer;
use App\Models\Dispatch\PoultryDispatchRouteStop;
use App\Models\Poultry\PoultryDispatchItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Accounting\AccountingService;

class InvoiceService
{
    protected InvoiceNumberGenerator $numberGenerator;

    public function __construct(InvoiceNumberGenerator $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /*
    |--------------------------------------------------------------------------
    | CREACIÓN MANUAL + CONTABILIDAD
    |--------------------------------------------------------------------------
    */

    public function create(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {

            $companyId  = $data['company_id'] ?? config('app.company_id');
            $numberData = $this->numberGenerator->generate($companyId);

            $customer    = Customer::findOrFail($data['customer_id']);
            $paymentTerm = $customer->paymentTerm;

            $now     = now();
            $dueDate = $now;

            // 📅 Vencimiento
            if ($paymentTerm) {
                switch ($paymentTerm->type) {
                    case 'cash':
                    case 'delivery':
                        $dueDate = $now;
                        break;
                    case 'credit':
                        $dueDate = $now->copy()->addDays($paymentTerm->credit_days ?? 0);
                        break;
                    case 'cut_off':
                        $cutDay = (int) $paymentTerm->cut_day;
                        $today  = $now->copy();
                        $dueDate = ($today->day <= $cutDay)
                            ? $today->day($cutDay)
                            : $today->addMonth()->day($cutDay);
                        break;
                }
            }

            // 🧾 Crear factura
            $invoice = Invoice::create([
                'company_id'             => $companyId,
                'company_tax_profile_id' => $numberData['tax_profile_id'],
                'customer_id'            => $customer->id,
                'payment_term_id'        => $paymentTerm?->id,
                'due_date'               => $dueDate,
                'poultry_dispatch_id'    => $data['poultry_dispatch_id'] ?? null,
                'document_type'          => $data['document_type'] ?? '01',
                'number'                 => $numberData['number'],
                'issue_datetime'         => $now,
                'environment'            => $numberData['environment'],
                'status'                 => 'draft',
                'subtotal'               => 0,
                'tax_total'              => 0,
                'total'                  => 0,
                'balance'                => 0,
                'payment_status'         => 'pending',
            ]);

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($data['items'] as $item) {

                $taxCategory = TaxCategory::findOrFail($item['tax_category_id']);

                $quantity      = (float) $item['quantity'];
                $unitPrice     = (float) $item['unit_price'];
                $lineExtension = round($quantity * $unitPrice, 2);
                $taxAmount     = round(($lineExtension * $taxCategory->percent) / 100, 2);
                $totalLine     = round($lineExtension + $taxAmount, 2);

                InvoiceItem::create([
                    'invoice_id'               => $invoice->id,
                    'poultry_dispatch_item_id' => $item['poultry_dispatch_item_id'] ?? null,
                    'poultry_type_id'          => $item['poultry_type_id'],
                    'description'              => $item['description'] ?? null,
                    'quantity'                 => $quantity,
                    'unit_price'               => $unitPrice,
                    'line_extension'           => $lineExtension,
                    'tax_category_id'          => $taxCategory->id,
                    'tax_amount'               => $taxAmount,
                    'total_line'               => $totalLine,
                ]);

                $subtotal += $lineExtension;
                $taxTotal += $taxAmount;
            }

            $subtotal = round($subtotal, 2);
            $taxTotal = round($taxTotal, 2);
            $total    = round($subtotal + $taxTotal, 2);

            $balance       = $total;
            $paymentStatus = 'pending';

            if ($paymentTerm && in_array($paymentTerm->type, ['cash', 'delivery'])) {
                $balance       = 0;
                $paymentStatus = 'paid';
            }

            $invoice->update([
                'subtotal'       => $subtotal,
                'tax_total'      => $taxTotal,
                'total'          => $total,
                'balance'        => $balance,
                'payment_status' => $paymentStatus,
            ]);

            // ============================
            // 🧾 CONTABILIDAD AUTOMÁTICA
            // ============================

            // 🔥 ID del cliente — es el tercero en facturas
            $customerId = $customer->id;

            $lines = [];

            if ($paymentStatus === 'paid') {

                // 💵 CONTADO
                $cashAccount  = AccountingService::getAccount('cash');
                $salesAccount = AccountingService::getAccount('sales_income');
                $taxAccount   = AccountingService::getAccount('tax_payable');

                // Caja/Banco — recibe el pago del cliente
                $lines[] = [
                    'account_id'       => $cashAccount,
                    'debit'            => $total,
                    'third_party_id'   => $customerId,  // ✅
                    'third_party_type' => 'customer',   // ✅
                ];

                // Ingreso por ventas
                $lines[] = [
                    'account_id'       => $salesAccount,
                    'credit'           => $subtotal,
                    'third_party_id'   => $customerId,  // ✅
                    'third_party_type' => 'customer',   // ✅
                ];

                // IVA por pagar
                if ($taxTotal > 0) {
                    $lines[] = [
                        'account_id'       => $taxAccount,
                        'credit'           => $taxTotal,
                        'third_party_id'   => $customerId,  // ✅
                        'third_party_type' => 'customer',   // ✅
                    ];
                }

            } else {

                // 🧾 CRÉDITO
                $receivableAccount = AccountingService::getAccount('accounts_receivable');
                $salesAccount      = AccountingService::getAccount('sales_income');
                $taxAccount        = AccountingService::getAccount('tax_payable');

                // Cuentas por cobrar
                $lines[] = [
                    'account_id'       => $receivableAccount,
                    'debit'            => $total,
                    'third_party_id'   => $customerId,  // ✅
                    'third_party_type' => 'customer',   // ✅
                ];

                // Ingreso por ventas
                $lines[] = [
                    'account_id'       => $salesAccount,
                    'credit'           => $subtotal,
                    'third_party_id'   => $customerId,  // ✅
                    'third_party_type' => 'customer',   // ✅
                ];

                // IVA por pagar
                if ($taxTotal > 0) {
                    $lines[] = [
                        'account_id'       => $taxAccount,
                        'credit'           => $taxTotal,
                        'third_party_id'   => $customerId,  // ✅
                        'third_party_type' => 'customer',   // ✅
                    ];
                }
            }

            AccountingService::createEntry([
                'company_id'    => $companyId,
                'date'          => $now,
                'description'   => 'Factura #' . $invoice->number,
                'reference'     => $invoice->id,
                'module_source' => 'invoice',
                'module_id'     => $invoice->id,
                'lines'         => $lines
            ]);

            // 🧾 Auditoría
            InvoiceAudit::create([
                'invoice_id' => $invoice->id,
                'company_id' => $companyId,
                'event_type' => 'created',
                'new_status' => 'draft',
                'ip_address' => request()?->ip(),
                'payload'    => $invoice->fresh()->toArray(),
            ]);

            return $invoice->fresh(['items']);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORTACIÓN CSV — respeta número original, fecha original
    |--------------------------------------------------------------------------
    */

    public function createFromImport(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {

            $companyId = $data['company_id'];
            $customer  = Customer::findOrFail($data['customer_id']);

            $issueDate = $data['issue_date']; // YYYY-MM-DD string
            $now       = \Carbon\Carbon::parse($issueDate);
            $dueDate   = $now->copy()->addDays(30); // crédito 30 días por defecto

            // Factura con número exacto del CSV
            $invoice = Invoice::create([
                'company_id'             => $companyId,
                'company_tax_profile_id' => 1,
                'customer_id'            => $customer->id,
                'payment_term_id'        => $customer->payment_term_id,
                'due_date'               => $dueDate,
                'document_type'          => 'FVE',
                'number'                 => $data['number'],
                'issue_datetime'         => $now,
                'environment'            => 'imported',
                'status'                 => 'imported',
                'subtotal'               => 0,
                'tax_total'              => 0,
                'total'                  => 0,
                'balance'                => 0,
                'payment_status'         => 'pending',
            ]);

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($data['items'] as $item) {
                $taxCategory   = TaxCategory::find($item['tax_category_id'] ?? 5) ?? TaxCategory::find(5);
                $quantity      = (float) $item['quantity'];
                $unitPrice     = (float) $item['unit_price'];
                $lineExtension = round($quantity * $unitPrice, 2);
                $taxAmount     = round(($lineExtension * $taxCategory->percent) / 100, 2);
                $totalLine     = round($lineExtension + $taxAmount, 2);

                InvoiceItem::create([
                    'invoice_id'      => $invoice->id,
                    'description'     => $item['description'],
                    'quantity'        => $quantity,
                    'unit_price'      => $unitPrice,
                    'line_extension'  => $lineExtension,
                    'tax_category_id' => $taxCategory->id,
                    'tax_amount'      => $taxAmount,
                    'total_line'      => $totalLine,
                ]);

                $subtotal += $lineExtension;
                $taxTotal += $taxAmount;
            }

            $subtotal = round($subtotal, 2);
            $taxTotal = round($taxTotal, 2);
            $total    = round($subtotal + $taxTotal, 2);

            $invoice->update([
                'subtotal'       => $subtotal,
                'tax_total'      => $taxTotal,
                'total'          => $total,
                'balance'        => $total,
                'payment_status' => 'pending',
            ]);

            // Contabilidad — cuentas por cobrar
            $receivableAccount = AccountingService::getAccount('accounts_receivable');
            $salesAccount      = AccountingService::getAccount('sales_income');

            $lines = [
                [
                    'account_id'       => $receivableAccount,
                    'debit'            => $total,
                    'third_party_id'   => $customer->id,
                    'third_party_type' => 'customer',
                ],
                [
                    'account_id'       => $salesAccount,
                    'credit'           => $subtotal,
                    'third_party_id'   => $customer->id,
                    'third_party_type' => 'customer',
                ],
            ];

            if ($taxTotal > 0) {
                $taxAccount = AccountingService::getAccount('tax_payable');
                $lines[] = [
                    'account_id'       => $taxAccount,
                    'credit'           => $taxTotal,
                    'third_party_id'   => $customer->id,
                    'third_party_type' => 'customer',
                ];
            }

            AccountingService::createEntry([
                'company_id'    => $companyId,
                'date'          => $now,
                'description'   => 'Factura importada #' . $invoice->number,
                'reference'     => $invoice->id,
                'module_source' => 'invoice',
                'module_id'     => $invoice->id,
                'lines'         => $lines,
            ]);

            InvoiceAudit::create([
                'invoice_id' => $invoice->id,
                'company_id' => $companyId,
                'event_type' => 'imported',
                'new_status' => 'imported',
                'payload'    => $invoice->fresh()->toArray(),
            ]);

            return $invoice->fresh(['items']);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FACTURACIÓN AUTOMÁTICA (YA HEREDA CONTABILIDAD)
    |--------------------------------------------------------------------------
    */

    public function createFromStop(PoultryDispatchRouteStop $stop): void
    {
        Log::info('🔔 Iniciando facturación automática', [
            'stop_id' => $stop->id
        ]);

        $confirmation = $stop->confirmation;

        if (!$confirmation || $confirmation->received_quantity <= 0) {
            return;
        }

        $customer = Customer::find($stop->customer_id);
        if (!$customer) return;

        $dispatchItem = PoultryDispatchItem::find($stop->dispatch_item_id)
            ?? PoultryDispatchItem::where('customer_id', $customer->id)
                ->where('poultry_dispatch_id', $stop->poultry_dispatch_id)
                ->latest()
                ->first();

        if (!$dispatchItem) return;

        $unitPrice = $dispatchItem->price_applied ?? $dispatchItem->price_suggested;
        if (!$unitPrice || $unitPrice <= 0) return;

        $tax = ($customer->type_regime_id == 48)
            ? TaxCategory::where('percent', 19)->where('is_vat', 1)->first()
            : TaxCategory::where('percent', 0)->where('is_vat', 1)->first();

        if (!$tax) return;

        // 🚫 Evitar duplicados
        if (Invoice::where('poultry_dispatch_id', $dispatchItem->poultry_dispatch_id)
            ->where('customer_id', $customer->id)
            ->exists()) {
            return;
        }

        // 🚀 CREA FACTURA (contabilidad y terceros se propagan automáticamente)
        $this->create([
            'company_id'          => config('app.company_id'),
            'customer_id'         => $customer->id,
            'poultry_dispatch_id' => $dispatchItem->poultry_dispatch_id,
            'items'               => [[
                'description'              => 'Pollito de engorde',
                'quantity'                 => (int) $confirmation->received_quantity,
                'unit_price'               => $unitPrice,
                'tax_category_id'          => $tax->id,
                'poultry_dispatch_item_id' => $dispatchItem->id,
                'poultry_type_id'          => $dispatchItem->poultry_type_id ?? null,
            ]]
        ]);
    }
}