{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-chart-line text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Estado de <span class="text-yellow-500">Resultados</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">P&L · Análisis de Rentabilidad</p>
                </div>
            </div>
            <button onclick="window.print()"
                class="bg-white/5 hover:bg-red-500/10 border border-white/10 hover:border-red-500/30 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf text-red-400"></i> Exportar
            </button>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- FILTROS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Desde</label>
                    <input type="date" name="from_date" value="{{ $from }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Hasta</label>
                    <input type="date" name="to_date" value="{{ $to }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                </div>
                <button class="h-[46px] bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Generar Reporte
                </button>
            </form>
        </div>

        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#0d121f] border {{ $grossProfit >= 0 ? 'border-blue-500/20' : 'border-red-500/20' }} rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Utilidad Bruta</p>
                <p class="text-2xl font-black {{ $grossProfit >= 0 ? 'text-blue-400' : 'text-red-400' }}">${{ number_format($grossProfit, 0, ',', '.') }}</p>
                <p class="text-[8px] text-zinc-600 uppercase mt-1">Ingresos − Costos de Producción</p>
            </div>
            <div class="bg-[#0d121f] border {{ $operatingProfit >= 0 ? 'border-yellow-500/20' : 'border-red-500/20' }} rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Utilidad Operacional</p>
                <p class="text-2xl font-black {{ $operatingProfit >= 0 ? 'text-yellow-500' : 'text-red-400' }}">${{ number_format($operatingProfit, 0, ',', '.') }}</p>
                <p class="text-[8px] text-zinc-600 uppercase mt-1">Utilidad Bruta − Gastos Operacionales</p>
            </div>
            <div class="bg-[#0d121f] border {{ $profit >= 0 ? 'border-emerald-500/20' : 'border-red-500/20' }} rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Utilidad Neta</p>
                <p class="text-2xl font-black {{ $profit >= 0 ? 'text-emerald-400' : 'text-red-400' }}">${{ number_format($profit, 0, ',', '.') }}</p>
                <p class="text-[8px] text-zinc-600 uppercase mt-1">Margen: {{ $totalRevenue > 0 ? number_format(($profit / $totalRevenue) * 100, 1) : 0 }}%</p>
            </div>
        </div>

        {{-- ESTADO DE RESULTADOS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">

            {{-- INGRESOS --}}
            <div class="px-6 py-3 bg-emerald-500/5 border-b border-white/5 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                        <i class="fas fa-arrow-trend-up text-emerald-400 text-[9px]"></i>
                    </div>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Ingresos Operativos</span>
                </div>
                <span class="text-[10px] font-black text-zinc-400">${{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            @foreach($revenues as $item)
                <div class="flex justify-between items-center px-10 py-3 border-b border-white/[0.02] hover:bg-white/[0.02] transition-colors group">
                    <div class="flex items-center gap-2">
                        <span class="text-[8px] font-black text-emerald-400/60 font-mono bg-emerald-500/5 px-1.5 py-0.5 rounded">{{ $item['account']->code }}</span>
                        <span class="text-[11px] font-bold text-zinc-400 uppercase group-hover:text-white transition-colors">{{ $item['account']->name }}</span>
                    </div>
                    <span class="text-sm font-black text-emerald-400 font-mono">${{ number_format($item['amount'], 0, ',', '.') }}</span>
                </div>
            @endforeach

            {{-- COSTOS --}}
            @if(count($costs) > 0)
            <div class="px-6 py-3 bg-amber-500/5 border-b border-white/5 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-lg bg-amber-500/10 flex items-center justify-center">
                        <i class="fas fa-industry text-amber-400 text-[9px]"></i>
                    </div>
                    <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">(-) Costos de Producción</span>
                </div>
                <span class="text-[10px] font-black text-zinc-400">${{ number_format($totalCost, 0, ',', '.') }}</span>
            </div>
            @foreach($costs as $item)
                <div class="flex justify-between items-center px-10 py-3 border-b border-white/[0.02] hover:bg-white/[0.02] transition-colors group">
                    <div class="flex items-center gap-2">
                        <span class="text-[8px] font-black text-amber-400/60 font-mono bg-amber-500/5 px-1.5 py-0.5 rounded">{{ $item['account']->code }}</span>
                        <span class="text-[11px] font-bold text-zinc-400 uppercase group-hover:text-white transition-colors">{{ $item['account']->name }}</span>
                    </div>
                    <span class="text-sm font-black text-amber-400 font-mono">(${{ number_format($item['amount'], 0, ',', '.') }})</span>
                </div>
            @endforeach
            @endif

            {{-- UTILIDAD BRUTA --}}
            <div class="px-6 py-4 bg-blue-500/5 border-b border-white/5 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <i class="fas fa-equals text-blue-400 text-[9px]"></i>
                    </div>
                    <span class="text-[11px] font-black text-blue-400 uppercase tracking-widest">= Utilidad Bruta</span>
                </div>
                <span class="text-lg font-black {{ $grossProfit >= 0 ? 'text-blue-400' : 'text-red-400' }} font-mono">${{ number_format($grossProfit, 0, ',', '.') }}</span>
            </div>

            {{-- GASTOS --}}
            @if(count($expenses) > 0)
            <div class="px-6 py-3 bg-red-500/5 border-b border-white/5 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-lg bg-red-500/10 flex items-center justify-center">
                        <i class="fas fa-arrow-trend-down text-red-400 text-[9px]"></i>
                    </div>
                    <span class="text-[10px] font-black text-red-400 uppercase tracking-widest">(-) Gastos Operacionales</span>
                </div>
                <span class="text-[10px] font-black text-zinc-400">${{ number_format($totalExpense, 0, ',', '.') }}</span>
            </div>
            @foreach($expenses as $item)
                <div class="flex justify-between items-center px-10 py-3 border-b border-white/[0.02] hover:bg-white/[0.02] transition-colors group">
                    <div class="flex items-center gap-2">
                        <span class="text-[8px] font-black text-red-400/60 font-mono bg-red-500/5 px-1.5 py-0.5 rounded">{{ $item['account']->code }}</span>
                        <span class="text-[11px] font-bold text-zinc-400 uppercase group-hover:text-white transition-colors">{{ $item['account']->name }}</span>
                    </div>
                    <span class="text-sm font-black text-red-400 font-mono">(${{ number_format($item['amount'], 0, ',', '.') }})</span>
                </div>
            @endforeach
            @endif

            {{-- UTILIDAD OPERACIONAL --}}
            <div class="px-6 py-4 bg-yellow-500/5 border-b border-white/5 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                        <i class="fas fa-equals text-yellow-500 text-[9px]"></i>
                    </div>
                    <span class="text-[11px] font-black text-yellow-500 uppercase tracking-widest">= Utilidad Operacional</span>
                </div>
                <span class="text-lg font-black {{ $operatingProfit >= 0 ? 'text-yellow-500' : 'text-red-400' }} font-mono">${{ number_format($operatingProfit, 0, ',', '.') }}</span>
            </div>

            {{-- UTILIDAD NETA --}}
            <div class="px-6 py-6 bg-black/30 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl {{ $profit >= 0 ? 'bg-emerald-500/10 border border-emerald-500/20' : 'bg-red-500/10 border border-red-500/20' }} flex items-center justify-center">
                        <i class="fas fa-trophy {{ $profit >= 0 ? 'text-emerald-400' : 'text-red-400' }} text-sm"></i>
                    </div>
                    <span class="text-sm font-black text-white uppercase tracking-widest">= Utilidad Neta</span>
                </div>
                <span class="text-3xl font-black {{ $profit >= 0 ? 'text-emerald-400' : 'text-red-400' }} font-mono">${{ number_format($profit, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <style>
        @media print {
            header, nav, form, button { display: none !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</x-app-layout>
