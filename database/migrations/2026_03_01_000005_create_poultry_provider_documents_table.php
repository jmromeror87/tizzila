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
        Schema::create('poultry_provider_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->longText('ocr_text')->nullable();
            $table->json('ia_payload')->nullable();
            $table->enum('processing_status', ['pending', 'processing', 'processed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('provider_id', 'idx_provider_month_year');
            $table->foreign('provider_id', 'fk_provider_docs_provider')->references('id')->on('providers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_provider_documents');
    }
};
