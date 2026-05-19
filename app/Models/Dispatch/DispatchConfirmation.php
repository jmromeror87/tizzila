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


namespace App\Models\Dispatch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Claim\SupplierClaim;


class DispatchConfirmation extends Model
{
    protected $table = 'dispatch_confirmations';

    protected $fillable = [
        'dispatch_route_id',
        'dispatch_stop_id',
        'scheduled_quantity',
        'received_quantity',
        'dead_quantity',
        'latitude',
        'longitude',
        'notes',
        'signature_path',
        'confirmed_at',
    ];

    protected $casts = [
        'scheduled_quantity' => 'integer',
        'received_quantity'  => 'integer',
        'dead_quantity'      => 'integer',
        'latitude'           => 'float',
        'longitude'          => 'float',
        'confirmed_at'       => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function route(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\Dispatch\PoultryDispatchRoute::class,
            'dispatch_route_id'
        );
    }

    public function stop(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\Dispatch\PoultryDispatchRouteStop::class,
            'dispatch_stop_id'
        );
    }
    public function supplierClaim()
{
    return $this->hasOne(SupplierClaim::class, 'dispatch_confirmation_id');
}

    public function evidences(): HasMany
    {
        return $this->hasMany(
            DispatchEvidence::class,
            'dispatch_confirmation_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function getDifferenceAttribute(): int
    {
        return ($this->received_quantity ?? 0)
             - ($this->scheduled_quantity ?? 0);
    }

    public function getDeadPercentageAttribute(): float
    {
        $received = max(1, $this->received_quantity ?? 0);

        return round(
            (($this->dead_quantity ?? 0) / $received) * 100,
            2
        );
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->confirmed_at)) {
                $model->confirmed_at = now();
            }
        });
    }
}
