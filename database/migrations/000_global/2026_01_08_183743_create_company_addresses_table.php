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
 * Archivo             : 2026_01_08_000005_create_company_addresses_table.php
 * Función             : Crear tabla de direcciones y sucursales de la compañía
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
        Schema::create('company_addresses', function (Blueprint $table) {
            $table->id();

            // 🏢 Relación con la compañía
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->cascadeOnDelete()
                  ->comment('Compañía a la que pertenece la dirección');

            // 🌍 Ubicación
            $table->foreignId('city_id')
                  ->constrained('cities')
                  ->restrictOnDelete()
                  ->comment('Ciudad donde se ubica la dirección');

            $table->string('address')->comment('Dirección física');
            $table->string('neighborhood')->nullable()->comment('Barrio / sector');
            $table->string('postal_code', 20)->nullable()->comment('Código postal');

            // 🏷️ Configuración
            $table->boolean('is_main')->default(false)->comment('Indica si es la dirección principal');

            // 🕒 Auditoría
            $table->timestamps();

            // 🔐 Reglas
            $table->index(['company_id', 'is_main'], 'company_addresses_main_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_addresses');
    }
};
