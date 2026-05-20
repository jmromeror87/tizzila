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
use App\Models\Poultry\PoultryDispatchItem;
use App\Models\Invoice\TaxCategory;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'poultry_dispatch_item_id',
        'description',
        'quantity',
        'unit_price',
        'line_extension',
        'tax_category_id',
        'tax_amount',
        'total_line',
        'poultry_type_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:4',
        'line_extension' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_line' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function poultryType()
{
    return $this->belongsTo(
        \App\Models\Poultry\PoultryType::class,
        'poultry_type_id'
    );
}

    public function dispatchItem()
    {
        return $this->belongsTo(PoultryDispatchItem::class, 'poultry_dispatch_item_id');
    }

    public function taxCategory()
    {
        return $this->belongsTo(TaxCategory::class);
    }
}