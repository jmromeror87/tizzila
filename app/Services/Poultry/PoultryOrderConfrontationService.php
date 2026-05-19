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


namespace App\Services\Poultry;

use App\Models\Poultry\PoultryOrderSchedule;
use App\Models\Poultry\PoultryProviderDocumentBatch;
use Carbon\Carbon;

class PoultryOrderConfrontationService
{
    public function confront(PoultryOrderSchedule $order): array
    {
        $programmedQty = (int) $order->quantity;

        // 🔒 Validación defensiva
        if (!$order->poultry_type_id) {
            return [
                'status'  => 'error',
                'message' => 'El pedido no tiene tipo de ave definido.',
                'summary' => [
                    'programmed' => $programmedQty,
                    'approved'   => 0,
                    'percentage' => 0,
                ],
                'issues'  => ['Tipo de ave no definido en el pedido'],
                'timestamp' => now(),
            ];
        }

        // 🔎 Buscar cantidad aprobada real
        $approvedQty = PoultryProviderDocumentBatch::query()
            ->whereHas('document', function ($q) use ($order) {
                $q->where('provider_id', $order->provider_id);
            })
            ->whereDate('delivery_date', Carbon::parse($order->dispatch_date)->toDateString())
            ->where('poultry_type_id', $order->poultry_type_id)
            ->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | CASOS BASE
        |--------------------------------------------------------------------------
        */

        if ($approvedQty === 0) {
            return $this->buildResponse(
                'pending',
                'El proveedor aún no ha aprobado cantidad para esta fecha',
                $programmedQty,
                0
            );
        }

        if ($programmedQty === 0) {
            return $this->buildResponse(
                'no_programmed',
                'No hay cantidad programada para esta fecha',
                0,
                $approvedQty
            );
        }

        /*
        |--------------------------------------------------------------------------
        | COMPARACIÓN
        |--------------------------------------------------------------------------
        */

        $percentage = round(($approvedQty / $programmedQty) * 100, 2);

        $issues = [];

        if ($approvedQty < $programmedQty) {
            $issues[] = "Aprobación parcial: {$approvedQty} de {$programmedQty}";
        }

        if ($approvedQty > $programmedQty) {
            $issues[] = "Aprobación excedida: {$approvedQty} sobre {$programmedQty}";
        }

        $status = empty($issues) ? 'approved' : 'under_review';

        return [
            'status'     => $status,
            'message'    => $this->messageFor($status),
            'summary'    => [
                'programmed' => $programmedQty,
                'approved'   => $approvedQty,
                'percentage' => $percentage,
            ],
            'issues'     => $issues,
            'timestamp'  => now(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function buildResponse(
        string $status,
        string $message,
        int $programmed,
        int $approved
    ): array {
        return [
            'status'    => $status,
            'message'   => $message,
            'summary'   => [
                'programmed' => $programmed,
                'approved'   => $approved,
                'percentage' => $programmed > 0
                    ? round(($approved / $programmed) * 100, 2)
                    : 0,
            ],
            'issues'    => [],
            'timestamp' => now(),
        ];
    }

    protected function messageFor(string $status): string
    {
        return match ($status) {
            'approved'      => 'Pedido aprobado en su totalidad por el proveedor',
            'under_review'  => 'Pedido aprobado parcialmente por el proveedor',
            'no_programmed' => 'No hay programación registrada',
            default         => 'Pedido pendiente de aprobación',
        };
    }
}
