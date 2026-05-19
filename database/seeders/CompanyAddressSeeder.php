<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : CompanyAddressSeeder.php
 * Función             : Registrar la dirección principal de DISTRIAVICOLA SOFRAQ SAS
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyAddressSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener la compañía por NIT
        $companyId = DB::table('companies')
            ->where('document_number', '901362908-3')
            ->value('id');

        if (!$companyId) {
            throw new \RuntimeException(
                'No existe la compañía DISTRIAVICOLA SOFRAQ SAS. Ejecuta CompanySeeder primero.'
            );
        }

        // Obtener el departamento Norte de Santander
        $stateId = DB::table('states')
            ->where('name', 'Norte de Santander')
            ->value('id');

        if (!$stateId) {
            throw new \RuntimeException(
                'No existe el departamento Norte de Santander. Verifica StatesSeeder.'
            );
        }

        // Obtener la ciudad Ocaña
        $cityId = DB::table('cities')
            ->where('state_id', $stateId)
            ->where('name', 'Ocaña')
            ->value('id');

        if (!$cityId) {
            throw new \RuntimeException(
                'No existe la ciudad Ocaña. Agrega Ocaña en CitiesSeeder antes de continuar.'
            );
        }

        DB::table('company_addresses')->updateOrInsert(
            [
                'company_id' => $companyId,
                'is_main'    => 1,
            ],
            [
                'city_id'      => $cityId,
                'address'      => 'CARRERA 12 200 14 TORRE 2',
                'neighborhood' => null,
                'postal_code'  => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        );
    }
}
