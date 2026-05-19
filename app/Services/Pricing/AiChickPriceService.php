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

namespace App\Services\Pricing;

class AiChickPriceService
{
    public function suggest(array $context): float
    {
        /**
         * ⚠️ Por ahora es una lógica base.
         * Luego aquí entra IA real / histórico / promedios.
         */

        $basePrice = 3400; // ejemplo base la ia 

        // Ajuste por cantidad
        if ($context['quantity'] >= 1000) {
            $basePrice -= 150;
        }

        return round($basePrice, 2);
    }
}
