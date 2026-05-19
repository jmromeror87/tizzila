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

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\Invoice\TaxCategory;
use Illuminate\Http\Request;
use App\Http\Requests\Invoice\TaxCategoryRequest;

class TaxCategoryController extends Controller
{
   public function index()
{
    // Asegúrate de que la variable se llame $taxes
    $taxes = TaxCategory::latest()->paginate(10);
    
    // Y que el compact use el mismo nombre 'taxes'
    return view('taxes.index', compact('taxes'));
}
    public function create()
    {
        return view('taxes.create');
    }

    public function store(TaxCategoryRequest $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:tax_categories,code',
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'type' => 'required|string',
            'is_exempt' => 'boolean',
            'is_excluded' => 'boolean',
            'is_active' => 'boolean',
        ]);

        

            TaxCategory::create($request->validated());
    return redirect()->route('taxes.index')->with('success', 'Impuesto configurado correctamente.');
    }

    public function show(TaxCategory $tax)
    {
        return view('taxes.show', compact('tax'));
    }

    public function edit(TaxCategory $tax)
    {
        return view('taxes.edit', compact('tax'));
    }

   public function update(TaxCategoryRequest $request, TaxCategory $tax)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:tax_categories,code,' . $tax->id,
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'type' => 'required|string',
            'is_exempt' => 'sometimes|boolean',
            'is_excluded' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

      $tax->update($request->validated());
    return redirect()->route('taxes.index')->with('success', 'Cambios aplicados exitosamente.');
    }

    public function destroy(TaxCategory $tax)
    {
        if ($tax->invoiceItems()->exists()) {
            return back()->with('error', 'No se puede eliminar: el impuesto ya está en uso en facturas.');
        }

        $tax->delete();
        return redirect()->route('taxes.index')->with('success', 'Impuesto eliminado.');
    }
}




