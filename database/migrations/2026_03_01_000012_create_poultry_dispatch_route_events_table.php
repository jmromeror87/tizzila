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
        Schema::create('poultry_dispatch_route_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatch_route_id');
            $table->enum('event_type', ['route_started', 'arrived_customer', 'delivery_completed', 'delivery_failed', 'route_finished']);
            $table->dateTime('event_time');
            $table->string('description')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('dispatch_route_id', 'route_event_fk')->references('id')->on('poultry_dispatch_routes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_dispatch_route_events');
    }
};
