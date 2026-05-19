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


namespace App\Policies;

use App\Models\User;
use App\Models\Claim\SupplierClaim;

class SupplierClaimPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'operaciones', 'finanzas', 'gerencia']);
    }

    public function view(User $user, SupplierClaim $claim): bool
    {
        return $user->hasRole(['admin', 'operaciones', 'finanzas', 'gerencia']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'operaciones']);
    }

    public function submit(User $user, SupplierClaim $claim): bool
    {
        return $user->hasRole(['admin', 'operaciones'])
            && $claim->status === SupplierClaim::STATUS_PENDING;
    }

    public function resolve(User $user, SupplierClaim $claim): bool
    {
        return $user->hasRole(['admin', 'finanzas'])
            && $claim->status === SupplierClaim::STATUS_SUBMITTED;
    }
}
