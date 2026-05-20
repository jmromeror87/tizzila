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
 * Archivo             : alter_countries_table_add_metadata.php
 * Función             : Actualizar la tabla countries agregando información descriptiva y operativa
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
        Schema::table('countries', function (Blueprint $table) {

            // 🌍 Datos descriptivos del país
            $table->string('code', 5)
                  ->unique()
                  ->after('id')
                  ->comment('Código ISO del país (ej: CO, US)');

            $table->string('name')
                  ->after('code')
                  ->comment('Nombre del país');

            // 💱 Información operativa
            $table->string('currency_code', 5)
                  ->nullable()
                  ->after('name')
                  ->comment('Código de moneda (ej: COP, USD)');

            $table->string('phone_prefix', 10)
                  ->nullable()
                  ->after('currency_code')
                  ->comment('Prefijo telefónico internacional');

            // 🔘 Estado
            $table->boolean('is_active')
                  ->default(true)
                  ->after('phone_prefix');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'name',
                'currency_code',
                'phone_prefix',
                'is_active',
            ]);
        });
    }
};
