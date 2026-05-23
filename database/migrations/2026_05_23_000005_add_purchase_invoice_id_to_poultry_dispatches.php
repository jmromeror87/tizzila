<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poultry_dispatches', function (Blueprint $table) {
            $table->foreignId('purchase_invoice_id')
                ->nullable()
                ->after('poultry_order_schedule_id')
                ->constrained('purchase_invoices')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('poultry_dispatches', function (Blueprint $table) {
            $table->dropForeign(['purchase_invoice_id']);
            $table->dropColumn('purchase_invoice_id');
        });
    }
};
