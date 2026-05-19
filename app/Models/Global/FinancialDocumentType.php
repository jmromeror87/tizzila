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

class FinancialDocumentType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'affects_inventory',
        'affects_accounting',
        'sign',
        'is_active',
    ];
}
