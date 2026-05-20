<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('poultry_order_schedules', function (Blueprint $table) {
            $table->enum('poultry_type', ['bb', 'lsl', 'lohmann'])->nullable()->after('provider_id');
        });
    }

    public function down(): void
    {
        Schema::table('poultry_order_schedules', function (Blueprint $table) {
            $table->dropColumn('poultry_type');
        });
    }
};
