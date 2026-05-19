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


namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Dispatch\PoultryDispatchRouteStop;
use App\Models\Dispatch\DispatchConfirmation;
use App\Models\Dispatch\DispatchEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CustomerDeliveryController extends Controller
{
    /**
     * ===============================================
     * Mostrar portal público del cliente
     * ===============================================
     */
    public function show(string $token)
    {
        $stop = PoultryDispatchRouteStop::with([
                'route',
                'customer',
                'confirmation'
            ])
            ->where('public_token', $token)
            ->first();

        if (!$stop) {
            abort(404, 'Enlace inválido.');
        }

        if ($stop->token_expires_at &&
            Carbon::now()->greaterThan($stop->token_expires_at)) {

            return view('customers.delivery.expired');
        }

        if (!$stop->client_viewed_at) {
            $stop->update([
                'client_viewed_at' => now()
            ]);
        }

        if ($stop->confirmation) {
            return view('customers.delivery.already-confirmed', compact('stop'));
        }

        return view('customers.delivery.portal', compact('stop'));
    }
    /**
 * ===============================================
 * Confirmación directa desde cliente
 * ===============================================
 */
public function confirm(Request $request, string $token)
{
    $stop = PoultryDispatchRouteStop::with([
        'confirmation',
        'route.dispatch'
    ])
    ->where('public_token', $token)
    ->firstOrFail();

    if ($stop->confirmation) {
        return redirect()->route('customer.delivery.show', $token)
            ->with('error', 'Esta entrega ya fue confirmada.');
    }

    if ($stop->token_expires_at &&
        now()->greaterThan($stop->token_expires_at)) {

        return view('customers.delivery.expired');
    }

    $validated = $request->validate([
        'received_quantity' => 'required|integer|min:0',
        'dead_quantity'     => 'nullable|integer|min:0',
        'notes'             => 'nullable|string|max:2000',
        'signature'         => 'nullable|string',
        'evidences.*'       => 'nullable|image|max:5120',
        'latitude'          => 'nullable|numeric',
        'longitude'         => 'nullable|numeric',
    ]);

    DB::transaction(function () use ($validated, $request, $stop) {

        // 1️⃣ Crear confirmación
        $confirmation = DispatchConfirmation::create([
            'dispatch_route_id'  => $stop->dispatch_route_id,
            'dispatch_stop_id'   => $stop->id,
            'scheduled_quantity' => $stop->chicks_quantity,
            'received_quantity'  => $validated['received_quantity'],
            'dead_quantity'      => $validated['dead_quantity'] ?? 0,
            'notes'              => $validated['notes'] ?? null,
            'latitude'           => $validated['latitude'] ?? null,
            'longitude'          => $validated['longitude'] ?? null,
            'confirmed_at'       => now(),
        ]);

        // 2️⃣ Guardar firma
        if (!empty($validated['signature'])) {

            $signatureData = $validated['signature'];

            if (str_contains($signatureData, ',')) {
                $signatureData = explode(',', $signatureData)[1];
            }

            $decoded = base64_decode($signatureData, strict: true);

            if ($decoded !== false) {
                $signaturePath = 'dispatch-signatures/' . uniqid() . '.png';
                Storage::disk('public')->put($signaturePath, $decoded);
                $confirmation->update(['signature_path' => $signaturePath]);
            }
        }

        // 3️⃣ Guardar evidencias
        if ($request->hasFile('evidences')) {

            foreach ($request->file('evidences') as $file) {

                $path = $file->store('dispatch-evidences', 'public');

                DispatchEvidence::create([
                    'dispatch_confirmation_id' => $confirmation->id,
                    'image_path' => $path,
                    'mime_type'  => $file->getMimeType(),
                    'size_int'   => $file->getSize(),
                ]);
            }
        }

        // 4️⃣ Actualizar parada
        $stop->update([
            'delivery_status'     => 'delivered',
            'actual_arrival_time' => now()->format('H:i:s'),
            'client_confirmed_at' => now(),
        ]);

        // 5️⃣ Auto cerrar ruta si ya no quedan pendientes
        $route = $stop->route;

        if ($route) {
            // Lock route row to prevent concurrent double-close
            $route = \App\Models\Dispatch\PoultryDispatchRoute::lockForUpdate()->find($route->id);

            $pending = $route->stops()
                ->where('delivery_status', 'pending')
                ->count();

            if ($pending === 0 && $route->status === 'in_progress') {
                $route->update([
                    'status'      => 'finished',
                    'finished_at' => now(),
                ]);
            }
        }

        // 6️⃣ Generar factura dentro de la transacción para mantener atomicidad
        $stop->load(['confirmation', 'route.dispatch']);
        app(\App\Services\Invoice\InvoiceService::class)->createFromStop($stop);
    });

    $stop->load([
        'confirmation',
        'route.dispatch'
    ]);

    return redirect()->route('customer.delivery.thanks', $token);
}
     
    /**
     * ===============================================
     * Pantalla de agradecimiento
     * ===============================================
     */
    public function thanks(string $token)
    {
        $stop = PoultryDispatchRouteStop::with([
                'customer',
                'confirmation'
            ])
            ->where('public_token', $token)
            ->firstOrFail();

        if (!$stop->confirmation) {
            abort(404);
        }

        return view('customers.delivery.thanks', compact('stop'));
    }
}
