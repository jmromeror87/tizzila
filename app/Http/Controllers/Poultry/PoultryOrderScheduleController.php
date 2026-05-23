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


namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Poultry\Provider;
use App\Models\Poultry\PoultryOrderSchedule;
use App\Models\Poultry\PoultryType;
use Illuminate\Http\Request;
use App\Services\Poultry\PoultryOrderConfrontationService;
use Carbon\Carbon;
use App\Models\Poultry\PoultryOrderDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\Poultry\PoultryProviderDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;

class PoultryOrderScheduleController extends Controller
{
    private function dispatchDateRule(): \Closure
    {
        return function ($_, $value, $fail) {
            $day = Carbon::parse($value)->dayOfWeek; // 0=Dom, 1=Lun, 4=Jue
            $hasOverride = !empty(request('date_override_reason'));

            if (!in_array($day, [1, 4]) && !$hasOverride) {
                $fail('Los despachos solo se realizan los lunes y jueves. Si hay una eventualidad, indique la justificación.');
            }
        };
    }

    /* ============================================================
     | INDEX
     ============================================================ */
    public function index(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $orders = PoultryOrderSchedule::query()
            ->with(['provider', 'poultryType', 'dispatch'])
            ->withExists('dispatch')
            ->whereMonth('dispatch_date', $month)
            ->whereYear('dispatch_date', $year)
            ->orderBy('dispatch_date')
            ->get();

        return view('poultry.orders.index', compact('orders', 'month', 'year'));
    }

    /* ============================================================
     | CREATE
     ============================================================ */
    public function create()
    {
        $providers = Provider::active()->poultry()->orderBy('business_name')->get();
        $types     = PoultryType::where('is_active', true)->get();

        return view('poultry.orders.create', compact('providers', 'types'));
    }

