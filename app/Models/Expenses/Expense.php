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


namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Expenses\ExpenseCategory;
use App\Models\Expenses\RecurringExpense;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $fillable = [
        'company_id',          // ✅ FIX 1: faltaba — el controlador lo envía pero nunca se guardaba
        'provider_id',
        'category_id',
        'recurring_expense_id',
        'cost_center_id',
        'journal_entry_id',

        'document_type',
        'document_number',

        'tax_base',
        'iva',
        'retefuente',
        'total',

        'status',
        'payment_method',

        'expense_date',
        'support_document',
        'description',

        'created_by',
    ];

    protected $casts = [
        'company_id'   => 'integer',   // ✅ FIX 3
        'provider_id'  => 'integer',
        'category_id'  => 'integer',
        'created_by'   => 'integer',
        'expense_date' => 'date',
        'tax_base'     => 'decimal:2',
        'iva'          => 'decimal:2',
        'retefuente'   => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function provider()
    {
        return $this->belongsTo(\App\Models\Poultry\Provider::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function recurring()
    {
        return $this->belongsTo(RecurringExpense::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(\App\Models\Accounting\JournalEntry::class);
    }

    public function costCenter()
    {
        // ✅ FIX 2: descomentada con null-safe — no explota si no existe el modelo
        // return $this->belongsTo(\App\Models\Accounting\CostCenter::class);
       // return $this->belongsTo(\App\Models\Accounting\CostCenter::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS CONTABLES
    |--------------------------------------------------------------------------
    */

    public function getSubtotalAttribute()
    {
        return $this->tax_base;
    }

    public function getTaxAttribute()
    {
        return $this->iva;
    }

    public function getWithholdingAttribute()
    {
        return $this->retefuente;
    }

    public function getNetTotalAttribute()
    {
        return $this->total;
    }

    // ✅ NUEVO — ¿tiene IVA?
    public function getHasIvaAttribute(): bool
    {
        return $this->iva > 0;
    }

    // ✅ NUEVO — ¿tiene retefuente?
    public function getHasRetefuenteAttribute(): bool
    {
        return $this->retefuente > 0;
    }

    // ✅ NUEVO — ¿está contabilizado?
    public function getIsAccountedAttribute(): bool
    {
        return !is_null($this->journal_entry_id);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('expense_date', now()->month)
                     ->whereYear('expense_date', now()->year);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    // ✅ NUEVO — filtrar por rango de fechas
    public function scopeDateBetween($query, $from, $to)
    {
        return $query->whereDate('expense_date', '>=', $from)
                     ->whereDate('expense_date', '<=', $to);
    }

    // ✅ NUEVO — sin contabilizar (útil para detectar gastos huérfanos)
    public function scopeUnaccounted($query)
    {
        return $query->whereNull('journal_entry_id');
    }

    // ✅ NUEVO — por proveedor
    public function scopeByProvider($query, $providerId)
    {
        return $query->where('provider_id', $providerId);
    }
}