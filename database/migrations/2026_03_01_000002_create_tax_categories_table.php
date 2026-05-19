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
        Schema::create('tax_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->nullable()->unique();
            $table->string('name', 100);
            $table->string('dian_tax_code', 10);
            $table->decimal('percent', 5, 2)->default(0.00);
            $table->boolean('is_vat')->default(true);
            $table->boolean('is_exempt')->default(false);
            $table->boolean('is_excluded')->default(false);
            $table->boolean('affects_withholding')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_categories');
    }
};