    /* ============================================================
     | STORE
     ============================================================ */
    public function store(Request $request)
    {


        $validated = $request->validate([
            'provider_id'          => ['required', 'exists:providers,id'],
            'dispatch_date'        => ['required', 'date', $this->dispatchDateRule()],
            'notes'                => ['nullable', 'string'],
            'date_override_reason' => ['nullable', 'string', 'max:255'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.type'         => ['required', 'exists:poultry_types,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['items'] as $item) {

            $type = PoultryType::findOrFail($item['type']);
            $dispatchDate = Carbon::parse($validated['dispatch_date']);

            PoultryOrderSchedule::create([
                'provider_id'          => $validated['provider_id'],
                'poultry_type_id'      => $type->id,
                'dispatch_date'        => $dispatchDate,
                'payment_due_date'     => $dispatchDate->copy()->addDays($type->payment_days),
                'quantity'             => $item['quantity'],
                'notes'                => $validated['notes'] ?? null,
                'date_override_reason' => $validated['date_override_reason'] ?? null,
                'status'               => 'planned',
            ]);
        }

        return redirect()
            ->route('poultry.orders.calendar')
            ->with('success', 'Pedido programado correctamente.');
    }

    /* ============================================================
     | EDIT
     ============================================================ */
    public function edit(PoultryOrderSchedule $order)
    {
        $providers = Provider::active()->poultry()->orderBy('business_name')->get();
        $types     = PoultryType::where('is_active', true)->get();

        return view('poultry.orders.edit', compact('order', 'providers', 'types'));
    }

    /* ============================================================
     | UPDATE
     ============================================================ */
    public function update(Request $request, PoultryOrderSchedule $order)
    {
        $validated = $request->validate([
            'provider_id'          => ['required', 'exists:providers,id'],
            'poultry_type_id'      => ['required', 'exists:poultry_types,id'],
            'quantity'             => ['required', 'integer', 'min:1'],
            'dispatch_date'        => ['required', 'date', $this->dispatchDateRule()],
            'notes'                => ['nullable', 'string'],
            'date_override_reason' => ['nullable', 'string', 'max:255'],
            'status'               => ['nullable', 'in:planned,dispatched,paid,cancelled'],
        ]);

        $type = PoultryType::findOrFail($validated['poultry_type_id']);

        $validated['payment_due_date'] =
            Carbon::parse($validated['dispatch_date'])
                ->addDays($type->payment_days);

        $order->update($validated);

        return redirect()
            ->route('poultry.orders.index')
            ->with('success', 'Pedido actualizado correctamente.');
    }

    /* ============================================================
     | CALENDAR EVENTS
     ============================================================ */
    public function calendarEvents(\Illuminate\Http\Request $request)
    {
        $start = $request->query('start') ? Carbon::parse($request->query('start')) : now()->startOfMonth();
        $end   = $request->query('end')   ? Carbon::parse($request->query('end'))   : now()->endOfMonth();

        return PoultryOrderSchedule::with(['provider', 'poultryType'])
            ->whereBetween('dispatch_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->map(function ($order) {

                $statusColor = match (true) {
                    $order->status === 'paid' => '#22c55e',
                    $order->status === 'dispatched' => '#3b82f6',
                    $order->status === 'cancelled' => '#ef4444',
                    $order->payment_due_date?->isPast() => '#ef4444',
                    $order->payment_due_date?->diffInDays(now()) <= 5 => '#facc15',
                    default => '#fbbf24',
                };

                return [
                    'id'    => $order->id,
                    'start' => $order->dispatch_date->toDateString(),
                    'url'   => route('poultry.orders.edit', $order),

                    'extendedProps' => [
                        'provider' => $order->provider?->business_name ?? 'No asignado',
                        'quantity' => $order->quantity,
                        'poultry'  => $order->poultryType?->name ?? ucfirst($order->poultry_type ?? ''),
                        'icon'     => $order->poultryType?->icon,
                        'status'   => strtoupper($order->status),
                        'type'     => match($order->poultry_type) {
                            'bb'      => 'pollito-bb',
                            'lsl'     => 'lohmann-lsl',
                            'lohmann' => 'lohmann-brown',
                            default   => $order->poultryType?->name
                                ? str($order->poultryType->name)->slug()->toString()
                                : 'otros',
                        },
                    ],

                    'backgroundColor' => $statusColor,
                    'borderColor'     => $statusColor,
                ];
            });
    }
    public function calendar()
    {
        return view('poultry.orders.calendar');
    }

    /* ============================================================
     | REPROGRAM
     ============================================================ */
    public function reprogram(Request $request)
    {
        $data = $request->validate([
            'id'            => ['required', 'exists:poultry_order_schedules,id'],
            'dispatch_date' => ['required', 'date'],
        ]);

        $order = PoultryOrderSchedule::findOrFail($data['id']);

        if ($order->status === 'paid') {
            return response()->json(['message' => 'Pedidos pagados no pueden reprogramarse'], 422);
        }

        $days = $order->poultryType->payment_days;

        $order->update([
            'dispatch_date'    => $data['dispatch_date'],
            'payment_due_date' => Carbon::parse($data['dispatch_date'])->addDays($days),
            'status'           => 'planned',
        ]);

        return response()->json(['success' => true]);
    }
     public function storeDocument(Request $request, PoultryOrderSchedule $order)
    {
        $validated = $request->validate([
            'document'       => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'document_type'  => ['required', 'string', 'max:50'],
        ]);

        $file = $validated['document'];

        // Hash para evitar duplicados
        $hash = hash_file('sha256', $file->getRealPath());

        if ($order->documents()->where('file_hash', $hash)->exists()) {
            return back()->withErrors([
                'document' => 'Este documento ya fue cargado para este pedido.'
            ]);
        }

        $path = $file->store(
            'poultry/orders/' . $order->id,
            'public'
        );

        PoultryOrderDocument::create([
            'poultry_order_schedule_id' => $order->id,
            'file_path'    => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'    => $file->getMimeType(),
            'document_type' => $validated['document_type'],
            'file_hash'    => $hash,
        ]);

        return back()->with('success', 'Documento cargado correctamente.');
    }
     public function confirm(Request $request, PoultryOrderSchedule $order)
    {
        // 🔒 Seguridad: si ya está aprobado, no hacer nada
        if ($order->approval_status === 'approved') {
            return redirect()
                ->route('poultry.orders.confront', $order)
                ->with('warning', 'Este pedido ya fue aprobado anteriormente.');
        }

        // ✅ Aprobación FINAL y MANUAL (único lugar donde ocurre)
        $order->update([
            'approval_status' => 'approved',
            'approved_at'     => now(),
            'approved_by'     => Auth::id(),
            // NO tocar status operativo aquí
        ]);

        // 🔄 Refrescamos el modelo para asegurar datos actualizados
        $order->refresh();

        return redirect()
            ->route('poultry.orders.confront', $order)
            ->with('success', 'Pedido validado y cerrado correctamente.');
    }

    public function incident(PoultryOrderSchedule $order)
    {
        // 1. Verificación de seguridad
        if (!in_array($order->approval_status, ['pending', 'under_review'])) {
            return redirect()
                ->route('poultry.orders.confront', $order)
                ->with('warning', 'No se puede marcar incidencia en un pedido ya resuelto.');
        }

        // 2. Actualización de estado a 'rejected' (o el slug que uses para incidentes)
        // Al ser 'rejected', los botones desaparecerán de la vista confront.blade.php
        $order->update([
            'approval_status' => 'rejected',
            'approved_by'      => Auth::id(),
            'notes'            => trim(($order->notes ?? '') . "\nMarcado con incidencia.")
        ]);

        return redirect()
            ->route('poultry.orders.confront', $order)
            ->with('warning', 'Se ha registrado el incidente y se ha cerrado el proceso de este pedido.');
    }


    public function previewDocument(PoultryProviderDocument $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Documento no encontrado');
        }

        return view('poultry.orders.documents.preview', [
            'document' => $document,
            'url'      => Storage::url($document->file_path),
        ]);
    }

public function confront(
    PoultryOrderSchedule $order,
    PoultryOrderConfrontationService $service
) {
    $result = $service->confront($order);

    $order->load(['provider.documents', 'poultryType']);

    /*
    |--------------------------------------------------------------------------
    | 1️⃣ Buscar los batches que realmente pertenecen a ESTA orden
    |--------------------------------------------------------------------------
    | Coinciden por:
    | - Fecha de entrega = dispatch_date
    | - Tipo de pollo
    */
    $relevantBatches = \App\Models\Poultry\PoultryProviderDocumentBatch::with('editor')
        ->whereDate('delivery_date', $order->dispatch_date)
        ->where('poultry_type_id', $order->poultry_type_id)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ Obtener el documento solo si existe un batch relacionado
    |--------------------------------------------------------------------------
    */
    $document = null;

    if ($relevantBatches->isNotEmpty()) {
        $document = \App\Models\Poultry\PoultryProviderDocument::find(
            $relevantBatches->first()->poultry_provider_document_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3️⃣ Usar payload efectivo (si ya implementaste verified_payload)
    |--------------------------------------------------------------------------
    */
    $payload = $document?->effective_payload ?? $document?->ia_payload ?? [];

    /*
    |--------------------------------------------------------------------------
    | 4️⃣ Construir extras para la vista
    |--------------------------------------------------------------------------
    */
    $extras = [
        'vaccines' => [
            'marek'   => $payload['vaccines']['marek'] ?? false,
            'gumboro' => $payload['vaccines']['gumboro'] ?? false,
            'others'  => $payload['vaccines']['others'] ?? 'No especificado',
        ],
        'pricing' => [
            'unit_cost'    => $payload['pricing']['unit_cost'] ?? 0,
            'fonav_cost'   => $payload['pricing']['fonav_cost'] ?? 0,
            'vaccine_cost' => $payload['pricing']['vaccine_cost'] ?? 0,
        ],
        'packaging_type' => $payload['packaging_type'] ?? 'N/A',
        'product_flags'  => $payload['product_flags'] ?? [],
        'notes'          => $payload['notes'] ?? 'Sin notas adicionales',
        'order_number'   => $payload['provider_order_number'] ?? 'N/A',
    ];

    /*
    |--------------------------------------------------------------------------
    | 5️⃣ Retornar vista
    |--------------------------------------------------------------------------
    */
    return view('poultry.orders.confront', [
        'order'            => $order,
        'document'         => $document,
        'batches'          => $relevantBatches, // 👈 SOLO LOS DE ESTA ORDEN
        'result'           => $result,
        'issues'           => $result['issues'] ?? [],
        'summary'          => $result['summary'],
        'extras'           => $extras,
    ]);
}
protected function persistApprovalStatus(PoultryOrderSchedule $order, array $result): void
    {
        // 🔒 Regla de oro: si el usuario ya aprobó, NO TOCAR
        if ($order->approval_status === 'approved') {
            return;
        }

        $summary = $result['summary'] ?? null;
        if (!$summary) return;

        $programmed = (int) $summary['programmed'];
        $approved   = (int) $summary['approved'];

        if ($approved === 0) {
            $status = 'pending';
        } elseif ($approved === $programmed) {
            $status = 'approved';
        } else {
            $status = 'under_review';
        }

        if ($order->approval_status === $status) return;

        $order->approval_status = $status;

        if ($status === 'approved') {
            $order->approved_at = now();
            $order->approved_by = Auth::id();
        }

        $order->notes = sprintf(
            'Confrontación automática: %d/%d (%s%%)',
            $approved,
            $programmed,
            $summary['percentage']
        );

        $order->save();
    }

public function storeApproval(Request $request, PoultryOrderSchedule $order)
{
    $validated = $request->validate([
        'provider_order_number' => ['required', 'string'],
        'document_date'         => ['required', 'date'],
        'approved_quantity'     => ['required', 'integer', 'min:1'],
        'unit_cost'             => ['required', 'numeric', 'min:0'],
        'fonav_cost'            => ['required', 'numeric', 'min:0'],
        'vaccine_cost'          => ['required', 'numeric', 'min:0'],

        // Lotes
        'batches'                       => ['required', 'array', 'min:1'],
        'batches.*.delivery_date'       => ['required', 'date'],
        'batches.*.approved_quantity'   => ['required', 'integer', 'min:1'],
    ]);

    /* ============================================================
       1️⃣ Crear Aprobación
    ============================================================ */

    $approval = $order->approval()->create([
        'provider_order_number' => $validated['provider_order_number'],
        'document_date'         => $validated['document_date'],

        // 🔥 NUEVO SISTEMA
        'poultry_type_id'       => $order->poultry_type_id,

        'approved_quantity'     => $validated['approved_quantity'],
        'unit_cost'             => $validated['unit_cost'],
        'fonav_cost'            => $validated['fonav_cost'],
        'vaccine_cost'          => $validated['vaccine_cost'],
        'total_unit_cost'       => (
            $validated['unit_cost']
            + $validated['fonav_cost']
            + $validated['vaccine_cost']
        ),

        'approval_status'       => 'approved',
        'approved_at'           => now(),
        'approved_by'           => Auth::id(),
    ]);

    /* ============================================================
       2️⃣ Guardar Lotes
    ============================================================ */

    foreach ($validated['batches'] as $batch) {

        $approval->batches()->create([
            'delivery_date'     => $batch['delivery_date'],
            'approved_quantity' => $batch['approved_quantity'],
        ]);
    }

    /* ============================================================
       3️⃣ Actualizar Pedido
    ============================================================ */

    $order->update([
        'status'          => 'dispatched',
        'approval_status' => 'approved',
        'approved_at'     => now(),
        'approved_by'     => Auth::id(),
    ]);

    return redirect()
        ->back()
        ->with('success', 'Aprobación y lotes registrados correctamente.');
}

    /* ============================================================
     | DESTROY
     ============================================================ */
    /* ============================================================
     | DSS EXCEDENTES — Sugerencias para sobrantes
     ============================================================ */
    public function dssSurplus(Request $request, PoultryOrderSchedule $order)
    {
        $surplus = max(0, (int) $request->query('surplus', 0));

        if ($surplus === 0) {
            return response()->json(['suggestions' => [], 'surplus' => 0]);
        }

        // Clientes variables (no fijos) activos, sin deuda vencida ni cupo excedido
        $suggestions = Customer::where('is_active', true)
            ->where('is_fixed_client', false)
            ->withSum(['invoices as total_balance' => fn($q) => $q->where('balance', '>', 0)], 'balance')
            ->withSum(['invoices as overdue_balance' => fn($q) => $q->where('payment_status', 'overdue')], 'balance')
            ->withSum(['invoices as total_invoiced'], 'total')
            ->withSum(['invoices as total_paid' => fn($q) => $q->where('balance', 0)], 'total')
            ->withCount(['invoices as total_orders'])
            ->get()
            ->filter(function ($c) {
                // Excluir bloqueados
                $overdue   = $c->overdue_balance > 0;
                $overLimit = $c->credit_limit > 0 && $c->total_balance > $c->credit_limit;
                return !$overdue && !$overLimit;
            })
            ->map(function ($c) use ($surplus) {
                $effectiveness = $c->total_invoiced > 0
                    ? round((($c->total_invoiced - ($c->total_balance ?? 0)) / $c->total_invoiced) * 100, 1)
                    : 0;

                // Score: 60% efectividad pago + 40% volumen histórico (normalizado)
                $score = $effectiveness;

                return [
                    'id'              => $c->id,
                    'name'            => $c->name,
                    'phone'           => $c->phone,
                    'effectiveness'   => $effectiveness,
                    'total_orders'    => $c->total_orders,
                    'total_balance'   => $c->total_balance ?? 0,
                    'credit_limit'    => $c->credit_limit,
                    'suggested_qty'   => min($surplus, max(100, (int) round($surplus * ($effectiveness / 100)))),
                    'score'           => $score,
                    'status_color'    => $effectiveness >= 90 ? 'emerald' : ($effectiveness >= 60 ? 'yellow' : 'orange'),
                ];
            })
            ->sortByDesc('score')
            ->take(6)
            ->values();

        return response()->json([
            'surplus'     => $surplus,
            'suggestions' => $suggestions,
        ]);
    }

    public function destroy(PoultryOrderSchedule $order)
    {
        $order->delete();

        return redirect()
            ->route('poultry.orders.index')
            ->with('success', 'Pedido eliminado.');
    }
}