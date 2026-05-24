<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Seeder: Terceros de Gastos — Enero a Mayo 2026
| Fuente: Excel de Gerencia DISTRIAVICOLA SOFRAQ SAS
| Autor: Jhoan Romero Rivera — Mayo 2026
|
| LEYENDA:
|   ✅ NIT CONFIRMADO  — verificado en RUES / Portafolio / Supersociedades
|   ⚠️  PENDIENTE      — reemplazar con NIT/CC real antes de producción
|--------------------------------------------------------------------------
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GastosProvidersSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $providers = [

            // ════════════════════════════════════════════════════════
            // GRUPO 1 · SEGURIDAD SOCIAL
            // ════════════════════════════════════════════════════════

            // ⚠️  CC Lina Cabrales: solicitar cédula física o desprendible de nómina
            [
                'tax_id'                   => 'PENDIENTE-SS-001',
                'tax_id_type'              => 'CC',
                'business_name'            => 'LINA CABRALES',
                'trade_name'               => 'Seguridad Social',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],

            // ════════════════════════════════════════════════════════
            // GRUPO 2 · PEAJES
            // Pago en taquilla (efectivo). Para soporte fiscal pedir
            // facturación consolidada a la concesionaria vial.
            // ════════════════════════════════════════════════════════

            // ⚠️  Peajes: NIT de la concesionaria — buscar en RUES
            [
                'tax_id'                   => 'PENDIENTE-PJ-001',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PEAJE MORRISON',
                'trade_name'               => 'Peaje Vía Bucaramanga-Lebrija km 6',
                'city'                     => 'Lebrija',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            [
                'tax_id'                   => 'PENDIENTE-PJ-002',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PEAJE PLATANAL',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            [
                'tax_id'                   => 'PENDIENTE-PJ-003',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PEAJE LA GÓMEZ',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            [
                'tax_id'                   => 'PENDIENTE-PJ-004',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PEAJE LEBRIJA',
                'city'                     => 'Lebrija',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            [
                'tax_id'                   => 'PENDIENTE-PJ-005',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PEAJE PICACHO',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            [
                'tax_id'                   => 'PENDIENTE-PJ-006',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PEAJE PAMPLONA',
                'city'                     => 'Pamplona',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            [
                'tax_id'                   => 'PENDIENTE-PJ-007',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PEAJE LOS ACACIOS',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],

            // ════════════════════════════════════════════════════════
            // GRUPO 3 · PARQUEADEROS
            // ════════════════════════════════════════════════════════

            // ⚠️  NIT pendiente — buscar en RUES Girón / Santander
            [
                'tax_id'                   => 'PENDIENTE-PQ-001',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'CENTRO DE SERVICIO AUTOMOTRIZ SOBRE RUEDAS SAS',
                'trade_name'               => 'Sobreruedas',
                'address_line'             => 'CARRERA 34 36 05 BARRIO ALDEA ALTA',
                'city'                     => 'Girón',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'phone'                    => '3178865133',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES Ocaña
            [
                'tax_id'                   => 'PENDIENTE-PQ-002',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PARQUEADERO LA PLAZA OCAÑA',
                'city'                     => 'Ocaña',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 900308801  Fuente: Portafolio.co
            [
                'tax_id'                   => '900308801',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'UNISAN SAS',
                'trade_name'               => 'Parqueadero Unisan BGA',
                'address_line'             => 'CALLE 42 29 65 PISO 5',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'phone'                    => '6076973754',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar "SOCIEDAD DE PARQUEOS SAS" en RUES
            [
                'tax_id'                   => 'PENDIENTE-PQ-003',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'SOCIEDAD DE PARQUEOS SAS',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES Cúcuta
            [
                'tax_id'                   => 'PENDIENTE-PQ-004',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PARQUEADERO CC VENTURA PLAZA CÚCUTA',
                'address_line'             => 'AV LIBERTADORES CC VENTURA PLAZA',
                'city'                     => 'Cúcuta',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — parqueadero pequeño BGA
            [
                'tax_id'                   => 'PENDIENTE-PQ-005',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PARQUEADERO EQUIPOS DE SISTEMAS BGA',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 901587449  Fuente: Portafolio.co / larepublica.co
            [
                'tax_id'                   => '901587449',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'MENSULI CAPITAL SAS',
                'trade_name'               => 'Parqueadero Mensuli Capital',
                'address_line'             => 'LUGAR TZ DE MENZULY KM 7',
                'city'                     => 'Piedecuesta',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'phone'                    => '3134801170',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  Persona natural — NIT pendiente (Parqueadero del Pollito)
            [
                'tax_id'                   => 'PENDIENTE-PQ-006',
                'tax_id_type'              => 'CC',
                'business_name'            => 'JOSE LUIS CARVAJALINO',
                'trade_name'               => 'Parqueadero del Pollito',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 30,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — Ruitoque Garden
            [
                'tax_id'                   => 'PENDIENTE-PQ-007',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'RUITOQUE GARDEN PARQUEADERO',
                'city'                     => 'Floridablanca',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],

            // ════════════════════════════════════════════════════════
            // GRUPO 4 · RESTAURANTES Y ALIMENTACIÓN
            // ════════════════════════════════════════════════════════

            // ✅ CC confirmado en descripción del gasto
            [
                'tax_id'                   => '37325055',
                'tax_id_type'              => 'CC',
                'business_name'            => 'MAUREN NUMA MONTAÑO',
                'trade_name'               => 'Restaurante Mauren',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  Persona natural — solicitar cédula (La Cuesta)
            [
                'tax_id'                   => 'PENDIENTE-RS-001',
                'tax_id_type'              => 'CC',
                'business_name'            => 'FRANCY ELENA PALACIO',
                'trade_name'               => 'Restaurante La Cuesta',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar "TRATTORIA MATERA" en RUES BGA
            [
                'tax_id'                   => 'PENDIENTE-RS-002',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'TRATTORIA MATERA SAS',
                'trade_name'               => 'Restaurante Trattoria Matera',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 901233167  Fuente: RUES / Portafolio.co
            [
                'tax_id'                   => '901233167',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'LA NOTA COLOMBIA SAS',
                'trade_name'               => 'Restaurante La Nota',
                'address_line'             => 'AVENIDA LIBERTADORES 7A 50',
                'city'                     => 'Cúcuta',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'phone'                    => '3173709311',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 901751857  Fuente: RUES / empresas.larepublica.co
            [
                'tax_id'                   => '901751857',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PLATANO MR VERDES SAS',
                'trade_name'               => 'Plátano Mr Verdes',
                'address_line'             => 'AVENIDA 1E 17 25',
                'city'                     => 'Cúcuta',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'phone'                    => '3245037280',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 900278802  Fuente: RUES / empresas.larepublica.co
            [
                'tax_id'                   => '900278802',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'INVERSIONES CINNAMON GOURMET SA',
                'trade_name'               => 'Cinnamon Gourmet',
                'address_line'             => 'CC LA QUINTA 1A FASE PISO 5 LOC 505 CABECERA DEL LLANO',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'phone'                    => '6076380610',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES
            [
                'tax_id'                   => 'PENDIENTE-RS-003',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'RESTAURANTE VILLA BARBACOA',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES Ocaña
            [
                'tax_id'                   => 'PENDIENTE-RS-004',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'RESTAURANTE LA PROVINCIA OCAÑA',
                'city'                     => 'Ocaña',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar "BAKERY COFFEE" en RUES Cúcuta
            [
                'tax_id'                   => 'PENDIENTE-RS-005',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'BAKERY & COFFEE BELÉN CÚCUTA',
                'city'                     => 'Cúcuta',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES BGA
            [
                'tax_id'                   => 'PENDIENTE-RS-006',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'PANADERIA ARTESANAL LA BRIOCHE',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 900403670  Fuente: Supersociedades / ImportKey
            [
                'tax_id'                   => '900403670',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'INVERSISA SAS',
                'trade_name'               => 'MrBono – Pan de Bono',
                'address_line'             => 'AV CIRCUNVALAR CL 110 3 79 BG 19 PARQUE INDUSTRIAL EUROPARK',
                'city'                     => 'Barranquilla',
                'department'               => 'Atlántico',
                'country'                  => 'Colombia',
                'phone'                    => '3005533849',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — restaurante BGA (Montañas Azules)
            [
                'tax_id'                   => 'PENDIENTE-RS-007',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'RESTAURANTE MONTAÑAS AZULES HI',
                'city'                     => 'Floridablanca',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES Cúcuta (Grupo G3)
            [
                'tax_id'                   => 'PENDIENTE-RS-008',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'RESTAURANTE INVERSIONES GRUPO G3',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — pizzería
            [
                'tax_id'                   => 'PENDIENTE-RS-009',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'DRIVE PIZZA',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES Ocaña
            [
                'tax_id'                   => 'PENDIENTE-RS-010',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'RESTAURANTE MATTIA OCAÑA',
                'city'                     => 'Ocaña',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES
            [
                'tax_id'                   => 'PENDIENTE-RS-011',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'MORDISCOS EMPANADAS AL HORNO SAS',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 860076919  Fuente: Cámara de Comercio / Supersociedades
            [
                'tax_id'                   => '860076919',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'CREPES Y WAFFLES SA',
                'trade_name'               => 'Crepes & Waffles',
                'address_line'             => 'CARRERA 9 73 41 PISO 4',
                'city'                     => 'Bogotá D.C.',
                'department'               => 'Bogotá D.C.',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],

            // ════════════════════════════════════════════════════════
            // GRUPO 5 · COMBUSTIBLES, PAPELERÍA Y COMERCIO
            // ════════════════════════════════════════════════════════

            // ✅ NIT: 901587571  Fuente: tusdatos.co / larepublica.co
            [
                'tax_id'                   => '901587571',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'EL NUEVO PUNTO PAISA SAS',
                'trade_name'               => 'El Nuevo Punto Paisa',
                'address_line'             => 'CALLE 11 10-28',
                'city'                     => 'Ocaña',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'phone'                    => '3046264293',
                'email'                    => 'servicioalcliente@elnuevopunto.com',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 900808763  Fuente: Portafolio.co (NIT sin DV)
            [
                'tax_id'                   => '900808763',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'ARMASIL DISTRIBUCIONES SAS',
                'trade_name'               => 'EDS Armasil Anillo Vial',
                'address_line'             => 'VIA RIO FRIO 25 01 ACCESO EDS ANILLO VIAL',
                'city'                     => 'Floridablanca',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'phone'                    => '6076790384',
                'payment_terms_days'       => 30,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 900459737  Fuente: Supersociedades
            [
                'tax_id'                   => '900459737',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'GRUPO EDS AUTOGAS SAS',
                'trade_name'               => 'EDS Autogas Morrison',
                'address_line'             => 'CARRERA 14 99 33 PISO 9',
                'city'                     => 'Bogotá D.C.',
                'department'               => 'Bogotá D.C.',
                'country'                  => 'Colombia',
                'phone'                    => '6017443539',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — buscar en RUES Cúcuta / Los Patios
            [
                'tax_id'                   => 'PENDIENTE-CM-001',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'COMERCIALIZADORA DE COMBUSTIBLES PINAR DEL RIO SAS',
                'trade_name'               => 'EDS Pinar del Río',
                'address_line'             => 'AVENIDA 10 61 60 LOTE 1 BARRIO PINAR DEL RIO',
                'city'                     => 'Los Patios',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 860053831  Fuente: Supersociedades
            [
                'tax_id'                   => '860053831',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'COMERCIAL ALLAN SAS',
                'trade_name'               => 'Comercial Allan',
                'address_line'             => 'AUTOPISTA MEDELLÍN KM 1.8 COSTADO SUR PARQUE SOKO IND BG 4',
                'city'                     => 'Cota',
                'department'               => 'Cundinamarca',
                'country'                  => 'Colombia',
                'email'                    => 'impuestos@heladospopsy.com',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT del concesionario local pendiente — buscar en RUES Cúcuta
            [
                'tax_id'                   => 'PENDIENTE-CM-002',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'O BOTICÁRIO CÚCUTA',
                'trade_name'               => 'O Boticário – CC Jardín Plaza',
                'city'                     => 'Cúcuta',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 900433078  Fuente: victoriassecretbeauty.co (oficial)
            [
                'tax_id'                   => '900433078',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'ANGEL´S GROUP SAS',
                'trade_name'               => 'Regalos / Victoria\'s Secret Colombia',
                'address_line'             => 'CARRERA 14 81-19 OFICINA 201',
                'city'                     => 'Bogotá D.C.',
                'department'               => 'Bogotá D.C.',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 800091124  Fuente: Portafolio.co / Cámara Oficial
            [
                'tax_id'                   => '800091124',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'CAMARA DE COMERCIO DE OCAÑA',
                'address_line'             => 'CALLE 11 15 33 EDIFICIO CAMARA DE COMERCIO',
                'city'                     => 'Ocaña',
                'department'               => 'Norte de Santander',
                'country'                  => 'Colombia',
                'phone'                    => '6075626105',
                'email'                    => 'juridica@camaraocana.com',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ✅ NIT: 890900943  Fuente: Supersociedades (Alkosto)
            [
                'tax_id'                   => '890900943',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'COLOMBIANA DE COMERCIO SA',
                'trade_name'               => 'Alkosto',
                'address_line'             => 'CARRERA 68 C 23 A 71',
                'city'                     => 'Bogotá D.C.',
                'department'               => 'Bogotá D.C.',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],

            // ════════════════════════════════════════════════════════
            // GRUPO 6 · SERVICIOS TECNOLÓGICOS
            // ════════════════════════════════════════════════════════

            // ✅ NIT: 805029424  Fuente: Alkosto / McAfee Colombia
            [
                'tax_id'                   => 'PENDIENTE-TEC-001',
                'tax_id_type'              => 'NIT',
                'business_name'            => 'MCAFEE COLOMBIA',
                'trade_name'               => 'McAfee Antivirus',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  NIT pendiente — lavadero de autos
            [
                'tax_id'                   => 'PENDIENTE-SV-001',
                'tax_id_type'              => 'CC',
                'business_name'            => 'HENRY OMAR MARIN TRUJILLO',
                'trade_name'               => 'Lavadero Calle 200',
                'city'                     => 'Bucaramanga',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'cash',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],

            // ════════════════════════════════════════════════════════
            // GRUPO 7 · PERSONAS NATURALES — HONORARIOS Y SERVICIOS
            // ════════════════════════════════════════════════════════

            // ⚠️  CC pendiente — solicitar cédula (Marcado de cajas)
            [
                'tax_id'                   => 'PENDIENTE-PN-001',
                'tax_id_type'              => 'CC',
                'business_name'            => 'ROSMARY GOMEZ',
                'trade_name'               => 'Marcado de Cajas – Girón',
                'city'                     => 'Girón',
                'department'               => 'Santander',
                'country'                  => 'Colombia',
                'phone'                    => '3177724405',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  CC pendiente — solicitar cédula (Flete Pollito)
            [
                'tax_id'                   => 'PENDIENTE-PN-002',
                'tax_id_type'              => 'CC',
                'business_name'            => 'VICTORIA PEREZ',
                'trade_name'               => 'Flete Pollito',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  CC pendiente — solicitar cédula (Flete Pollito)
            [
                'tax_id'                   => 'PENDIENTE-PN-003',
                'tax_id_type'              => 'CC',
                'business_name'            => 'MONICA ALVIDIADES',
                'trade_name'               => 'Flete Pollito',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 0,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  CC pendiente — Contadora (honorarios + paquete cartera)
            [
                'tax_id'                   => 'PENDIENTE-PN-004',
                'tax_id_type'              => 'CC',
                'business_name'            => 'ALEXANDRA ZAMBRANO',
                'trade_name'               => 'Contadora Honorarios',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 30,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],
            // ⚠️  CC pendiente — Revisor Fiscal (confirmar nombre completo)
            [
                'tax_id'                   => 'PENDIENTE-PN-005',
                'tax_id_type'              => 'CC',
                'business_name'            => 'REVISOR FISCAL',
                'trade_name'               => 'Honorarios Revisor Fiscal',
                'country'                  => 'Colombia',
                'payment_terms_days'       => 30,
                'preferred_payment_method' => 'transfer',
                'status'                   => 'active',
                'provider_type'            => 'expense',
            ],

        ];

        // ── Rellenar campos opcionales con null para evitar errores ──
        $defaults = [
            'trade_name'               => null,
            'address_line'             => null,
            'city'                     => null,
            'department'               => null,
            'postal_code'              => null,
            'phone'                    => null,
            'email'                    => null,
            'contacts'                 => null,
            'payment_conditions'       => null,
            'created_at'               => $now,
            'updated_at'               => $now,
        ];

        $inserted = 0;
        $skipped  = 0;

        foreach ($providers as $data) {
            $row = array_merge($defaults, $data);

            // Evitar duplicados si se corre el seeder más de una vez
            $exists = DB::table('providers')
                ->where('tax_id', $row['tax_id'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            DB::table('providers')->insert($row);
            $inserted++;
        }

        $this->command->info("✅ Terceros de gastos: {$inserted} insertados, {$skipped} ya existían.");
        $this->command->warn("⚠️  Recuerda reemplazar los PENDIENTE-XXX con el NIT/CC real.");
    }
}
