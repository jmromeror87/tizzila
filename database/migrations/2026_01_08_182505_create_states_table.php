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
 * Archivo             : 2026_01_08_000002_create_states_table.php
 * Función             : Crear tabla de departamentos/estados asociados a un país
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
        Schema::create('states', function (Blueprint $table) {
            $table->id();

            // 🌍 Relación con países
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnDelete()
                  ->comment('País al que pertenece el departamento/estado');

            // 🗺️ Identificación del estado/departamento
            $table->string('code', 10)->comment('Código del estado (ej: ANT, CUN)');
            $table->string('name')->comment('Nombre del estado/departamento');

            // 🔘 Estado
            $table->boolean('is_active')->default(true);

            // 🕒 Auditoría
            $table->timestamps();

            // 🔐 Reglas
            $table->unique(['country_id', 'code'], 'states_country_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
