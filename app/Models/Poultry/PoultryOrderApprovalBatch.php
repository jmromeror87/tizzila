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
 * Módulo              : Configuración Base
 * Archivo             : PoultryOrderApprovalBatch.php
 * Función             : Descripción de la función del archivo
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

class PoultryOrderApprovalBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'poultry_order_approval_id',
        'delivery_date',
        'approved_quantity',
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    /* ============================================================
     | RELACIONES
     ============================================================ */

    public function approval()
    {
        return $this->belongsTo(PoultryOrderApproval::class, 'poultry_order_approval_id');
    }
}
