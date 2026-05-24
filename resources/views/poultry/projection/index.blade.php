{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Comercial · Planeación</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Proyección <span class="text-yellow-500">3 Meses</span>
                </h2>
            </div>
            <p class="text-[9px] text-zinc-600 font-black uppercase tracking-widest">
                {{ now()->translatedFormat('d F Y') }}
            </p>
        </div>
    </x-slot>

    <div class="py-4 space-y-6">

        {{-- MESES --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($projection as $i => $month)
            @php
                $isCurrentMonth = $i === 0;
                $barFixed    = $month['total_programmed'] > 0 ? min(100, round(($month['fixed_committed'] / $month['total_programmed']) * 100)) : 0;
                $barVariable = 100 - $barFixed;
            @endphp
            <div class="bg-[#0d121f] border {{ $isCurrentMonth ? 'border-yellow-500/30' : 'border-white/5' }} rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b {{ $isCurrentMonth ? 'border-yellow-500/10' : 'border-white/5' }} flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest {{ $isCurrentMonth ? 'text-yellow-500' : 'text-zinc-500' }}">
                            {{ $isCurrentMonth ? 'MES ACTUAL' : 'Próximo mes' }}
                        </p>
                        <p class="text-lg font-black text-white uppercase">{{ $month['month'] }}</p>
                    </div>
                    <span class="text-[9px] font-black px-2 py-1 rounded-lg {{ $month['orders_count'] > 0 ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20' : 'text-zinc-600 bg-white/5 border border-white/5' }}">
                        {{ $month['orders_count'] }} pedidos
                    </span>
                </div>

                <div class="p-5 space-y-4">

                    {{-- Total programado --}}
                    <div class="text-center py-3 bg-black/30 rounded-xl">
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Programado</p>
                        <p class="text-3xl font-black text-white tracking-tighter">{{ number_format($month['total_programmed']) }}</p>
                        <p class="text-[9px] text-zinc-600 mt-0.5">aves</p>
                    </div>

                    {{-- Barra fijo vs variable --}}
                    <div>
                        <div class="flex justify-between text-[9px] font-black mb-1.5">
                            <span class="text-yellow-400">FIJO {{ $barFixed }}%</span>
                            <span class="text-blue-400">VARIABLE {{ $barVariable }}%</span>
                        </div>
                        <div class="w-full h-3 rounded-full overflow-hidden bg-white/5 flex">
                            <div class="bg-yellow-500 h-full transition-all" style="width:{{ $barFixed }}%"></div>
                            <div class="bg-blue-500 h-full transition-all" style="width:{{ $barVariable }}%"></div>
                        </div>
                    </div>

                    {{-- Detalle --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-1.5 border-b border-white/5">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Clientes Fijos</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-yellow-400">{{ number_format($month['fixed_committed']) }}</p>
                                <p class="text-[8px] text-zinc-600">{{ $month['fixed_clients'] }} clientes</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-white/5">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Disponible Variable</p>
                            </div>
                            <p class="text-xs font-black text-blue-400">{{ number_format($month['variable_available']) }}</p>
                        </div>

                        {{-- Por tipo de ave --}}
                        @foreach($month['by_type'] as $type => $qty)
                        <div class="flex justify-between items-center py-1 text-[9px]">
                            <span class="text-zinc-600 font-black uppercase">{{ $type }}</span>
                            <span class="text-zinc-400 font-black">{{ number_format($qty) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- CLIENTES FIJOS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Clientes Fijos Comprometidos</h3>
                    <p class="text-[9px] text-zinc-600 mt-0.5">Demanda garantizada cada semana</p>
                </div>
                <a href="{{ route('customers.index') }}"
                   class="text-[9px] font-black text-yellow-400 hover:text-yellow-300 transition-colors">
                    Gestionar <i class="fas fa-arrow-right text-[8px]"></i>
                </a>
            </div>

            @if($fixedClients->isEmpty())
            <div class="py-12 text-center text-zinc-600">
                <i class="fas fa-star text-3xl opacity-20 mb-3 block"></i>
                <p class="text-[9px] font-black uppercase tracking-widest">Sin clientes fijos configurados</p>
                <p class="text-[9px] text-zinc-700 mt-1">Marca clientes como "fijo" en su perfil para verlos aquí</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-zinc-500">Cliente</th>
                            <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-zinc-500">Aves/Semana</th>
                            <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-zinc-500">Est. Mensual</th>
                            <th class="px-5 py-3 text-center text-[9px] font-black uppercase tracking-widest text-zinc-500">Efectividad Pago</th>
                            <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-zinc-500">Cartera Activa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @foreach($fixedClients as $client)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-5 py-3">
                                <a href="{{ route('customers.show', $client) }}" class="text-xs font-black text-white hover:text-yellow-400 transition-colors">
                                    {{ $client->name }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-right text-xs font-black text-yellow-400">
                                {{ number_format($client->fixed_weekly_quantity) }}
                            </td>
                            <td class="px-5 py-3 text-right text-xs font-black text-zinc-400">
                                ~{{ number_format($client->fixed_weekly_quantity * 4) }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $eff = $client->effectiveness;
                                    $color = $eff >= 90 ? 'text-emerald-400' : ($eff >= 60 ? 'text-yellow-400' : 'text-red-400');
                                @endphp
                                <span class="text-xs font-black {{ $color }}">{{ $eff }}%</span>
                            </td>
                            <td class="px-5 py-3 text-right text-xs font-black {{ ($client->total_balance ?? 0) > 0 ? 'text-red-400' : 'text-zinc-600' }}">
                                ${{ number_format($client->total_balance ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-white/10">
                        <tr>
                            <td class="px-5 py-3 text-[9px] font-black text-zinc-500 uppercase">TOTAL</td>
                            <td class="px-5 py-3 text-right text-sm font-black text-yellow-400">
                                {{ number_format($fixedClients->sum('fixed_weekly_quantity')) }}/sem
                            </td>
                            <td class="px-5 py-3 text-right text-xs font-black text-zinc-400">
                                ~{{ number_format($fixedClients->sum('fixed_weekly_quantity') * 4) }}/mes
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
