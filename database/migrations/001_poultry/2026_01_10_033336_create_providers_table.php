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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();

            // 🏢 Identificación fiscal
            $table->string('tax_id', 30)->unique();
            $table->enum('tax_id_type', ['NIT', 'CC', 'CE', 'PASSPORT']);
            $table->string('business_name');
            $table->string('trade_name')->nullable();

            // 📍 Dirección
            $table->string('address_line')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('postal_code', 20)->nullable();

            // 📞 Contacto general
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();

            // 👤 Personas de contacto (JSON)
            $table->json('contacts')->nullable()
                ->comment('Contactos: facturación, comercial, etc.');

            // 💰 Términos de pago
            $table->unsignedInteger('payment_terms_days')->default(0)
                ->comment('Días de plazo por defecto');
            $table->text('payment_conditions')->nullable();
            $table->enum('preferred_payment_method', ['transfer', 'cash', 'check'])
                ->default('transfer');

            // ⚙️ Estado
            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            // 🕒 Auditoría
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
