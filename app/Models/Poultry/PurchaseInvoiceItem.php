<?php

namespace App\Models\Poultry;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceItem extends Model
{
    protected $fillable = [
        'purchase_invoice_id',
        'code',
        'description',
        'quantity',
        'unit_price',
        'total',
        'is_main_product',
    ];

    protected $casts = [
        'is_main_product' => 'boolean',
    ];

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }
}
