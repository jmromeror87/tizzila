{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-university text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Balance <span class="text-yellow-500">General</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">Situación Financiera Consolidada</p>
                </div>
            </div>
            <button onclick="window.print()"
                class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                <i class="fas fa-file-invoice"></i> Certificar Balance
            </button>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- FILTROS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Fecha de Corte</label>
                    <input type="date" name="to_date" value="{{ $to }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                </div>
                <button class="h-[46px] bg-white/5 hover:bg-white/10 border border-white/10 hover:border-yellow-500/30 text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-sync-alt text-yellow-500"></i> Actualizar Valores
                </button>
            </form>
        </div>

        {{-- ACTIVOS / PASIVOS / PATRIMONIO --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

            {{-- ACTIVOS --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden flex flex-col">
                <div class="px-5 py-3 bg-blue-500/5 border-b border-white/5 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-black text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2 py-0.5 rounded-lg">01</span>
                        <h3 class="text-[11px] font-black text-blue-400 uppercase tracking-widest">Activos</h3>
                    </div>
                    <i class="fas fa-plus-circle text-blue-500/30"></i>
                </div>
                <div class="flex-grow divide-y divide-white/[0.03]">
                    @foreach($assets as $item)
                        <div class="flex justify-between items-center px-5 py-3 hover:bg-white/[0.02] transition-colors group">
                            <div>
                                <p class="text-[11px] font-black text-zinc-300 uppercase group-hover:text-blue-400 transition-colors">{{ $item['account']->name }}</p>
                                <p class="text-[8px] font-bold text-zinc-600 uppercase">{{ $item['account']->code }}</p>
                            </div>
                            <span class="text-sm font-black text-white font-mono">${{ number_format($item['balance'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="px-5 py-4 bg-blue-500/10 border-t border-white/5 flex justify-between items-center">
                    <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest">Total Activos</span>
                    <span class="text-lg font-black text-white font-mono">${{ number_format($totalAssets, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- PASIVOS --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden flex flex-col">
                <div class="px-5 py-3 bg-red-500/5 border-b border-white/5 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-black text-red-400 bg-red-500/10 border border-red-500/20 px-2 py-0.5 rounded-lg">02</span>
                        <h3 class="text-[11px] font-black text-red-400 uppercase tracking-widest">Pasivos</h3>
                    </div>
                    <i class="fas fa-minus-circle text-red-500/30"></i>
                </div>
                <div class="flex-grow divide-y divide-white/[0.03]">
                    @foreach($liabilities as $item)
                        <div class="flex justify-between items-center px-5 py-3 hover:bg-white/[0.02] transition-colors group">
                            <div>
                                <p class="text-[11px] font-black text-zinc-300 uppercase group-hover:text-red-400 transition-colors">{{ $item['account']->name }}</p>
                                <p class="text-[8px] font-bold text-zinc-600 uppercase">{{ $item['account']->code }}</p>
                            </div>
                            <span class="text-sm font-black text-white font-mono">${{ number_format($item['balance'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="px-5 py-4 bg-red-500/10 border-t border-white/5 flex justify-between items-center">
                    <span class="text-[9px] font-black text-red-400 uppercase tracking-widest">Total Pasivos</span>
                    <span class="text-lg font-black text-white font-mono">${{ number_format($totalLiabilities, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- PATRIMONIO --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden flex flex-col">
                <div class="px-5 py-3 bg-emerald-500/5 border-b border-white/5 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-black text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-lg">03</span>
                        <h3 class="text-[11px] font-black text-emerald-400 uppercase tracking-widest">Patrimonio</h3>
                    </div>
                    <i class="fas fa-seedling text-emerald-500/30"></i>
                </div>
                <div class="flex-grow divide-y divide-white/[0.03]">
                    @forelse($equity as $item)
                        <div class="flex justify-between items-center px-5 py-3 hover:bg-white/[0.02] transition-colors group">
                            <div>
                                <p class="text-[11px] font-black text-zinc-300 uppercase group-hover:text-emerald-400 transition-colors">{{ $item['account']->name }}</p>
                                <p class="text-[8px] font-bold text-zinc-600 uppercase">{{ $item['account']->code }}</p>
                            </div>
                            <span class="text-sm font-black text-white font-mono">${{ number_format($item['balance'], 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center">
                            <p class="text-[9px] text-zinc-700 uppercase font-bold">Sin cuentas de patrimonio</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-5 py-4 bg-emerald-500/10 border-t border-white/5 flex justify-between items-center">
                    <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">Total Patrimonio</span>
                    <span class="text-lg font-black text-white font-mono">${{ number_format($totalEquity, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- ECUACIÓN PATRIMONIAL --}}
        <div class="bg-[#0d121f] border {{ round($totalAssets, 2) == round($totalLiabilities + $totalEquity, 2) ? 'border-emerald-500/20' : 'border-red-500/20' }} rounded-2xl p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-2">Ecuación Patrimonial</p>
                <div class="flex items-center gap-4">
                    <span class="text-3xl font-black text-white">${{ number_format($totalAssets, 0, ',', '.') }}</span>
                    <span class="text-xl font-black text-zinc-600">=</span>
                    <span class="text-3xl font-black text-zinc-400">${{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</span>
                </div>
            </div>
            @if(round($totalAssets, 2) == round($totalLiabilities + $totalEquity, 2))
                <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 px-5 py-3 rounded-xl">
                    <i class="fas fa-check-double text-emerald-400"></i>
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Balance Consolidado</span>
                </div>
            @else
                <div class="flex items-center gap-2 bg-red-500/10 border border-red-500/20 px-5 py-3 rounded-xl">
                    <i class="fas fa-times-circle text-red-400"></i>
                    <span class="text-[10px] font-black text-red-400 uppercase tracking-widest">Diferencia Detectada</span>
                </div>
            @endif
        </div>
    </div>

    <style>
        @media print {
            header, nav, form, button { display: none !important; }
        }
    </style>
</x-app-layout>
