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

/**
 * ───────────────────────────────────────────────────────────────
 * Nombre del Proyecto : Tizzila App
 * Tipo de Software    : Software Propietario (SaaS por Suscripción)
 * Autor               : Jhoan Romero
 * Empresa / Marca     : Tizzila
 *
 * Módulo              : Proveedores
 * Archivo             : ProviderController.php
 * Función             : Descripción de la función del archivo
 *
 * © Copyright (C) 2026 Jhoan Romero / Tizzila
 * Todos los derechos reservados.
 *
 * Este software es PROPIETARIO y CONFIDENCIAL.
 * Su uso está permitido únicamente a usuarios autorizados
 * mediante licencia o suscripción activa otorgada por Jhoan romero r.
 *
 * Queda estrictamente prohibida la copia, modificación,
 * distribución, sublicenciamiento o ingeniería inversa,
 * total o parcial, sin autorización expresa y por escrito
 * del titular de los derechos.
 *
 * Este software se proporciona tal cual , con grantia segun el contrato de licencia.
 * ───────────────────────────────────────────────────────────────
 */


namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Poultry\Provider;
use Illuminate\Http\Request;
use App\Models\Poultry\PoultryOrderSchedule;

class ProviderController extends Controller
{
    /* ============================================================
     | INDEX
     ============================================================ */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $providers = Provider::when($search, fn($q) =>
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('tax_id', 'like', "%{$search}%")
                  ->orWhere('trade_name', 'like', "%{$search}%")
            )
            ->withCount('poultryOrderSchedules')
            ->orderBy('business_name')
            ->paginate(20)
            ->withQueryString();

        return view('poultry.providers.index', compact('providers', 'search'));
    }

    /* ============================================================
     | CREATE
     ============================================================ */
    public function create()
    {
        return view('poultry.providers.create');
    }

    /* ============================================================
     | STORE
     ============================================================ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tax_id'                   => ['required', 'string', 'max:30', 'unique:providers,tax_id'],
            'tax_id_type'              => ['required', 'in:NIT,CC,CE,PASSPORT'],
            'business_name'            => ['required', 'string', 'max:255'],
            'trade_name'               => ['nullable', 'string', 'max:255'],
            'address_line'             => ['nullable', 'string', 'max:255'],
            'city'                     => ['nullable', 'string', 'max:100'],
            'department'               => ['nullable', 'string', 'max:100'],
            'country'                  => ['nullable', 'string', 'max:100'],
            'postal_code'              => ['nullable', 'string', 'max:20'],
            'phone'                    => ['nullable', 'string', 'max:50'],
            'email'                    => ['nullable', 'email'],
            'contacts'                 => ['nullable', 'array'],
            'payment_terms_days'       => ['nullable', 'integer', 'min:0'],
            'payment_conditions'       => ['nullable', 'string'],
            'preferred_payment_method' => ['required', 'in:transfer,cash,check'],
            'status'                   => ['required', 'in:active,inactive'],
        ]);

        Provider::create($validated);

        return redirect()
            ->route('poultry.providers.index')
            ->with('success', 'Proveedor creado correctamente.');
    }

    /* ============================================================
     | EDIT
     ============================================================ */
    public function edit(Provider $provider)
    {
        return view('poultry.providers.edit', compact('provider'));
    }

    /* ============================================================
     | UPDATE
     ============================================================ */
    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'tax_id'                   => ['required', 'string', 'max:30', 'unique:providers,tax_id,' . $provider->id],
            'tax_id_type'              => ['required', 'in:NIT,CC,CE,PASSPORT'],
            'business_name'            => ['required', 'string', 'max:255'],
            'trade_name'               => ['nullable', 'string', 'max:255'],
            'address_line'             => ['nullable', 'string', 'max:255'],
            'city'                     => ['nullable', 'string', 'max:100'],
            'department'               => ['nullable', 'string', 'max:100'],
            'country'                  => ['nullable', 'string', 'max:100'],
            'postal_code'              => ['nullable', 'string', 'max:20'],
            'phone'                    => ['nullable', 'string', 'max:50'],
            'email'                    => ['nullable', 'email'],
            'contacts'                 => ['nullable', 'array'],
            'payment_terms_days'       => ['nullable', 'integer', 'min:0'],
            'payment_conditions'       => ['nullable', 'string'],
            'preferred_payment_method' => ['required', 'in:transfer,cash,check'],
            'status'                   => ['required', 'in:active,inactive'],
        ]);

        $provider->update($validated);

        return redirect()
            ->route('poultry.providers.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function show(Provider $provider)
    {
        $stats = PoultryOrderSchedule::where('provider_id', $provider->id)
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(quantity) as total_programmed,
                SUM(CASE WHEN approval_status = "approved" THEN quantity ELSE 0 END) as total_approved,
                SUM(CASE WHEN approval_status = "under_review" THEN quantity ELSE 0 END) as total_under_review,
                SUM(CASE WHEN approval_status = "pending" THEN quantity ELSE 0 END) as total_pending,
                SUM(CASE WHEN status = "paid" THEN quantity ELSE 0 END) as total_paid_qty,
                SUM(CASE WHEN status = "dispatched" THEN quantity ELSE 0 END) as total_dispatched_qty,
                SUM(CASE WHEN status = "cancelled" THEN quantity ELSE 0 END) as total_cancelled_qty
            ')
            ->first();

        $percentage = $stats->total_programmed > 0
            ? round(($stats->total_approved / $stats->total_programmed) * 100, 1)
            : 0;

        $recentOrders = PoultryOrderSchedule::where('provider_id', $provider->id)
            ->orderByDesc('dispatch_date')
            ->limit(8)
            ->get();

        $claimsCount   = $provider->claims()->count()   ?? 0;
        $documentsCount = $provider->documents()->count() ?? 0;

        $monthlyBirds = PoultryOrderSchedule::where('provider_id', $provider->id)
            ->selectRaw('YEAR(dispatch_date) as year, MONTH(dispatch_date) as month, SUM(quantity) as total')
            ->where('status', '!=', 'cancelled')
            ->groupByRaw('YEAR(dispatch_date), MONTH(dispatch_date)')
            ->orderByRaw('YEAR(dispatch_date) DESC, MONTH(dispatch_date) DESC')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        return view('poultry.providers.show', compact(
            'provider', 'stats', 'percentage', 'recentOrders', 'claimsCount', 'documentsCount', 'monthlyBirds'
        ));
    }


    /* ============================================================
     | DESTROY
     ============================================================ */
    public function destroy(Provider $provider)
    {
        if ($provider->poultryOrderSchedules()->exists()) {
            return back()->with('error', 'No se puede eliminar un proveedor con pedidos asociados.');
        }

        $provider->delete();

        return redirect()
            ->route('poultry.providers.index')
            ->with('success', 'Proveedor eliminado.');
    }
}
