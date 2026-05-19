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
        Schema::create('poultry_dispatch_route_stops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatch_route_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('poultry_order_schedule_id');
            $table->integer('stop_order');
            $table->integer('chicks_quantity');
            $table->text('customer_address');
            $table->time('estimated_arrival_time')->nullable();
            $table->time('actual_arrival_time')->nullable();
            $table->enum('delivery_status', ['pending', 'delivered', 'failed'])->default('pending');
            $table->char('public_token', 36)->nullable()->unique('unique_public_token');
            $table->dateTime('token_expires_at')->nullable();
            $table->dateTime('client_viewed_at')->nullable();
            $table->dateTime('client_confirmed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unsignedBigInteger('poultry_dispatch_id')->nullable();
            $table->unsignedBigInteger('dispatch_item_id')->nullable();

            $table->foreign('dispatch_route_id', 'route_stop_route_fk')->references('id')->on('poultry_dispatch_routes')->onDelete('cascade');
            $table->foreign('customer_id', 'route_stop_customer_fk')->references('id')->on('customers')->onDelete('restrict');
            $table->foreign('poultry_order_schedule_id', 'route_stop_order_fk')->references('id')->on('poultry_order_schedules')->onDelete('restrict');
            $table->foreign('poultry_dispatch_id', 'route_stop_dispatch_fk')->references('id')->on('poultry_dispatches')->onDelete('set null');
            $table->foreign('dispatch_item_id', 'route_stop_dispatch_item_fk')->references('id')->on('poultry_dispatch_items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_dispatch_route_stops');
    }
};
