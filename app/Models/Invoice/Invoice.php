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


namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\global\Company;
use App\Models\Customer\Customer;
use App\Models\poultry\PoultryDispatch;
use App\Models\poultry\PoultryOrderSchedule;
use App\Models\Invoice\InvoiceItem;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\InvoiceAudit;
use App\Models\global\CompanyTaxProfile;
use App\Models\global\PaymentTerm;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'company_id',
        'company_tax_profile_id',
        'poultry_order_schedule_id',
        'poultry_dispatch_id',
        'customer_id',

        // 🔹 FINANCIERO
        'payment_term_id',
        'due_date',
        'balance',
        'payment_status',

        'document_type',
        'number',
        'cufe',
        'issue_datetime',
        'subtotal',
        'taxable_amount',
        'exempt_amount',
        'excluded_amount',
        'tax_total',
        'total',
        'status',
        'radian_status',
        'dian_track_id',
        'dian_status_code',
        'dian_status_message',
        'xml_signed',
        'xml_response',
        'environment',
    ];

    protected $casts = [
        'issue_datetime' => 'datetime',
        'due_date' => 'datetime',

        'subtotal' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'exempt_amount' => 'decimal:2',
        'excluded_amount' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function taxProfile()
    {
        return $this->belongsTo(CompanyTaxProfile::class, 'company_tax_profile_id');
    }

    public function poultryDispatch()
    {
        return $this->belongsTo(PoultryDispatch::class);
    }

    public function orderSchedule()
    {
        return $this->belongsTo(PoultryOrderSchedule::class, 'poultry_order_schedule_id');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }


    public function audits()
    {
        return $this->hasMany(InvoiceAudit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS FINANCIEROS
    |--------------------------------------------------------------------------
    */

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && in_array($this->payment_status, ['pending', 'partial']);
    }

    // Allocations de pagos
public function paymentAllocations()
{
    return $this->hasMany(
        InvoicePaymentAllocation::class,
        'invoice_id'
    );
}

// Pagos relacionados
public function payments()
{
    return $this->belongsToMany(
        InvoicePayment::class,
        'invoice_payment_allocations',
        'invoice_id',
        'invoice_payment_id'
    )->withPivot('amount_applied')
     ->withTimestamps();
}

// Total pagado
public function totalPaid()
{
    return $this->paymentAllocations()->sum('amount_applied');
}

// Saldo pendiente
public function balance()
{
    return $this->total - $this->totalPaid();
}

// Factura pagada
public function isPaid()
{
    return $this->balance() <= 0;
}


}