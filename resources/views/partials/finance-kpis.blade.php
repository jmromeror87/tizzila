{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

{{-- 🚀 KPIs SUPERIORES --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    {{-- Ingresos Card --}}
    <div class="group relative bg-[#0a0a0c] p-8 rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden transition-all hover:border-emerald-500/30">
        {{-- Efecto de Luz Emerald --}}
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/5 blur-[50px] rounded-full group-hover:bg-emerald-500/10 transition-all"></div>
        
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 group-hover:scale-110 transition-all text-emerald-500">
            <i class="fas fa-arrow-up text-4xl"></i>
        </div>

        <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-3">Ingresos Totales</p>
        <h2 class="text-4xl font-black text-white tracking-tighter">
            ${{ number_format($totalIncome, 0, ',', '.') }}
        </h2>
        
        <div class="mt-4 flex items-center gap-2">
            <span class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/20">
                <i class="fas fa-caret-up mr-1"></i>12.5%
            </span>
            <span class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest">vs mes anterior</span>
        </div>
    </div>

    {{-- Gastos Card --}}
    <div class="group relative bg-[#0a0a0c] p-8 rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden transition-all hover:border-rose-500/30">
        {{-- Efecto de Luz Rose --}}
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-rose-500/5 blur-[50px] rounded-full group-hover:bg-rose-500/10 transition-all"></div>

        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 group-hover:scale-110 transition-all text-rose-500">
            <i class="fas fa-arrow-down text-4xl"></i>
        </div>

        <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-3">Gastos Operativos</p>
        <h2 class="text-4xl font-black text-white tracking-tighter">
            ${{ number_format($totalExpense, 0, ',', '.') }}
        </h2>

        <div class="mt-4 flex items-center gap-2">
            <span class="text-[10px] font-black text-rose-500 bg-rose-500/10 px-2 py-0.5 rounded-md border border-rose-500/20">
                <i class="fas fa-caret-down mr-1"></i>4.2%
            </span>
            <span class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest">tendencia de ahorro</span>
        </div>
    </div>

    {{-- Utilidad Card --}}
    <div class="group relative bg-[#0a0a0c] p-8 rounded-[2.5rem] border border-[#f3c444]/20 shadow-2xl overflow-hidden transition-all hover:border-[#f3c444]">
        <div class="absolute -inset-1 bg-[#f3c444]/5 opacity-0 group-hover:opacity-100 blur-2xl transition-opacity"></div>
        
        <p class="text-[10px] font-black text-[#f3c444] uppercase tracking-[0.3em] mb-3 relative z-10">Utilidad Neta</p>
        <h2 class="text-4xl font-black text-white tracking-tighter relative z-10">
            ${{ number_format($profit, 0, ',', '.') }}
        </h2>

        <div class="mt-4 relative z-10">
            <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden border border-white/5">
                <div class="bg-gradient-to-r from-[#f3c444] to-yellow-200 h-full rounded-full shadow-[0_0_15px_#f3c444] transition-all duration-1000" 
                     style="width: {{ $profitPercentage ?? 65 }}%;"></div>
            </div>
            <div class="flex justify-between items-center mt-2">
                <p class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest">Objetivo Mensual</p>
                <p class="text-[9px] font-black text-[#f3c444] uppercase tracking-widest">{{ $profitPercentage ?? 65 }}%</p>
            </div>
        </div>
    </div>
</div>