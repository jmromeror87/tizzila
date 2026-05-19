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


namespace App\Models\Poultry;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoultryProviderDocumentBatch extends Model
{
    use HasFactory;

    protected $fillable = [
    'poultry_provider_document_id',
    'delivery_date',
    'quantity',
    'original_quantity',
    'verified_quantity',
    'poultry_type_id',
    'was_manually_edited',
    'edited_by',
    'edited_at',
    'edit_reason',
    'verification_status',
];

    protected $casts = [
    'delivery_date' => 'date',
    'quantity' => 'integer',
    'original_quantity' => 'integer',
    'verified_quantity' => 'integer',
    'was_manually_edited' => 'boolean',
    'edited_at' => 'datetime',
];

    /* ============================================================
     | RELACIONES
     ============================================================ */

    public function document()
    {
        return $this->belongsTo(
            PoultryProviderDocument::class,
            'poultry_provider_document_id'
        );
    }
    public function editor()
{
    return $this->belongsTo(\App\Models\User::class, 'edited_by');
}
public function getEffectiveQuantityAttribute()
{
    return $this->verified_quantity ?? $this->quantity;
}

    // 🔥 Nueva relación al catálogo de tipos
    public function poultryType()
    {
        return $this->belongsTo(
            PoultryType::class,
            'poultry_type_id'
        );
    }
}
