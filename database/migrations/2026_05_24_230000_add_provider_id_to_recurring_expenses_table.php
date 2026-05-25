<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recurring_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id')->nullable()->after('company_id');
            $table->foreign('provider_id', 'fk_recurring_provider')->references('id')->on('providers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('recurring_expenses', function (Blueprint $table) {
            $table->dropForeign('fk_recurring_provider');
            $table->dropColumn('provider_id');
        });
    }
};
