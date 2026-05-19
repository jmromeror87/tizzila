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
        Schema::create('dispatch_confirmations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatch_route_id');
            $table->unsignedBigInteger('dispatch_stop_id');
            $table->integer('scheduled_quantity')->default(0);
            $table->integer('received_quantity')->default(0);
            $table->integer('dead_quantity')->default(0);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('notes')->nullable();
            $table->string('signature_path', 1024)->nullable();
            $table->dateTime('confirmed_at');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('dispatch_route_id', 'fk_dc_route')->references('id')->on('poultry_dispatch_routes')->onDelete('cascade');
            $table->foreign('dispatch_stop_id', 'fk_dc_stop')->references('id')->on('poultry_dispatch_route_stops')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_confirmations');
    }
};
