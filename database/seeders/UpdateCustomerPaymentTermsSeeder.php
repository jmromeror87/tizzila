<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCustomerPaymentTermsSeeder extends Seeder
{
    public function run(): void
    {
        // plazo_ter → payment_term_id en Tizzila
        // 0 días  → 1 (Contado)
        // 3 días  → 3 (Crédito 15 días)
        // 8 días  → 3 (Crédito 15 días)
        // 12 días → 3 (Crédito 15 días)

        $creditos = [
            '1090485643' => ['term' => 3, 'cupo' => 2000000],
            '1098803063' => ['term' => 3, 'cupo' => 100000],
            '27585821'   => ['term' => 3, 'cupo' => 1000000],
            '27673199'   => ['term' => 3, 'cupo' => 2000000],
            '27789860'   => ['term' => 3, 'cupo' => 1000000],
            '37227720'   => ['term' => 3, 'cupo' => 3000000],
            '37327566'   => ['term' => 3, 'cupo' => 1000000],
            '37329963'   => ['term' => 3, 'cupo' => 1000000],
            '37366974'   => ['term' => 3, 'cupo' => 1000000],
            '37749425'   => ['term' => 3, 'cupo' => 1000000],
            '46646878'   => ['term' => 3, 'cupo' => 1000000],
            '5692214'    => ['term' => 3, 'cupo' => 4000000],
            '60254851'   => ['term' => 3, 'cupo' => 4000000],
            '60319657'   => ['term' => 3, 'cupo' => 2000000],
            '60327410'   => ['term' => 3, 'cupo' => 3000000],
            '63271782'   => ['term' => 3, 'cupo' => 3000000],
            '901182183'  => ['term' => 3, 'cupo' => 5000000],
            '901215885'  => ['term' => 3, 'cupo' => 1000000],
            '901243904'  => ['term' => 3, 'cupo' => 4000000],
            '901250201'  => ['term' => 3, 'cupo' => 4000000],
            '901253669'  => ['term' => 3, 'cupo' => 2000000],
            '901340281'  => ['term' => 3, 'cupo' => 4000000],
            '91040959'   => ['term' => 3, 'cupo' => 4000000],
            '91044851'   => ['term' => 3, 'cupo' => 3000000],
            '91112503'   => ['term' => 3, 'cupo' => 10000000],
            '91202202'   => ['term' => 3, 'cupo' => 2000000],
            '91350642'   => ['term' => 3, 'cupo' => 3000000],
            '91473541'   => ['term' => 3, 'cupo' => 2000000],
            '91487704'   => ['term' => 3, 'cupo' => 500000],
            '91514445'   => ['term' => 3, 'cupo' => 2000000],
        ];

        $actualizados = 0;

        foreach ($creditos as $nit => $data) {
            $rows = DB::table('customers')
                ->where('identification_number', $nit)
                ->update([
                    'payment_term_id' => $data['term'],
                    'credit_limit'    => $data['cupo'],
                ]);
            $actualizados += $rows;
        }

        // Todos los demás → Contado, cupo 0
        DB::table('customers')
            ->whereNull('payment_term_id')
            ->orWhere('payment_term_id', '!=', 3)
            ->update(['payment_term_id' => 1, 'credit_limit' => 0]);

        $this->command->info("Términos actualizados: {$actualizados} con crédito, resto contado.");
    }
}
