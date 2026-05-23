<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PoultryOrderSchedule2026Seeder extends Seeder
{
    // Días de pago por tipo (poultry_type_id)
    const PAYMENT_DAYS = [
        1 => 15, // Pollito BB
        2 => 30, // Lohmann LSL
        3 => 30, // Lohmann Brown
    ];

    // Programación 2026 confirmada por PRONAVICOLA - DISTRIAVICOLA SOFRAQ/LINA CABRALES
    // [fecha, pollito_bb, lsl, lohmann_brown]
    const SCHEDULE = [
        // ENERO
        ['2026-01-08', 33300,    0,     0],
        ['2026-01-12', 37000,    0,  6000],
        ['2026-01-15', 49500,    0,  3700],
        ['2026-01-19', 46000,    0,  5000],
        ['2026-01-22', 42000, 3000,     0],
        ['2026-01-26', 38000,    0,     0],
        ['2026-01-29', 45500, 1000,     0],
        // FEBRERO
        ['2026-02-02', 33000,    0,     0],
        ['2026-02-05', 30000,    0,     0],
        ['2026-02-09', 35000,    0, 12000],
        ['2026-02-12', 28400,    0,     0],
        ['2026-02-16', 42000,    0,  8000],
        ['2026-02-19', 25800,    0,     0],
        ['2026-02-23', 47800,    0,  5500],
        ['2026-02-26', 37100,    0,     0],
        // MARZO
        ['2026-03-02', 30000,    0,     0],
        ['2026-03-05', 28000,    0,     0],
        ['2026-03-09', 27000, 6000,  6000],
        ['2026-03-12', 28000,    0,     0],
        ['2026-03-16', 26000,    0,     0],
        ['2026-03-19', 28000,    0,     0],
        ['2026-03-23', 31000,    0,  5000],
        ['2026-03-26', 30000, 2000,     0],
        ['2026-03-30', 32000,    0,     0],
        // ABRIL (Jueves 02/04 NO CARGAR - Jueves Santo)
        ['2026-04-06', 33000,    0,     0],
        ['2026-04-09', 27000,    0,     0],
        ['2026-04-13', 32000, 4000,  7000],
        ['2026-04-16', 32000,    0,     0],
        ['2026-04-20', 30000,    0,     0],
        ['2026-04-23', 33000,    0,     0],
        ['2026-04-27', 28000,    0,  6000],
        ['2026-04-30', 32000,    0,     0],
        // MAYO
        ['2026-05-04', 33000,    0,     0],
        ['2026-05-07', 33000, 3000,  2000],
        ['2026-05-11', 30000,    0,     0],
        ['2026-05-14', 32000,    0,     0],
        ['2026-05-18', 30000,    0,  6000],
        ['2026-05-21', 33000,    0,     0],
        ['2026-05-25', 32000, 4000,  5000],
        ['2026-05-28', 31000,    0,     0],
        // JUNIO
        ['2026-06-01', 28000,    0,     0],
        ['2026-06-04', 28000,    0,     0],
        ['2026-06-08', 27000,    0,  5000],
        ['2026-06-11', 26000, 4000,     0],
        ['2026-06-15', 28000,    0,     0],
        ['2026-06-18', 30000,    0,     0],
        ['2026-06-22', 30000,    0,  7000],
        ['2026-06-25', 30000, 2000,     0],
        ['2026-06-29', 28000,    0,     0],
        // JULIO
        ['2026-07-02', 30000,    0,     0],
        ['2026-07-06', 27000,    0,  6000],
        ['2026-07-09', 29000,    0,     0],
        ['2026-07-13', 28000, 6000,     0],
        ['2026-07-16', 26000,    0,     0],
        ['2026-07-20', 28000,    0,  5000],
        ['2026-07-23', 28000,    0,     0],
        ['2026-07-27', 28000,    0,     0],
        ['2026-07-30', 28000,    0,  1000],
        // AGOSTO
        ['2026-08-03', 29000,    0,     0],
        ['2026-08-06', 28000, 3000,     0],
        ['2026-08-10', 32000,    0,  5000],
        ['2026-08-13', 28000,    0,     0],
        ['2026-08-17', 28000,    0,     0],
        ['2026-08-20', 33000,    0,     0],
        ['2026-08-24', 28000, 3000,  6000],
        ['2026-08-27', 28000,    0,     0],
        ['2026-08-31', 31000,    0,  2500],
        // SEPTIEMBRE
        ['2026-09-03', 32000,    0,     0],
        ['2026-09-07', 29000,    0,  8000],
        ['2026-09-10', 31000,    0,     0],
        ['2026-09-14', 30000, 6000,     0],
        ['2026-09-17', 33000,    0,     0],
        ['2026-09-21', 30000,    0,  8000],
        ['2026-09-24', 33000,    0,     0],
        ['2026-09-28', 30000,    0,     0],
        // OCTUBRE
        ['2026-10-01', 33000,    0,     0],
        ['2026-10-05', 31000,    0,  7000],
        ['2026-10-08', 32000, 2000,     0],
        ['2026-10-12', 33000,    0,     0],
        ['2026-10-15', 31000,    0,     0],
        ['2026-10-19', 29000, 5000,  3000],
        ['2026-10-22', 31000,    0,     0],
        ['2026-10-26', 29000,    0,  5000],
        ['2026-10-29', 28000,    0,     0],
        // NOVIEMBRE
        ['2026-11-02', 33000,    0,     0],
        ['2026-11-05', 30000,    0,     0],
        ['2026-11-09', 31000,    0,  7000],
        ['2026-11-12', 33000,    0,     0],
        ['2026-11-16', 29000, 7000,     0],
        ['2026-11-19', 28000,    0,     0],
        ['2026-11-23', 31000,    0,  8000],
        ['2026-11-26', 30000,    0,     0],
        ['2026-11-30', 30000,    0,     0],
        // DICIEMBRE
        ['2026-12-01', 38000,    0,  4000],
        ['2026-12-03', 37000, 4000,     0],
        ['2026-12-07', 35000,    0,  4000],
        ['2026-12-10', 30000,    0,     0],
        ['2026-12-14', 27000,    0,     0],
        ['2026-12-17', 28000,    0,     0],
    ];

    public function run(): void
    {
        // 1. Crear o actualizar proveedor PRONAVICOLA
        $providerId = $this->upsertProvider();
        $this->command->info("✓ Proveedor PRONAVICOLA id={$providerId}");

        // 2. Eliminar solo pedidos de PRONAVICOLA sin despachos asociados
        //    Los pedidos con despacho son históricos y no se tocan
        $withDispatch = DB::table('poultry_dispatches')
            ->distinct()
            ->pluck('poultry_order_schedule_id')
            ->toArray();

        $deleted = DB::table('poultry_order_schedules')
            ->where('provider_id', $providerId)
            ->when(!empty($withDispatch), fn($q) => $q->whereNotIn('id', $withDispatch))
            ->delete();
        $this->command->info("✓ {$deleted} pedidos anteriores eliminados (se conservan " . count($withDispatch) . " con despachos reales)");

        // 3. Insertar programación 2026 del Excel de gerencia
        $rows = [];
        $now  = now();

        foreach (self::SCHEDULE as [$date, $bb, $lsl, $lohmann]) {
            $dispatch = Carbon::parse($date);

            if ($bb > 0) {
                $rows[] = $this->row($dispatch, $providerId, 1, 'bb', $bb, $now);
            }
            if ($lsl > 0) {
                $rows[] = $this->row($dispatch, $providerId, 2, 'lsl', $lsl, $now);
            }
            if ($lohmann > 0) {
                $rows[] = $this->row($dispatch, $providerId, 3, 'lohmann', $lohmann, $now);
            }
        }

        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('poultry_order_schedules')->insert($chunk);
        }

        $this->command->info('✓ ' . count($rows) . ' pedidos 2026 cargados (BB: ' .
            array_sum(array_column(array_filter($rows, fn($r) => $r['poultry_type'] === 'bb'), 'quantity')) . ' | LSL: ' .
            array_sum(array_column(array_filter($rows, fn($r) => $r['poultry_type'] === 'lsl'), 'quantity')) . ' | Lohmann: ' .
            array_sum(array_column(array_filter($rows, fn($r) => $r['poultry_type'] === 'lohmann'), 'quantity')) . ')');
    }

    private function upsertProvider(): int
    {
        $taxId = '8903212139'; // NIT 890321213 DV 9

        $existing = DB::table('providers')->where('tax_id', $taxId)->first();

        $data = [
            'tax_id'                   => $taxId,
            'tax_id_type'              => 'NIT',
            'business_name'            => 'PRODUCTORA NACIONAL AVICOLA S.A.S',
            'trade_name'               => 'PRONAVICOLA',
            'address_line'             => 'MEDIACANOA KM 8 VIA BUGA BUENAVENTURA',
            'city'                     => 'MEDIACANOA',
            'department'               => 'VALLE DEL CAUCA',
            'country'                  => 'CO',
            'phone'                    => '6022374242',
            'email'                    => 'info@pronavicola.com',
            'payment_terms_days'       => 15,
            'preferred_payment_method' => 'transfer',
            'status'                   => 'active',
            'provider_type'            => 'poultry',
            'updated_at'               => now(),
        ];

        if ($existing) {
            DB::table('providers')->where('tax_id', $taxId)->update($data);
            return $existing->id;
        }

        $data['created_at'] = now();
        return DB::table('providers')->insertGetId($data);
    }

    private function row(Carbon $dispatch, int $providerId, int $typeId, string $typeEnum, int $qty, $now): array
    {
        return [
            'provider_id'      => $providerId,
            'poultry_type_id'  => $typeId,
            'poultry_type'     => $typeEnum,
            'quantity'         => $qty,
            'dispatch_date'    => $dispatch->toDateString(),
            'payment_due_date' => $dispatch->copy()->addDays(self::PAYMENT_DAYS[$typeId])->toDateString(),
            'status'           => 'planned',
            'approval_status'  => 'pending',
            'notes'            => 'Programación 2026 - DISTRIAVICOLA SOFRAQ/LINA CABRALES',
            'created_at'       => $now,
            'updated_at'       => $now,
        ];
    }
}
