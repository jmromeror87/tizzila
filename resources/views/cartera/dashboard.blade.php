{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Dashboard <span class="text-yellow-500">Cartera</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Análisis de Recaudos y Vencimientos
                </p>
            </div>
            <a href="{{ route('cartera.index') }}"
               class="h-9 px-5 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest w-fit">
                <i class="fas fa-list-ul text-xs"></i> Ver Cartera Completa
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Total en Cartera</p>
                <p class="text-2xl font-black text-white tracking-tighter">${{ number_format($total, 0, ',', '.') }}</p>
                <div class="mt-3 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 w-full"></div>
                </div>
            </div>
            <div class="bg-[#0d121f] border border-rose-500/10 rounded-2xl p-5">
                <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></span> Vencido
                </p>
                <p class="text-2xl font-black text-rose-500 tracking-tighter">${{ number_format($overdue, 0, ',', '.') }}</p>
                <p class="text-[9px] text-gray-600 font-bold mt-2 uppercase">Requiere gestión inmediata</p>
            </div>
            <div class="bg-[#0d121f] border border-yellow-500/10 rounded-2xl p-5">
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-widest mb-2">Pendiente x Vencer</p>
                <p class="text-2xl font-black text-yellow-500 tracking-tighter">${{ number_format($pending, 0, ',', '.') }}</p>
                <p class="text-[9px] text-gray-600 font-bold mt-2 uppercase">Ingresos proyectados</p>
            </div>
            <div class="bg-[#0d121f] border border-blue-500/10 rounded-2xl p-5">
                <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-2">Saldos Parciales</p>
                <p class="text-2xl font-black text-blue-400 tracking-tighter">${{ number_format($partial, 0, ',', '.') }}</p>
                <p class="text-[9px] text-gray-600 font-bold mt-2 uppercase">Facturas con abono</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Aging de cartera --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-5 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-yellow-500 text-xs"></i> Aging de Cartera (Mora)
                </h3>
                @php $totalAging = array_sum($aging); @endphp
                <div class="space-y-4">
                    @foreach($aging as $range => $value)
                        @php
                            $pct = $totalAging > 0 ? ($value / $totalAging) * 100 : 0;
                            $color = match($range) {
                                '0_30'  => '#eab308',
                                '31_60' => '#fb923c',
                                '61_90' => '#f87171',
                                default => '#ef4444',
                            };
                            $label = str_replace('_', '-', $range) . ' Días';
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">{{ $label }}</span>
                                <span class="text-[10px] font-black text-white">${{ number_format($value, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700"
                                     style="width: {{ $pct }}%; background-color: {{ $color }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Top deudores --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-5 flex items-center gap-2">
                    <i class="fas fa-crown text-yellow-500 text-xs"></i> Mayores Saldos Pendientes
                </h3>
                <div class="space-y-2">
                    @foreach($topDebtors as $i => $debtor)
                        <div class="flex items-center justify-between bg-white/[0.02] px-4 py-3 rounded-xl border border-white/5 hover:border-yellow-500/20 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="h-7 w-7 rounded-lg bg-black/40 border border-white/5 flex items-center justify-center text-[9px] font-black text-gray-500 group-hover:text-yellow-500 transition-colors">
                                    {{ $i + 1 }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-white uppercase">{{ $debtor->customer->name ?? '—' }}</p>
                                    <p class="text-[9px] text-gray-600 font-bold uppercase">Cliente recurrente</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-rose-500">${{ number_format($debtor->total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Próximos vencimientos --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-4 flex items-center gap-2">
                <i class="fas fa-clock text-rose-500 text-xs"></i> Próximos Vencimientos (7 días)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse($upcoming as $invoice)
                    <div class="flex items-center justify-between bg-white/[0.02] px-4 py-3 rounded-xl border border-white/5 hover:bg-white/[0.04] transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-xl bg-black/40 border border-white/5 flex items-center justify-center">
                                <i class="fas fa-file-invoice text-gray-600 text-[10px]"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-white uppercase">#{{ $invoice->id }} — {{ $invoice->customer->name ?? '—' }}</p>
                                <p class="text-[9px] font-black text-yellow-500 uppercase mt-0.5">Vence en {{ now()->diffInDays($invoice->due_date) }} días</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-white">${{ number_format($invoice->balance, 0, ',', '.') }}</p>
                            <p class="text-[9px] text-gray-600 font-bold uppercase">{{ optional($invoice->due_date)->format('d M, Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-10 text-center bg-white/[0.01] rounded-xl border border-dashed border-white/10">
                        <i class="fas fa-check-circle text-emerald-500 text-2xl block mb-2 opacity-30"></i>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">Sin facturas críticas por vencer</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
