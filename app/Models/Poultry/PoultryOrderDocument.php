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
 * Archivo             : PoultryOrderDocument.php
 * Función             : Gestión de Documentos Asociados a Pedidos de Aves
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

class PoultryOrderDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'poultry_order_schedule_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_hash',
        'document_type',
    ];

    /* ============================================================
     | RELACIONES
     ============================================================ */

    public function order()
    {
        return $this->belongsTo(PoultryOrderSchedule::class, 'poultry_order_schedule_id');
    }
}
