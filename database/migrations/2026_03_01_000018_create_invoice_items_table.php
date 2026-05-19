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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('poultry_dispatch_item_id')->nullable();
            $table->unsignedBigInteger('poultry_type_id')->nullable();
            $table->string('description');
            $table->decimal('quantity', 15, 4);
            $table->decimal('unit_price', 15, 4);
            $table->decimal('line_extension', 15, 2);
            $table->unsignedBigInteger('tax_category_id');
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('total_line', 15, 2);
            $table->timestamps();

            $table->foreign('invoice_id', 'fk_item_invoice')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('poultry_dispatch_item_id', 'fk_item_dispatch_item')->references('id')->on('poultry_dispatch_items');
            $table->foreign('poultry_type_id', 'fk_invoice_item_poultry_type')->references('id')->on('poultry_types')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('tax_category_id', 'fk_item_tax')->references('id')->on('tax_categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
