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
        Schema::create('poultry_dispatch_routes', function (Blueprint $table) {
            $table->id();
            $table->date('dispatch_date');
            $table->time('departure_time');
            $table->unsignedBigInteger('driver_id');
            $table->text('origin_address');
            $table->enum('status', ['planned', 'in_progress', 'finished', 'cancelled'])->default('planned');
            $table->char('driver_public_token', 36)->nullable()->unique('unique_driver_public_token');
            $table->dateTime('driver_token_expires_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->timestamp('driver_notified_at')->nullable();
            $table->timestamp('driver_opened_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->integer('total_customers')->default(0);
            $table->integer('total_chicks')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unsignedBigInteger('poultry_dispatch_id')->nullable();

            $table->foreign('driver_id', 'route_driver_fk')->references('id')->on('poultry_drivers')->onDelete('restrict');
            $table->foreign('poultry_dispatch_id', 'route_dispatch_fk')->references('id')->on('poultry_dispatches')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_dispatch_routes');
    }
};
