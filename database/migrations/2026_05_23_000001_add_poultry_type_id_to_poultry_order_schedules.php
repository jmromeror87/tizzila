<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poultry_order_schedules', function (Blueprint $table) {
            $table->foreignId('poultry_type_id')
                ->nullable()
                ->after('poultry_type')
                ->constrained('poultry_types')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('poultry_order_schedules', function (Blueprint $table) {
            $table->dropForeign(['poultry_type_id']);
            $table->dropColumn('poultry_type_id');
        });
    }
};
