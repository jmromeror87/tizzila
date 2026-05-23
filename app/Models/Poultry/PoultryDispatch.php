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


namespace App\Models\Poultry;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Dispatch\PoultryDispatchRoute;

class PoultryDispatch extends Model
{
    use HasFactory;

   protected $fillable = [
        'poultry_order_schedule_id',
        'purchase_invoice_id',
        'dispatch_date',
        'dispatch_time',
        'status',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'dispatch_date' => 'date',
        'started_at'    => 'datetime',
        'finished_at'   => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(PoultryOrderSchedule::class, 'poultry_order_schedule_id');
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }
    public function route()
{
    return $this->hasOne(PoultryDispatchRoute::class, 'poultry_dispatch_id');
}


    // 🔗 Clientes / items
    public function items()
    {
        return $this->hasMany(PoultryDispatchItem::class);
    }
    protected static function booted()
{
    static::updating(function ($dispatch) {

        // Permitir cambio automático a routed
        if (
            $dispatch->isDirty('status') &&
            in_array($dispatch->status, ['routed', 'delivered'])
        ) {
            return;
        }

        // Bloquear cualquier otro cambio si no está en scheduled
        if ($dispatch->getOriginal('status') !== 'scheduled') {
            throw new \Exception('No se permite modificar un despacho ya registrado.');
        }
    });
}
public function schedule()
{
    return $this->belongsTo(
        \App\Models\Poultry\PoultryOrderSchedule::class,
        'schedule_id'
    );
}
public function routeStops()
{
    return $this->hasMany(
        \App\Models\Dispatch\PoultryDispatchRouteStop::class,
        'poultry_dispatch_id'
    );
}


}
