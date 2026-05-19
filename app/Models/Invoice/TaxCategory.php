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
use App\Models\Invoice\InvoiceItem;

class TaxCategory extends Model
{
    use HasFactory;

    protected $table = 'tax_categories';

    protected $fillable = [
        'code',           // Código DIAN (ej: 01 IVA, 04 INC, etc.)
        'name',           // Nombre del impuesto
        'percentage',     // 19.00, 5.00, 0.00
        'type',           // iva, retefuente, ica, etc.
        'is_exempt',
        'is_excluded',
        'is_active',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'is_exempt' => 'boolean',
        'is_excluded' => 'boolean',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function calculateTax($baseAmount)
    {
        return round(($baseAmount * $this->percentage) / 100, 2);
    }
}