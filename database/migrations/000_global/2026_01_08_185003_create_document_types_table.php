<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : 2026_01_08_000008_create_document_types_table.php
 * Función             : Crear tabla de tipos de documento para personas y empresas
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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();

            // 🌍 Relación país (permite reutilizar para otros países)
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnDelete()
                  ->comment('País al que pertenece el tipo de documento');

            // 🪪 Identificación
            $table->string('code', 10)->comment('Código del documento (CC, NIT, TI, PAS)');
            $table->string('name')->comment('Nombre del tipo de documento');

            // 👤 Aplicación del documento
            $table->enum('applies_to', ['person', 'company', 'both'])
                  ->default('person')
                  ->comment('Indica a quién aplica el documento');

            // 🔢 Validaciones
            $table->unsignedTinyInteger('length_min')->nullable()->comment('Longitud mínima');
            $table->unsignedTinyInteger('length_max')->nullable()->comment('Longitud máxima');
            $table->boolean('numeric_only')->default(true)->comment('Solo números');

            // 🔘 Estado
            $table->boolean('is_active')->default(true);

            // 🕒 Auditoría
            $table->timestamps();

            // 🔐 Reglas
            $table->unique(['country_id', 'code'], 'document_types_country_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
