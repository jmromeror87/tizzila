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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    protected $fillable = [
        'document_type_id',
        'document_number',
        'legal_name',
        'trade_name',
        'email',
        'phone',
        'website',
        'logo_path',
        'is_active',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) return null;
        return Storage::disk('public')->url($this->logo_path);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CompanyAddress::class);
    }

    public function mainAddress(): HasOne
    {
        return $this->hasOne(CompanyAddress::class)
            ->where('is_main', 1);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(CompanySetting::class);
    }

    public function taxProfiles(): HasMany
    {
        return $this->hasMany(CompanyTaxProfile::class);
    }

    public function activeTaxProfile(): HasOne
    {
        return $this->hasOne(CompanyTaxProfile::class)
            ->where('is_active', 1);
    }
}
