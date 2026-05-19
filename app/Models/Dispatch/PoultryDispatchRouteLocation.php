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

class PoultryDispatchRouteLocation extends Model
{
    protected $table = 'poultry_dispatch_route_locations';

    public $timestamps = false;

    protected $fillable = [
        'dispatch_route_id',
        'latitude',
        'longitude',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /* ===============================
     | RELACIONES
     =============================== */

    public function route()
    {
        return $this->belongsTo(PoultryDispatchRoute::class, 'dispatch_route_id');
    }
}
