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
use App\Models\Dispatch\PoultryDispatchRoute;
use App\Models\Dispatch\PoultryDispatchRouteStop;
use App\Jobs\GenerateDispatchRoutes;


class DispatchRouteController extends Controller
{
    /* ============================================================
     | INDEX – Rutas del día
     | Solo lectura
     ============================================================ */
    public function index()
    {
        $routes = PoultryDispatchRoute::today()
            ->with(['driver', 'stops.customer'])
            ->orderBy('departure_time')
            ->get();

        return view('dispatch.routes.index', compact('routes'));
    }
    public function sendWhatsAppLink(PoultryDispatchRouteStop $stop)
    {
        $stop->load('customer');

        if (!$stop->customer || !$stop->customer->phone) {
            return back()->with('error', 'El cliente no tiene número de teléfono.');
        }

        $link = url('/cliente/entrega/' . $stop->public_token);

        try {
            app(\App\Services\WhatsApp\WhatsAppService::class)
                ->sendDeliveryLink($stop->customer->phone, $stop->customer->name ?? 'Cliente', $link);

            return back()->with('success', 'Enlace de entrega enviado por WhatsApp.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar WhatsApp: ' . $e->getMessage());
        }
    }


    public function generate()
    {
       # GenerateDispatchRoutes::dispatch(true); // cola
        GenerateDispatchRoutes::dispatchSync(true); // ejecución inmediata

        return back()->with('success', 'Generación de rutas iniciada.');
    }


    /* ============================================================
     | SHOW – Detalle de una ruta
     ============================================================ */
    public function show(PoultryDispatchRoute $route)
    {
        $route->load([
            'driver',
            'stops.customer'
        ]);

        $mapStops = $route->stops
            ->sortBy('stop_order')
            ->map(fn($stop) => [
                'order'   => $stop->stop_order,
                'address' => $stop->customer_address,
                'eta'     => $stop->estimated_arrival_time,
            ])
            ->values();

        return view('dispatch.routes.show', [
            'route'       => $route,
            'mapStops'    => $mapStops,
            'mapboxToken' => config('services.mapbox.token'),
        ]);
    }


    /* ============================================================
     | START – Iniciar ruta (06:00 AM)
     ============================================================ */
    public function start(PoultryDispatchRoute $route)
    {
        if ($route->status !== 'planned') {
            return back()->with('warning', 'La ruta ya fue iniciada o finalizada.');
        }

        $route->update([
            'status'     => 'in_progress',
            'started_at' => now(),
        ]);

        // 🔗 Sincronizar con la programación (dispatch)
        if ($route->dispatch && $route->dispatch->status === 'scheduled') {
            $route->dispatch->update([
                'status' => 'en_route',
            ]);
        }

        return back()->with('success', 'Ruta iniciada correctamente.');
    }

 
    /* ============================================================
     | notificar al conductor  – Iniciar ruta
     ============================================================ */
    public function sendDriverWhatsApp(PoultryDispatchRoute $route)
    {
        $route->load('driver', 'stops.customer');

        if (!$route->driver || !$route->driver->phone) {
            return back()->with('error', 'El conductor no tiene número de teléfono.');
        }

        $link        = url('/driver/route/' . $route->driver_public_token);
        $driverName  = $route->driver->full_name ?? $route->driver->name ?? 'Conductor';
        $totalBirds  = $route->stops->sum('chicks_quantity');
        $totalStops  = $route->stops->count();

        // Detalle de paradas para el mensaje
        $stopsDetail = $route->stops
            ->sortBy('stop_order')
            ->map(fn($stop, $i) =>
                ($i + 1) . ". *" . ($stop->customer->name ?? 'Cliente') . "*\n" .
                "   📍 " . ($stop->customer_address ?? 'Sin dirección') . "\n" .
                "   🐣 " . number_format($stop->chicks_quantity) . " pollitos"
            )
            ->implode("\n");

        $message =
            "*Tizzila App — Hoja de Ruta*\n\n" .
            "🚚 Hola *{$driverName}*\n\n" .
            "━━━━━━━━━━━━━━━━\n" .
            "Ruta: *#{$route->id}*\n" .
            "Fecha: *" . now()->format('d/m/Y') . "*\n" .
            "Total pollitos: *" . number_format($totalBirds) . "*\n" .
            "Paradas: *{$totalStops}*\n" .
            "━━━━━━━━━━━━━━━━\n\n" .
            "*PARADAS:*\n" . $stopsDetail . "\n\n" .
            "━━━━━━━━━━━━━━━━\n" .
            "▶ Iniciar ruta:\n" . $link;

        try {
            app(\App\Services\WhatsApp\WhatsAppService::class)->send(
                $route->driver->phone,
                $message
            );

            $route->update(['driver_notified_at' => now()]);

            return back()->with('success', 'Ruta enviada al conductor por WhatsApp.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar WhatsApp: ' . $e->getMessage());
        }
    }





    /* ============================================================
     | FINISH – Finalizar ruta
     ============================================================ */
    public function finish(PoultryDispatchRoute $route)
    {
        if ($route->status !== 'in_progress') {
            return back()->with('warning', 'La ruta no está en progreso.');
        }

        $route->update([
            'status'      => 'finished',
            'finished_at' => now(),
        ]);

        // 🔗 Sincronizar con la programación (dispatch)
        if ($route->dispatch && $route->dispatch->status === 'en_route') {
            $route->dispatch->update([
                'status' => 'delivered',
            ]);
        }

        return redirect()
            ->route('dispatch.routes.index')
            ->with('success', 'Ruta finalizada correctamente.');
    }



    /* ============================================================
 | MARCAR ENTREGA EXITOSA
 ============================================================ */
    public function deliverStop(PoultryDispatchRouteStop $stop)
    {
        if ($stop->delivery_status !== 'pending') {
            return back()->with('warning', 'Esta entrega ya fue procesada.');
        }

        $stop->update([
            'delivery_status'     => 'delivered',
            'actual_arrival_time' => now()->format('H:i'),
        ]);

        return back()->with('success', 'Entrega marcada como realizada.');
    }

    /* ============================================================
 | MARCAR ENTREGA FALLIDA
 ============================================================ */
    public function failStop(PoultryDispatchRouteStop $stop)
    {
        if ($stop->delivery_status !== 'pending') {
            return back()->with('warning', 'Esta entrega ya fue procesada.');
        }

        $stop->update([
            'delivery_status'     => 'failed',
            'actual_arrival_time' => now()->format('H:i'),
        ]);

        return back()->with('warning', 'Entrega marcada como fallida.');
    }
}
