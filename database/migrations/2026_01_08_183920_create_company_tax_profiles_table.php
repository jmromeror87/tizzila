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
 * Archivo             : 2026_01_08_000006_create_company_tax_profiles_table.php
 * Función             : Crear tabla de configuración fiscal y tributaria de la compañía
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
        Schema::create('company_tax_profiles', function (Blueprint $table) {
            $table->id();

            // 🏢 Relación con la compañía
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->cascadeOnDelete()
                  ->comment('Compañía asociada al perfil fiscal');

            // 🧾 Régimen y responsabilidades
            $table->string('tax_regime')->comment('Régimen tributario (ej: Simplificado, Responsable IVA)');
            $table->json('responsibility_codes')->nullable()->comment('Códigos de responsabilidad DIAN');

            // 📑 Resolución DIAN / facturación
            $table->string('dian_resolution')->nullable()->comment('Número de resolución DIAN');
            $table->date('resolution_date')->nullable()->comment('Fecha de resolución');
            $table->string('prefix', 10)->nullable()->comment('Prefijo de facturación');
            $table->unsignedBigInteger('from_number')->nullable()->comment('Numeración inicial');
            $table->unsignedBigInteger('to_number')->nullable()->comment('Numeración final');

            // 🔘 Estado
            $table->boolean('is_active')->default(true);

            // 🕒 Auditoría
            $table->timestamps();

            // 🔐 Reglas
            $table->index(['company_id', 'is_active'], 'company_tax_profiles_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_tax_profiles');
    }
};
