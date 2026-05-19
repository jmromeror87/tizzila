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


namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Global\Company;
use App\Models\User;
use App\Models\Customer\Customer; // Asegúrate de que la ruta sea correcta

class InvoicePayment extends Model
{
    use HasFactory;

    protected $table = 'invoice_payments';

    protected $fillable = [
        'company_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Empresa
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Usuario que creó el pago
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Allocations del pago

    public function allocations()
{
    return $this->hasMany(
        \App\Models\Invoice\InvoicePaymentAllocation::class,
        'invoice_payment_id'
    );
}

    // Facturas relacionadas (many-to-many)
    public function invoices()
    {
        return $this->belongsToMany(
            Invoice::class,
            'invoice_payment_allocations',
            'invoice_payment_id',
            'invoice_id'
        )->withPivot('amount_applied')
         ->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    // Total aplicado a facturas
    public function totalAllocated()
    {
        return $this->allocations()->sum('amount_applied');
    }

    // Saldo restante del pago
    public function remainingAmount()
    {
        return $this->amount - $this->totalAllocated();
    }

    public function invoice()
{
    return $this->belongsTo(\App\Models\Invoice\Invoice::class, 'invoice_id');
}
public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'created_by');
}
}