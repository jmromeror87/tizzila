<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : CompanySettingsSeeder.php
 * Función             : Registrar la configuración operativa de la empresa DISTRIAVICOLA SOFRAQ SAS
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySettingsSeeder extends Seeder
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

        DB::table('company_settings')->updateOrInsert(
            [
                'company_id' => $companyId,
            ],
            [
                'default_currency' => 'COP',
                'timezone'         => 'America/Bogota',
                'language'         => 'es',
                'fiscal_year_start'=> 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]
        );
    }
}
