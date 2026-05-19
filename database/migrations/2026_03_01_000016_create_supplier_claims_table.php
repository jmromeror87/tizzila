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
        Schema::create('supplier_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatch_confirmation_id')->unique('uniq_confirmation_claim');
            $table->unsignedBigInteger('provider_id');
            $table->integer('scheduled_quantity');
            $table->integer('received_quantity');
            $table->integer('dead_quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('claim_amount', 12, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected', 'credited'])->default('pending')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('dispatch_confirmation_id', 'fk_claim_confirmation')->references('id')->on('dispatch_confirmations')->onDelete('cascade');
            $table->foreign('provider_id', 'fk_claim_provider')->references('id')->on('providers')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_claims');
    }
};
