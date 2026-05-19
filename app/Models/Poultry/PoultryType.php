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
use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class PoultryType extends Model
{
    protected $fillable = [
    'code',
    'name',
    'icon',
    'payment_days',
    'is_active',
];

    public function poultryType()
{
    return $this->belongsTo(PoultryType::class);
}

}
