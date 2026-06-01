<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Customer\Customer;
use App\Models\Global\Company;

class SaleRecord extends Model
{
    use HasFactory;

    protected $table = 'sale_records';

    protected $fillable = [
        'company_id',
        'invoice_number',
        'invoice_status',
        'sale_date',
        'nit_cliente',
        'nombre_cliente',
        'customer_id',
        'zona',
        'tipo_producto',
        'linea',
        'observacion',
        'cantidad',
        'precio_compra',
        'precio_venta',
        'total_compra',
        'total_venta',
        'utilidad',
        'saldo',
        'payment_status',
        'created_by',
    ];

    protected $casts = [
        'sale_date'    => 'date',
        'cantidad'     => 'decimal:2',
        'precio_compra'=> 'decimal:2',
        'precio_venta' => 'decimal:2',
        'total_compra' => 'decimal:2',
        'total_venta'  => 'decimal:2',
        'utilidad'     => 'decimal:2',
        'saldo'        => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isFacturado(): bool
    {
        return $this->invoice_status === 'facturado';
    }
}
