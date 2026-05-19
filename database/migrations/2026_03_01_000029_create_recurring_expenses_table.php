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
        Schema::create('recurring_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('expense_category_id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('frequency', ['daily', 'weekly', 'biweekly', 'monthly'])->default('monthly')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_run_date');
            $table->date('last_run_date')->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->timestamps();

            $table->foreign('company_id', 'fk_recurring_company')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('expense_category_id', 'fk_recurring_category')->references('id')->on('expense_categories')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_expenses');
    }
};
