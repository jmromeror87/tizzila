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
 * Archivo             : 2026_01_08_000004_create_companies_table.php
 * Función             : Crear tabla de compañías con información legal y comercial
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // 🪪 Identificación legal
            $table->foreignId('document_type_id')
                  ->constrained('document_types')
                  ->restrictOnDelete()
                  ->comment('Tipo de documento legal de la empresa');

            $table->string('document_number', 50)->comment('Número de documento (NIT)');
            $table->string('legal_name')->comment('Razón social');
            $table->string('trade_name')->nullable()->comment('Nombre comercial');

            // 📞 Información de contacto
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('website')->nullable();

            // 🎨 Branding
            $table->string('logo_path')->nullable()->comment('Ruta del logo de la empresa');

            // 🔘 Estado
            $table->boolean('is_active')->default(true);

            // 🕒 Auditoría
            $table->timestamps();

            // 🔐 Reglas
            $table->unique(['document_type_id', 'document_number'], 'companies_document_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
