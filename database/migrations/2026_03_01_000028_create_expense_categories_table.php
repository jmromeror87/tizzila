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
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('puc_code', 20);
            $table->enum('type', ['cost', 'operational', 'administrative', 'financial', 'other'])->default('operational');
            $table->timestamps();
            $table->boolean('is_active')->default(true)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_categories');
    }
};
