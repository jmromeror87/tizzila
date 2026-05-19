<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : DocumentTypesSeeder.php
 * Función             : Insertar tipos de documento oficiales para Colombia (CC, TI, CE, NIT, PAS)
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el ID del país Colombia
        $countryId = DB::table('countries')
            ->where('code', 'CO')
            ->value('id');

        if (!$countryId) {
            throw new \RuntimeException(
                'No existe el país CO en la tabla countries. Ejecuta CountriesSeeder primero.'
            );
        }

        $documentTypes = [
            [
                'code'          => 'CC',
                'name'          => 'Cédula de Ciudadanía',
                'applies_to'    => 'person',
                'length_min'    => 6,
                'length_max'    => 10,
                'numeric_only'  => 1,
            ],
            [
                'code'          => 'TI',
                'name'          => 'Tarjeta de Identidad',
                'applies_to'    => 'person',
                'length_min'    => 6,
                'length_max'    => 11,
                'numeric_only'  => 1,
            ],
            [
                'code'          => 'CE',
                'name'          => 'Cédula de Extranjería',
                'applies_to'    => 'person',
                'length_min'    => 6,
                'length_max'    => 12,
                'numeric_only'  => 0,
            ],
            [
                'code'          => 'NIT',
                'name'          => 'Número de Identificación Tributaria',
                'applies_to'    => 'company',
                'length_min'    => 9,
                'length_max'    => 11,
                'numeric_only'  => 1,
            ],
            [
                'code'          => 'PAS',
                'name'          => 'Pasaporte',
                'applies_to'    => 'both',
                'length_min'    => 6,
                'length_max'    => 12,
                'numeric_only'  => 0,
            ],
        ];

        foreach ($documentTypes as $doc) {
            DB::table('document_types')->updateOrInsert(
                [
                    'country_id' => $countryId,
                    'code'       => $doc['code'],
                ],
                [
                    'name'         => $doc['name'],
                    'applies_to'   => $doc['applies_to'],
                    'length_min'   => $doc['length_min'],
                    'length_max'   => $doc['length_max'],
                    'numeric_only' => $doc['numeric_only'],
                    'is_active'    => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }
    }
}
