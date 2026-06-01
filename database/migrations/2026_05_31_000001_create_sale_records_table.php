<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');

            // Documento
            $table->string('invoice_number', 20)->nullable(); // FVE6826 o null
            $table->enum('invoice_status', ['facturado', 'sin_factura'])->default('sin_factura');
            $table->date('sale_date');

            // Cliente
            $table->string('nit_cliente', 30)->nullable();
            $table->string('nombre_cliente', 200);
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();

            // Producto
            $table->string('zona', 80)->nullable();
            $table->enum('tipo_producto', ['pollito', 'pollita'])->default('pollito');
            $table->string('linea', 30)->nullable(); // BROILER, LOHMAN, LSL
            $table->string('observacion', 100)->nullable(); // DESPIQUE, NINGUNA

            // Financiero
            $table->decimal('cantidad', 12, 2)->default(0);
            $table->decimal('precio_compra', 12, 2)->default(0);
            $table->decimal('precio_venta', 12, 2)->default(0);
            $table->decimal('total_compra', 14, 2)->default(0);
            $table->decimal('total_venta', 14, 2)->default(0);
            $table->decimal('utilidad', 14, 2)->default(0);

            // Cartera
            $table->decimal('saldo', 14, 2)->default(0); // total_venta - pagos
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['company_id', 'sale_date']);
            $table->index(['nit_cliente']);
            $table->index(['invoice_status']);
            $table->index(['payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_records');
    }
};
