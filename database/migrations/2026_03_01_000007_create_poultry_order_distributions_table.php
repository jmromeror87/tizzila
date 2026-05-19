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
        Schema::create('poultry_order_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poultry_order_schedule_id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['poultry_order_schedule_id', 'customer_id'], 'order_customer_unique');
            $table->foreign('poultry_order_schedule_id', 'pod_order_fk')->references('id')->on('poultry_order_schedules')->onDelete('cascade');
            $table->foreign('customer_id', 'pod_customer_fk')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_order_distributions');
    }
};
