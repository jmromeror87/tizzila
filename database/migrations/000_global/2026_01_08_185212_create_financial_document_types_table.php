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
 * Módulo              : Configuración Global
 * Archivo             : 2026_01_08_000009_create_financial_document_types_table.php
 * Función             : Crear tabla de tipos de documentos financieros y operativos
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 *
 * Software PROPIETARIO y CONFIDENCIAL.
 * Uso restringido a licencias válidas de Tizzila.
 * ───────────────────────────────────────────────────────────────
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_document_types', function (Blueprint $table) {
            $table->id();

            // 🧾 Identificación del documento
            $table->string('code', 10)->comment('Código del documento (FV, FC, NC, ND, REM, PC, PP)');
            $table->string('name')->comment('Nombre del tipo de documento');

            // 📂 Clasificación
            $table->enum('category', ['sales', 'purchases', 'inventory', 'treasury'])
                  ->comment('Categoría funcional del documento');

            // ⚙️ Comportamiento
            $table->boolean('affects_inventory')->default(false)->comment('Afecta inventario');
            $table->boolean('affects_accounting')->default(true)->comment('Afecta contabilidad');
            $table->smallInteger('sign')->default(1)->comment('Signo contable: 1 suma, -1 resta');

            // 🔘 Estado
            $table->boolean('is_active')->default(true);

            // 🕒 Auditoría
            $table->timestamps();

            // 🔐 Reglas
            $table->unique('code', 'financial_document_types_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_document_types');
    }
};
