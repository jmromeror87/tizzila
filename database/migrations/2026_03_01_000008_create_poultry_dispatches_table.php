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
        Schema::create('poultry_dispatches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poultry_order_schedule_id');
            $table->date('dispatch_date');
            $table->time('dispatch_time');
            $table->enum('status', ['scheduled', 'dispatched', 'delivered', 'cancelled'])->default('scheduled')->nullable();
            $table->timestamps();

            $table->unique('poultry_order_schedule_id', 'unique_schedule_dispatch');
            $table->foreign('poultry_order_schedule_id', 'fk_dispatch_order')->references('id')->on('poultry_order_schedules')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_dispatches');
    }
};
