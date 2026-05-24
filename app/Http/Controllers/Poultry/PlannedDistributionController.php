<?php

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Poultry\PoultryOrderSchedule;
use App\Models\Poultry\PoultryOrderDistribution;
use App\Models\Customer\Customer;
use Illuminate\Http\Request;

class PlannedDistributionController extends Controller
{
    public function show(PoultryOrderSchedule $order)
    {
        $order->load(['distributions.customer', 'poultryType', 'provider']);

        $totalAssigned = $order->distributions->sum('quantity');
        $remaining     = $order->quantity - $totalAssigned;

        // Clientes fijos activos no asignados aún
        $assignedIds = $order->distributions->pluck('customer_id');
        $fixedClients = Customer::where('is_fixed_client', true)
            ->where('is_active', true)
            ->whereNotIn('id', $assignedIds)
            ->get();

        // Todos los clientes para el selector manual
        $allClients = Customer::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'is_fixed_client', 'fixed_weekly_quantity']);

        return view('poultry.distributions.planned', compact(
            'order', 'totalAssigned', 'remaining', 'fixedClients', 'allClients'
        ));
    }

    public function autoLoadFixed(PoultryOrderSchedule $order)
    {
        $fixedClients = Customer::where('is_fixed_client', true)
            ->where('is_active', true)
            ->get();

        $added = 0;
        foreach ($fixedClients as $client) {
            $exists = PoultryOrderDistribution::where('poultry_order_schedule_id', $order->id)
                ->where('customer_id', $client->id)
                ->exists();

            if (!$exists && $client->fixed_weekly_quantity > 0) {
                PoultryOrderDistribution::create([
                    'poultry_order_schedule_id' => $order->id,
                    'customer_id'               => $client->id,
                    'quantity'                  => $client->fixed_weekly_quantity,
                ]);
                $added++;
            }
        }

        return back()->with('success', "{$added} clientes fijos cargados automáticamente.");
    }

    public function store(Request $request, PoultryOrderSchedule $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        // Evitar duplicado
        $existing = PoultryOrderDistribution::where('poultry_order_schedule_id', $order->id)
            ->where('customer_id', $validated['customer_id'])
            ->first();

        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $validated['quantity']]);
        } else {
            PoultryOrderDistribution::create([
                'poultry_order_schedule_id' => $order->id,
                'customer_id'               => $validated['customer_id'],
                'quantity'                  => $validated['quantity'],
            ]);
        }

        return back()->with('success', 'Cliente agregado a la distribución.');
    }

    public function update(Request $request, PoultryOrderSchedule $order, PoultryOrderDistribution $distribution)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $distribution->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cantidad actualizada.');
    }

    public function destroy(PoultryOrderSchedule $order, PoultryOrderDistribution $distribution)
    {
        $distribution->delete();
        return back()->with('success', 'Cliente removido de la distribución.');
    }
}
