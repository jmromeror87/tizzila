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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('company_tax_profile_id');
            $table->unsignedBigInteger('poultry_order_schedule_id')->nullable();
            $table->unsignedBigInteger('poultry_dispatch_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('payment_term_id')->nullable();
            $table->enum('payment_type', ['cash', 'delivery', 'credit', 'cut_off'])->nullable();
            $table->string('document_type', 5)->default('01');
            $table->string('number', 50);
            $table->string('cufe')->nullable();
            $table->dateTime('issue_datetime');
            $table->dateTime('due_date')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('taxable_amount', 15, 2)->default(0.00);
            $table->decimal('exempt_amount', 15, 2)->default(0.00);
            $table->decimal('excluded_amount', 15, 2)->default(0.00);
            $table->decimal('tax_total', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2);
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->enum('status', ['draft', 'signed', 'sent', 'accepted', 'rejected', 'contingency', 'voided'])->default('draft')->nullable();
            $table->enum('radian_status', ['not_required', 'pending', 'registered', 'rejected'])->default('not_required')->nullable();
            $table->string('dian_track_id')->nullable();
            $table->string('dian_status_code', 50)->nullable();
            $table->text('dian_status_message')->nullable();
            $table->longText('xml_signed')->nullable();
            $table->longText('xml_response')->nullable();
            $table->enum('environment', ['testing', 'production']);
            $table->timestamps();

            $table->unique(['company_id', 'number'], 'unique_company_invoice');
            $table->foreign('company_id', 'fk_invoice_company')->references('id')->on('companies');
            $table->foreign('customer_id', 'fk_invoice_customer')->references('id')->on('customers');
            $table->foreign('poultry_dispatch_id', 'fk_invoice_dispatch')->references('id')->on('poultry_dispatches');
            $table->foreign('poultry_order_schedule_id', 'fk_invoice_order_schedule')->references('id')->on('poultry_order_schedules');
            $table->foreign('payment_term_id', 'fk_invoice_payment_term')->references('id')->on('payment_terms')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('company_tax_profile_id', 'fk_invoice_tax_profile')->references('id')->on('company_tax_profiles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
