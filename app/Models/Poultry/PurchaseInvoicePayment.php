<?php

namespace App\Models\Poultry;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoicePayment extends Model
{
    protected $fillable = [
        'purchase_invoice_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference',
        'notes',
        'registered_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'registered_by');
    }
}
