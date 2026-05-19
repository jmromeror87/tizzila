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

class PoultryOrderApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'poultry_order_schedule_id',
        'provider_order_number',
        'document_date',
        'document_type',
        'poultry_type',
        'approved_quantity',
        'packaging_type',
        'delivery_batches',
        'vaccine_marek',
        'vaccine_gumboro',
        'vaccine_others',
        'unit_cost',
        'fonav_cost',
        'vaccine_cost',
        'total_unit_cost',
        'approval_status',
        'approved_at',
        'approved_by',
        'notes',

        // ✅ SIN ESPACIO
        'ocr_text',

        // 🔧 CAMPOS TÉCNICOS
        'source_document_path',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'document_date'    => 'date',
        'approved_at'      => 'datetime',
        'processed_at'     => 'datetime',
        'delivery_batches' => 'array',
        'vaccine_marek'    => 'boolean',
        'vaccine_gumboro'  => 'boolean',
        'unit_cost'        => 'decimal:2',
        'fonav_cost'       => 'decimal:2',
        'vaccine_cost'     => 'decimal:2',
        'total_unit_cost'  => 'decimal:2',
    ];

    /* ================= RELACIONES ================= */

    public function order()
    {
        return $this->belongsTo(
            PoultryOrderSchedule::class,
            'poultry_order_schedule_id'
        );
    }

    public function batches()
    {
        return $this->hasMany(PoultryOrderApprovalBatch::class);
    }

    public function approver()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'approved_by'
        );
    }

    /* ================= HELPERS ================= */

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function totalApprovedFromBatches(): int
    {
        return $this->batches->sum('approved_quantity');
    }
}
