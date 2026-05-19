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
        Schema::create('invoice_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_payment_id');
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('amount_applied', 12, 2);
            $table->timestamps();

            $table->foreign('invoice_payment_id', 'fk_alloc_payment')->references('id')->on('invoice_payments')->onDelete('cascade');
            $table->foreign('invoice_id', 'fk_alloc_invoice')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_payment_allocations');
    }
};
