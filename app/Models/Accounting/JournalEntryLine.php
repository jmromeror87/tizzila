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
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryLine extends Model
{
    protected $table = 'journal_entry_lines';

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'third_party_id',
        'third_party_type',
        'cost_center_id',
        'description',
        'debit',
        'credit',
    ];

    protected $casts = [
        'journal_entry_id' => 'integer',
        'account_id'       => 'integer',
        'third_party_id'   => 'integer',
        'cost_center_id'   => 'integer',
        'debit'            => 'decimal:2',
        'credit'           => 'decimal:2',
    ];

    // =========================================================
    // RELACIONES BASE
    // =========================================================

    public function entry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    // Alias para compatibilidad con código existente
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    // =========================================================
    // ✅ RELACIONES DE TERCEROS (PARA EAGER LOADING)
    // Permiten: JournalEntry::with(['lines.customer', 'lines.provider'])
    // → 1 solo query para todos los customers
    // → 1 solo query para todos los providers
    // → 0 queries en la vista
    // =========================================================

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customer\Customer::class, 'third_party_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Poultry\Provider::class, 'third_party_id');
    }

    // =========================================================
    // ✅ ACCESSOR — resuelve el tercero correcto según tipo
    // Uso en blade: $line->third_party  → 0 queries adicionales
    // =========================================================

    public function getThirdPartyAttribute()
    {
        if (!$this->third_party_id || !$this->third_party_type) {
            return null;
        }

        return match($this->third_party_type) {
            'customer' => $this->customer,
            'provider' => $this->provider,
            default    => null,
        };
    }

    // =========================================================
    // ✅ ACCESSOR — nombre del tercero directo
    // Uso en blade: $line->third_party_name
    // =========================================================

    public function getThirdPartyNameAttribute(): ?string
    {
        $party = $this->third_party;

        if (!$party) return null;

        return $party->business_name ?? $party->name ?? null;
    }
}