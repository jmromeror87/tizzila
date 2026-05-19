{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('poultry.dispatches.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        Manifiesto <span class="text-yellow-500">#{{ str_pad($dispatch->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                        {{ $dispatch->order->provider->business_name }} · Pedido #{{ $dispatch->order->id }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $stLabel = match($dispatch->status) {
                        'pending'    => 'Pendiente',
                        'scheduled'  => 'Programado',
                        'dispatched' => 'Despachado',
                        'delivered'  => 'Entregado',
                        default      => ucfirst($dispatch->status),
                    };
                    $stClass = match($dispatch->status) {
                        'delivered'  => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500',
                        'dispatched' => 'bg-yellow-500/10 border-yellow-500/20 text-yellow-500',
                        'scheduled'  => 'bg-blue-500/10 border-blue-500/20 text-blue-400',
                        default      => 'bg-zinc-500/10 border-zinc-500/20 text-zinc-400',
                    };
                @endphp
                <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $stClass }}">
                    {{ $stLabel }}
                </span>
                <button onclick="window.print()"
                        class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-print text-xs"></i>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-2">Fecha de Salida</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-black text-white">{{ $dispatch->dispatch_date->format('d') }}</span>
                    <span class="text-xs font-black text-gray-500 uppercase">{{ $dispatch->dispatch_date->isoFormat('MMMM') }}</span>
                </div>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-2">Hora de Despacho</p>
                <div class="flex items-center gap-2">
                    <i class="far fa-clock text-yellow-500 text-sm"></i>
                    <span class="text-xl font-black text-white tabular-nums">
                        {{ \Carbon\Carbon::parse($dispatch->dispatch_time)->format('h:i A') }}
                    </span>
                </div>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-2">Carga Total</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-black text-yellow-500">{{ number_format($dispatch->items->sum('quantity')) }}</span>
                    <span class="text-[9px] font-black text-gray-600 uppercase">Unidades</span>
                </div>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-2">Clientes</p>
                <div class="flex items-center gap-2">
                    <i class="fas fa-route text-yellow-500 text-sm"></i>
                    <span class="text-xl font-black text-white">{{ $dispatch->items->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Origen --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl px-5 py-3 flex items-center gap-3">
            <i class="fas fa-warehouse text-yellow-500 text-xs"></i>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                Origen: <span class="text-white">{{ $dispatch->order->provider->business_name }}</span>
            </span>
        </div>

        {{-- Tabla de distribución --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-500">Hoja de Ruta / Distribución</h3>
                <div class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                    <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest">Live Manifest</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-5 py-3 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500">Consignatario</th>
                            <th class="px-5 py-3 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500">Dirección</th>
                            <th class="px-5 py-3 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 text-right">Carga</th>
                            <th class="px-5 py-3 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 text-right">P. Sugerido</th>
                            <th class="px-5 py-3 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 text-right">P. Aplicado</th>
                            <th class="px-5 py-3 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 text-right">Fuente</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @foreach($dispatch->items as $item)
                            @php
                                $ps = match($item->price_source ?? '') {
                                    'ai'         => ['color' => 'text-yellow-500', 'label' => 'IA'],
                                    'manual'     => ['color' => 'text-gray-400',   'label' => 'Manual'],
                                    'historical' => ['color' => 'text-blue-400',   'label' => 'Histórico'],
                                    default      => ['color' => 'text-gray-600',   'label' => 'N/A'],
                                };
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-black/40 border border-white/5 flex items-center justify-center text-[10px] font-black text-gray-500 group-hover:text-yellow-500 transition-colors">
                                            {{ substr($item->customer->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-white uppercase group-hover:text-yellow-400 transition-colors">{{ $item->customer->name }}</p>
                                            <p class="text-[9px] text-gray-600 font-bold">{{ $item->customer->identification_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-[10px] text-gray-500 leading-snug max-w-[180px]">{{ $item->customer->address }}</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <p class="text-sm font-black text-white group-hover:text-yellow-500 transition-colors">{{ number_format($item->quantity) }}</p>
                                    <p class="text-[8px] text-gray-700 uppercase">Uds</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <p class="text-sm font-black text-yellow-500/70">${{ $item->price_suggested ? number_format($item->price_suggested, 2) : 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <p class="text-sm font-black text-yellow-500">${{ $item->price_applied ? number_format($item->price_applied, 2) : 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <span class="text-[9px] font-black uppercase tracking-widest {{ $ps['color'] }}">{{ $ps['label'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-white/[0.02] border-t border-white/5">
                            <td colspan="2" class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-500">
                                Volumen Neto Despachado
                            </td>
                            <td class="px-5 py-3 text-right">
                                <span class="text-xl font-black text-yellow-500">{{ number_format($dispatch->items->sum('quantity')) }}</span>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Audit footer --}}
        <div class="flex items-center justify-between text-[9px] font-bold text-gray-700 uppercase tracking-widest px-1">
            <span>Registro: {{ $dispatch->created_at->format('d/m/Y H:i') }}</span>
            <span>Tizzila OS · Logistic Manifest</span>
        </div>

    </div>
</x-app-layout>
