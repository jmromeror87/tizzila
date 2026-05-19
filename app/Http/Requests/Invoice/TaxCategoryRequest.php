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

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class TaxCategoryRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        $id = $this->route('tax') ? $this->route('tax')->id : null;

        return [
            'code' => "required|string|max:10|unique:tax_categories,code,{$id}",
            'name' => "required|string|max:255",
            'percentage' => "required|numeric|min:0|max:100",
            'type' => "required|in:iva,retefuente,ica,inc",
            'is_active' => "sometimes|boolean",
        ];
    }

    public function messages()
    {
        return [
            'code.unique' => 'Este código DIAN ya está registrado en el sistema.',
            'percentage.numeric' => 'El porcentaje debe ser un valor numérico (ej: 19.00).',
            'name.required' => 'El nombre del tributo es obligatorio para el reporte legal.',
        ];
    }
}