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
        Schema::create('poultry_provider_document_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poultry_provider_document_id');
            $table->date('delivery_date');
            $table->integer('quantity');
            $table->integer('original_quantity')->nullable();
            $table->integer('verified_quantity')->nullable();
            $table->boolean('was_manually_edited')->default(false);
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->timestamp('edited_at')->nullable();
            $table->text('edit_reason')->nullable();
            $table->string('verification_status', 32)->default('ai_processed');
            $table->unsignedBigInteger('poultry_type_id');
            $table->timestamps();

            $table->foreign('poultry_provider_document_id', 'fk_doc_batches_doc')->references('id')->on('poultry_provider_documents')->onDelete('cascade');
            $table->foreign('poultry_type_id', 'fk_doc_batches_type')->references('id')->on('poultry_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('edited_by', 'fk_poultry_batches_edited_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_provider_document_batches');
    }
};
