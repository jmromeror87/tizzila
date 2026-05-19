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


namespace App\Models\Claim;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dispatch\DispatchConfirmation;
use App\Models\Dispatch\DispatchEvidence;
use App\Models\Poultry\Provider;

class SupplierClaim extends Model
{
    protected $table = 'supplier_claims';

  protected $fillable = [
    'dispatch_confirmation_id',
    'provider_id',
    'scheduled_quantity',
    'received_quantity',
    'dead_quantity',
    'unit_price',
    'claim_amount',
    'notes',
    'status',
    'submitted_at',
    'resolved_at',
];

    protected $casts = [
        'submitted_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | STATUS CONSTANTS
    |--------------------------------------------------------------------------
    */

    const STATUS_PENDING   = 'pending';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_CREDITED  = 'credited';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_SUBMITTED,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_CREDITED,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Confirmation where the mortality was reported
     */
    public function confirmation()
    {
        return $this->belongsTo(
            DispatchConfirmation::class,
            'dispatch_confirmation_id'
        );
    }

    /**
     * Provider responsible for the chicks
     */
    public function provider()
{
    return $this->belongsTo(Provider::class, 'provider_id');
}

    /**
     * Evidences from confirmation
     */
    public function evidences()
    {
        return $this->hasMany(
            DispatchEvidence::class,
            'dispatch_confirmation_id',
            'dispatch_confirmation_id'
        );
    }
}