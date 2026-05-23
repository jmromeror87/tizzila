{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('poultry.orders.show', $order) }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Factura de Compra · PRONAVICOLA</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Registrar <span class="text-yellow-500">Factura</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs px-5 py-4 rounded-2xl">
            <ul class="space-y-1">@foreach($errors->all() as $e)<li>· {{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        {{-- RESUMEN DEL PEDIDO --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Pedido</p>
                <p class="text-sm font-black text-white">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Proveedor</p>
                <p class="text-sm font-black text-yellow-400">{{ $provider->business_name ?? 'PRONAVICOLA' }}</p>
            </div>
            <div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Tipo</p>
                <p class="text-sm font-black text-white">{{ $order->poultryType->name ?? $order->poultry_type }}</p>
            </div>
            <div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Programado</p>
                <p class="text-sm font-black text-white">{{ number_format($order->quantity) }} aves</p>
            </div>
        </div>

        <form method="POST" action="{{ route('purchase-invoices.store', $order) }}">
            @csrf

            {{-- DATOS DE LA FACTURA --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">01</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Datos de la Factura</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">N° Factura PRONAVICOLA <span class="text-red-400">*</span></label>
                        <input type="text" name="invoice_number" value="{{ old('invoice_number') }}"
                            placeholder="PREL19672"
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-white outline-none focus:border-yellow-500/50 uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">N° Pedido Proveedor</label>
                        <input type="text" name="provider_order_number" value="{{ old('provider_order_number') }}"
                            placeholder="PDC-00177702"
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-white outline-none focus:border-yellow-500/50 uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Fecha Factura <span class="text-red-400">*</span></label>
                        <input type="date" name="invoice_date" value="{{ old('invoice_date', now()->toDateString()) }}"
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Fecha Vencimiento</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Observaciones</label>
                        <input type="text" name="notes" value="{{ old('notes') }}"
                            placeholder="Ej: VEREDA URENGUE BLONAY FINCA DINAMARCA"
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                    </div>
                </div>
            </div>

            {{-- CANTIDADES Y PRECIOS --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden mt-5">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">02</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Aves Recibidas y Costos</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Cantidad Recibida <span class="text-red-400">*</span></label>
                        <input type="number" name="quantity_invoiced" id="qty_invoiced"
                            value="{{ old('quantity_invoiced', $order->quantity) }}" min="1"
                            class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50"
                            oninput="calcTotal()">
                        @if(old('quantity_invoiced', $order->quantity) != $order->quantity)
                        <p class="text-[9px] text-yellow-400 mt-1">Programado: {{ number_format($order->quantity) }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Precio Unitario <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-sm font-black">$</span>
                            <input type="number" name="unit_price" id="unit_price"
                                value="{{ old('unit_price', 2470) }}" min="0"
                                class="w-full pl-7 pr-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50"
                                oninput="calcTotal()">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">FONAV (por ave)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-sm font-black">$</span>
                            <input type="number" name="fonav_amount" id="fonav_amount"
                                value="{{ old('fonav_amount', 37.8) }}" min="0"
                                class="w-full pl-7 pr-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50"
                                oninput="calcTotal()">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Vacunas (por ave)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-sm font-black">$</span>
                            <input type="number" name="vaccine_amount" id="vaccine_amount"
                                value="{{ old('vaccine_amount', 0) }}" min="0"
                                class="w-full pl-7 pr-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50"
                                oninput="calcTotal()">
                        </div>
                    </div>
                </div>

                {{-- TOTAL EN VIVO --}}
                <div class="px-6 pb-6">
                    <div class="bg-black/40 border border-yellow-500/20 rounded-xl p-4 grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Subtotal Aves</p>
                            <p id="display_subtotal" class="text-lg font-black text-white">$0</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">FONAV + Vacunas</p>
                            <p id="display_extras" class="text-lg font-black text-yellow-400">$0</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-yellow-500 uppercase tracking-widest mb-1">Total Factura</p>
                            <p id="display_total" class="text-2xl font-black text-white">$0</p>
                        </div>
                    </div>
                    @php
                        $diff = $order->quantity - $order->quantity; // placeholder, real via JS
                    @endphp
                    <div id="diff-alert" class="hidden mt-3 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest"></div>
                </div>
            </div>

            {{-- ACCIONES --}}
            <div class="flex items-center justify-end gap-4 pt-2 mt-5">
                <a href="{{ route('poultry.orders.show', $order) }}"
                   class="text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-400 text-black px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                    <i class="fas fa-file-invoice"></i> Registrar Factura
                </button>
            </div>
        </form>
    </div>

    <script>
        const programmedQty = {{ $order->quantity }};

        function fmt(n) {
            return '$' + Math.round(n).toLocaleString('es-CO');
        }

        function calcTotal() {
            const qty     = parseFloat(document.getElementById('qty_invoiced').value) || 0;
            const price   = parseFloat(document.getElementById('unit_price').value) || 0;
            const fonav   = parseFloat(document.getElementById('fonav_amount').value) || 0;
            const vaccine = parseFloat(document.getElementById('vaccine_amount').value) || 0;

            const subtotal = qty * price;
            const extras   = qty * (fonav + vaccine);
            const total    = subtotal + extras;

            document.getElementById('display_subtotal').textContent = fmt(subtotal);
            document.getElementById('display_extras').textContent   = fmt(extras);
            document.getElementById('display_total').textContent    = fmt(total);

            const diff  = qty - programmedQty;
            const alert = document.getElementById('diff-alert');
            if (diff > 0) {
                alert.className = 'mt-3 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-yellow-500/10 border border-yellow-500/20 text-yellow-400';
                alert.textContent = '⚠ Excedente: +' + diff.toLocaleString('es-CO') + ' aves sobre lo programado';
                alert.classList.remove('hidden');
            } else if (diff < 0) {
                alert.className = 'mt-3 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-red-500/10 border border-red-500/20 text-red-400';
                alert.textContent = '↓ Faltante: ' + Math.abs(diff).toLocaleString('es-CO') + ' aves menos de lo programado';
                alert.classList.remove('hidden');
            } else {
                alert.classList.add('hidden');
            }
        }

        calcTotal();
    </script>
</x-app-layout>
