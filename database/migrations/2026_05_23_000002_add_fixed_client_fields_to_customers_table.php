<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_fixed_client')->default(false)->after('credit_limit');
            $table->unsignedInteger('fixed_weekly_quantity')->default(0)->after('is_fixed_client');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['is_fixed_client', 'fixed_weekly_quantity']);
        });
    }
};
