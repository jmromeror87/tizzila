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

class AccountingPeriod extends Model
{
    protected $table = 'accounting_periods';

    protected $fillable = [
        'company_id',
        'year',
        'month',
        'status',
        'closed_by',
        'closed_at',

         // 🔓 reapertura
    'reopened_by',
    'reopened_at',
    'reopen_reason',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
        'closed_by' => 'integer',
        'closed_at' => 'datetime',
    ];

    /**
     * Empresa a la que pertenece el periodo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Global\Company::class);
    }

    /**
     * Usuario que cerró el periodo
     */
    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'closed_by');
    }

    /**
     * Verifica si un periodo está cerrado
     */
     public static function isClosed($companyId, $date)
{
    $year = date('Y', strtotime($date));
    $month = date('m', strtotime($date));

    return self::where('company_id', $companyId)
        ->where('year', $year)
        ->where('month', $month)
        ->where('status', 'closed')
        ->exists();
}
    /**
     * Scope: periodos cerrados
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope: periodos abiertos
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Formato legible del periodo
     */
    public function getPeriodLabelAttribute(): string
    {
        return sprintf('%04d-%02d', $this->year, $this->month);
    }

    /**
     * Ej: 2026-04
     */
    public function getMonthYearAttribute(): string
    {
        return sprintf('%04d-%02d', $this->year, $this->month);
    }
}