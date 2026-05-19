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
        Schema::table('poultry_order_schedules', function (Blueprint $table) {
            $table->string('date_override_reason')->nullable()
                ->after('notes')
                ->comment('Justificación cuando el despacho no es lunes ni jueves');
        });
    }

    public function down(): void
    {
        Schema::table('poultry_order_schedules', function (Blueprint $table) {
            $table->dropColumn('date_override_reason');
        });
    }
};
