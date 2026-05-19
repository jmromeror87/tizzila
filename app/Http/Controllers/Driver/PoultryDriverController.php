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


namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver\PoultryDriver;
use Illuminate\Http\Request;

class PoultryDriverController extends Controller
{
    /* ============================================================
     | INDEX – Listado de conductores
     ============================================================ */
    public function index()
    {
        $drivers = PoultryDriver::withCount('routes')->orderBy('full_name')->get();

        return view('poultry.drivers.index', compact('drivers'));
    }

    /* ============================================================
     | CREATE – Formulario
     ============================================================ */
    public function create()
    {
        return view('poultry.drivers.create');
    }

    /* ============================================================
     | STORE – Guardar conductor
     ============================================================ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'string', 'max:20'],
            'whatsapp'    => ['nullable', 'string', 'max:20'],
            'truck_type'  => ['nullable', 'string', 'max:100'],
            'truck_plate' => ['required', 'string', 'max:20', 'unique:poultry_drivers,truck_plate'],
            'status'      => ['nullable', 'in:active,inactive'],
        ]);

        PoultryDriver::create($validated);

        return redirect()
            ->route('poultry.drivers.index')
            ->with('success', 'Conductor creado correctamente.');
    }

    /* ============================================================
     | SHOW – Detalle (opcional, pero útil)
     ============================================================ */
    public function show(PoultryDriver $driver)
    {
        return view('poultry.drivers.show', compact('driver'));
    }

    /* ============================================================
     | EDIT – Formulario edición
     ============================================================ */
    public function edit(PoultryDriver $driver)
    {
        return view('poultry.drivers.edit', compact('driver'));
    }

    /* ============================================================
     | UPDATE – Actualizar conductor
     ============================================================ */
    public function update(Request $request, PoultryDriver $driver)
    {
        $validated = $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'string', 'max:20'],
            'whatsapp'    => ['nullable', 'string', 'max:20'],
            'truck_type'  => ['nullable', 'string', 'max:100'],
            'truck_plate' => [
                'required',
                'string',
                'max:20',
                'unique:poultry_drivers,truck_plate,' . $driver->id
            ],
            'status'      => ['required', 'in:active,inactive'],
        ]);

        $driver->update($validated);

        return redirect()
            ->route('poultry.drivers.index')
            ->with('success', 'Conductor actualizado correctamente.');
    }

    /* ============================================================
     | DESTROY – Desactivar (NO borrar)
     ============================================================ */
    public function destroy(PoultryDriver $driver)
    {
        // Buena práctica: NO borrar, solo desactivar
        $driver->update([
            'status' => 'inactive'
        ]);

        return redirect()
            ->route('poultry.drivers.index')
            ->with('warning', 'Conductor desactivado.');
    }
}
