<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MarketEvent extends Model
{
    protected $table = 'market_events';

    protected $fillable = [
        'title', 'description', 'type', 'severity',
        'estimated_impact_pct', 'starts_at', 'ends_at',
        'is_active', 'registered_by',
    ];

    protected $casts = [
        'starts_at'  => 'date',
        'ends_at'    => 'date',
        'is_active'  => 'boolean',
    ];

    public function registeredBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'registered_by');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)
                 ->where('starts_at', '<=', now())
                 ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    public static function typeLabels(): array
    {
        return [
            'competitor_pricing' => 'Competidor con precio bajo',
            'feed_quality'       => 'Mala calidad del alimento',
            'strike'             => 'Paro / huelga',
            'oversupply'         => 'Sobreoferta del mercado',
            'demand_drop'        => 'Caída de demanda',
            'other'              => 'Otra novedad',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabels()[$this->type] ?? $this->type;
    }
}
