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
use App\Models\Invoice\Invoice;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'finanzas', 'comercial', 'gerencia']);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->hasRole(['admin', 'finanzas', 'comercial', 'gerencia']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'finanzas']);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->hasRole(['admin', 'finanzas'])
            && $invoice->payment_status !== 'paid';
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->isAdmin();
    }
}
