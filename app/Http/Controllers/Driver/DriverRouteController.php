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


namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Dispatch\PoultryDispatchRoute;
use App\Models\Dispatch\PoultryDispatchRouteStop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsApp\WhatsAppService;

class DriverRouteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 📍 Mostrar Panel del Conductor
    |--------------------------------------------------------------------------
    */
    public function show(string $token)
{
    $route = $this->validateToken($token);

    /*
    |--------------------------------------------------------------------------
    | 🔴 SI LA RUTA ESTÁ FINALIZADA → MOSTRAR REPORTE
    |--------------------------------------------------------------------------
    */
    if ($route->status === 'finished') {

        // Cargar conductor
        $route->load('driver');

        // Traer confirmaciones reales
        $confirmations = \App\Models\Dispatch\DispatchConfirmation::with([
                'stop.customer'
            ])
            ->where('dispatch_route_id', $route->id)
            ->get();

        // Totales reales desde confirmaciones
        $totalScheduled = $confirmations->sum('scheduled_quantity');
        $totalReceived  = $confirmations->sum('received_quantity');
        $totalDead      = $confirmations->sum('dead_quantity');

        // Duración real
        $duration = null;

        if ($route->started_at && $route->finished_at) {
            $duration = \Carbon\Carbon::parse($route->started_at)
                ->diffInMinutes(
                    \Carbon\Carbon::parse($route->finished_at)
                );
        }

        return view('driver.route-report', [
            'route'          => $route,
            'confirmations'  => $confirmations,
            'totalScheduled' => $totalScheduled,
            'totalReceived'  => $totalReceived,
            'totalDead'      => $totalDead,
            'duration'       => $duration
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 🟢 SI LA RUTA ESTÁ ACTIVA → MOSTRAR VISTA OPERATIVA
    |--------------------------------------------------------------------------
    */

    $route->load([
        'stops' => function ($query) {
            $query->where('delivery_status', 'pending')
                  ->orderBy('stop_order')
                  ->with('customer');
        },
        'driver',
    ]);

    $totalChicks = $route->stops->sum('chicks_quantity');

    return view('driver.route', [
        'route'       => $route,
        'totalChicks' => $totalChicks
    ]);
}


    /*
    |--------------------------------------------------------------------------
    | 🟢 Iniciar Ruta
    |--------------------------------------------------------------------------
    */
    public function start(string $token)
    {
        $route = $this->validateToken($token);

        if ($route->status !== 'planned') {
            return back()->with('error', 'La ruta ya fue iniciada o finalizada.');
        }

        $route->update([
            'status'     => 'in_progress',
            'started_at' => now(),
        ]);

        Log::info('🚛 Ruta iniciada', [
            'route_id' => $route->id,
            'driver_id' => $route->driver_id,
        ]);

        return back()->with('success', 'Ruta iniciada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | 📍 Confirmar Llegada
    |--------------------------------------------------------------------------
    */
    public function arrive(PoultryDispatchRouteStop $stop)
    {
        if ($stop->delivery_status !== 'pending') {
            return back()->with('error', 'Esta parada ya fue procesada.');
        }

        $stop->update([
            'actual_arrival_time' => now()->format('H:i:s'),
        ]);

        Log::info('📍 Llegada confirmada', [
            'stop_id' => $stop->id,
            'route_id' => $stop->dispatch_route_id,
        ]);

        return back()->with('success', 'Llegada confirmada.');
    }

    /*
    |--------------------------------------------------------------------------
    | ✅ Completar Entrega
    |--------------------------------------------------------------------------
    */
    public function complete(PoultryDispatchRouteStop $stop)
    {
        if ($stop->delivery_status !== 'pending') {
            return back()->with('error', 'Entrega ya finalizada.');
        }

        DB::transaction(function () use ($stop) {

            $stop->update([
                'delivery_status' => 'delivered',
            ]);

            Log::info('✅ Entrega completada', [
                'stop_id' => $stop->id,
                'route_id' => $stop->dispatch_route_id,
                'quantity' => $stop->chicks_quantity
            ]);
        });

        return back()->with('success', 'Entrega completada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | 🏁 Finalizar Ruta
    |--------------------------------------------------------------------------
    */
    public function finish(string $token)
    {
        $route = $this->validateToken($token);

        if ($route->status !== 'in_progress') {
            return back()->with('error', 'La ruta no está en progreso.');
        }

        if ($route->stops()->where('delivery_status', 'pending')->exists()) {
            return back()->with('error', 'Hay entregas pendientes.');
        }

        $route->update([
            'status'      => 'finished',
            'finished_at' => now(),
        ]);

        Log::info('🏁 Ruta finalizada', [
            'route_id' => $route->id,
            'driver_id' => $route->driver_id,
        ]);

        return back()->with('success', 'Ruta finalizada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | 📊 Resumen
    |--------------------------------------------------------------------------
    */
    public function summary(string $token)
    {
        $route = $this->validateToken($token);

        $route->load('stops');

        $totalStops = $route->stops->count();
        $deliveredStops = $route->stops->where('delivery_status', 'delivered')->count();
        $totalChicks = $route->stops->sum('chicks_quantity');

        $duration = null;

        if ($route->started_at && $route->finished_at) {
            $duration = $route->started_at->diffForHumans($route->finished_at, true);
        }

        return view('driver.summary', compact(
            'route',
            'totalStops',
            'deliveredStops',
            'totalChicks',
            'duration'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | 🔐 Validar Token
    |--------------------------------------------------------------------------
    */
    private function validateToken(string $token): PoultryDispatchRoute
    {
        $route = PoultryDispatchRoute::where('driver_public_token', $token)
            ->where('driver_token_expires_at', '>=', now())
            ->first();

        if (!$route) {
            abort(404, 'Token inválido o expirado.');
        }

        return $route;
    }
   

public function sendWhatsAppLink(PoultryDispatchRouteStop $stop)
{
    if (!$stop->public_token) {
        abort(404, 'Esta parada no tiene token público.');
    }

    $stop->load('customer');

    if (!$stop->customer || !$stop->customer->phone) {
        $msg = 'El cliente no tiene número de teléfono.';
        return request()->expectsJson()
            ? response()->json(['ok' => false, 'error' => $msg], 422)
            : back()->with('error', $msg);
    }

    $link = url('/cliente/entrega/' . $stop->public_token);

    try {
        app(WhatsAppService::class)->sendDeliveryLink(
            $stop->customer->phone,
            $stop->customer->name ?? 'Cliente',
            $link
        );

        // Guardar timestamp de notificación en la parada
        $stop->update(['client_wa_sent_at' => now()]);

        $msg = 'Enlace enviado por WhatsApp.';
        return request()->expectsJson()
            ? response()->json(['ok' => true, 'message' => $msg, 'sent_at' => now()->format('H:i')])
            : back()->with('success', $msg);

    } catch (\Exception $e) {
        $msg = 'Error al enviar WhatsApp: ' . $e->getMessage();
        return request()->expectsJson()
            ? response()->json(['ok' => false, 'error' => $msg], 500)
            : back()->with('error', $msg);
    }
}

public function sendDriverWhatsApp(PoultryDispatchRoute $route)
{
    $route->load('driver', 'stops.customer');

    if (!$route->driver || !$route->driver->phone) {
        return back()->with('error', 'El conductor no tiene número de teléfono.');
    }

    $link       = url('/driver/route/' . ($route->driver_token ?? $route->driver_public_token));
    $driverName = $route->driver->full_name ?? $route->driver->name ?? 'Conductor';
    $totalBirds = $route->stops->sum('chicks_quantity');
    $totalStops = $route->stops->count();

    $stopsDetail = $route->stops
        ->sortBy('stop_order')
        ->values()
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
        app(WhatsAppService::class)->send($route->driver->phone, $message);
        return back()->with('success', 'Ruta enviada al conductor por WhatsApp.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al enviar WhatsApp: ' . $e->getMessage());
    }
}



}
