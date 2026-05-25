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


namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Poultry\PoultryOrderSchedule; 
use App\Models\Invoice\Invoice;

class Customer extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * Basado en requerimientos DIAN para Facturación Electrónica.
     */
    protected $fillable = [
        'name',
        'identification_number',
        'dv',
        'type_document_id',
        'type_organization_id',
        'type_regime_id',
        'type_liability_id',
        'municipality_id',
        'address',
        'city',
        'department',
        'delivery_zone',
        'latitude',
        'longitude',
        'postal_code',
        'email',
        'phone',
        'is_active',
        'payment_term_id',
        'credit_limit',
        'is_fixed_client',
        'fixed_weekly_quantity',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_fixed_client' => 'boolean',
            'latitude' => 'decimal:8',
    'longitude' => 'decimal:8',
    ];

    /**
     * Mutador para guardar siempre el nombre en mayúsculas (estándar factura)
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
    public function paymentTerm()
{
    return $this->belongsTo(\App\Models\Global\PaymentTerm::class);
}
public function invoices()
{
    return $this->hasMany(Invoice::class);
}

public function poultryOrders()
    {
        return $this->belongsToMany(
            PoultryOrderSchedule::class,
            'poultry_order_distributions',
            'customer_id',
            'poultry_order_schedule_id'
        )->withPivot('quantity');
    }

}