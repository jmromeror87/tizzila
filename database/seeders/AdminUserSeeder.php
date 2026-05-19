<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Obtener rol admin
        $adminRoleId = DB::table('roles')
            ->where('name', 'admin')
            ->value('id');

        if (!$adminRoleId) {
            throw new \Exception('Rol admin no existe. Ejecuta RolesTableSeeder primero.');
        }

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@tizzila.com'],
            [
                'name' => 'Administrador Tizzila',
                'password' => Hash::make('Admin123*'),
                'role_id' => $adminRoleId,
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
