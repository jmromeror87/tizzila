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


namespace App\Models\Dispatch;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Customer;
use App\Models\Poultry\PoultryOrderSchedule;
use Illuminate\Support\Str;



class PoultryDispatchRouteStop extends Model
{
    protected $table = 'poultry_dispatch_route_stops';

    protected $fillable = [
    'dispatch_route_id',
    'customer_id',
    'poultry_order_schedule_id',
    'stop_order',
    'chicks_quantity',
    'customer_address',
    'estimated_arrival_time',
    'actual_arrival_time',
    'delivery_status',
    'dispatch_item_id',

    // NUEVOS CAMPOS CLIENTE
    'public_token',
    'token_expires_at',
    'client_viewed_at',
    'client_confirmed_at',
    'client_wa_sent_at',
];

    

   protected $casts = [
    'estimated_arrival_time' => 'datetime:H:i',
    'actual_arrival_time'    => 'datetime:H:i',

    // NUEVOS
    'token_expires_at' => 'datetime',
    'client_viewed_at' => 'datetime',
    'client_confirmed_at' => 'datetime',
    'client_wa_sent_at'   => 'datetime',
];

    /* ===============================
     | RELACIONES
     =============================== */

    public function route()
    {
        return $this->belongsTo(PoultryDispatchRoute::class, 'dispatch_route_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withDefault([
            'name' => 'Cliente no asignado',
        ]);
    }

    public function order()
    {
        return $this->belongsTo(PoultryOrderSchedule::class, 'poultry_order_schedule_id');
    }

    public function orderSchedule()
    {
        return $this->belongsTo(PoultryOrderSchedule::class, 'poultry_order_schedule_id');
    }

    /* ===============================
     | SCOPES
     =============================== */

    public function scopePending($query)
    {
        return $query->where('delivery_status', 'pending');
    }
    public function dispatch()
{
    return $this->belongsTo(
        \App\Models\Poultry\PoultryDispatch::class,
        'poultry_dispatch_id'
    );
}
public function confirmation()
{
    return $this->hasOne(
        \App\Models\Dispatch\DispatchConfirmation::class,
        'dispatch_stop_id'
    );
}


}
