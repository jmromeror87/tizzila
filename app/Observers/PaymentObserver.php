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


namespace App\Observers;

use App\Models\Invoice\InvoicePayment;

class PaymentObserver
{
    public function created(InvoicePayment $payment): void
    {
        // 🔒 Evitar duplicidad de lógica
        // La contabilidad ya se ejecuta en el servicio
    }
}