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
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->enum('provider_type', ['poultry', 'expense', 'general'])
                ->default('general')
                ->after('status')
                ->comment('poultry = proveedor de pollitos, expense = proveedor de gastos');
        });

        // Marcar proveedores que ya tienen documentos de pollitos como tipo poultry
        DB::statement("
            UPDATE providers
            SET provider_type = 'poultry'
            WHERE id IN (SELECT DISTINCT provider_id FROM poultry_provider_documents)
        ");

        // Marcar proveedores que tienen pedidos de aves como tipo poultry
        DB::statement("
            UPDATE providers
            SET provider_type = 'poultry'
            WHERE id IN (SELECT DISTINCT provider_id FROM poultry_order_schedules)
        ");
    }

    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn('provider_type');
        });
    }
};
