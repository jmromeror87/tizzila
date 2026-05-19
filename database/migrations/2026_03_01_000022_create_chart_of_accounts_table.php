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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('code', 20);
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'income', 'expense', 'cost']);
            $table->unsignedTinyInteger('level')->default(1);
            $table->enum('normal_balance', ['debit', 'credit']);
            $table->boolean('is_posting')->default(false);
            $table->boolean('requires_third_party')->default(false);
            $table->boolean('requires_cost_center')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'code'], 'chart_accounts_company_code_unique');
            $table->foreign('company_id', 'chart_accounts_company_fk')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent_id', 'chart_accounts_parent_fk')->references('id')->on('chart_of_accounts')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
