<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $roles = [
            [
                'name' => 'admin',
                'label' => 'Administrador',
                'description' => 'Control total del sistema',
            ],
            [
                'name' => 'operaciones',
                'label' => 'Operaciones',
                'description' => 'Programación, rutas, despachos y tracking',
            ],
            [
                'name' => 'comercial',
                'label' => 'Comercial',
                'description' => 'Clientes, precios y ventas',
            ],
            [
                'name' => 'finanzas',
                'label' => 'Finanzas',
                'description' => 'Compras, facturación, cartera y gastos',
            ],
            [
                'name' => 'gerencia',
                'label' => 'Gerencia',
                'description' => 'Lectura y tablero gerencial',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                array_merge($role, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
