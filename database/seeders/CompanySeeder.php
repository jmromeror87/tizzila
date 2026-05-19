<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Proyecto : Tizzila App
 * Módulo   : Configuración Global
 * Archivo  : CompanySeeder.php
 * Función  : Registrar la empresa DISTRIAVICOLA SOFRAQ SAS
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // País Colombia
        $countryId = DB::table('countries')->where('code', 'CO')->value('id');

        // Tipo de documento NIT
        $documentTypeId = DB::table('document_types')
            ->where('country_id', $countryId)
            ->where('code', 'NIT')
            ->value('id');

        if (!$documentTypeId) {
            throw new \RuntimeException('No existe el tipo de documento NIT.');
        }

        DB::table('companies')->updateOrInsert(
            [
                'document_type_id' => $documentTypeId,
                'document_number'  => '901362908-3',
            ],
            [
                'legal_name' => 'DISTRIAVICOLA SOFRAQ SAS',
                'trade_name' => 'SOFRAQ',
                'email'      => 'distrisofraq@gmail.com',
                'phone'      => '3132106246',
                'website'    => null,
                'logo_path'  => null,
                'is_active'  => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]
        );
    }
}
