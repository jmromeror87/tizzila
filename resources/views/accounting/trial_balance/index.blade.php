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
                {{-- Icono de Balance con Glow Tizzila --}}
                <div class="relative hidden sm:block">
                    <div class="absolute -inset-1 bg-amber-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-amber-500 border border-amber-500/50 shadow-[0_10px_20px_rgba(245,158,11,0.2)]">
                        <i class="fas fa-balance-scale text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Balance de <span class="text-[#f3c444]">Comprobación</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        Verificación de Partida Doble
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 text-right">
                <div class="hidden md:block">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest leading-none mb-1">Periodo Actual</p>
                    <p class="text-xs font-bold text-white uppercase ">
                        {{ \Carbon\Carbon::parse($from)->format('d M') }} — {{ \Carbon\Carbon::parse($to)->format('d M, Y') }}
                    </p>
                </div>
               
            </div>
            <a href="{{ route('trial.balance.pdf', request()->all()) }}"
   class="relative group inline-flex items-center gap-3 px-6 py-3 bg-[#0a0a0c] border border-white/10 rounded-xl transition-all duration-300 hover:border-red-500/50 hover:bg-red-500/[0.02] shadow-lg shadow-black/20">
    
    {{-- Efecto de Brillo Rojo al Hover --}}
    <div class="absolute -inset-1 bg-red-600/10 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
    
    {{-- Icono con fondo dinámico --}}
    <div class="relative bg-red-500/10 h-8 w-8 rounded-lg flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
        <i class="fas fa-file-pdf"></i>
    </div>

    {{-- Texto --}}
    <div class="relative flex flex-col items-start">
        <span class="text-[10px] font-black text-white uppercase tracking-[0.2em] group-hover:text-red-500 transition-colors">
            Exportar reporte
        </span>
        <span class="text-[8px] font-bold text-zinc-500 uppercase tracking-widest leading-none mt-1">
            Formato PDF Oficial
        </span>
    </div>
</a>
        </div>
    </x-slot>

    <div class="p-8 space-y-8">

        {{-- 📊 KPI DE VALIDACIÓN RÁPIDA --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Status Card --}}
            <div class="group relative bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                @if(round($totalDebit,2) == round($totalCredit,2))
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 blur-[40px] rounded-full"></div>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Estado Contable</p>
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <h2 class="text-xl font-black text-emerald-500 tracking-tighter uppercase">Balance Cuadrado</h2>
                    </div>
                @else
                    <div class="absolute top-0 right-0 w-24 h-24 bg-red-500/10 blur-[40px] rounded-full"></div>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Estado Contable</p>
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-red-500 animate-bounce"></div>
                        <h2 class="text-xl font-black text-red-500 tracking-tighter uppercase">Descuadre Detectado</h2>
                    </div>
                @endif
            </div>

            {{-- Total Débitos --}}
            <div class="bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl">
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Sumas Débitos</p>
                <h2 class="text-3xl font-black text-white tracking-tighter">
                    ${{ number_format($totalDebit, 0, ',', '.') }}
                </h2>
            </div>

            {{-- Total Créditos --}}
            <div class="bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl">
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Sumas Créditos</p>
                <h2 class="text-3xl font-black text-white tracking-tighter">
                    ${{ number_format($totalCredit, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        {{-- 🔍 FILTROS --}}
        <div class="group relative bg-[#0a0a0c] rounded-[2.5rem] p-8 border border-white/5 shadow-2xl">
            <form method="GET" class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
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
                    <i class="fas fa-sync-alt mr-2"></i> Refrescar Balance
                </button>
            </form>
        </div>

        {{-- 📋 TABLA DE BALANCE --}}
        <div class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black/40 border-b border-white/5">
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Cuenta / Código PGC</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Débitos</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Créditos</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right bg-white/[0.02]">Saldo Neto</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($lines as $line)
                            <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-white uppercase group-hover:text-[#f3c444] transition-colors leading-none">
                                            {{ $line->account->name }}
                                        </span>
                                        <span class="text-[10px] font-bold text-zinc-600 mt-1 uppercase tracking-widest">
                                            {{ $line->account->code }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right font-mono font-black text-sm text-zinc-400 group-hover:text-white transition-colors">
                                    {{ number_format($line->total_debit, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-6 text-right font-mono font-black text-sm text-zinc-400 group-hover:text-white transition-colors">
                                    {{ number_format($line->total_credit, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-6 text-right font-mono font-black text-sm bg-white/[0.01] {{ $line->balance > 0 ? 'text-emerald-500' : ($line->balance < 0 ? 'text-rose-500' : 'text-zinc-700') }}">
                                    ${{ number_format(abs($line->balance), 0, ',', '.') }}
                                    <span class="text-[9px] opacity-50">{{ $line->balance >= 0 ? 'DB' : 'CR' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <i class="fas fa-exclamation-triangle text-4xl text-zinc-800 mb-4 block"></i>
                                    <span class="text-zinc-600 font-black uppercase tracking-widest text-xs">No hay datos para este rango de fechas</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="bg-black border-t-2 border-white/5">
                        <tr>
                            <td class="px-8 py-10 text-right font-black text-[10px] uppercase text-zinc-500 tracking-[0.3em]">Sumas Iguales</td>
                            <td class="px-8 py-10 text-right font-mono font-black text-xl text-white">
                                {{ number_format($totalDebit, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-10 text-right font-mono font-black text-xl text-white border-r border-white/5">
                                {{ number_format($totalCredit, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-10 text-right bg-white/[0.02]">
                                <div class="flex flex-col items-end">
                                    <span class="text-[9px] font-black uppercase text-zinc-500 mb-1 ">Diferencia</span>
                                    <span class="text-lg font-black {{ round($totalDebit - $totalCredit, 2) == 0 ? 'text-emerald-500' : 'text-red-500 underline decoration-double' }}">
                                        ${{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>