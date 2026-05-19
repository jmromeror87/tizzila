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

use App\Models\Invoice\Invoice;


class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        // 🔒 Contabilidad se maneja en InvoiceService
        // Este observer queda intencionalmente vacío
    }
}