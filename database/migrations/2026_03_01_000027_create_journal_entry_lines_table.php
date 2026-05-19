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
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_entry_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('third_party_id')->nullable();
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00);
            $table->timestamps();
            $table->enum('third_party_type', ['customer', 'provider'])->nullable();

            $table->foreign('journal_entry_id', 'jel_entry_fk')->references('id')->on('journal_entries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('account_id', 'jel_account_fk')->references('id')->on('chart_of_accounts')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entry_lines');
    }
};
