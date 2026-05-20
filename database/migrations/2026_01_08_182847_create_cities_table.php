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
 * Archivo             : 2026_01_08_000003_create_cities_table.php
 * Función             : Crear tabla de ciudades/municipios asociadas a un estado/departamento
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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            // 🗺️ Relación con estados/departamentos
            $table->foreignId('state_id')
                  ->constrained('states')
                  ->cascadeOnDelete()
                  ->comment('Estado/departamento al que pertenece la ciudad');

            // 🏙️ Identificación de la ciudad
            $table->string('name')->comment('Nombre de la ciudad/municipio');
            $table->string('dane_code', 20)->nullable()->comment('Código DANE (Colombia)');

            // 🔘 Estado
            $table->boolean('is_active')->default(true);

            // 🕒 Auditoría
            $table->timestamps();

            // 🔐 Reglas
            $table->unique(['state_id', 'name'], 'cities_state_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
