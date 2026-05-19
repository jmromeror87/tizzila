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


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer|exists:customers,id',

            'items' => 'required|array|min:1',

            'items.*.poultry_type_id' => 'required|integer|exists:poultry_types,id',

            'items.*.description' => 'nullable|string|max:255',

            'items.*.quantity' => 'required|numeric|min:0.0001',

            'items.*.unit_price' => 'required|numeric|min:0',

            'items.*.tax_category_id' => 'required|exists:tax_categories,id',
        ];
    }
}