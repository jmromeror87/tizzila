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
                {{-- Icono de Banco/Balance con Glow Tizzila --}}
                <div class="relative hidden sm:block">
                    <div class="absolute -inset-1 bg-blue-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-blue-400 border border-blue-500/50 shadow-[0_10px_20px_rgba(59,130,246,0.2)]">
                        <i class="fas fa-university text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Balance <span class="text-[#f3c444]">General</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        Situación Financiera Consolidada
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="window.print()" 
                        class="px-6 py-3 bg-[#f3c444] text-black text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition-all shadow-lg shadow-[#f3c444]/20 flex items-center gap-2">
                    <i class="fas fa-file-invoice"></i> Certificar Balance
                </button>
            </div>
        </div>
    </x-slot>

    <div class="p-8 space-y-8">

        {{-- 🔍 FILTROS --}}
        <div class="group relative bg-[#0a0a0c] rounded-[2.5rem] p-8 border border-white/5 shadow-2xl">
            <form method="GET" class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div>
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block">Fecha de Corte</label>
                    <input type="date" name="to_date" value="{{ $to }}"
                           class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-[#f3c444] transition-all">
                </div>
                <div class="md:col-span-2">
                    <button class="h-[46px] px-8 bg-white/5 border border-white/10 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-white/10 hover:border-[#f3c444]/50 transition-all active:scale-95">
                        <i class="fas fa-sync-alt mr-2 text-[#f3c444]"></i> Actualizar Valores
                    </button>
                </div>
            </form>
        </div>

        {{-- 📐 ECUACIÓN PATRIMONIAL GRID --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- 🔵 ACTIVOS --}}
            <div class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl flex flex-col">
                <div class="px-8 py-6 bg-blue-500/5 border-b border-white/5 flex justify-between items-center">
                    <h3 class="text-xs font-black text-blue-400 uppercase tracking-widest">1. Activos</h3>
                    <i class="fas fa-plus-circle text-blue-500/30"></i>
                </div>
                <div class="p-4 flex-grow">
                    @foreach($assets as $item)
                        <div class="flex justify-between items-center px-4 py-4 border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors rounded-xl group">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-zinc-300 uppercase leading-none group-hover:text-blue-400 transition-colors">{{ $item['account']->name }}</span>
                                <span class="text-[9px] font-bold text-zinc-600 mt-1 uppercase">{{ $item['account']->code }}</span>
                            </div>
                            <span class="text-sm font-black text-white group-hover:scale-110 transition-transform">
                                ${{ number_format($item['balance'], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="px-8 py-6 bg-blue-500/10 flex justify-between items-center">
                    <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Total Activos</span>
                    <span class="text-xl font-black text-white">${{ number_format($totalAssets, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- 🔴 PASIVOS --}}
            <div class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl flex flex-col">
                <div class="px-8 py-6 bg-rose-500/5 border-b border-white/5 flex justify-between items-center">
                    <h3 class="text-xs font-black text-rose-400 uppercase tracking-widest">2. Pasivos</h3>
                    <i class="fas fa-minus-circle text-rose-500/30"></i>
                </div>
                <div class="p-4 flex-grow">
                    @foreach($liabilities as $item)
                        <div class="flex justify-between items-center px-4 py-4 border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors rounded-xl group">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-zinc-300 uppercase leading-none group-hover:text-rose-400 transition-colors">{{ $item['account']->name }}</span>
                                <span class="text-[9px] font-bold text-zinc-600 mt-1 uppercase">{{ $item['account']->code }}</span>
                            </div>
                            <span class="text-sm font-black text-white">
                                ${{ number_format($item['balance'], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="px-8 py-6 bg-rose-500/10 flex justify-between items-center">
                    <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Total Pasivos</span>
                    <span class="text-xl font-black text-white">${{ number_format($totalLiabilities, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- 🟢 PATRIMONIO --}}
            <div class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl flex flex-col">
                <div class="px-8 py-6 bg-emerald-500/5 border-b border-white/5 flex justify-between items-center">
                    <h3 class="text-xs font-black text-emerald-400 uppercase tracking-widest">3. Patrimonio</h3>
                    <i class="fas fa-seedling text-emerald-500/30"></i>
                </div>
                <div class="p-4 flex-grow">
                    @foreach($equity as $item)
                        <div class="flex justify-between items-center px-4 py-4 border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors rounded-xl group">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-zinc-300 uppercase leading-none group-hover:text-emerald-400 transition-colors">{{ $item['account']->name }}</span>
                                <span class="text-[9px] font-bold text-zinc-600 mt-1 uppercase">{{ $item['account']->code }}</span>
                            </div>
                            <span class="text-sm font-black text-white">
                                ${{ number_format($item['balance'], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="px-8 py-6 bg-emerald-500/10 flex justify-between items-center">
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Total Patrimonio</span>
                    <span class="text-xl font-black text-white">${{ number_format($totalEquity, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>

        {{-- ✅ VALIDACIÓN DE ECUACIÓN PATRIMONIAL --}}
        <div class="group relative p-[2px] rounded-[2.5rem] overflow-hidden shadow-2xl">
            {{-- Fondo dinámico según validación --}}
            <div class="absolute inset-0 {{ ($totalAssets == ($totalLiabilities + $totalEquity)) ? 'bg-emerald-500/20 animate-pulse' : 'bg-rose-500/20 animate-bounce' }}"></div>
            
            <div class="relative bg-[#0a0a0c] p-8 rounded-[2.4rem] flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.4em] mb-2">Ecuación Patrimonial</p>
                    <div class="flex items-center gap-4">
                        <h2 class="text-4xl font-black text-white tracking-tighter">
                            ${{ number_format($totalAssets, 0, ',', '.') }}
                        </h2>
                        <span class="text-2xl font-black text-zinc-700">=</span>
                        <h2 class="text-4xl font-black text-zinc-400 tracking-tighter">
                            ${{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}
                        </h2>
                    </div>
                </div>

                @if(round($totalAssets, 2) == round($totalLiabilities + $totalEquity, 2))
                    <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 px-6 py-4 rounded-2xl">
                        <i class="fas fa-check-double text-emerald-500 text-xl"></i>
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Balance Consolidado</span>
                    </div>
                @else
                    <div class="flex items-center gap-3 bg-rose-500/10 border border-rose-500/20 px-6 py-4 rounded-2xl">
                        <i class="fas fa-times-circle text-rose-500 text-xl"></i>
                        <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Diferencia Detectada</span>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>