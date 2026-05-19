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


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Poultry\PoultryOrderSchedule;
use App\Models\Poultry\PoultryDispatch;
use App\Models\Poultry\PoultryType;
use App\Models\Expenses\Expense;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth   = Carbon::now()->subMonth()->endOfMonth();

        

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ VENTAS MES ACTUAL (🔥 REAL)
        |--------------------------------------------------------------------------
        */
        $salesThisMonth = Invoice::whereBetween('issue_datetime', [$startOfMonth, $endOfMonth])
            ->sum('total');



        /*
        |--------------------------------------------------------------------------
        | 2️⃣ VENTAS MES ANTERIOR
        |--------------------------------------------------------------------------
        */
        $salesLastMonth = Invoice::whereBetween('issue_datetime', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total');

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ CRECIMIENTO %
        |--------------------------------------------------------------------------
        */
        $salesGrowthPct = $salesLastMonth > 0
            ? round((($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ CARTERA REAL (🔥 BALANCE)
        |--------------------------------------------------------------------------
        */
        $totalReceivables = Invoice::where('balance', '>', 0)->sum('balance');

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ GASTOS DEL MES
        |--------------------------------------------------------------------------
        */
        $expensesThisMonth = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('total');

        /*
        |--------------------------------------------------------------------------
        | 6️⃣ UTILIDAD REAL
        |--------------------------------------------------------------------------
        */
        $netProfit = $salesThisMonth - $expensesThisMonth;


        // 🔥 MARGEN NETO
$marginNetPct = $salesThisMonth > 0
    ? round(($netProfit / $salesThisMonth) * 100, 1)
    : 0;

        /*
        |--------------------------------------------------------------------------
        | 7️⃣ MÉTRICAS OPERATIVAS
        |--------------------------------------------------------------------------
        */
        $ordersMetrics = PoultryOrderSchedule::selectRaw("
            SUM(CASE WHEN dispatch_date = CURDATE() THEN 1 ELSE 0 END) AS pedidos_hoy,
            SUM(CASE WHEN dispatch_date = CURDATE() THEN quantity ELSE 0 END) AS aves_hoy,
            SUM(CASE 
                WHEN MONTH(dispatch_date) = MONTH(CURDATE()) 
                AND YEAR(dispatch_date) = YEAR(CURDATE()) 
            THEN 1 ELSE 0 END) AS pedidos_mes,
            SUM(CASE 
                WHEN MONTH(dispatch_date) = MONTH(CURDATE()) 
                AND YEAR(dispatch_date) = YEAR(CURDATE()) 
            THEN quantity ELSE 0 END) AS aves_mes
        ")
        ->where('status', '!=', 'cancelled')
        ->first();

        $pedidosHoy = (int) ($ordersMetrics->pedidos_hoy ?? 0);
        $avesHoy    = (int) ($ordersMetrics->aves_hoy ?? 0);
        $pedidosMes = (int) ($ordersMetrics->pedidos_mes ?? 0);
        $avesMes    = (int) ($ordersMetrics->aves_mes ?? 0);

        /*
        |--------------------------------------------------------------------------
        | 8️⃣ AYER
        |--------------------------------------------------------------------------
        */
        $ordersYesterday = PoultryOrderSchedule::whereDate('dispatch_date', $yesterday)
            ->where('status', '!=', 'cancelled')
            ->count();

        $birdsYesterday = PoultryOrderSchedule::whereDate('dispatch_date', $yesterday)
            ->where('status', '!=', 'cancelled')
            ->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | 9️⃣ VARIACIÓN
        |--------------------------------------------------------------------------
        */
        $pedidosVar = $ordersYesterday > 0
            ? round((($pedidosHoy - $ordersYesterday) / $ordersYesterday) * 100, 1)
            : ($pedidosHoy > 0 ? 100 : 0);

        $avesVar = $birdsYesterday > 0
            ? round((($avesHoy - $birdsYesterday) / $birdsYesterday) * 100, 1)
            : ($avesHoy > 0 ? 100 : 0);

        /*
        |--------------------------------------------------------------------------
        | 🔟 ALERTAS
        |--------------------------------------------------------------------------
        */
        $alertLevel = match (true) {
            $pedidosHoy == 0 => 'critical',
            $pedidosVar < -20 => 'warning',
            default => 'healthy'
        };

        
        /*
        |--------------------------------------------------------------------------
        | 11️⃣ DESPACHOS HOY
        |--------------------------------------------------------------------------
        */
        $deliveriesToday = PoultryDispatch::whereDate('dispatch_date', $today)
            ->whereIn('status', ['dispatched', 'delivered'])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | 12️⃣ FACTURAS ACTIVAS
        |--------------------------------------------------------------------------
        */
        $pendingInvoices = Invoice::where('balance', '>', 0)->count();

        /*
        |--------------------------------------------------------------------------
        | 13️⃣ PERFORMANCE
        |--------------------------------------------------------------------------
        */
        $goal = max(1, config('app.revenue_goal', 100000000));
        $performancePct = intval(min(100, max(0, ($salesThisMonth / $goal) * 100)));

        /*
        |--------------------------------------------------------------------------
        | 14️⃣ SEMANA (🔥 REAL)
        |--------------------------------------------------------------------------
        */
        $weeklyLabels = [];
        $weeklySales = [];
        $weeklyExpenses = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $weeklyLabels[] = $date->isoFormat('ddd');

            $weeklySales[] = (float) Invoice::whereDate('issue_datetime', $date)
                ->sum('total');

            $weeklyExpenses[] = (float) Expense::whereDate('expense_date', $date)
                ->sum('total');
        }

        /*
        |--------------------------------------------------------------------------
        | 15️⃣ TOP 5 CLIENTES POR VENTAS (MES)
        |--------------------------------------------------------------------------
        */
        $topClients = Invoice::whereBetween('issue_datetime', [$startOfMonth, $endOfMonth])
            ->select('customer_id', DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(*) as invoice_count'))
            ->groupBy('customer_id')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->with('customer:id,name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 16️⃣ EFECTIVIDAD DE PAGO POR CLIENTE (TOP 6)
        |--------------------------------------------------------------------------
        */
        $paymentEffectiveness = Invoice::select(
                'customer_id',
                DB::raw('SUM(total) as total_invoiced'),
                DB::raw('SUM(total - balance) as total_paid'),
                DB::raw('COUNT(*) as total_invoices'),
                DB::raw('SUM(CASE WHEN balance = 0 THEN 1 ELSE 0 END) as paid_count')
            )
            ->where('total', '>', 0)
            ->groupBy('customer_id')
            ->having('total_invoiced', '>', 0)
            ->orderByDesc(DB::raw('(SUM(total - balance) / SUM(total))'))
            ->limit(6)
            ->with('customer:id,name')
            ->get()
            ->map(function ($row) {
                $row->effectiveness_pct = $row->total_invoiced > 0
                    ? round(($row->total_paid / $row->total_invoiced) * 100, 1)
                    : 0;
                return $row;
            });

        /*
        |--------------------------------------------------------------------------
        | 17️⃣ TOP TIPOS DE AVE POR VOLUMEN (MES)
        |--------------------------------------------------------------------------
        */
        $topBirdTypes = PoultryOrderSchedule::whereBetween('dispatch_date', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->select('poultry_type_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('poultry_type_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('poultryType:id,name')
            ->get();

        $totalBirdsMonth = $topBirdTypes->sum('total_qty') ?: 1;

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */
        return view('dashboard', compact(
            'user',
            'salesThisMonth',
            'salesLastMonth',
            'salesGrowthPct',
            'totalReceivables',
            'netProfit',
            'performancePct',
            'pedidosHoy',
            'avesHoy',
            'pedidosMes',
            'avesMes',
            'deliveriesToday',
            'pendingInvoices',
            'pedidosVar',
             'marginNetPct', // 👈 AQUÍ
            'avesVar',
            'alertLevel',
            'expensesThisMonth',
            'weeklySales',
            'weeklyExpenses',
            'weeklyLabels',
            'topClients',
            'paymentEffectiveness',
            'topBirdTypes',
            'totalBirdsMonth'
        ));
    }
}