<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Soltar FK que depende del índice unique
        DB::statement('ALTER TABLE poultry_order_distributions DROP FOREIGN KEY poultry_order_distributions_ibfk_1');
        // 2. Soltar el índice unique
        DB::statement('ALTER TABLE poultry_order_distributions DROP INDEX order_customer_unique');
        // 3. Agregar índice normal en poultry_order_schedule_id para la FK
        DB::statement('ALTER TABLE poultry_order_distributions ADD INDEX idx_pod_order (poultry_order_schedule_id)');
        // 4. Recrear la FK
        DB::statement('ALTER TABLE poultry_order_distributions ADD CONSTRAINT pod_order_fk FOREIGN KEY (poultry_order_schedule_id) REFERENCES poultry_order_schedules(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE poultry_order_distributions DROP FOREIGN KEY pod_order_fk');
        DB::statement('ALTER TABLE poultry_order_distributions DROP INDEX idx_pod_order');
        DB::statement('ALTER TABLE poultry_order_distributions ADD UNIQUE KEY order_customer_unique (poultry_order_schedule_id, customer_id)');
        DB::statement('ALTER TABLE poultry_order_distributions ADD CONSTRAINT poultry_order_distributions_ibfk_1 FOREIGN KEY (poultry_order_schedule_id) REFERENCES poultry_order_schedules(id) ON DELETE CASCADE');
    }
};
