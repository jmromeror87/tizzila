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


namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Poultry\PoultryType;
use Illuminate\Http\Request;

class PoultryTypeController extends Controller
{
    /* ============================================================
     | INDEX
     ============================================================ */
    public function index()
    {
        $types = PoultryType::orderBy('name')->get();

        return view('poultry.types.index', compact('types'));
    }

    /* ============================================================
     | CREATE
     ============================================================ */
    public function create()
    {
        return view('poultry.types.create');
    }

    /* ============================================================
     | STORE
     ============================================================ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'         => ['required', 'string', 'max:50', 'unique:poultry_types,code'],
            'name'         => ['required', 'string', 'max:100'],
            'icon'         => ['nullable', 'string', 'max:20'],
            'payment_days' => ['required', 'integer', 'min:0'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        PoultryType::create([
            'code'         => strtolower($validated['code']),
            'name'         => $validated['name'],
            'icon'         => $validated['icon'] ?? null,
            'payment_days' => $validated['payment_days'],
            'is_active'    => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('poultry.types.index')
            ->with('success', 'Tipo de ave creado correctamente.');
    }

    /* ============================================================
     | SHOW
     ============================================================ */
    public function show(PoultryType $poultryType)
    {
        return view('poultry.types.show', compact('poultryType'));
    }
    public function edit(PoultryType $type)
{
    return view('poultry.types.edit', compact('type'));
}

public function update(Request $request, PoultryType $type)
{
    $validated = $request->validate([
        'code' => 'required|string|max:50|unique:poultry_types,code,' . $type->id,
        'name' => 'required|string|max:100',
        'icon' => 'nullable|string|max:20',
        'payment_days' => 'required|integer|min:0',
        'is_active' => 'nullable|boolean',
    ]);

    $type->update([
        'code' => strtolower($validated['code']),
        'name' => $validated['name'],
        'icon' => $validated['icon'],
        'payment_days' => $validated['payment_days'],
        'is_active' => $request->boolean('is_active'),
    ]);

    return redirect()
        ->route('poultry.types.index')
        ->with('success', 'Tipo actualizado correctamente.');
}

}
