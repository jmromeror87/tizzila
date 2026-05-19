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


namespace App\Jobs;

use App\Models\Dispatch\PoultryDispatchRoute;
use App\Models\Dispatch\PoultryDispatchRouteStop;
use App\Models\Driver\PoultryDriver;
use App\Models\Poultry\PoultryDispatch;
use App\Services\Dispatch\MapboxRouteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateDispatchRoutes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected bool $forceRun;

    public function __construct(bool $forceRun = false)
    {
        $this->forceRun = $forceRun;
    }

    public function handle(MapboxRouteService $mapbox): void
    {
        $now = now();

        Log::info('🚀 GenerateDispatchRoutes iniciado', [
            'forceRun' => $this->forceRun,
            'fecha'    => $now->toDateString(),
        ]);

        if (!$this->forceRun && !in_array($now->dayOfWeekIso, [1,4], true)) {
            Log::info('⛔ Día no permitido');
            return;
        }

        $dispatches = PoultryDispatch::with([
            'order.provider',
            'items.customer',
        ])
        ->when(
            !$this->forceRun,
            fn($q) => $q->whereDate('dispatch_date', $now->toDateString())
        )
        ->where('status','scheduled')
        ->whereHas('items')
        ->get();

        if ($dispatches->isEmpty()) {
            Log::info('⚠️ No hay dispatches pendientes');
            return;
        }

        DB::transaction(function () use ($dispatches,$mapbox){

            foreach ($dispatches as $dispatch){

                try{

                    /*
                    |--------------------------------------------------------------------------
                    | 1️⃣ PROTEGER DUPLICADOS
                    |--------------------------------------------------------------------------
                    */

                    if (PoultryDispatchRoute::where('poultry_dispatch_id',$dispatch->id)->exists()){
                        Log::info("⏭ Dispatch {$dispatch->id} ya tiene ruta");
                        continue;
                    }

                    $provider = $dispatch->order->provider;

                    if (!$provider || empty($provider->fullAddress())){
                        Log::warning("Proveedor sin dirección",[
                            'dispatch'=>$dispatch->id
                        ]);
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 2️⃣ CONDUCTOR DISPONIBLE
                    |--------------------------------------------------------------------------
                    */

                    $driver = PoultryDriver::active()
                        ->whereNotExists(function($q) use ($dispatch){
                            $q->select(DB::raw(1))
                                ->from('poultry_dispatch_routes')
                                ->whereColumn('poultry_dispatch_routes.driver_id','poultry_drivers.id')
                                ->whereDate('poultry_dispatch_routes.dispatch_date',$dispatch->dispatch_date);
                        })
                        ->lockForUpdate()
                        ->first();

                    if (!$driver){
                        Log::warning("❌ No hay conductor disponible");
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 3️⃣ CREAR RUTA
                    |--------------------------------------------------------------------------
                    */

                    $route = PoultryDispatchRoute::create([
                        'dispatch_date'=>$dispatch->dispatch_date,
                        'departure_time'=>'06:00',
                        'driver_id'=>$driver->id,
                        'origin_address'=>$provider->fullAddress(),
                        'status'=>'planned',
                        'poultry_dispatch_id'=>$dispatch->id,
                        'driver_public_token'=>(string)Str::uuid(),
                        'driver_token_expires_at'=>now()->addHours(12),
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | 4️⃣ CONSTRUIR PARADAS VALIDAS
                    |--------------------------------------------------------------------------
                    */

                    $stops=[];
                    $totalChicks=0;

                    foreach($dispatch->items as $item){

                        $customer=$item->customer;

                        if(
                            !$customer ||
                            !$customer->address ||
                            !$customer->latitude ||
                            !$customer->longitude ||
                            $customer->latitude==0 ||
                            $customer->longitude==0
                        ){
                            Log::warning("Cliente {$item->customer_id} sin coordenadas válidas");
                            continue;
                        }

                        $stops[]=[
                            'customer_id'=>$item->customer_id,
                            'address'=>$customer->address,
                            'chicks_quantity'=>$item->quantity,
                            'dispatch_item_id'=>$item->id,
                            'poultry_order_schedule_id'=>$dispatch->poultry_order_schedule_id
                        ];

                        $totalChicks+=$item->quantity;
                    }

                    if(empty($stops)){
                        Log::warning("Dispatch {$dispatch->id} sin clientes válidos");
                        $route->delete();
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 5️⃣ GENERAR RUTA MAPBOX
                    |--------------------------------------------------------------------------
                    */

                    try{

                        $routeData=$mapbox->generateRoute(
                            $provider->fullAddress(),
                            collect($stops)->map(fn($s)=>[
                                'customer_id'=>$s['customer_id'],
                                'address'=>$s['address'],
                                'chicks_quantity'=>$s['chicks_quantity'],
                            ])->values()->all(),
                            '06:00'
                        );

                    }catch(\Throwable $e){

                        Log::error("Mapbox falló → fallback distancia",[
                            'dispatch'=>$dispatch->id
                        ]);

                        /*
                        |--------------------------------------------------------------------------
                        | FALLBACK SIMPLE
                        |--------------------------------------------------------------------------
                        */

                        $routeData=[
                            'stops'=>collect($stops)
                                ->sortBy('customer_id')
                                ->values()
                                ->map(function($s,$i){
                                    return[
                                        'customer_id'=>$s['customer_id'],
                                        'address'=>$s['address'],
                                        'chicks_quantity'=>$s['chicks_quantity'],
                                        'stop_order'=>$i+1,
                                        'estimated_arrival'=>null
                                    ];
                                })
                                ->toArray()
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 6️⃣ GUARDAR PARADAS
                    |--------------------------------------------------------------------------
                    */

                    foreach($routeData['stops'] as $stopData){

                        $origin=collect($stops)
                            ->firstWhere('customer_id',$stopData['customer_id']);

                        if(!$origin){
                            continue;
                        }

                        $customer=$dispatch->items
                            ->firstWhere('customer_id',$stopData['customer_id'])
                            ?->customer;

                        if(!$customer){
                            continue;
                        }

                        PoultryDispatchRouteStop::create([
                            'dispatch_route_id'=>$route->id,
                            'poultry_dispatch_id'=>$dispatch->id,
                            'dispatch_item_id'=>$origin['dispatch_item_id'],
                            'poultry_order_schedule_id'=>$origin['poultry_order_schedule_id'],
                            'customer_id'=>$stopData['customer_id'],
                            'stop_order'=>$stopData['stop_order'],
                            'chicks_quantity'=>$stopData['chicks_quantity'],
                            'customer_address'=>$stopData['address'],
                            'estimated_arrival_time'=>$stopData['estimated_arrival'],
                            'delivery_status'=>'pending',
                            'latitude'=>$customer->latitude,
                            'longitude'=>$customer->longitude,
                            'public_token'=>(string)Str::uuid(),
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | 7️⃣ ACTUALIZAR RUTA
                    |--------------------------------------------------------------------------
                    */

                    $route->update([
                        'total_customers'=>count($routeData['stops']),
                        'total_chicks'=>$totalChicks
                    ]);

                    $dispatch->update([
                        'status'=>'dispatched'
                    ]);

                    Log::info("✅ Ruta {$route->id} creada para dispatch {$dispatch->id}");

                }catch(\Throwable $e){

                    Log::error("❌ Error en dispatch {$dispatch->id}",[
                        'error'=>$e->getMessage()
                    ]);

                }

            }

        });

        Log::info('✅ GenerateDispatchRoutes finalizado correctamente');
    }
}