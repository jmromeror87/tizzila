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


namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    protected $table = 'journal_entries';
protected $fillable = [
    'company_id',
    'date',
    'reference',
    'description',
    'module_source',
    'module_id',
    'status',
    'created_by',
    'total_debit',   // ✅
    'total_credit',  // ✅
    'reversed_entry_id',
    'reversed_by',
    'reversed_at',
    'reversal_reason',
];

protected $casts = [
    'company_id'        => 'integer',
    'module_id'         => 'integer',
    'reversed_entry_id' => 'integer',
    'reversed_by'       => 'integer',
    'total_debit'       => 'decimal:2', // ✅
    'total_credit'      => 'decimal:2', // ✅
    'date'              => 'date',
    'reversed_at'       => 'datetime',
];

    // RELACIONES

    public function lines(): HasMany
    {
        return $this->hasMany(\App\Models\Accounting\JournalEntryLine::class, 'journal_entry_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Global\Company::class);
    }

    // HELPERS

    public function totalDebit(): float
    {
        return (float) $this->lines()->sum('debit');
    }

    public function totalCredit(): float
    {
        return (float) $this->lines()->sum('credit');
    }
    public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'reversed_by');
}

    public function isBalanced(): bool
    {
        return round($this->totalDebit(), 2) === round($this->totalCredit(), 2);
    }
    public function reversedFrom()
{
    return $this->belongsTo(JournalEntry::class, 'reversed_entry_id');
}

public function reversal()
{
    return $this->hasOne(JournalEntry::class, 'reversed_entry_id');
}
}
