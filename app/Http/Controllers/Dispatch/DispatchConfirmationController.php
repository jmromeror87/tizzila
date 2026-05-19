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


namespace App\Http\Controllers\Dispatch;

use App\Http\Controllers\Controller;
use App\Models\Dispatch\DispatchConfirmation;
use App\Models\Dispatch\DispatchEvidence;
use App\Models\Dispatch\PoultryDispatchRoute;
use App\Models\Dispatch\PoultryDispatchRouteStop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DispatchConfirmationController extends Controller
{
    /**
     * Mostrar formulario de confirmación
     */
    public function create(PoultryDispatchRouteStop $stop)
    {
        if ($stop->confirmation) {
            return redirect()
                ->route('dispatch.routes.show', $stop->dispatch_route_id)
                ->with('error', 'Esta parada ya fue confirmada.');
        }

        return view('dispatch.confirmations.create', compact('stop'));
    }

    /**
     * Guardar confirmación
     */
    public function store(Request $request, PoultryDispatchRouteStop $stop)
    {
        if ($stop->confirmation) {
            return back()->with('error', 'Esta parada ya fue confirmada.');
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

            /* ===============================================
             | 1️⃣ Crear confirmación
             =============================================== */
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

            /* ===============================================
             | 2️⃣ Guardar firma
             =============================================== */
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

            /* ===============================================
             | 3️⃣ Guardar evidencias
             =============================================== */
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

            /* ===============================================
             | 4️⃣ Actualizar parada + INVALIDAR TOKEN
             =============================================== */
            $stop->update([
                'delivery_status'     => 'delivered',
                'actual_arrival_time' => now()->format('H:i:s'),
                'client_confirmed_at' => now(),
                'token_expires_at'    => now(), // 🔒 aquí muere el token
            ]);

            /* ===============================================
             | 5️⃣ Auto cerrar ruta
             =============================================== */
            $route = PoultryDispatchRoute::lockForUpdate()->find($stop->dispatch_route_id);

            if ($route) {

                $pendingStops = $route->stops()
                    ->where('delivery_status', 'pending')
                    ->count();

                if ($pendingStops === 0 && $route->status === 'in_progress') {

                    $route->update([
                        'status'      => 'finished',
                        'finished_at' => now(),
                    ]);

                    // 🔐 Cerrar también el dispatch para que no se regenere
                    if ($route->dispatch && $route->dispatch->status === 'scheduled') {
                        $route->dispatch->update([
                            'status' => 'delivered'
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('customer.delivery.thanks', $stop->public_token);
    }

    /**
     * Mostrar acta (Admin)
     */
    public function show(DispatchConfirmation $confirmation)
    {
        $confirmation->load([
            'stop.customer',
            'evidences'
        ]);

        return view('dispatch.confirmations.show', compact('confirmation'));
    }
}
