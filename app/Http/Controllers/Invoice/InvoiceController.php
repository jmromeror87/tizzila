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
use App\Http\Requests\InvoiceStoreRequest;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\TaxCategory;
use App\Models\Customer\Customer;
use App\Services\Invoice\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Poultry\PoultryType;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['customer'])
            ->latest()
            ->paginate(15);

        return view('invoice.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $taxCategories = TaxCategory::where('is_active', 1)->get();
        $types = PoultryType::orderBy('name')->get(); 

        return view('invoice.create', compact('customers', 'taxCategories','types'));
    }

    public function store(InvoiceStoreRequest $request, InvoiceService $service)
    {
        $invoice = $service->create([
            ...$request->validated(),
            'company_id' => Auth::user()->company_id,
        ]);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Factura creada correctamente.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load([
            'items',
            'customer',
            'payments',
            'paymentTerm'
        ]);

        return view('invoice.show', compact('invoice'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'items']);

        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'))
            ->setPaper('letter', 'portrait');

        return $pdf->download("Factura_{$invoice->number}.pdf");
    }
}