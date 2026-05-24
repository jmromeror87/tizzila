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


namespace App\Http\Controllers\Cartera;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice\Invoice;
use App\Models\Customer\Customer;
use App\Models\Invoice\InvoicePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\Invoice\InvoicePaymentService;
use App\Services\WhatsApp\WhatsAppService;
use App\Models\WhatsappCollectionLog;
use Illuminate\Support\Carbon;

class CarteraController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 📊 DASHBOARD CARTERA
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $today = now();

        $total   = Invoice::sum('balance');
        $overdue = Invoice::where('balance', '>', 0)
            ->whereDate('due_date', '<', $today)
            ->sum('balance');
        $pending = Invoice::where('payment_status', 'pending')->sum('balance');
        $partial = Invoice::where('payment_status', 'partial')->sum('balance');

        $aging = [
            '0_30' => Invoice::whereBetween('due_date', [
                $today->copy()->subDays(30), $today
            ])->where('balance', '>', 0)->sum('balance'),

            '31_60' => Invoice::whereBetween('due_date', [
                $today->copy()->subDays(60), $today->copy()->subDays(31)
            ])->where('balance', '>', 0)->sum('balance'),

            '61_90' => Invoice::whereBetween('due_date', [
                $today->copy()->subDays(90), $today->copy()->subDays(61)
            ])->where('balance', '>', 0)->sum('balance'),

            '90_plus' => Invoice::where('due_date', '<', $today->copy()->subDays(90))
                ->where('balance', '>', 0)->sum('balance'),
        ];

        $topDebtors = Invoice::selectRaw('customer_id, SUM(balance) as total')
            ->where('balance', '>', 0)
            ->groupBy('customer_id')
            ->with('customer')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $upcoming = Invoice::with('customer')
            ->where('balance', '>', 0)
            ->whereBetween('due_date', [$today, $today->copy()->addDays(7)])
            ->orderBy('due_date')
            ->get();

        return view('cartera.dashboard', compact(
            'total', 'overdue', 'pending', 'partial',
            'aging', 'topDebtors', 'upcoming'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | 📋 LISTADO DE CARTERA
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Invoice::with('customer')->where('balance', '>', 0);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('due_date', [$request->from, $request->to]);
        }

        $invoices  = $query->orderBy('due_date', 'asc')->paginate(15);
        $customers = Customer::orderBy('name')->pluck('name', 'id');

        return view('cartera.index', compact('invoices', 'customers'));
    }

    /*
    |--------------------------------------------------------------------------
    | 👤 ESTADO DE CUENTA CLIENTE
    |--------------------------------------------------------------------------
    */
    public function showCustomer($id)
    {
        $customer = Customer::with([
            'invoices' => function ($q) {
                $q->with('payments')->orderBy('due_date', 'desc');
            }
        ])->findOrFail($id);

        $totalDebt = $customer->invoices->sum('balance');

        return view('cartera.customer', compact('customer', 'totalDebt'));
    }

    /*
    |--------------------------------------------------------------------------
    | 📊 AGING REPORT
    |--------------------------------------------------------------------------
    */
    public function aging()
    {
        $today = now();

        $aging = [
            '0_30' => Invoice::whereBetween('due_date', [
                $today->copy()->subDays(30), $today
            ])->where('balance', '>', 0)->sum('balance'),

            '31_60' => Invoice::whereBetween('due_date', [
                $today->copy()->subDays(60), $today->copy()->subDays(31)
            ])->where('balance', '>', 0)->sum('balance'),

            '61_90' => Invoice::whereBetween('due_date', [
                $today->copy()->subDays(90), $today->copy()->subDays(61)
            ])->where('balance', '>', 0)->sum('balance'),

            '90_plus' => Invoice::where('due_date', '<', $today->copy()->subDays(90))
                ->where('balance', '>', 0)->sum('balance'),
        ];

        return view('cartera.aging', compact('aging'));
    }

    /*
    |--------------------------------------------------------------------------
    | 💸 REGISTRAR PAGO DESDE CARTERA
    |--------------------------------------------------------------------------
    */
    public function pay(Request $request, Invoice $invoice, InvoicePaymentService $service)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'reference'      => 'nullable|string|max:100',
        ]);

        // 🔒 VALIDACIONES
        if ($invoice->balance <= 0) {
            return back()->with('error', 'Esta factura ya está totalmente pagada');
        }

        if ($request->amount > $invoice->balance) {
            return back()->with('error', 'El monto supera el saldo pendiente de $' . number_format($invoice->balance, 0, ',', '.'));
        }

        try {
            // ✅ El service maneja: contabilidad, tercero, balance y payment_status
            // NO hay recálculo manual aquí
            $service->registerPayment([
                'company_id'     => $invoice->company_id,
                'payment_date'   => now(),
                'amount'         => $request->amount,
                'payment_method' => $request->payment_method,
                'reference'      => $request->reference ?? null,
                'notes'          => null,
                'created_by'     => Auth::id(),
                'allocations'    => [
                    [
                        'invoice_id' => $invoice->id,
                        'amount'     => $request->amount
                    ]
                ]
            ]);

            return back()->with('success', 'Pago registrado correctamente');

        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 🧾 RECIBO
    |--------------------------------------------------------------------------
    */
    public function receipt($id)
    {
        $payment = InvoicePayment::with('invoice.customer')->findOrFail($id);

        return view('cartera.receipt', compact('payment'));
    }

    /*
    |--------------------------------------------------------------------------
    | 🧠 HELPER: ESTADO DINÁMICO
    |--------------------------------------------------------------------------
    */
    public function resolveStatus($invoice)
    {
        if ($invoice->balance <= 0) return 'paid';
        if ($invoice->due_date && $invoice->due_date < today()) return 'overdue';
        if ($invoice->payment_status === 'partial') return 'partial';
        return 'pending';
    }

    /*
    |--------------------------------------------------------------------------
    | 📄 GENERAR PDF RECIBO
    |--------------------------------------------------------------------------
    */
    public function generateReceiptPdf($payment)
    {
        $pdf      = Pdf::loadView('payments.receipt_pdf', compact('payment'));
        $fileName = "recibo_{$payment->id}.pdf";

        Storage::put("public/receipts/" . $fileName, $pdf->output());

        return asset("storage/receipts/" . $fileName);
    }

    /*
    |--------------------------------------------------------------------------
    | 📱 ENVIAR RECIBO POR WHATSAPP
    |--------------------------------------------------------------------------
    */
    public function sendReceiptWhatsApp($paymentId)
    {
        $payment = InvoicePayment::with(['allocations.invoice.customer'])
            ->findOrFail($paymentId);

        $firstAllocation = $payment->allocations->first();

        if (!$firstAllocation || !$firstAllocation->invoice || !$firstAllocation->invoice->customer) {
            return back()->with('error', 'No se encontró cliente asociado al pago');
        }

        $customer = $firstAllocation->invoice->customer;
        $telefono = $customer->phone ?? null;

        if (!$telefono) {
            return back()->with('error', 'El cliente no tiene teléfono registrado');
        }

        $cliente = $customer->name ?? 'Cliente';
        $monto   = number_format($payment->amount, 0, ',', '.');
        $recibo  = str_pad($payment->id, 6, '0', STR_PAD_LEFT);

        try {
            app(WhatsAppService::class)->sendReceipt($telefono, $cliente, $recibo, $monto);
            return back()->with('success', '¡Recibo enviado por WhatsApp correctamente!');
        } catch (\Exception $e) {
            Log::error('[WhatsApp] sendReceiptWhatsApp: ' . $e->getMessage());
            return back()->with('error', 'Error al enviar WhatsApp: ' . $e->getMessage());
        }
    }

    public function sendCollectionReminder(Customer $customer)
    {
        if (!$customer->phone) {
            return back()->with('error', 'El cliente no tiene teléfono registrado.');
        }

        $overdueInvoices = $customer->invoices()
            ->where('balance', '>', 0)
            ->where('payment_status', 'overdue')
            ->get();

        if ($overdueInvoices->isEmpty()) {
            return back()->with('error', 'El cliente no tiene facturas vencidas.');
        }

        $totalOverdue = $overdueInvoices->sum('balance');
        $daysOverdue  = (int) Carbon::parse($overdueInvoices->min('due_date'))->diffInDays(now());
        $wa           = app(WhatsAppService::class);
        $message      = $wa->buildOverdueMessage($customer->name, $totalOverdue, $daysOverdue, $overdueInvoices->count());

        try {
            $sent = $wa->sendOverdueReminder($customer->phone, $customer->name, $totalOverdue, $daysOverdue, $overdueInvoices->count());

            WhatsappCollectionLog::create([
                'customer_id' => $customer->id,
                'phone'       => $customer->phone,
                'message'     => $message,
                'type'        => 'manual',
                'sent'        => $sent,
            ]);

            return back()->with('success', "Recordatorio enviado a {$customer->name} por WhatsApp.");
        } catch (\Exception $e) {
            WhatsappCollectionLog::create([
                'customer_id' => $customer->id,
                'phone'       => $customer->phone,
                'message'     => $message,
                'type'        => 'manual',
                'sent'        => false,
                'error'       => $e->getMessage(),
            ]);
            return back()->with('error', 'Error al enviar WhatsApp: ' . $e->getMessage());
        }
    }
}