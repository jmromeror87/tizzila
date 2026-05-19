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

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Proveedores
 * Archivo             : Provider.php
 * Función             : Gestión de Proveedores
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 *
 * Este software es PROPIETARIO y CONFIDENCIAL.
 * Su uso está permitido únicamente a usuarios autorizados
 * mediante licencia o suscripción activa otorgada por Jhoan romero r.
 *
 * Queda estrictamente prohibida la copia, modificación,
 * distribución, sublicenciamiento o ingeniería inversa,
 * total o parcial, sin autorización expresa y por escrito
 * del titular de los derechos.
 *
 * Este software se proporciona tal cual , con grantia segun el contrato de licencia.
 * ───────────────────────────────────────────────────────────────
 */


namespace App\Models\Poultry;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';

    protected $fillable = [
        'tax_id',
        'tax_id_type',
        'business_name',
        'trade_name',
        'address_line',
        'city',
        'department',
        'country',
        'postal_code',
        'phone',
        'email',
        'contacts',
        'payment_terms_days',
        'payment_conditions',
        'preferred_payment_method',
        'status',
        'provider_type',
    ];

    const TYPE_POULTRY = 'poultry';
    const TYPE_EXPENSE = 'expense';
    const TYPE_GENERAL = 'general';

    protected $casts = [
        'contacts' => 'array',
    ];

    /* ============================================================
     | RELATIONSHIPS
     ============================================================ */

    public function poultryOrderSchedules()
    {
        return $this->hasMany(PoultryOrderSchedule::class, 'provider_id');
    }
    public function fullAddress(): string
{
    return collect([
        $this->address_line,
        $this->city,
        $this->department,
        $this->country,
    ])->filter()->implode(', ');
}


    /* ============================================================
     | SCOPES
     ============================================================ */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePoultry($query)
    {
        return $query->where('provider_type', self::TYPE_POULTRY);
    }
     // 👇 RELACIÓN QUE FALTABA
    public function documents()
    {
        return $this->hasMany(
            PoultryProviderDocument::class,
            'provider_id'
        );
    }
    public function expenses()
    {
        return $this->hasMany(\App\Models\Expenses\Expense::class, 'provider_id');
    }

    public function claims()
    {
        return $this->hasMany(\App\Models\Claim\SupplierClaim::class, 'provider_id');
    }
}
