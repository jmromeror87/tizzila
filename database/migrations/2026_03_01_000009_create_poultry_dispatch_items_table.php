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
        Schema::create('poultry_dispatch_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poultry_dispatch_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedInteger('quantity');
            $table->decimal('price_suggested', 10, 2);
            $table->decimal('price_applied', 10, 2)->nullable();
            $table->enum('price_source', ['ai', 'manual', 'historical'])->default('ai')->nullable();
            $table->timestamps();

            $table->foreign('poultry_dispatch_id', 'fk_dispatch_item_dispatch')->references('id')->on('poultry_dispatches')->onDelete('cascade');
            $table->foreign('customer_id', 'fk_dispatch_item_customer')->references('id')->on('customers')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_dispatch_items');
    }
};
