<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : CountriesSeeder.php
 * Función             : Insertar países base del sistema (Colombia por defecto)
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('countries')->updateOrInsert(
            ['code' => 'CO'],
            [
                'name'          => 'Colombia',
                'currency_code' => 'COP',
                'phone_prefix'  => '+57',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );
    }
}
