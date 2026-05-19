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


namespace App\Models\Global;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyTaxProfile extends Model
{
    protected $fillable = [
        'company_id',
        'tax_regime',
        'responsibility_codes',
        'dian_resolution',
        'resolution_date',
        'prefix',
        'from_number',
        'to_number',
        'is_active',
    ];

    protected $casts = [
        'responsibility_codes' => 'array',
        'resolution_date' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
