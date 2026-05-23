{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div class="absolute inset-0 bg-yellow-500/30 blur-2xl rounded-full"></div>
                    <div class="relative bg-black p-3 rounded-2xl border border-yellow-500/40 shadow-[0_0_20px_rgba(234,179,8,0.15)]">
                        <i class="fas fa-bolt text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        CORE <span class="text-yellow-500">TIZZILA</span>
                    </h2>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-[9px] text-zinc-500 uppercase tracking-[0.4em] font-black">Control System</span>
                        <span class="h-[1px] w-6 bg-zinc-800"></span>
                        <span class="text-[9px] text-yellow-500/70 font-mono font-bold">BETA_V.2.0.26</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-zinc-900/60 border border-white/5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Sincronizado</span>
                <span class="text-[9px] text-zinc-600 font-bold">{{ now()->format('d M Y · H:i') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-8">

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ROW 1: BIENVENIDA + KPIs PRINCIPALES --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}

        {{-- Saludo + Análisis Rápido --}}
        <div class="bg-[#0a0d16] border border-white/5 rounded-3xl p-6 md:p-8 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-500/[0.04] blur-[100px] rounded-full pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/[0.04] blur-[80px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="max-w-lg">
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.4em] font-black mb-3">Bienvenido de vuelta</p>
                    <h1 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tighter leading-none mb-3">
                        {{ explode(' ', auth()->user()->name)[0] }}<span class="text-yellow-500">.</span>
                    </h1>
                    <p class="text-sm text-zinc-500 font-bold leading-relaxed">
                        Las ventas
                        @if($marginNetPct >= 0)
                            superan los gastos en <span class="text-emerald-400">{{ $marginNetPct }}%</span> este periodo.
                            @if($salesGrowthPct >= 0)
                                Crecimiento del <span class="text-yellow-500">↑{{ abs($salesGrowthPct) }}%</span> vs mes anterior.
                            @else
                                Tendencia <span class="text-red-400">↓{{ abs($salesGrowthPct) }}%</span> vs mes anterior.
                            @endif
                        @else
                            <span class="text-red-400">están por debajo de los gastos en {{ abs($marginNetPct) }}%</span> este periodo.
                        @endif
                    </p>
                </div>

                {{-- Mini Gráfica Semanal --}}
                <div class="lg:w-[440px] w-full bg-black/40 border border-white/5 p-6 rounded-2xl">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-2">Flujo Semanal</p>
                            <div class="flex gap-4">
                                <div class="flex items-center gap-1.5">
                                    <div class="h-2 w-2 rounded-full bg-yellow-500"></div>
                                    <span class="text-[9px] font-black text-zinc-400 uppercase">Ventas</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="h-2 w-2 rounded-full bg-red-500"></div>
                                    <span class="text-[9px] font-black text-zinc-400 uppercase">Gastos</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-black text-white tracking-tighter">${{ number_format($salesThisMonth, 0) }}</p>
                            <p class="text-[9px] font-black text-zinc-500 uppercase mt-0.5">Ventas del Mes</p>
                        </div>
                    </div>
                    <div class="h-[110px]">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ROW 2: KPIs FINANCIEROS --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Ventas Mes --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 hover:border-yellow-500/20 transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center">
                        <i class="fas fa-arrow-trend-up text-yellow-500 text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black px-2 py-0.5 rounded-lg {{ $salesGrowthPct >= 0 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                        {{ $salesGrowthPct >= 0 ? '↑' : '↓' }} {{ abs($salesGrowthPct) }}%
                    </span>
                </div>
                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Ventas del Mes</p>
                <p class="text-2xl font-black text-white tracking-tighter">${{ number_format($salesThisMonth, 0) }}</p>
                <p class="text-[9px] text-zinc-600 mt-1">vs ${{ number_format($salesLastMonth, 0) }} anterior</p>
            </div>

            {{-- Gastos Mes --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 hover:border-red-500/20 transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <div class="h-10 w-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                        <i class="fas fa-arrow-trend-down text-red-400 text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black px-2 py-0.5 rounded-lg bg-red-500/10 text-red-400 border border-red-500/20">
                        Egresos
                    </span>
                </div>
                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Gastos del Mes</p>
                <p class="text-2xl font-black text-red-400 tracking-tighter">${{ number_format($expensesThisMonth, 0) }}</p>
                <div class="mt-2">
                    <div class="flex justify-between text-[9px] text-zinc-600 mb-1">
                        <span>vs Ventas</span>
                        <span>{{ $salesThisMonth > 0 ? round(($expensesThisMonth/$salesThisMonth)*100,1) : 0 }}%</span>
                    </div>
                    <div class="h-1 bg-black/50 rounded-full">
                        <div class="h-full bg-red-500 rounded-full" style="width: {{ min(100, $salesThisMonth > 0 ? round(($expensesThisMonth/$salesThisMonth)*100,1) : 0) }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Utilidad --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 hover:border-emerald-500/20 transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <div class="h-10 w-10 rounded-xl {{ $netProfit >= 0 ? 'bg-emerald-500/10 border-emerald-500/20' : 'bg-red-500/10 border-red-500/20' }} border flex items-center justify-center">
                        <i class="fas fa-circle-dollar-to-slot {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }} text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black px-2 py-0.5 rounded-lg {{ $netProfit >= 0 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                        Utilidad
                    </span>
                </div>
                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Utilidad Neta</p>
                <p class="text-2xl font-black {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }} tracking-tighter">${{ number_format($netProfit, 0) }}</p>
                <p class="text-[9px] text-zinc-600 mt-1">Margen: <span class="{{ $marginNetPct >= 0 ? 'text-emerald-500' : 'text-red-500' }}">{{ $marginNetPct }}%</span></p>
            </div>

            {{-- Cartera --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 hover:border-amber-500/20 transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <div class="h-10 w-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                        <i class="fas fa-clock-rotate-left text-amber-400 text-sm"></i>
                    </div>
                    <span class="text-[9px] font-black px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        {{ $pendingInvoices }} facturas
                    </span>
                </div>
                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Cartera Pendiente</p>
                <p class="text-2xl font-black text-amber-400 tracking-tighter">${{ number_format($totalReceivables, 0) }}</p>
                <p class="text-[9px] text-zinc-600 mt-1">Por recuperar</p>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ROW 3: MARGEN OPERATIVO VISUAL + PEDIDOS HOY --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Margen Operativo Visual --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6 col-span-1">
                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-4">Análisis Ventas vs Gastos</p>

                @php
                    $maxVal = max($salesThisMonth, $expensesThisMonth, 1);
                    $salesBar = round(($salesThisMonth / $maxVal) * 100);
                    $expBar = round(($expensesThisMonth / $maxVal) * 100);
                @endphp

                <div class="space-y-4 mb-6">
                    <div>
                        <div class="flex justify-between text-[9px] font-black uppercase tracking-widest mb-1.5">
                            <span class="text-yellow-500"><i class="fas fa-arrow-up mr-1"></i>Ventas</span>
                            <span class="text-white">${{ number_format($salesThisMonth, 0) }}</span>
                        </div>
                        <div class="h-3 bg-black/50 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500 rounded-full shadow-[0_0_8px_rgba(234,179,8,0.4)]" style="width: {{ $salesBar }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[9px] font-black uppercase tracking-widest mb-1.5">
                            <span class="text-red-400"><i class="fas fa-arrow-down mr-1"></i>Gastos</span>
                            <span class="text-white">${{ number_format($expensesThisMonth, 0) }}</span>
                        </div>
                        <div class="h-3 bg-black/50 rounded-full overflow-hidden">
                            <div class="h-full bg-red-500 rounded-full" style="width: {{ $expBar }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[9px] font-black uppercase tracking-widest mb-1.5">
                            <span class="{{ $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }}"><i class="fas fa-equals mr-1"></i>Utilidad</span>
                            <span class="{{ $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }}">${{ number_format($netProfit, 0) }}</span>
                        </div>
                        <div class="h-3 bg-black/50 rounded-full overflow-hidden">
                            <div class="h-full {{ $netProfit >= 0 ? 'bg-emerald-500' : 'bg-red-500' }} rounded-full shadow-[0_0_8px_rgba(16,185,129,0.3)]" style="width: {{ min(100, max(0, abs($marginNetPct))) }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Margen Neto</p>
                    <p class="text-3xl font-black {{ $marginNetPct >= 0 ? 'text-emerald-400' : 'text-red-400' }} tracking-tighter">
                        {{ $marginNetPct }}<span class="text-sm">%</span>
                    </p>
                </div>
                <p class="text-[9px] text-zinc-700 mt-1">
                    {{ $marginNetPct > 20 ? 'Margen saludable — operación eficiente' : ($marginNetPct > 0 ? 'Margen ajustado — monitorear gastos' : 'Gastos superan ingresos — acción requerida') }}
                </p>
            </div>

            {{-- Pedidos Hoy --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6 col-span-1 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Operación Hoy</p>
                    <span class="text-[9px] font-black px-2 py-0.5 rounded-lg
                        @if($alertLevel === 'critical') bg-red-500/10 text-red-400 border border-red-500/20
                        @elseif($alertLevel === 'warning') bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                        @else bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                        @endif">
                        {{ strtoupper($alertLevel) }}
                    </span>
                </div>

                <div class="flex items-end justify-between mb-4">
                    <div>
                        <p class="text-5xl font-black text-white tracking-tighter leading-none">{{ $pedidosHoy }}</p>
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mt-1">Pedidos Hoy</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black text-blue-400 tracking-tighter">{{ number_format($avesHoy) }}</p>
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mt-1">Aves</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-white/5">
                    <div class="bg-black/30 rounded-xl p-3">
                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Mes (Órdenes)</p>
                        <p class="text-lg font-black text-white">{{ number_format($pedidosMes) }}</p>
                    </div>
                    <div class="bg-black/30 rounded-xl p-3">
                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Mes (Aves)</p>
                        <p class="text-lg font-black text-blue-400">{{ number_format($avesMes) }}</p>
                    </div>
                </div>
            </div>

            {{-- Objetivo Facturación --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6 col-span-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Objetivo Mensual</p>
                        <span class="text-[9px] font-black text-white px-2 py-0.5 rounded-lg bg-white/5 border border-white/10">{{ $performancePct }}%</span>
                    </div>
                    <div class="flex items-center justify-center my-6">
                        <div class="relative h-32 w-32">
                            <canvas id="donutChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center flex-col">
                                <p class="text-2xl font-black text-white leading-none">{{ $performancePct }}%</p>
                                <p class="text-[8px] text-zinc-600 font-black uppercase">Meta</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-white/5">
                    <div class="flex justify-between text-xs">
                        <div>
                            <p class="text-[9px] text-zinc-600 font-black uppercase tracking-widest">Facturado</p>
                            <p class="font-black text-white">${{ number_format($salesThisMonth, 0) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-zinc-600 font-black uppercase tracking-widest">Meta</p>
                            <p class="font-black text-zinc-400">${{ number_format(config('app.revenue_goal', 100000000), 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ROW 4: TOP CLIENTES + TIPOS DE AVE --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Top Clientes por Ventas --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                            <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top Clientes del Mes
                        </h3>
                        <p class="text-[9px] text-zinc-600 mt-0.5">Por volumen de ventas facturadas</p>
                    </div>
                    <a href="{{ route('customers.index') }}" class="text-[9px] text-zinc-500 hover:text-yellow-500 font-black uppercase tracking-widest transition-colors">
                        Ver todos <i class="fas fa-arrow-right ml-1 text-[8px]"></i>
                    </a>
                </div>
                <div class="p-4 space-y-2">
                    @forelse($topClients as $i => $client)
                        @php
                            $maxSales = $topClients->first()->total_sales ?: 1;
                            $barW = round(($client->total_sales / $maxSales) * 100);
                            $medals = ['text-yellow-500','text-zinc-400','text-amber-600','text-zinc-500','text-zinc-500'];
                            $icons = ['fa-trophy','fa-medal','fa-award','fa-star','fa-star'];
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/[0.02] transition-colors group">
                            <div class="w-6 text-center">
                                <i class="fas {{ $icons[$i] ?? 'fa-circle' }} text-xs {{ $medals[$i] ?? 'text-zinc-600' }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-black text-white truncate pr-2 uppercase tracking-tight">
                                        {{ $client->customer->name ?? 'Sin nombre' }}
                                    </p>
                                    <p class="text-xs font-black text-yellow-500 whitespace-nowrap">${{ number_format($client->total_sales, 0) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-black/50 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-500/60 rounded-full" style="width: {{ $barW }}%"></div>
                                    </div>
                                    <span class="text-[9px] text-zinc-600">{{ $client->invoice_count }} fact.</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-zinc-600">
                            <i class="fas fa-chart-bar text-2xl mb-2 block opacity-20"></i>
                            <p class="text-[9px] font-black uppercase tracking-widest">Sin datos este mes</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Top Tipos de Ave --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                            <i class="fas fa-egg text-orange-400 mr-2"></i>Tipos de Ave Más Vendidos
                        </h3>
                        <p class="text-[9px] text-zinc-600 mt-0.5">Por volumen de unidades programadas este mes</p>
                    </div>
                    <div class="h-32 w-32">
                        <canvas id="birdPieChart"></canvas>
                    </div>
                </div>
                <div class="p-4 space-y-2">
                    @php
                        $birdColors = ['bg-yellow-500', 'bg-blue-500', 'bg-emerald-500', 'bg-purple-500', 'bg-orange-500'];
                        $birdTextColors = ['text-yellow-500', 'text-blue-400', 'text-emerald-400', 'text-purple-400', 'text-orange-400'];
                    @endphp
                    @forelse($topBirdTypes as $i => $bird)
                        @php $pct = round(($bird->total_qty / $totalBirdsMonth) * 100, 1); @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/[0.02] transition-colors">
                            <div class="h-2 w-2 rounded-full {{ $birdColors[$i] ?? 'bg-zinc-500' }} shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-black text-white truncate pr-2 uppercase tracking-tight">
                                        {{ $bird->type_name ?? 'Sin tipo' }}
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] {{ $birdTextColors[$i] ?? 'text-zinc-400' }} font-black">{{ number_format($bird->total_qty) }} und</span>
                                        <span class="text-[9px] text-zinc-600">{{ $pct }}%</span>
                                    </div>
                                </div>
                                <div class="h-1.5 bg-black/50 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $birdColors[$i] ?? 'bg-zinc-500' }} opacity-70" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-zinc-600">
                            <i class="fas fa-egg text-2xl mb-2 block opacity-20"></i>
                            <p class="text-[9px] font-black uppercase tracking-widest">Sin pedidos este mes</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ROW 5: EFECTIVIDAD DE PAGO --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                        <i class="fas fa-shield-check text-emerald-500 mr-2"></i>Efectividad de Pago por Cliente
                    </h3>
                    <p class="text-[9px] text-zinc-600 mt-0.5">Porcentaje de facturas pagadas vs total facturado — histórico</p>
                </div>
                <a href="{{ route('cartera.index') }}" class="text-[9px] text-zinc-500 hover:text-yellow-500 font-black uppercase tracking-widest transition-colors">
                    Cartera <i class="fas fa-arrow-right ml-1 text-[8px]"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/[0.04]">
                            <th class="px-6 py-3 text-left text-[9px] font-black uppercase tracking-widest text-zinc-600">Cliente</th>
                            <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-zinc-600">Total Facturado</th>
                            <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-zinc-600">Total Pagado</th>
                            <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-zinc-600">Pendiente</th>
                            <th class="px-6 py-3 text-center text-[9px] font-black uppercase tracking-widest text-zinc-600 w-48">Efectividad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($paymentEffectiveness as $row)
                            @php
                                $pct = $row->effectiveness_pct;
                                $pending = $row->total_invoiced - $row->total_paid;
                                if ($pct >= 90) { $barColor = 'bg-emerald-500'; $textColor = 'text-emerald-400'; $label = 'EXCELENTE'; }
                                elseif ($pct >= 70) { $barColor = 'bg-yellow-500'; $textColor = 'text-yellow-400'; $label = 'BUENO'; }
                                elseif ($pct >= 40) { $barColor = 'bg-orange-500'; $textColor = 'text-orange-400'; $label = 'REGULAR'; }
                                else { $barColor = 'bg-red-500'; $textColor = 'text-red-400'; $label = 'CRITICO'; }
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-white uppercase tracking-tight">{{ $row->customer->name ?? '—' }}</p>
                                    <p class="text-[9px] text-zinc-600">{{ $row->paid_count }}/{{ $row->total_invoices }} facturas pagadas</p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <p class="text-xs font-black text-white">${{ number_format($row->total_invoiced, 0) }}</p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <p class="text-xs font-black text-emerald-400">${{ number_format($row->total_paid, 0) }}</p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <p class="text-xs font-black {{ $pending > 0 ? 'text-amber-400' : 'text-zinc-600' }}">${{ number_format($pending, 0) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-black/50 rounded-full overflow-hidden">
                                            <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <div class="w-20 text-right">
                                            <span class="text-[9px] font-black {{ $textColor }}">{{ $pct }}%</span>
                                            <span class="text-[8px] text-zinc-700 block">{{ $label }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-zinc-600">
                                    <p class="text-[9px] font-black uppercase tracking-widest">Sin datos de cartera</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════ --}}
        {{-- ROW 6: ACCIONES RÁPIDAS --}}
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        <div>
            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-4">Acceso Rápido</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="/poultry/orders/calendar" class="group bg-[#0d121f] border border-white/5 hover:border-yellow-500/30 hover:bg-yellow-500/5 rounded-2xl p-5 transition-all flex flex-col items-center gap-3 text-center">
                    <i class="fas fa-layer-group text-yellow-500 text-xl group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="text-xs font-black text-white uppercase tracking-tight">Programar</p>
                        <p class="text-[9px] text-zinc-600 font-bold uppercase">Cargas</p>
                    </div>
                </a>
                <a href="/poultry/orders" class="group bg-[#0d121f] border border-white/5 hover:border-blue-500/30 hover:bg-blue-500/5 rounded-2xl p-5 transition-all flex flex-col items-center gap-3 text-center">
                    <i class="fas fa-barcode text-blue-400 text-xl group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="text-xs font-black text-white uppercase tracking-tight">Registrar</p>
                        <p class="text-[9px] text-zinc-600 font-bold uppercase">Ventas</p>
                    </div>
                </a>
                <a href="{{ route('invoices.create') }}" class="group bg-[#0d121f] border border-white/5 hover:border-emerald-500/30 hover:bg-emerald-500/5 rounded-2xl p-5 transition-all flex flex-col items-center gap-3 text-center">
                    <i class="fas fa-file-invoice text-emerald-400 text-xl group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="text-xs font-black text-white uppercase tracking-tight">Facturar</p>
                        <p class="text-[9px] text-zinc-600 font-bold uppercase">Nueva Factura</p>
                    </div>
                </a>
                <a href="{{ route('expenses.create') }}" class="group bg-[#0d121f] border border-white/5 hover:border-red-500/30 hover:bg-red-500/5 rounded-2xl p-5 transition-all flex flex-col items-center gap-3 text-center">
                    <i class="fas fa-file-invoice-dollar text-red-400 text-xl group-hover:scale-110 transition-transform"></i>
                    <div>
                        <p class="text-xs font-black text-white uppercase tracking-tight">Gasto</p>
                        <p class="text-[9px] text-zinc-600 font-bold uppercase">Registrar</p>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ─── Gráfica Semanal ──────────────────────────────────────────
        const ctxWeekly = document.getElementById('weeklyChart');
        if (ctxWeekly) {
            new Chart(ctxWeekly, {
                type: 'line',
                data: {
                    labels: @json($weeklyLabels ?? []),
                    datasets: [
                        {
                            label: 'Ventas',
                            data: @json($weeklySales ?? []),
                            borderColor: '#eab308',
                            backgroundColor: 'rgba(234,179,8,0.1)',
                            tension: 0.4, fill: true,
                            pointRadius: 3, pointBackgroundColor: '#eab308'
                        },
                        {
                            label: 'Gastos',
                            data: @json($weeklyExpenses ?? []),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,0.08)',
                            tension: 0.4, fill: true,
                            pointRadius: 3, pointBackgroundColor: '#ef4444'
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#000', padding: 8, cornerRadius: 8,
                            callbacks: { label: ctx => '$ ' + ctx.raw.toLocaleString() }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#52525b', font: { size: 9, weight: 'bold' } } },
                        y: { display: false, grid: { color: 'rgba(255,255,255,0.03)' } }
                    }
                }
            });
        }

        // ─── Donut Objetivo ───────────────────────────────────────────
        const ctxDonut = document.getElementById('donutChart');
        if (ctxDonut) {
            const pct = {{ $performancePct }};
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [pct, 100 - pct],
                        backgroundColor: ['#eab308', 'rgba(255,255,255,0.04)'],
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '78%',
                    plugins: { legend: { display: false }, tooltip: { enabled: false } }
                }
            });
        }

        // ─── Pie Tipos de Ave ─────────────────────────────────────────
        const ctxBird = document.getElementById('birdPieChart');
        if (ctxBird) {
            new Chart(ctxBird, {
                type: 'doughnut',
                data: {
                    labels: @json($topBirdTypes->pluck('type_name')),
                    datasets: [{
                        data: @json($topBirdTypes->pluck('total_qty')),
                        backgroundColor: ['#eab308','#3b82f6','#10b981','#a855f7','#f97316'],
                        borderWidth: 0,
                        borderRadius: 3
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '60%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#000', padding: 8,
                            callbacks: { label: ctx => ctx.label + ': ' + ctx.raw.toLocaleString() + ' und' }
                        }
                    }
                }
            });
        }

    });
    </script>
</x-app-layout>
