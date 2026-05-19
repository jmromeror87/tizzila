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
use App\Models\Invoice\InvoicePayment;

class InvoicePaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'finanzas', 'gerencia']);
    }

    public function view(User $user, InvoicePayment $payment): bool
    {
        return $user->hasRole(['admin', 'finanzas', 'gerencia']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'finanzas']);
    }

    public function delete(User $user, InvoicePayment $payment): bool
    {
        return $user->isAdmin();
    }
}
