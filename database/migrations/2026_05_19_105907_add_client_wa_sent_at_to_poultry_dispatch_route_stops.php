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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('poultry_dispatch_route_stops', function (Blueprint $table) {
            $table->timestamp('client_wa_sent_at')->nullable()->after('client_confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('poultry_dispatch_route_stops', function (Blueprint $table) {
            $table->dropColumn('client_wa_sent_at');
        });
    }
};
