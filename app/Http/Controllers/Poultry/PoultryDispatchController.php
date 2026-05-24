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
use Illuminate\Http\Request;
use App\Models\Poultry\PoultryDispatch;
use App\Models\Poultry\PoultryDispatchItem;
use App\Models\Poultry\PoultryOrderSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\Pricing\AiChickPriceService;


class PoultryDispatchController extends Controller
{
    /**
     * Listado de despachos
     */


    public function index(Request $request)
    {
        // 1. Iniciamos la consulta con las relaciones necesarias
        // Usamos select en las relaciones para cargar solo lo que la vista necesita (optimización de memoria)
        $query = PoultryDispatch::with([
            'order' => function ($q) {
                $q->select('id', 'provider_id');
            },
            'order.provider' => function ($q) {
                $q->select('id', 'business_name');
            },
            'items' => function ($q) {
                $q->select('id', 'poultry_dispatch_id', 'customer_id');
            },
            'items.customer' => function ($q) {
                $q->select('id', 'name');
            },
        ]);

        // 2. Lógica de Filtro de Búsqueda (Opcional, pero recomendada)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('order.provider', function ($q) use ($search) {
                        $q->where('business_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('items.customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 3. Paginación: Cambiamos ->get() por ->paginate()
        // Esto habilita los métodos hasPages(), links(), etc., en la vista.
        $dispatches = $query->orderByDesc('dispatch_date')
            ->orderByDesc('dispatch_time')
            ->paginate(15) // 15 registros por página
            ->withQueryString(); // Mantiene los filtros al cambiar de página

        return view('poultry.dispatches.index', compact('dispatches'));
    }

    /**
     * Formulario de creación
     */
    public function create(PoultryOrderSchedule $order)
    {
        // Solo pedidos aprobados
        abort_if($order->approval_status !== 'approved', 403);

        // Pre-cargar distribución planeada para poblar el despacho
        $plannedDistributions = $order->distributions()->with('customer')->get();

        return view('poultry.dispatches.create', compact('order', 'plannedDistributions'));
    }

    /**
     * Guardar despacho
     */
    public function store(Request $request, AiChickPriceService $priceService)
    {
        // 1️⃣ Validación
        $validated = $request->validate([
            'poultry_order_schedule_id' => 'required|exists:poultry_order_schedules,id',
            'items' => 'required|array|min:1',
            'items.*.customer_id' => 'required|exists:customers,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_applied' => 'nullable|numeric|min:0',
        ]);

        // 2️⃣ Pedido
        $order = PoultryOrderSchedule::findOrFail(
            $validated['poultry_order_schedule_id']
        );

        // 🔒 BLOQUEO: evitar crear despacho duplicado
        $existingDispatch = PoultryDispatch::where(
            'poultry_order_schedule_id',
            $order->id
        )->first();

        if ($existingDispatch) {
            return redirect()
                ->route('poultry.dispatches.show', $existingDispatch->id)
                ->with('warning', 'Este pedido ya tiene un despacho generado.');
        }

        // 🔒 BLOQUEO DURO DE CRÉDITO: validar cada cliente antes de proceder
        $customerIds = collect($validated['items'])->pluck('customer_id')->unique();
        $blockedCustomers = \App\Models\Customer\Customer::whereIn('id', $customerIds)
            ->with(['invoices' => fn($q) => $q->where('balance', '>', 0)])
            ->get()
            ->filter(function ($customer) {
                $totalBalance  = $customer->invoices->sum('balance');
                $overdueBalance = $customer->invoices->where('payment_status', 'overdue')->sum('balance');
                $overLimit     = $customer->credit_limit > 0 && $totalBalance > $customer->credit_limit;
                return $overdueBalance > 0 || $overLimit;
            });

        if ($blockedCustomers->isNotEmpty()) {
            $names = $blockedCustomers->map(fn($c) => $c->name)->implode(', ');
            return back()->withErrors([
                'credit_block' => "Despacho bloqueado. Los siguientes clientes tienen deuda vencida o cupo excedido: {$names}. Regulariza la cartera antes de despachar."
            ]);
        }

        // 3️⃣ Fecha (lunes o jueves)
        $dispatchDate = Carbon::parse($order->dispatch_date);

        if (!in_array($dispatchDate->dayOfWeek, [Carbon::MONDAY, Carbon::THURSDAY])) {
            return back()->withErrors([
                'dispatch_date' => 'La fecha programada no corresponde a lunes o jueves.'
            ]);
        }

        // 4️⃣ Hora fija
        $dispatchTime = Carbon::createFromTime(6, 0);

        // 5️⃣ Guardado atómico
        $dispatch = DB::transaction(function () use (
            $validated,
            $dispatchDate,
            $dispatchTime,
            $priceService
        ) {

            $dispatch = PoultryDispatch::create([
                'poultry_order_schedule_id' => $validated['poultry_order_schedule_id'],
                'dispatch_date' => $dispatchDate->toDateString(),
                'dispatch_time' => $dispatchTime->format('H:i:s'),
                'status' => 'scheduled',
            ]);

            foreach ($validated['items'] as $item) {

                // 🧠 Precio sugerido por IA (BACKEND MANDA)
                $suggestedPrice = $priceService->suggest([
                    'customer_id'   => $item['customer_id'],
                    'quantity'      => $item['quantity'],
                    'dispatch_date' => $dispatchDate->toDateString(),
                ]);

                $appliedPrice = $item['price_applied'] ?? $suggestedPrice;

                PoultryDispatchItem::create([
                    'poultry_dispatch_id' => $dispatch->id,
                    'customer_id'         => $item['customer_id'],
                    'quantity'            => $item['quantity'],
                    'price_suggested'     => $suggestedPrice,
                    'price_applied'       => $appliedPrice,
                    'price_source'        => isset($item['price_applied']) ? 'manual' : 'ai',
                ]);
            }

            return $dispatch;
        });

        return redirect()
            ->route('poultry.dispatches.show', $dispatch->id)
            ->with('success', 'Asignación y precios guardados correctamente.');
    }


    /**
     * Detalle del despacho
     */
    public function show(PoultryDispatch $dispatch)
    {
        $dispatch->load([
            'order.provider',
            'items.customer'
        ]);

        return view('poultry.dispatches.show', compact('dispatch'));
    }
    public function edit()
    {
        abort(403, 'Los despachos son documentos históricos y no pueden editarse.');
    }

    public function update()
    {
        abort(403, 'Los despachos son documentos históricos y no pueden editarse.');
    }

    public function destroy()
    {
        abort(403, 'Los despachos son documentos históricos y no pueden eliminarse.');
    }
}
