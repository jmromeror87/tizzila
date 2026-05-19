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


namespace App\Models\Driver;

use Illuminate\Database\Eloquent\Model;

class PoultryDriver extends Model
{
    protected $table = 'poultry_drivers';

    protected $fillable = [
        'full_name',
        'phone',
        'whatsapp',
        'truck_type',
        'truck_plate',
        'status',
    ];

    /* ===============================
     | SCOPES
     =============================== */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function routes()
{
    return $this->hasMany(
        \App\Models\Dispatch\PoultryDispatchRoute::class,
        'driver_id'
    );
}


    /* ===============================
     | ACCESSORS (opcional)
     =============================== */
    public function getDisplayNameAttribute()
    {
        return "{$this->full_name} ({$this->truck_plate})";
    }

    /* ===============================
     | RELACIONES FUTURAS
     =============================== */
    // public function dispatches()
    // {
    //     return $this->hasMany(PoultryDispatch::class, 'driver_id');
    // }
}
