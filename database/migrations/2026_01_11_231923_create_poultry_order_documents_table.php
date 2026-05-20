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


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poultry_order_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('poultry_order_schedule_id')
                ->constrained('poultry_order_schedules')
                ->cascadeOnDelete();

            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type', 100);

            // Tipo de documento: factura, orden proveedor, guía, etc.
            $table->string('document_type')->nullable();

            // Hash para evitar duplicados
            $table->string('file_hash', 64)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_order_documents');
    }
};
