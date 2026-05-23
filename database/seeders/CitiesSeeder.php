<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : CitiesSeeder.php
 * Función             : Insertar ciudades y municipios principales de Colombia
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * Obtenemos los estados indexados por código
         * Ej: ['ANT' => 1, 'CUN' => 5, ...]
         */
        $states = DB::table('states')->pluck('id', 'code');

        if ($states->isEmpty()) {
            throw new \RuntimeException(
                'No existen estados en la tabla states. Ejecuta StatesSeeder primero.'
            );
        }

        /**
         * Ciudades principales de Colombia
         * (puedes ampliar este arreglo cuando quieras)
         */
        $cities = [
            // Bogotá D.C.
            ['state' => 'BOG', 'name' => 'Bogotá D.C.', 'dane' => '11001'],

            // Antioquia
            ['state' => 'ANT', 'name' => 'Medellín',  'dane' => '05001'],
            ['state' => 'ANT', 'name' => 'Envigado',  'dane' => '05266'],
            ['state' => 'ANT', 'name' => 'Bello',     'dane' => '05088'],
            ['state' => 'ANT', 'name' => 'Itagüí',    'dane' => '05360'],

            // Valle del Cauca
            ['state' => 'VAC', 'name' => 'Cali',      'dane' => '76001'],
            ['state' => 'VAC', 'name' => 'Palmira',   'dane' => '76520'],
            ['state' => 'VAC', 'name' => 'Buenaventura', 'dane' => '76109'],

            // Atlántico
            ['state' => 'ATL', 'name' => 'Barranquilla', 'dane' => '08001'],
            ['state' => 'ATL', 'name' => 'Soledad',      'dane' => '08758'],

            // Bolívar
            ['state' => 'BOL', 'name' => 'Cartagena', 'dane' => '13001'],

            // Santander
            ['state' => 'SAN', 'name' => 'Bucaramanga', 'dane' => '68001'],
            ['state' => 'SAN', 'name' => 'Floridablanca', 'dane' => '68276'],

            // Norte de Santander
            ['state' => 'NSA', 'name' => 'Cúcuta',          'dane' => '54001'],
            ['state' => 'NSA', 'name' => 'Ocaña',            'dane' => '54498'],
            ['state' => 'NSA', 'name' => 'Pamplona',         'dane' => '54518'],
            ['state' => 'NSA', 'name' => 'Villa del Rosario', 'dane' => '54874'],

            // Cundinamarca
            ['state' => 'CUN', 'name' => 'Soacha',    'dane' => '25754'],
            ['state' => 'CUN', 'name' => 'Chía',      'dane' => '25175'],
            ['state' => 'CUN', 'name' => 'Zipaquirá', 'dane' => '25899'],
        ];

        foreach ($cities as $city) {

            // Validar que exista el estado
            if (!isset($states[$city['state']])) {
                continue; // ignora si el estado no existe
            }

            DB::table('cities')->updateOrInsert(
                [
                    'state_id' => $states[$city['state']],
                    'name'     => $city['name'],
                ],
                [
                    'dane_code' => $city['dane'],
                    'is_active' => 1,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]
            );
        }
    }
}
