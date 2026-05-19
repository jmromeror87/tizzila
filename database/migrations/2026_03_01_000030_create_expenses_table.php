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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->default(1);
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->enum('document_type', ['invoice', 'equivalent', 'support_doc']);
            $table->string('document_number', 100)->nullable();
            $table->decimal('tax_base', 15, 2)->default(0.00);
            $table->decimal('iva', 15, 2)->default(0.00);
            $table->decimal('retefuente', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('approved')->nullable();
            $table->date('expense_date');
            $table->enum('payment_method', ['cash', 'transfer', 'card', 'other'])->default('transfer')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->string('support_document')->nullable();
            $table->unsignedBigInteger('recurring_expense_id')->nullable();
            $table->unsignedBigInteger('journal_entry_id')->nullable();

            $table->foreign('category_id', 'fk_expense_category')->references('id')->on('expense_categories');
            $table->foreign('provider_id', 'fk_expense_provider')->references('id')->on('providers');
            $table->foreign('recurring_expense_id', 'fk_expense_recurring')->references('id')->on('recurring_expenses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
