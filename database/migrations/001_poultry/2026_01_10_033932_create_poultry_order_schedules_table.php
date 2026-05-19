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
        Schema::create('poultry_order_schedules', function (Blueprint $table) {
            $table->id();

            // 🔗 Relación con proveedores
            $table->foreignId('provider_id')
                ->constrained('providers')
                ->restrictOnDelete();

            // 🐣 Tipo de ave
            $table->enum('poultry_type', ['bb', 'lsl', 'lohmann'])
                ->comment('bb=Pollito BB, lsl=Pollita LSL, lohmann=Pollita Lohmann Brown');

            // 🔢 Cantidad
            $table->unsignedInteger('quantity');

            // 📅 Fechas clave
            $table->date('dispatch_date')
                ->comment('Solo lunes o jueves');

            $table->date('payment_due_date')
                ->comment('Calculada automáticamente según tipo de ave');

            // 🚦 Estado
            $table->enum('status', ['planned', 'dispatched', 'paid', 'cancelled'])
                ->default('planned');

            // 📝 Observaciones
            $table->text('notes')->nullable();

            // 🕒 Auditoría
            $table->timestamps();

            // 📌 Índices
            $table->index('dispatch_date', 'idx_poultry_orders_dispatch_date');
            $table->index('payment_due_date', 'idx_poultry_orders_payment_due');
            $table->index('provider_id', 'idx_poultry_orders_provider');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_order_schedules');
    }
};
