<?php

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Configuración Global
 * Archivo             : FinancialDocumentTypesSeeder.php
 * Función             : Insertar tipos de documentos financieros y operativos del sistema
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 * ───────────────────────────────────────────────────────────────
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialDocumentTypesSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            // 📈 Ventas
            [
                'code' => 'FV',
                'name' => 'Factura de Venta',
                'category' => 'sales',
                'affects_inventory' => 1,
                'affects_accounting' => 1,
                'sign' => 1,
            ],
            [
                'code' => 'NC',
                'name' => 'Nota Crédito (Venta)',
                'category' => 'sales',
                'affects_inventory' => 1,
                'affects_accounting' => 1,
                'sign' => -1,
            ],
            [
                'code' => 'ND',
                'name' => 'Nota Débito (Venta)',
                'category' => 'sales',
                'affects_inventory' => 1,
                'affects_accounting' => 1,
                'sign' => 1,
            ],
            [
                'code' => 'PC',
                'name' => 'Pedido de Cliente',
                'category' => 'sales',
                'affects_inventory' => 0,
                'affects_accounting' => 0,
                'sign' => 0,
            ],

            // 📉 Compras
            [
                'code' => 'FC',
                'name' => 'Factura de Compra',
                'category' => 'purchases',
                'affects_inventory' => 1,
                'affects_accounting' => 1,
                'sign' => 1,
            ],
            [
                'code' => 'PP',
                'name' => 'Pedido a Proveedor',
                'category' => 'purchases',
                'affects_inventory' => 0,
                'affects_accounting' => 0,
                'sign' => 0,
            ],

            // 📦 Inventario
            [
                'code' => 'REM',
                'name' => 'Remisión',
                'category' => 'inventory',
                'affects_inventory' => 1,
                'affects_accounting' => 0,
                'sign' => 1,
            ],

            // 💰 Tesorería
            [
                'code' => 'RC',
                'name' => 'Recibo de Caja',
                'category' => 'treasury',
                'affects_inventory' => 0,
                'affects_accounting' => 1,
                'sign' => 1,
            ],
            [
                'code' => 'CE',
                'name' => 'Comprobante de Egreso',
                'category' => 'treasury',
                'affects_inventory' => 0,
                'affects_accounting' => 1,
                'sign' => -1,
            ],
        ];

        foreach ($documents as $doc) {
            DB::table('financial_document_types')->updateOrInsert(
                ['code' => $doc['code']],
                [
                    'name' => $doc['name'],
                    'category' => $doc['category'],
                    'affects_inventory' => $doc['affects_inventory'],
                    'affects_accounting' => $doc['affects_accounting'],
                    'sign' => $doc['sign'],
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
