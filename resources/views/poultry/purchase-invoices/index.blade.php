{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Finanzas · Proveedor</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Cuentas por <span class="text-yellow-500">Pagar</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-5 py-3 rounded-2xl">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Total Facturado</p>
                <p class="text-2xl font-black text-white tracking-tighter">${{ number_format($totalInvoiced, 0, ',', '.') }}</p>
                <p class="text-[9px] text-zinc-600 mt-1">{{ $invoices->count() }} facturas</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Total Pagado</p>
                <p class="text-2xl font-black text-emerald-400 tracking-tighter">${{ number_format($totalPaid, 0, ',', '.') }}</p>
                @php $pct = $totalInvoiced > 0 ? round(($totalPaid / $totalInvoiced) * 100) : 0; @endphp
                <p class="text-[9px] text-zinc-600 mt-1">{{ $pct }}% del total</p>
            </div>
            <div class="bg-[#0d121f] border border-{{ $totalDebt > 0 ? 'red-500/20' : 'white/5' }} rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Saldo Pendiente</p>
                <p class="text-2xl font-black {{ $totalDebt > 0 ? 'text-red-400' : 'text-zinc-600' }} tracking-tighter">${{ number_format($totalDebt, 0, ',', '.') }}</p>
                @if($overdueCount > 0)
                <p class="text-[9px] text-red-400 font-black mt-1 uppercase">{{ $overdueCount }} VENCIDAS</p>
                @elseif($dueSoon > 0)
                <p class="text-[9px] text-yellow-400 font-black mt-1 uppercase">{{ $dueSoon }} vencen pronto</p>
                @else
                <p class="text-[9px] text-zinc-600 mt-1">Al día</p>
                @endif
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Avance Pago</p>
                <p class="text-2xl font-black text-white tracking-tighter">{{ $pct }}%</p>
                <div class="w-full bg-white/5 rounded-full h-1.5 mt-2 overflow-hidden">
                    <div class="{{ $pct >= 90 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-yellow-500' : 'bg-red-500') }} h-1.5 rounded-full transition-all"
                         style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Historial de Facturas</h3>
            </div>

            @if($invoices->isEmpty())
            <div class="py-16 flex flex-col items-center gap-3 text-zinc-600">
                <i class="fas fa-file-invoice-dollar text-4xl opacity-30"></i>
                <p class="text-[10px] font-black uppercase tracking-widest">Sin facturas de compra registradas</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-zinc-500">Factura</th>
                            <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-zinc-500">Fecha</th>
                            <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-zinc-500">Vencimiento</th>
                            <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-zinc-500">Total</th>
                            <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-zinc-500">Saldo</th>
                            <th class="px-5 py-3 text-center text-[9px] font-black uppercase tracking-widest text-zinc-500">Estado</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @foreach($invoices as $inv)
                        @php
                            $isOverdue = $inv->payment_status !== 'paid' && $inv->due_date && $inv->due_date->isPast();
                            $isDueSoon = $inv->payment_status !== 'paid' && $inv->due_date && $inv->due_date->isFuture() && $inv->due_date->diffInDays(now()) <= 5;
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors {{ $isOverdue ? 'bg-red-500/5' : '' }}">
                            <td class="px-5 py-4">
                                <p class="text-xs font-black text-white">{{ $inv->invoice_number }}</p>
                                @if($inv->provider_order_number)
                                <p class="text-[9px] text-zinc-600">{{ $inv->provider_order_number }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-zinc-400">{{ $inv->invoice_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">
                                @if($inv->due_date)
                                <p class="text-xs {{ $isOverdue ? 'text-red-400 font-black' : ($isDueSoon ? 'text-yellow-400 font-black' : 'text-zinc-400') }}">
                                    {{ $inv->due_date->format('d/m/Y') }}
                                </p>
                                @if($isOverdue)
                                <p class="text-[8px] text-red-400 font-black uppercase">{{ $inv->due_date->diffForHumans() }}</p>
                                @elseif($isDueSoon)
                                <p class="text-[8px] text-yellow-400 font-black uppercase">{{ $inv->due_date->diffForHumans() }}</p>
                                @endif
                                @else
                                <span class="text-zinc-600">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right text-xs font-black text-white">${{ number_format($inv->total, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right text-xs font-black {{ $inv->balance > 0 ? 'text-red-400' : 'text-zinc-600' }}">
                                ${{ number_format($inv->balance, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @php
                                    $sc = ['pending' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20', 'partial' => 'text-blue-400 bg-blue-500/10 border-blue-500/20', 'paid' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20'];
                                    $sl = ['pending' => 'PENDIENTE', 'partial' => 'PARCIAL', 'paid' => 'PAGADA'];
                                @endphp
                                <span class="text-[8px] font-black px-2 py-0.5 rounded-lg border {{ $isOverdue ? 'text-red-400 bg-red-500/10 border-red-500/20' : $sc[$inv->payment_status] }}">
                                    {{ $isOverdue ? 'VENCIDA' : $sl[$inv->payment_status] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('purchase-invoices.show', $inv) }}"
                                   class="text-[9px] font-black text-yellow-400 hover:text-yellow-300 transition-colors">
                                    Ver <i class="fas fa-arrow-right text-[8px]"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
