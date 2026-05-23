{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('poultry.orders.show', $purchaseInvoice->order) }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Factura de Compra · {{ $purchaseInvoice->provider->business_name ?? 'PRONAVICOLA' }}</p>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        {{ $purchaseInvoice->invoice_number }}
                    </h2>
                </div>
            </div>
            @php
                $statusColors = [
                    'pending' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20',
                    'partial' => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
                    'paid'    => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                ];
                $statusLabels = ['pending' => 'PENDIENTE', 'partial' => 'PARCIAL', 'paid' => 'PAGADA'];
            @endphp
            <span class="text-[9px] font-black px-3 py-1.5 rounded-xl border {{ $statusColors[$purchaseInvoice->payment_status] }}">
                {{ $statusLabels[$purchaseInvoice->payment_status] }}
            </span>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-5 py-4 rounded-2xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- KPIs PRINCIPALES --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Aves Facturadas</p>
                <p class="text-3xl font-black text-white tracking-tighter">{{ number_format($purchaseInvoice->quantity_invoiced) }}</p>
                @if($purchaseInvoice->order->quantity != $purchaseInvoice->quantity_invoiced)
                    @php $diff = $purchaseInvoice->quantity_invoiced - $purchaseInvoice->order->quantity; @endphp
                    <p class="text-[9px] font-black mt-1 {{ $diff > 0 ? 'text-yellow-400' : 'text-red-400' }}">
                        {{ $diff > 0 ? '+' : '' }}{{ number_format($diff) }} vs programado
                    </p>
                @endif
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Distribuidas</p>
                <p class="text-3xl font-black text-emerald-400 tracking-tighter">{{ number_format($totalDistributed) }}</p>
            </div>
            <div class="bg-[#0d121f] border border-{{ $pendingQty > 0 ? 'yellow-500/20' : 'white/5' }} rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Por Distribuir</p>
                <p class="text-3xl font-black {{ $pendingQty > 0 ? 'text-yellow-400' : 'text-zinc-600' }} tracking-tighter">{{ number_format($pendingQty) }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Total Factura</p>
                <p class="text-2xl font-black text-white tracking-tighter">${{ number_format($purchaseInvoice->total, 0, ',', '.') }}</p>
                <p class="text-[9px] text-zinc-500 mt-1">Vence: {{ $purchaseInvoice->due_date?->format('d/m/Y') ?? '—' }}</p>
            </div>
        </div>

        {{-- DETALLES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Info factura --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Datos de la Factura</h3>
                </div>
                <div class="p-6 space-y-3">
                    @foreach([
                        ['N° Factura', $purchaseInvoice->invoice_number],
                        ['Pedido Proveedor', $purchaseInvoice->provider_order_number ?? '—'],
                        ['Fecha Factura', $purchaseInvoice->invoice_date->format('d/m/Y')],
                        ['Fecha Vencimiento', $purchaseInvoice->due_date?->format('d/m/Y') ?? '—'],
                        ['Precio Unitario', '$'.number_format($purchaseInvoice->unit_price, 0, ',', '.')],
                        ['FONAV (por ave)', '$'.number_format($purchaseInvoice->fonav_amount, 2)],
                        ['Vacunas (por ave)', '$'.number_format($purchaseInvoice->vaccine_amount, 2)],
                    ] as [$label, $value])
                    <div class="flex justify-between items-center py-1.5 border-b border-white/5">
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">{{ $label }}</p>
                        <p class="text-xs font-black text-white">{{ $value }}</p>
                    </div>
                    @endforeach
                    @if($purchaseInvoice->notes)
                    <p class="text-[9px] text-zinc-500 pt-2">{{ $purchaseInvoice->notes }}</p>
                    @endif
                </div>
            </div>

            {{-- Despachos generados --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Distribuciones / Despachos</h3>
                    @if($pendingQty > 0)
                    <a href="{{ route('poultry.dispatches.create', $purchaseInvoice->order) }}"
                       class="h-8 px-4 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-plus text-[9px]"></i> Nueva Distribución
                    </a>
                    @endif
                </div>
                @if($purchaseInvoice->dispatches->isEmpty())
                <div class="py-12 flex flex-col items-center gap-3 text-zinc-600">
                    <i class="fas fa-truck text-3xl opacity-30"></i>
                    <p class="text-[9px] font-black uppercase tracking-widest">Sin distribuciones aún</p>
                    <a href="{{ route('poultry.dispatches.create', $purchaseInvoice->order) }}"
                       class="mt-2 h-9 px-5 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        <i class="fas fa-plus text-[9px]"></i> Crear Primera Distribución
                    </a>
                </div>
                @else
                <div class="divide-y divide-white/[0.04]">
                    @foreach($purchaseInvoice->dispatches as $dispatch)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-black text-white">Despacho #{{ str_pad($dispatch->id, 4, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[9px] text-zinc-500">{{ $dispatch->items->sum('quantity') }} aves · {{ $dispatch->dispatch_date->format('d/m/Y') }}</p>
                        </div>
                        <a href="{{ route('poultry.dispatches.show', $dispatch) }}"
                           class="text-[9px] font-black text-yellow-400 hover:text-yellow-300 transition-colors">
                            Ver <i class="fas fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
