<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whatsapp_collection_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices');
            $table->string('phone');
            $table->text('message');
            $table->enum('type', ['overdue_reminder', 'manual', 'receipt'])->default('overdue_reminder');
            $table->boolean('sent')->default(false);
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('whatsapp_collection_logs'); }
};
