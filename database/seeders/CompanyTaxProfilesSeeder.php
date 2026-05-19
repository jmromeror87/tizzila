<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : CompanyTaxProfilesSeeder.php
 * Función             : Registrar el perfil tributario base de DISTRIAVICOLA SOFRAQ SAS
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTaxProfilesSeeder extends Seeder
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

        DB::table('company_tax_profiles')->updateOrInsert(
            [
                'company_id' => $companyId,
                'is_active'  => 1,
            ],
            [
                'tax_regime' => 'Responsable de IVA',
                'responsibility_codes' => json_encode([
                    'O-13', // Gran contribuyente (ejemplo)
                    'R-99-PN', // No responsable de IVA (ajusta si aplica)
                ]),
                'dian_resolution' => null,
                'resolution_date'=> null,
                'prefix'          => null,
                'from_number'     => null,
                'to_number'       => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );
    }
}
