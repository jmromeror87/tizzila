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
use App\Models\Customer\Customer;

class PoultryDispatchItem extends Model
{
    use HasFactory;

       protected $fillable = [
        'poultry_dispatch_id',
        'customer_id',
        'quantity',

        // 🔑 PRECIOS (OBLIGATORIOS)
        'price_suggested',
        'price_applied',
        'price_source',
    ];

    protected $casts = [
        'quantity'        => 'integer',
        'price_suggested' => 'decimal:2',
        'price_applied'   => 'decimal:2',
    ];
    // 🔗 Despacho
    public function dispatch()
    {
        return $this->belongsTo(PoultryDispatch::class, 'poultry_dispatch_id');
    }
    public function poultryDispatch()
{
    return $this->belongsTo(
        \App\Models\Poultry\PoultryDispatch::class,
        'poultry_dispatch_id'
    );
}

    // 🔗 Cliente final
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
