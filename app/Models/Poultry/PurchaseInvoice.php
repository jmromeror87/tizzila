<?php

namespace App\Models\Poultry;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'poultry_order_schedule_id',
        'provider_id',
        'invoice_number',
        'provider_order_number',
        'invoice_date',
        'due_date',
        'quantity_invoiced',
        'unit_price',
        'fonav_amount',
        'vaccine_amount',
        'subtotal',
        'total',
        'payment_status',
        'balance',
        'notes',
        'file_path',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(PoultryOrderSchedule::class, 'poultry_order_schedule_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    public function dispatches()
    {
        return $this->hasMany(PoultryDispatch::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchaseInvoicePayment::class);
    }
}
