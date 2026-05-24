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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\ValidationException;
use App\Models\Poultry\PoultryOrderDocument;
use App\Models\Poultry\PurchaseInvoice;
use App\Models\Customer\Customer;

class PoultryOrderSchedule extends Model
{
    use HasFactory;

    protected $table = 'poultry_order_schedules';

    protected $fillable = [
        'provider_id',
        'poultry_type_id',
        'quantity',
        'dispatch_date',
        'payment_due_date',
        'status',
        'approval_status',
        'approved_at',
        'approved_by',
        'notes',
        'date_override_reason',
        'provider_document_id',
    ];

    protected $casts = [
        'dispatch_date'    => 'date',
        'payment_due_date' => 'date',
        'approved_at'      => 'datetime',
    ];

    /* ============================================================
     | RELATIONSHIPS
     ============================================================ */

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function poultryType()
    {
        return $this->belongsTo(PoultryType::class, 'poultry_type_id');
    }

    public function customers()
    {
        return $this->belongsToMany(
            Customer::class,
            'poultry_order_distributions',
            'poultry_order_schedule_id',
            'customer_id'
        )->withPivot('quantity');
    }

    public function distributions()
    {
        return $this->hasMany(\App\Models\Poultry\PoultryOrderDistribution::class);
    }

    public function approval()
    {
        return $this->hasOne(
            PoultryOrderApproval::class,
            'poultry_order_schedule_id'
        );
    }

    public function dispatch()
    {
        return $this->hasOne(
            PoultryDispatch::class,
            'poultry_order_schedule_id'
        );
    }

    public function dispatches()
    {
        return $this->hasMany(PoultryDispatch::class);
    }

    public function purchaseInvoice()
    {
        return $this->hasOne(PurchaseInvoice::class);
    }

    public function documents()
    {
        return $this->hasMany(
            PoultryOrderDocument::class,
            'poultry_order_schedule_id'
        );
    }

    public function providerDocument()
    {
        return $this->belongsTo(
            PoultryProviderDocument::class,
            'provider_document_id'
        );
    }

    /* ============================================================
     | BOOT LOGIC
     ============================================================ */

    protected static function booted()
    {
        static::saving(function ($model) {

            $dispatchDate = Carbon::parse($model->dispatch_date);

            // ✅ Validar lunes (1) o jueves (4)
            if (! in_array($dispatchDate->dayOfWeekIso, [1, 4])) {
                throw ValidationException::withMessages([
                    'dispatch_date' => 'La fecha de despacho solo puede ser lunes o jueves.',
                ]);
            }

            // ✅ Validar que exista tipo
            if (!$model->poultryType) {
                throw ValidationException::withMessages([
                    'poultry_type_id' => 'Tipo de ave no válido.',
                ]);
            }

            // ⏱️ Calcular fecha de pago dinámica
            $model->payment_due_date = $dispatchDate
                ->copy()
                ->addDays($model->poultryType->payment_days);
        });
    }

    /* ============================================================
     | SCOPES
     ============================================================ */

    public function scopePendingPayment($query)
    {
        return $query->where('status', '!=', 'paid');
    }

    public function scopeDueSoon($query, $days = 5)
    {
        return $query->whereBetween(
            'payment_due_date',
            [now(), now()->addDays($days)]
        );
    }
}
