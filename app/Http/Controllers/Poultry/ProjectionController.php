<?php

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Poultry\PoultryOrderSchedule;
use App\Models\Customer\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProjectionController extends Controller
{
    public function index()
    {
        $months = collect();
        for ($i = 0; $i < 3; $i++) {
            $months->push(Carbon::now()->addMonths($i)->startOfMonth());
        }

        $projection = $months->map(function ($start) {
            $end = $start->copy()->endOfMonth();

            $orders = PoultryOrderSchedule::whereBetween('dispatch_date', [$start, $end])
                ->where('status', '!=', 'cancelled')
                ->with('poultryType')
                ->get();

            $totalProgrammed = $orders->sum('quantity');

            // Capacidad comprometida clientes fijos
            $fixedClients = Customer::where('is_fixed_client', true)
                ->where('is_active', true)
                ->get();

            $weeksInMonth   = ceil($start->daysInMonth / 7);
            $fixedCommitted = $fixedClients->sum('fixed_weekly_quantity') * $weeksInMonth;

            // Disponible para clientes variables
            $variableAvailable = max(0, $totalProgrammed - $fixedCommitted);

            // Por tipo de ave
            $byType = $orders->groupBy(fn($o) => $o->poultryType?->name ?? $o->poultry_type ?? 'Sin tipo')
                ->map(fn($group) => $group->sum('quantity'));

            return [
                'month'              => $start->translatedFormat('F Y'),
                'month_short'        => $start->translatedFormat('M'),
                'year'               => $start->year,
                'total_programmed'   => $totalProgrammed,
                'fixed_committed'    => $fixedCommitted,
                'variable_available' => $variableAvailable,
                'fixed_clients'      => $fixedClients->count(),
                'orders_count'       => $orders->count(),
                'by_type'            => $byType,
                'coverage_pct'       => $totalProgrammed > 0
                    ? min(100, round(($fixedCommitted / $totalProgrammed) * 100))
                    : 0,
            ];
        });

        // Top clientes fijos
        $fixedClients = Customer::where('is_fixed_client', true)
            ->where('is_active', true)
            ->withSum(['invoices as total_balance' => fn($q) => $q->where('balance', '>', 0)], 'balance')
            ->withSum(['invoices as total_invoiced'], 'total')
            ->orderByDesc('fixed_weekly_quantity')
            ->get()
            ->map(function ($c) {
                $c->effectiveness = $c->total_invoiced > 0
                    ? round((($c->total_invoiced - ($c->total_balance ?? 0)) / $c->total_invoiced) * 100, 1)
                    : 100;
                return $c;
            });

        return view('poultry.projection.index', compact('projection', 'fixedClients'));
    }
}
