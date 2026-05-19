<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : StatesSeeder.php
 * Función             : Insertar departamentos oficiales de Colombia
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener ID de Colombia
        $countryId = DB::table('countries')
            ->where('code', 'CO')
            ->value('id');

        if (!$countryId) {
            throw new \RuntimeException(
                'No existe el país CO en la tabla countries. Ejecuta CountriesSeeder primero.'
            );
        }

        $states = [
            ['code' => 'ANT', 'name' => 'Antioquia'],
            ['code' => 'ATL', 'name' => 'Atlántico'],
            ['code' => 'BOL', 'name' => 'Bolívar'],
            ['code' => 'BOY', 'name' => 'Boyacá'],
            ['code' => 'CAL', 'name' => 'Caldas'],
            ['code' => 'CAQ', 'name' => 'Caquetá'],
            ['code' => 'CAS', 'name' => 'Casanare'],
            ['code' => 'CAU', 'name' => 'Cauca'],
            ['code' => 'CES', 'name' => 'Cesar'],
            ['code' => 'CHO', 'name' => 'Chocó'],
            ['code' => 'COR', 'name' => 'Córdoba'],
            ['code' => 'CUN', 'name' => 'Cundinamarca'],
            ['code' => 'BOG', 'name' => 'Bogotá D.C.'],
            ['code' => 'GUA', 'name' => 'Guainía'],
            ['code' => 'GUV', 'name' => 'Guaviare'],
            ['code' => 'HUI', 'name' => 'Huila'],
            ['code' => 'LAG', 'name' => 'La Guajira'],
            ['code' => 'MAG', 'name' => 'Magdalena'],
            ['code' => 'MET', 'name' => 'Meta'],
            ['code' => 'NAR', 'name' => 'Nariño'],
            ['code' => 'NSA', 'name' => 'Norte de Santander'],
            ['code' => 'PUT', 'name' => 'Putumayo'],
            ['code' => 'QUI', 'name' => 'Quindío'],
            ['code' => 'RIS', 'name' => 'Risaralda'],
            ['code' => 'SAP', 'name' => 'San Andrés y Providencia'],
            ['code' => 'SAN', 'name' => 'Santander'],
            ['code' => 'SUC', 'name' => 'Sucre'],
            ['code' => 'TOL', 'name' => 'Tolima'],
            ['code' => 'VAC', 'name' => 'Valle del Cauca'],
            ['code' => 'VAU', 'name' => 'Vaupés'],
            ['code' => 'VID', 'name' => 'Vichada'],
        ];

        foreach ($states as $state) {
            DB::table('states')->updateOrInsert(
                [
                    'country_id' => $countryId,
                    'code'       => $state['code'],
                ],
                [
                    'name'       => $state['name'],
                    'is_active'  => 1,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]
            );
        }
    }
}
