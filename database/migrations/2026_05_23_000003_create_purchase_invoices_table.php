<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poultry_order_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('providers')->restrictOnDelete();

            $table->string('invoice_number');           // PREL19672
            $table->string('provider_order_number')->nullable(); // PDC-00177702
            $table->date('invoice_date');
            $table->date('due_date')->nullable();

            $table->unsignedInteger('quantity_invoiced'); // Cantidad real facturada
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('fonav_amount', 15, 2)->default(0);
            $table->decimal('vaccine_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->decimal('balance', 15, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
