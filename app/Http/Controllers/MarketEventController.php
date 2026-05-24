<?php

namespace App\Http\Controllers;

use App\Models\MarketEvent;
use Illuminate\Http\Request;

class MarketEventController extends Controller
{
    public function index()
    {
        $active   = MarketEvent::active()->latest()->get();
        $past     = MarketEvent::where('is_active', false)
                        ->orWhere('ends_at', '<', now())
                        ->latest()->take(20)->get();
        $typeLabels = MarketEvent::typeLabels();

        return view('market-events.index', compact('active', 'past', 'typeLabels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                => 'required|string|max:200',
            'description'          => 'nullable|string|max:1000',
            'type'                 => 'required|in:competitor_pricing,feed_quality,strike,oversupply,demand_drop,other',
            'severity'             => 'required|in:low,medium,high',
            'estimated_impact_pct' => 'required|integer|min:-100|max:0',
            'starts_at'            => 'required|date',
            'ends_at'              => 'nullable|date|after_or_equal:starts_at',
        ]);

        MarketEvent::create([
            ...$validated,
            'registered_by' => auth()->id(),
        ]);

        return back()->with('success', 'Novedad de mercado registrada.');
    }

    public function toggle(MarketEvent $event)
    {
        $event->update(['is_active' => !$event->is_active]);
        return back()->with('success', $event->is_active ? 'Novedad reactivada.' : 'Novedad cerrada.');
    }

    public function destroy(MarketEvent $event)
    {
        $event->delete();
        return back()->with('success', 'Novedad eliminada.');
    }
}
