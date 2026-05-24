<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('market_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', [
                'competitor_pricing',
                'feed_quality',
                'strike',
                'oversupply',
                'demand_drop',
                'other',
            ])->default('other');
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->integer('estimated_impact_pct')->default(0); // negativo = baja demanda
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('registered_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_events');
    }
};
