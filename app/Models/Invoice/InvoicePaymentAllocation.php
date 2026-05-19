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

class InvoicePaymentAllocation extends Model
{
    use HasFactory;

    protected $table = 'invoice_payment_allocations';

    protected $fillable = [
        'invoice_payment_id',
        'invoice_id',
        'amount_applied'
    ];

    protected $casts = [
        'amount_applied' => 'decimal:2'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Pago
   

    // Factura
   
    public function invoice()
{
    return $this->belongsTo(
        \App\Models\Invoice\Invoice::class,
        'invoice_id'
    );
}

public function payment()
{
    return $this->belongsTo(
        \App\Models\Invoice\InvoicePayment::class,
        'invoice_payment_id'
    );
}
}