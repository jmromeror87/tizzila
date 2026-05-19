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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Customer\Customer; 

class PaymentTerm extends Model
{
    use HasFactory;

    protected $table = 'payment_terms';

    protected $fillable = [
        'name',
        'type',
        'invoice_trigger',
        'cut_day',
        'credit_days',
        'requires_radian',
    ];

    protected $casts = [
        'cut_day' => 'integer',
        'credit_days' => 'integer',
        'requires_radian' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}