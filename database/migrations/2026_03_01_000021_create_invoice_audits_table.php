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
        Schema::create('invoice_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('company_id');
            $table->string('event_type', 100);
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('company_id', 'fk_audit_company')->references('id')->on('companies');
            $table->foreign('invoice_id', 'fk_audit_invoice')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('user_id', 'fk_audit_user')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_audits');
    }
};
