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
use App\Models\Driver\PoultryDriver;
use App\Models\Poultry\PoultryDispatch;

class PoultryDispatchRoute extends Model
{
    protected $table = 'poultry_dispatch_routes';

    protected $fillable = [
        'dispatch_date',
        'departure_time',
        'driver_id',
        'origin_address',
        'status',
        'started_at',
        'finished_at',
        'total_customers',
        'total_chicks',
         
    
    'driver_notified_at',

        // 🔐 NUEVOS CAMPOS
    'driver_public_token',
    'driver_token_expires_at',

         // Precios sugeridos / aplicados
        //'price_suggested',   // DECIMAL(10,2) - sugerido por IA
        //'price_applied',     // DECIMAL(10,2) - precio final aplicado
        //'price_source',      // ENUM('ai','manual','historical') - origen del precio

    ];

    protected $casts = [
        'dispatch_date' => 'date',
        'started_at'    => 'datetime',
        'finished_at'   => 'datetime',
        'driver_token_expires_at' => 'datetime',
          'driver_notified_at' => 'datetime',
        //'price_suggested' => 'decimal:2',
        //'price_applied'   => 'decimal:2',
        //'price_source'    => 'string',
    ];

    /* ===============================
     | RELACIONES
     =============================== */

    public function driver()
    {
        return $this->belongsTo(PoultryDriver::class);
    }

    public function schedule()
    {
        return $this->belongsTo(
            \App\Models\Poultry\PoultryOrderSchedule::class,
            'schedule_id'
        );
    }

    public function stops()
    {
        return $this->hasMany(PoultryDispatchRouteStop::class, 'dispatch_route_id')
                    ->orderBy('stop_order');
    }
        public function dispatch()
{
    return $this->belongsTo(
        PoultryDispatch::class,
        'poultry_dispatch_id' // 👈 CLAVE CORRECTA
    );
}


    public function events()
    {
        return $this->hasMany(PoultryDispatchRouteEvent::class, 'dispatch_route_id');
    }

    public function locations()
    {
        return $this->hasMany(PoultryDispatchRouteLocation::class, 'dispatch_route_id');
    }
   public function confirmation()
{
    return $this->hasOne(\App\Models\Dispatch\DispatchConfirmation::class, 'dispatch_stop_id');
}

    /* ===============================
     | SCOPES
     =============================== */

    public function scopeToday($query)
    {
        return $query->whereDate('dispatch_date', now()->toDateString());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['planned', 'in_progress']);
    }

}
