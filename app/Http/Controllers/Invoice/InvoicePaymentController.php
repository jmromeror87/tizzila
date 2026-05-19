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


namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Invoice\InvoicePaymentService;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoicePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePaymentController extends Controller
{
    protected $paymentService;

    public function __construct(InvoicePaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Listar pagos de una factura
     */
    public function index($invoiceId)
    {
        $invoice  = Invoice::findOrFail($invoiceId);
        $payments = $this->paymentService->getInvoicePayments($invoiceId);
        $balance  = $this->paymentService->getBalance($invoiceId);

        return view('invoice.dashboard_payments', compact('invoice', 'payments', 'balance'));
    }

    public function all()
    {
        $payments = InvoicePayment::with(['invoice.customer', 'user'])
            ->latest()
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /**
     * Formulario para registrar pago
     */
    public function create($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $balance = $this->paymentService->getBalance($invoiceId);

        // ✅ Bloquear acceso si ya está pagada
        if ($balance <= 0) {
            return redirect()
                ->route('invoices.payments.index', $invoiceId)
                ->with('error', 'Esta factura ya está completamente pagada');
        }

        return view('payments.create', compact('invoice', 'balance'));
    }

    public function dashboard($invoiceId)
    {
        $invoice = Invoice::with(['customer', 'payments'])->findOrFail($invoiceId);

        return view('invoices.dashboard_payments', compact('invoice'));
    }

    public function store(Request $request, $invoiceId)
    {
        try {

            $invoice = Invoice::findOrFail($invoiceId);

            // 🚨 VALIDACIÓN — factura ya pagada
            if ($invoice->balance <= 0) {
                return back()->with('error', 'La factura ya está completamente pagada');
            }

            // 🔐 VALIDACIÓN DE CAMPOS
            $request->validate([
                'payment_date'   => 'required|date',
                'amount'         => 'required|numeric|min:0.01|max:' . $invoice->balance,
                'payment_method' => 'required|string',
                'reference'      => 'nullable|string|max:100',
                'notes'          => 'nullable|string|max:500',
            ]);

            // 🔥 REGISTRAR PAGO
            // ✅ El service maneja: contabilidad, tercero, balance y payment_status
            $this->paymentService->registerPayment([
                'company_id'     => Auth::user()->company_id ?? 1,
                'payment_date'   => $request->payment_date,
                'amount'         => $request->amount,
                'payment_method' => $request->payment_method,
                'reference'      => $request->reference,
                'notes'          => $request->notes,
                'created_by'     => Auth::id(),
                'allocations'    => [
                    [
                        'invoice_id' => $invoice->id,
                        'amount'     => $request->amount
                    ]
                ]
            ]);

            // ✅ NO hay recálculo manual — el service ya actualizó balance y payment_status

            return redirect()
                ->route('invoices.payments.index', $invoice->id)
                ->with('success', 'Pago registrado correctamente');

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Ver un pago
     */
    public function show($paymentId)
    {
        $payment = InvoicePayment::with('allocations.invoice')
            ->findOrFail($paymentId);

        return view('payments.show', compact('payment'));
    }

    public function receiptPdf($id)
    {
        $payment = InvoicePayment::with([
            'user',
            'allocations.invoice.customer'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('payments.receipt_pdf', compact('payment'))
                  ->setPaper('letter');

        return $pdf->stream('recibo_' . $payment->id . '.pdf');
    }

    /**
     * Eliminar pago
     */
    public function destroy($paymentId)
    {
        try {
            $this->paymentService->deletePayment($paymentId);
            return back()->with('success', 'Pago eliminado correctamente');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}