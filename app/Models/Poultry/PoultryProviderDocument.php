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

class PoultryProviderDocument extends Model
{
    use HasFactory;

    /**
     * Campos permitidos para asignación masiva
     */
    protected $fillable = [
    'provider_id',
    'file_path',
    'original_name',
    'mime_type',

    'period_start',
    'period_end',

    'processing_status',
    'error_message',

    'ocr_text',
    'ia_payload',
];

    /**
     * Casts automáticos
     */
    protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'ia_payload'   => 'array',
        'ocr_text'    => 'array',
    ];

    /* ============================================================
     | RELACIONES
     ============================================================ */

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function batches()
    {
        return $this->hasMany(
            PoultryProviderDocumentBatch::class,
            'poultry_provider_document_id'
        );
    }
}
