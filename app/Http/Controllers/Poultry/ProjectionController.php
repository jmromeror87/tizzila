<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Poultry\PoultryOrderSchedule;
use App\Models\Customer\Customer;
use App\Services\Poultry\ProjectionAIService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ProjectionController extends Controller
{
    public function index(ProjectionAIService $ai)
    {
        // ── 1. HISTORIAL: últimos 6 meses reales ───────────────────────────
        $historical = $this->buildHistorical();

        // ── 2. PRÓXIMOS 3 MESES: pedidos ya programados + estadística ──────
        $projection = $this->buildProjection($historical);

        // ── 3. CLIENTES FIJOS ───────────────────────────────────────────────
        $fixedClients = $this->buildFixedClients();

        // ── 4. ANÁLISIS IA ──────────────────────────────────────────────────
        $aiAnalysis = $ai->analyze(
            $historical->toArray(),
            $projection->map(fn($m) => [
                'month'           => $m['month'],
                'total_programmed'=> $m['total_programmed'],
                'stat_prediction' => $m['stat_prediction'],
                'fixed_committed' => $m['fixed_committed'],
            ])->toArray(),
            $fixedClients->map(fn($c) => [
                'name'             => $c->name,
                'weekly_quantity'  => $c->fixed_weekly_quantity,
                'effectiveness_pct'=> $c->effectiveness,
            ])->toArray()
        );

        return view('poultry.projection.index', compact(
            'projection', 'historical', 'fixedClients', 'aiAnalysis'
        ));
    }

    // ────────────────────────────────────────────────────────────────────────

    private function buildHistorical(): Collection
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end   = $start->copy()->endOfMonth();

            $orders = PoultryOrderSchedule::whereBetween('dispatch_date', [$start, $end])
                ->where('status', '!=', 'cancelled')
                ->with('poultryType')
                ->get();

            $total = $orders->sum('quantity');

            $byType = $orders->groupBy(fn($o) => $o->poultryType?->name ?? 'Sin tipo')
                ->map(fn($g) => $g->sum('quantity'));

            $months->push([
                'month'       => $start->translatedFormat('M Y'),
                'month_ts'    => $start->timestamp,
                'total'       => $total,
                'orders_count'=> $orders->count(),
                'by_type'     => $byType->toArray(),
            ]);
        }
        return $months;
    }

    private function buildProjection(Collection $historical): Collection
    {
        // Regresión lineal simple sobre el historial
        $trend = $this->linearTrend($historical->pluck('total'));

        $months = collect();
        for ($i = 0; $i < 3; $i++) {
            $start = Carbon::now()->addMonths($i)->startOfMonth();
            $end   = $start->copy()->endOfMonth();

            $orders = PoultryOrderSchedule::whereBetween('dispatch_date', [$start, $end])
                ->where('status', '!=', 'cancelled')
                ->with('poultryType')
                ->get();

            $totalProgrammed = $orders->sum('quantity');

            // Predicción estadística: promedio ponderado + tendencia
            $avg         = $historical->pluck('total')->filter()->average() ?? 0;
            $statPredict = max(0, (int) round($avg + ($trend * ($i + 1))));

            // Clientes fijos: demanda mínima garantizada
            $weeksInMonth   = (int) ceil($start->daysInMonth / 7);
            $fixedClients   = Customer::where('is_fixed_client', true)->where('is_active', true)->get();
            $fixedCommitted = $fixedClients->sum('fixed_weekly_quantity') * $weeksInMonth;

            $variableAvailable = max(0, ($totalProgrammed ?: $statPredict) - $fixedCommitted);

            $byType = $orders->groupBy(fn($o) => $o->poultryType?->name ?? 'Sin tipo')
                ->map(fn($g) => $g->sum('quantity'));

            $coveragePct = ($totalProgrammed ?: $statPredict) > 0
                ? min(100, round(($fixedCommitted / ($totalProgrammed ?: $statPredict)) * 100))
                : 0;

            $isCurrent = $i === 0;

            $months->push([
                'month'              => $start->translatedFormat('F Y'),
                'month_label'        => $isCurrent ? 'Mes Actual' : 'Próximo Mes',
                'total_programmed'   => $totalProgrammed,
                'stat_prediction'    => $statPredict,
                'fixed_committed'    => $fixedCommitted,
                'variable_available' => $variableAvailable,
                'fixed_count'        => $fixedClients->count(),
                'orders_count'       => $orders->count(),
                'by_type'            => $byType->toArray(),
                'coverage_pct'       => $coveragePct,
                'is_current'         => $isCurrent,
                'has_data'           => $totalProgrammed > 0,
            ]);
        }
        return $months;
    }

    private function buildFixedClients(): Collection
    {
        return Customer::where('is_fixed_client', true)
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
    }

    /**
     * Regresión lineal simple: retorna la pendiente (cantidad por mes).
     */
    private function linearTrend(Collection $values): float
    {
        $n      = $values->count();
        $points = $values->filter()->values();

        if ($points->count() < 2) return 0;

        $xBar = ($n - 1) / 2;
        $yBar = $points->average();

        $num = 0;
        $den = 0;
        foreach ($points as $i => $y) {
            $num += ($i - $xBar) * ($y - $yBar);
            $den += ($i - $xBar) ** 2;
        }

        return $den > 0 ? $num / $den : 0;
    }
}
