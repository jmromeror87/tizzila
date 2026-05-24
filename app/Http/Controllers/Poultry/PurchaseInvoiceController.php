<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Poultry\PoultryOrderSchedule;
use App\Models\Poultry\PurchaseInvoice;
use App\Models\Poultry\Provider;
use App\Models\Poultry\PurchaseInvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        $invoices = PurchaseInvoice::with(['provider', 'order', 'payments'])
            ->orderByDesc('invoice_date')
            ->get();

        $totalDebt     = $invoices->sum('balance');
        $totalInvoiced = $invoices->sum('total');
        $totalPaid     = $invoices->sum(fn($i) => $i->total - $i->balance);
        $overdueCount  = $invoices->where('payment_status', '!=', 'paid')
            ->filter(fn($i) => $i->due_date && $i->due_date->isPast())
            ->count();

        $dueSoon = $invoices->where('payment_status', '!=', 'paid')
            ->filter(fn($i) => $i->due_date && $i->due_date->isFuture() && $i->due_date->diffInDays(now()) <= 5)
            ->count();

        return view('poultry.purchase-invoices.index', compact(
            'invoices', 'totalDebt', 'totalInvoiced', 'totalPaid', 'overdueCount', 'dueSoon'
        ));
    }

    public function create(PoultryOrderSchedule $order)
    {
        if ($order->purchaseInvoice) {
            return redirect()->route('purchase-invoices.show', $order->purchaseInvoice)
                ->with('info', 'Este pedido ya tiene una factura de compra registrada.');
        }

        $provider = $order->provider ?? Provider::first();

        return view('poultry.purchase-invoices.create', compact('order', 'provider'));
    }

    public function store(Request $request, PoultryOrderSchedule $order)
    {
        $validated = $request->validate([
            'invoice_number'       => 'required|string|max:50',
            'provider_order_number'=> 'nullable|string|max:50',
            'invoice_date'         => 'required|date',
            'due_date'             => 'nullable|date',
            'quantity_invoiced'    => 'required|integer|min:1',
            'unit_price'           => 'required|numeric|min:0',
            'fonav_amount'         => 'nullable|numeric|min:0',
            'vaccine_amount'       => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string|max:500',
            'file'                 => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'items'                => 'nullable|array',
            'items.*.code'         => 'nullable|string|max:20',
            'items.*.description'  => 'required|string|max:255',
            'items.*.quantity'     => 'required|integer|min:0',
            'items.*.unit_price'   => 'required|numeric|min:0',
            'items.*.is_main_product' => 'nullable|boolean',
        ]);

        $fonav   = $validated['fonav_amount'] ?? 0;
        $vaccine = $validated['vaccine_amount'] ?? 0;
        $qty     = $validated['quantity_invoiced'];
        $unitPrice = $validated['unit_price'];

        $subtotal = $qty * $unitPrice;
        $total    = $subtotal + ($fonav * $qty / 1000) + ($vaccine * $qty / 1000);

        // Recalcular total desde los ítems si se enviaron
        if (!empty($validated['items'])) {
            $total = collect($validated['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        }

        $invoice = PurchaseInvoice::create([
            'poultry_order_schedule_id' => $order->id,
            'provider_id'               => $order->provider_id,
            'invoice_number'            => $validated['invoice_number'],
            'provider_order_number'     => $validated['provider_order_number'] ?? null,
            'invoice_date'              => $validated['invoice_date'],
            'due_date'                  => $validated['due_date'] ?? null,
            'quantity_invoiced'         => $qty,
            'unit_price'                => $unitPrice,
            'fonav_amount'              => $fonav,
            'vaccine_amount'            => $vaccine,
            'subtotal'                  => $subtotal,
            'total'                     => $total,
            'balance'                   => $total,
            'payment_status'            => 'pending',
            'notes'                     => $validated['notes'] ?? null,
            'file_path'                 => $request->hasFile('file')
                ? $request->file('file')->store('purchase-invoices', 'public')
                : null,
        ]);

        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'code'            => $item['code'] ?? null,
                    'description'     => $item['description'],
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $item['unit_price'],
                    'total'           => $item['quantity'] * $item['unit_price'],
                    'is_main_product' => !empty($item['is_main_product']),
                ]);
            }
        }

        // Actualizar cantidad real en el pedido
        $order->update(['verified_quantity' => $qty]);

        return redirect()
            ->route('purchase-invoices.show', $invoice)
            ->with('success', "Factura {$invoice->invoice_number} registrada. Ahora puedes crear la distribución.");
    }

    public function pay(Request $request, PurchaseInvoice $purchaseInvoice)
    {
        $validated = $request->validate([
            'payment_date'   => 'required|date',
            'amount'         => 'required|numeric|min:1|max:' . $purchaseInvoice->balance,
            'payment_method' => 'required|in:transferencia,efectivo,cheque',
            'reference'      => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:300',
        ]);

        PurchaseInvoicePayment::create([
            ...$validated,
            'purchase_invoice_id' => $purchaseInvoice->id,
            'registered_by'       => Auth::id(),
        ]);

        $totalPaid  = $purchaseInvoice->payments()->sum('amount') + $validated['amount'];
        $newBalance = max(0, $purchaseInvoice->total - $totalPaid);
        $status     = $newBalance == 0 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'pending');

        $purchaseInvoice->update([
            'balance'        => $newBalance,
            'payment_status' => $status,
        ]);

        return redirect()
            ->route('purchase-invoices.show', $purchaseInvoice)
            ->with('success', 'Pago registrado. Saldo pendiente: $' . number_format($newBalance, 0, ',', '.'));
    }

    public function uploadFile(Request $request, PurchaseInvoice $purchaseInvoice)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $path = $request->file('file')->store('purchase-invoices', 'public');
        $purchaseInvoice->update(['file_path' => $path]);

        return redirect()
            ->route('purchase-invoices.show', $purchaseInvoice)
            ->with('success', 'Archivo de factura cargado correctamente.');
    }

    public function show(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoice->load(['order', 'provider', 'items', 'dispatches.items', 'payments']);

        $totalDistributed = $purchaseInvoice->dispatches
            ->flatMap->items
            ->sum('quantity');

        $pendingQty = $purchaseInvoice->quantity_invoiced - $totalDistributed;

        return view('poultry.purchase-invoices.show', compact(
            'purchaseInvoice',
            'totalDistributed',
            'pendingQty'
        ));
    }
}
