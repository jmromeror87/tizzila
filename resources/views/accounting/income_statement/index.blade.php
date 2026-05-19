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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4 py-2">
            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block">
                    <div class="absolute -inset-1 bg-[#f3c444]/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-[#f3c444] border border-[#f3c444]/50 shadow-[0_10px_20px_rgba(243,196,68,0.2)]">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Estado de <span class="text-[#f3c444]">Resultados</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        P&L · Análisis de Rentabilidad
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                        class="px-6 py-3 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all flex items-center gap-2">
                    <i class="fas fa-file-pdf text-red-500"></i> Exportar
                </button>
            </div>
        </div>
    </x-slot>

    <div class="p-8 space-y-8">

        {{-- 🔍 FILTROS --}}
        <div class="bg-[#0a0a0c] rounded-[2.5rem] p-8 border border-white/5 shadow-2xl">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div>
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block">Desde</label>
                    <input type="date" name="from_date" value="{{ $from }}"
                           class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-[#f3c444] transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block">Hasta</label>
                    <input type="date" name="to_date" value="{{ $to }}"
                           class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-[#f3c444] transition-all">
                </div>
                <button class="h-[46px] px-8 bg-[#f3c444] text-black font-black text-[10px] uppercase tracking-widest rounded-xl hover:scale-105 transition-all shadow-lg shadow-[#f3c444]/20">
                    <i class="fas fa-filter mr-2"></i> Generar Reporte
                </button>
            </form>
        </div>

        {{-- 📊 KPIs PRINCIPALES --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Utilidad Bruta --}}
            <div class="bg-[#0a0a0c] p-6 rounded-[2rem] border {{ $grossProfit >= 0 ? 'border-blue-500/20' : 'border-rose-500/20' }} shadow-xl">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-2">Utilidad Bruta</p>
                <h3 class="text-2xl font-black {{ $grossProfit >= 0 ? 'text-blue-400' : 'text-rose-500' }} tracking-tighter">
                    ${{ number_format($grossProfit, 0, ',', '.') }}
                </h3>
                <p class="text-[8px] font-black text-zinc-600 uppercase mt-2">
                    Ingresos − Costos de Producción
                </p>
            </div>

            {{-- Utilidad Operacional --}}
            <div class="bg-[#0a0a0c] p-6 rounded-[2rem] border {{ $operatingProfit >= 0 ? 'border-amber-500/20' : 'border-rose-500/20' }} shadow-xl">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-2">Utilidad Operacional</p>
                <h3 class="text-2xl font-black {{ $operatingProfit >= 0 ? 'text-[#f3c444]' : 'text-rose-500' }} tracking-tighter">
                    ${{ number_format($operatingProfit, 0, ',', '.') }}
                </h3>
                <p class="text-[8px] font-black text-zinc-600 uppercase mt-2">
                    Utilidad Bruta − Gastos Operacionales
                </p>
            </div>

            {{-- Utilidad Neta --}}
            <div class="bg-[#0a0a0c] p-6 rounded-[2rem] border {{ $profit >= 0 ? 'border-emerald-500/20' : 'border-rose-500/20' }} shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 {{ $profit >= 0 ? 'bg-emerald-500/5' : 'bg-rose-500/5' }} blur-[40px] rounded-full"></div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-2">Utilidad Neta</p>
                <h3 class="text-3xl font-black {{ $profit >= 0 ? 'text-emerald-500' : 'text-rose-500' }} tracking-tighter">
                    ${{ number_format($profit, 0, ',', '.') }}
                </h3>
                <p class="text-[8px] font-black text-zinc-600 uppercase mt-2">
                    Margen: {{ $totalRevenue > 0 ? number_format(($profit / $totalRevenue) * 100, 1) : 0 }}%
                </p>
            </div>
        </div>

        {{-- 📋 ESTADO DE RESULTADOS COMPLETO --}}
        <div class="bg-[#0a0a0c] rounded-[3rem] border border-white/5 overflow-hidden shadow-2xl">

            {{-- INGRESOS --}}
            <div class="border-b border-white/5">
                <div class="px-8 py-5 bg-emerald-500/5 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                            <i class="fas fa-arrow-trend-up text-emerald-500 text-[10px]"></i>
                        </div>
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Ingresos Operativos</span>
                    </div>
                    <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest">
                        ${{ number_format($totalRevenue, 0, ',', '.') }}
                    </span>
                </div>
                @foreach($revenues as $item)
                    <div class="flex justify-between items-center px-10 py-3 border-b border-white/[0.02] hover:bg-white/[0.02] transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="text-[9px] font-black text-emerald-500/60 font-mono bg-emerald-500/5 px-2 py-0.5 rounded">{{ $item['account']->code }}</span>
                            <span class="text-[11px] font-bold text-zinc-400 uppercase group-hover:text-white transition-colors">{{ $item['account']->name }}</span>
                        </div>
                        <span class="text-sm font-black text-emerald-400 font-mono">
                            ${{ number_format($item['amount'], 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- COSTOS DE PRODUCCIÓN --}}
            @if(count($costs) > 0)
            <div class="border-b border-white/5">
                <div class="px-8 py-5 bg-amber-500/5 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-amber-500/10 flex items-center justify-center">
                            <i class="fas fa-industry text-amber-500 text-[10px]"></i>
                        </div>
                        <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">(-) Costos de Producción</span>
                    </div>
                    <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest">
                        ${{ number_format($totalCost, 0, ',', '.') }}
                    </span>
                </div>
                @foreach($costs as $item)
                    <div class="flex justify-between items-center px-10 py-3 border-b border-white/[0.02] hover:bg-white/[0.02] transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="text-[9px] font-black text-amber-500/60 font-mono bg-amber-500/5 px-2 py-0.5 rounded">{{ $item['account']->code }}</span>
                            <span class="text-[11px] font-bold text-zinc-400 uppercase group-hover:text-white transition-colors">{{ $item['account']->name }}</span>
                        </div>
                        <span class="text-sm font-black text-amber-400 font-mono">
                            (${{ number_format($item['amount'], 0, ',', '.') }})
                        </span>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- UTILIDAD BRUTA --}}
            <div class="px-8 py-5 bg-blue-500/5 border-b border-white/5 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="h-7 w-7 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <i class="fas fa-equals text-blue-400 text-[10px]"></i>
                    </div>
                    <span class="text-[11px] font-black text-blue-400 uppercase tracking-widest">= Utilidad Bruta</span>
                </div>
                <span class="text-lg font-black {{ $grossProfit >= 0 ? 'text-blue-400' : 'text-rose-500' }} font-mono">
                    ${{ number_format($grossProfit, 0, ',', '.') }}
                </span>
            </div>

            {{-- GASTOS OPERACIONALES --}}
            @if(count($expenses) > 0)
            <div class="border-b border-white/5">
                <div class="px-8 py-5 bg-rose-500/5 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-rose-500/10 flex items-center justify-center">
                            <i class="fas fa-arrow-trend-down text-rose-500 text-[10px]"></i>
                        </div>
                        <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">(-) Gastos Operacionales</span>
                    </div>
                    <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest">
                        ${{ number_format($totalExpense, 0, ',', '.') }}
                    </span>
                </div>
                @foreach($expenses as $item)
                    <div class="flex justify-between items-center px-10 py-3 border-b border-white/[0.02] hover:bg-white/[0.02] transition-colors group">
                        <div class="flex items-center gap-3">
                            <span class="text-[9px] font-black text-rose-500/60 font-mono bg-rose-500/5 px-2 py-0.5 rounded">{{ $item['account']->code }}</span>
                            <span class="text-[11px] font-bold text-zinc-400 uppercase group-hover:text-white transition-colors">{{ $item['account']->name }}</span>
                        </div>
                        <span class="text-sm font-black text-rose-400 font-mono">
                            (${{ number_format($item['amount'], 0, ',', '.') }})
                        </span>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- UTILIDAD OPERACIONAL --}}
            <div class="px-8 py-5 bg-[#f3c444]/5 border-b border-white/5 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="h-7 w-7 rounded-lg bg-[#f3c444]/10 flex items-center justify-center">
                        <i class="fas fa-equals text-[#f3c444] text-[10px]"></i>
                    </div>
                    <span class="text-[11px] font-black text-[#f3c444] uppercase tracking-widest">= Utilidad Operacional</span>
                </div>
                <span class="text-lg font-black {{ $operatingProfit >= 0 ? 'text-[#f3c444]' : 'text-rose-500' }} font-mono">
                    ${{ number_format($operatingProfit, 0, ',', '.') }}
                </span>
            </div>

            {{-- UTILIDAD NETA --}}
            <div class="px-8 py-8 bg-black/40 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl {{ $profit >= 0 ? 'bg-emerald-500/10' : 'bg-rose-500/10' }} flex items-center justify-center">
                        <i class="fas fa-trophy {{ $profit >= 0 ? 'text-emerald-500' : 'text-rose-500' }} text-sm"></i>
                    </div>
                    <span class="text-sm font-black text-white uppercase tracking-widest">= Utilidad Neta</span>
                </div>
                <span class="text-3xl font-black {{ $profit >= 0 ? 'text-emerald-500' : 'text-rose-500' }} font-mono">
                    ${{ number_format($profit, 0, ',', '.') }}
                </span>
            </div>

        </div>

    </div>

    <style>
        .font-mono { font-family: 'JetBrains Mono', 'Fira Code', monospace; }
        @media print {
            header, nav, form, button { display: none !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</x-app-layout>