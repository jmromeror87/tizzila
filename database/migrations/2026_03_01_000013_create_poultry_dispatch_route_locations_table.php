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
        Schema::create('poultry_dispatch_route_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatch_route_id');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->dateTime('recorded_at');

            $table->foreign('dispatch_route_id', 'route_location_fk')->references('id')->on('poultry_dispatch_routes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poultry_dispatch_route_locations');
    }
};
