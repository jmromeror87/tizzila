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


namespace App\Services\Invoice;

use App\Models\Global\CompanyTaxProfile;
use Illuminate\Support\Facades\DB;
use Exception;

class InvoiceNumberGenerator
{
    public function generate(int $companyId): array
{
    return DB::transaction(function () use ($companyId) {

        $profile = CompanyTaxProfile::where('company_id', $companyId)
            ->where('is_active', 1)
            ->lockForUpdate()
            ->first();

        if (!$profile) {
            throw new Exception('No existe perfil tributario activo.');
        }

        if (!$profile->prefix) {
            throw new Exception('El perfil tributario no tiene prefijo configurado.');
        }

        if ($profile->valid_until && now()->toDateString() > $profile->valid_until) {
            throw new Exception('La resolución DIAN está vencida.');
        }

        if (is_null($profile->current_number)) {
            $profile->current_number = $profile->from_number;
            $profile->save();
        }

        if ($profile->current_number > $profile->to_number) {
            throw new Exception('Rango de numeración agotado.');
        }

        $nextNumber = $profile->current_number;

        $formatted = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $profile->increment('current_number');

        return [
            'prefix' => $profile->prefix,
            'number' => $profile->prefix . $formatted,
            'raw_number' => $nextNumber,
            'technical_key' => $profile->technical_key,
            'environment' => $profile->environment,
            'tax_profile_id' => $profile->id
        ];
    });
}
}