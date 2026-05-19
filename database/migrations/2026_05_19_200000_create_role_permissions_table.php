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
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->string('module', 60); // slug del módulo: clientes, facturacion, etc.
            $table->timestamps();

            $table->unique(['role_id', 'module']);
        });

        // Módulos del sistema
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 60)->unique();  // clave interna: facturacion
            $table->string('label', 100);           // nombre visible: Facturación
            $table->string('icon', 50)->default('fa-circle'); // icono FontAwesome
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Módulos iniciales
        $modules = [
            ['slug' => 'dashboard',     'label' => 'Dashboard',          'icon' => 'fa-th-large',             'sort_order' => 1],
            ['slug' => 'clientes',      'label' => 'Clientes',            'icon' => 'fa-users',                'sort_order' => 2],
            ['slug' => 'programacion',  'label' => 'Programación',        'icon' => 'fa-calendar-check',       'sort_order' => 3],
            ['slug' => 'logistica',     'label' => 'Logística / Rutas',   'icon' => 'fa-truck',                'sort_order' => 4],
            ['slug' => 'facturacion',   'label' => 'Facturación',         'icon' => 'fa-file-invoice-dollar',  'sort_order' => 5],
            ['slug' => 'cartera',       'label' => 'Cartera',             'icon' => 'fa-file-signature',       'sort_order' => 6],
            ['slug' => 'pagos',         'label' => 'Pagos',               'icon' => 'fa-hand-holding-usd',     'sort_order' => 7],
            ['slug' => 'gastos',        'label' => 'Gastos',              'icon' => 'fa-file-invoice',         'sort_order' => 8],
            ['slug' => 'contabilidad',  'label' => 'Contabilidad',        'icon' => 'fa-book',                 'sort_order' => 9],
            ['slug' => 'configuracion', 'label' => 'Configuración',       'icon' => 'fa-cog',                  'sort_order' => 10],
            ['slug' => 'usuarios',      'label' => 'Gestión Usuarios',    'icon' => 'fa-users-cog',            'sort_order' => 11],
        ];

        foreach ($modules as $m) {
            DB::table('system_modules')->insert(array_merge($m, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Permisos iniciales según la política definida
        $policy = [
            'admin'       => ['dashboard','clientes','programacion','logistica','facturacion','cartera','pagos','gastos','contabilidad','configuracion','usuarios'],
            'gerencia'    => ['dashboard','clientes','programacion','facturacion','cartera','pagos','gastos','contabilidad'],
            'finanzas'    => ['dashboard','clientes','facturacion','cartera','pagos','gastos','contabilidad'],
            'operaciones' => ['dashboard','programacion','logistica'],
            'comercial'   => ['dashboard','clientes','programacion','facturacion'],
        ];

        $roles = DB::table('roles')->pluck('id', 'name');

        foreach ($policy as $roleName => $modules) {
            if (!isset($roles[$roleName])) continue;
            foreach ($modules as $module) {
                DB::table('role_permissions')->insert([
                    'role_id'    => $roles[$roleName],
                    'module'     => $module,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('system_modules');
    }
};
