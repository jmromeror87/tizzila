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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identification_number', 20)->unique();
            $table->char('dv', 1)->nullable();
            $table->string('type_document_id', 5);
            $table->string('type_organization_id', 5);
            $table->string('type_regime_id', 5);
            $table->string('type_liability_id', 10)->default('R-99-PN')->nullable();
            $table->string('municipality_id', 10);
            $table->string('address');
            $table->string('postal_code', 10)->nullable();
            $table->string('email')->unique();
            $table->string('phone', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedBigInteger('payment_term_id')->nullable();

            $table->unique(['type_document_id', 'identification_number'], 'customers_unique_document');
            $table->foreign('payment_term_id', 'fk_customer_payment_term')->references('id')->on('payment_terms');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
