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


namespace App\Services\Claims;

use App\Models\Claim\SupplierClaim;
use App\Models\Dispatch\DispatchConfirmation;

class CreateSupplierClaimService
{
    public function execute(DispatchConfirmation $confirmation): ?SupplierClaim
    {
        // Si no hay muertos no se crea reclamo
        if ($confirmation->dead_quantity <= 0) {
            return null;
        }

        // Evitar duplicados
        $existingClaim = SupplierClaim::where(
            'dispatch_confirmation_id',
            $confirmation->id
        )->first();

        if ($existingClaim) {
            return $existingClaim;
        }

        // Obtener el proveedor desde la ruta
        $route = $confirmation->route;
        $providerId = $route->provider_id ?? null;

        if (!$providerId) {
            return null;
        }

        // Precio unitario (debes tenerlo en el stop o dispatch)
        $stop = $confirmation->stop;
        $unitPrice = $stop->unit_price ?? 0;

        // Calcular monto del reclamo
        $claimAmount = $confirmation->dead_quantity * $unitPrice;

        // Crear el reclamo
        return SupplierClaim::create([
            'dispatch_confirmation_id' => $confirmation->id,
            'supplier_id' => $providerId,

            'scheduled_quantity' => $confirmation->scheduled_quantity,
            'received_quantity' => $confirmation->received_quantity,
            'dead_quantity' => $confirmation->dead_quantity,

            'unit_price' => $unitPrice,
            'claim_amount' => $claimAmount,

            'status' => SupplierClaim::STATUS_PENDING ?? 'pending',
        ]);
    }
}