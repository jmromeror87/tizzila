<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poultry_order_distributions', function (Blueprint $table) {
            $table->decimal('sale_price', 10, 2)->nullable()->after('quantity');
            $table->decimal('vaccine_price', 10, 2)->nullable()->after('sale_price');
            $table->decimal('despique_price', 10, 2)->nullable()->after('vaccine_price');
            $table->enum('beak_condition', ['con_pico', 'sin_pico'])->nullable()->after('despique_price');
            $table->text('observations')->nullable()->after('beak_condition');
        });
    }

    public function down(): void
    {
        Schema::table('poultry_order_distributions', function (Blueprint $table) {
            $table->dropColumn(['sale_price', 'vaccine_price', 'despique_price', 'beak_condition', 'observations']);
        });
    }
};
