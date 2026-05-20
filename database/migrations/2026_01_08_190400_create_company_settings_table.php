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
 * Archivo             : 2026_01_08_000007_create_company_settings_table.php
 * Función             : Crear tabla de configuración operativa y preferencias globales de la compañía
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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();

            // 🏢 Relación con la compañía (1 a 1)
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->cascadeOnDelete()
                  ->comment('Compañía a la que pertenecen las configuraciones');

            // ⚙️ Preferencias del sistema
            $table->string('default_currency', 5)->default('COP')->comment('Moneda por defecto');
            $table->string('timezone')->default('America/Bogota')->comment('Zona horaria');
            $table->string('language', 5)->default('es')->comment('Idioma del sistema');

            // 📆 Configuración fiscal
            $table->tinyInteger('fiscal_year_start')->default(1)->comment('Mes de inicio del año fiscal (1-12)');

            // 🕒 Auditoría
            $table->timestamps();

            // 🔐 Reglas
            $table->unique('company_id', 'company_settings_company_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
