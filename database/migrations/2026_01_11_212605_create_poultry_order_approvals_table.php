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
 * Archivo             : 2026_01_11_212605_create_poultry_order_approvals_table.php
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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('poultry_order_approvals', function (Blueprint $table) {
            $table->id();

            // Relación con el pedido interno
            $table->foreignId('poultry_order_schedule_id')
                ->constrained()
                ->cascadeOnDelete();

            // Documento proveedor
            $table->string('provider_order_number')->nullable(); // ej: 81899
            $table->date('document_date')->nullable();
            $table->enum('document_type', [
                'order_confirmation',
                'invoice',
                'delivery_note'
            ])->default('order_confirmation');

            // Producto aprobado
            $table->enum('poultry_type', ['bb', 'lsl', 'lohmann']);
            $table->integer('approved_quantity');

            // Logística
            $table->string('packaging_type')->nullable(); // cartón / plástico
            $table->json('delivery_batches')->nullable(); // respaldo rápido

            // Vacunas
            $table->boolean('vaccine_marek')->default(false);
            $table->boolean('vaccine_gumboro')->default(false);
            $table->string('vaccine_others')->nullable();

            // Costos
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->decimal('fonav_cost', 12, 2)->nullable();
            $table->decimal('vaccine_cost', 12, 2)->nullable();
            $table->decimal('total_unit_cost', 12, 2)->nullable();

            // Estado de aprobación
            $table->enum('approval_status', [
                'pending',
                'under_review',
                'approved',
                'rejected'
            ])->default('pending');

            // Auditoría
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_order_approvals');
    }
};
