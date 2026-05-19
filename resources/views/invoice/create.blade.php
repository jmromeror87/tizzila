{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('invoices.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        Nueva <span class="text-yellow-500">Factura</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                        Fecha contable: {{ now()->translatedFormat('d \d\e F, Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-2 text-[9px] font-black text-zinc-500 uppercase tracking-widest">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sesión: {{ now()->format('H:i') }}
                </span>
                <a href="{{ route('invoices.index') }}"
                   class="h-9 px-4 rounded-xl bg-white/5 border border-white/10 hover:bg-rose-500/10 hover:border-rose-500/20 hover:text-rose-400 text-zinc-400 flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-times text-xs"></i> Cancelar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="grid grid-cols-12 gap-4">

            {{-- PANEL IZQUIERDO: líneas de factura --}}
            <div class="col-span-12 lg:col-span-9 space-y-0">

                {{-- Toolbar --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-t-2xl px-5 py-4 flex items-end gap-6">
                    <div class="w-64">
                        <label class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-1.5 block">Receptor del Comprobante</label>
                        <select name="customer_id" form="invoice_form" required
                                class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl py-2.5 px-3 text-xs text-white font-bold focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer">
                            <option value="">Buscar cliente...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="addItem()"
                            class="h-9 px-5 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2">
                        <i class="fas fa-plus text-xs"></i> Nueva Línea
                        <span class="opacity-50 text-[8px]">(F2)</span>
                    </button>
                </div>

                {{-- Cabecera tabla --}}
                <div class="bg-white/[0.02] border-x border-white/5 px-5 py-2.5 grid grid-cols-12 gap-3">
                    <div class="col-span-3 text-[8px] font-black text-zinc-500 uppercase tracking-widest">Producto</div>
                    <div class="col-span-3 text-[8px] font-black text-zinc-500 uppercase tracking-widest">Descripción</div>
                    <div class="col-span-2 text-[8px] font-black text-zinc-500 uppercase tracking-widest text-center">Cant.</div>
                    <div class="col-span-2 text-[8px] font-black text-zinc-500 uppercase tracking-widest text-center">P. Unitario</div>
                    <div class="col-span-1 text-[8px] font-black text-zinc-500 uppercase tracking-widest text-center">IVA</div>
                    <div class="col-span-1 text-[8px] font-black text-zinc-500 uppercase tracking-widest text-right">Total</div>
                </div>

                {{-- Filas dinámicas --}}
                <form method="POST" action="{{ route('invoices.store') }}" id="invoice_form">
                    @csrf
                    <div id="items_container"
                         class="bg-[#0d121f] border-x border-white/5 min-h-[120px] max-h-[480px] overflow-y-auto scroll-custom">
                    </div>
                </form>

                {{-- Footer con totales --}}
                <div class="bg-white/[0.01] border border-white/5 rounded-b-2xl px-5 py-3 flex items-center justify-between">
                    <span id="row_count" class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Líneas registradas: 0</span>
                    <div class="flex items-center gap-8">
                        <div class="text-right">
                            <span class="block text-[8px] font-black text-zinc-600 uppercase mb-0.5">Subtotal</span>
                            <span id="subtotal_display" class="text-sm text-white font-mono font-black">$0.00</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[8px] font-black text-zinc-600 uppercase mb-0.5">IVA</span>
                            <span id="tax_display" class="text-sm text-yellow-500/60 font-mono font-black">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PANEL DERECHO: totales + submit --}}
            <div class="col-span-12 lg:col-span-3">
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6 sticky top-6">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.4em] mb-2">Total Neto Factura</p>
                    <h3 id="grand_total_display"
                        class="text-5xl font-black text-white tracking-tighter font-mono mb-6 text-right">$0.00</h3>

                    <div class="space-y-3 mb-6 border-t border-white/5 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black text-zinc-600 uppercase">Unidades Totales:</span>
                            <span id="items_qty_sum" class="text-xs text-white font-mono">0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black text-zinc-600 uppercase">Servidor Fiscal:</span>
                            <span class="text-[8px] font-black text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded">ONLINE</span>
                        </div>
                    </div>

                    <button type="submit" form="invoice_form"
                            class="w-full bg-yellow-500 hover:bg-yellow-400 text-black py-3.5 rounded-xl font-black uppercase text-[11px] tracking-widest transition-all active:scale-95 flex items-center justify-center gap-2">
                        Sincronizar <i class="fas fa-bolt text-xs"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

<script>
let index = 0;
const taxRates = {@foreach($taxCategories as $tax)'{{ $tax->id }}': {{ $tax->rate ?? 0 }},@endforeach};

function addItem() {
    const container = document.getElementById('items_container');
    const html = `
        <div class="item-row group grid grid-cols-12 gap-3 px-5 py-3 border-b border-white/[0.04] hover:bg-white/[0.01] transition-colors items-center">
            <div class="col-span-3">
                <select name="items[${index}][poultry_type_id]" required onchange="syncRowData(this)"
                        class="w-full bg-transparent border-none text-[11px] text-white font-black focus:ring-0 p-0 cursor-pointer select-clean">
                    <option value="" class="bg-zinc-950 text-zinc-500">Seleccionar Producto...</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" class="bg-zinc-950" data-price="{{ $type->default_price ?? 0 }}">{{ strtoupper($type->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-3">
                <input type="text" name="items[${index}][description]"
                       class="description-field w-full bg-transparent border-none text-[10px] text-zinc-500 font-bold focus:ring-0 p-0 uppercase placeholder:text-zinc-800"
                       placeholder="DESCRIPCIÓN OPERATIVA">
            </div>
            <div class="col-span-2">
                <input type="number" step="0.01" name="items[${index}][quantity]" oninput="calculateTotals()"
                       class="input-qty no-spinner w-full bg-black/30 border border-white/10 rounded-lg py-1.5 px-2 text-xs text-white font-black text-center focus:outline-none focus:border-yellow-500/50 font-mono"
                       placeholder="0.00" required>
            </div>
            <div class="col-span-2">
                <input type="number" step="0.01" name="items[${index}][unit_price]" oninput="calculateTotals()"
                       class="input-price no-spinner w-full bg-black/30 border border-white/10 rounded-lg py-1.5 px-2 text-xs text-yellow-500 font-black text-center focus:outline-none focus:border-yellow-500/50 font-mono"
                       placeholder="0.00" required>
            </div>
            <div class="col-span-1">
                <select name="items[${index}][tax_category_id]" onchange="calculateTotals()"
                        class="w-full bg-transparent border-none text-[9px] text-zinc-600 font-black focus:ring-0 p-0 text-center cursor-pointer">
                    @foreach($taxCategories as $tax)
                        <option value="{{ $tax->id }}" class="bg-zinc-950">{{ $tax->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-1 flex items-center justify-end gap-1">
                <span class="row-total text-[10px] text-white font-mono font-black">$0.00</span>
                <button type="button" onclick="this.closest('.item-row').remove(); calculateTotals();"
                        class="h-5 w-5 flex items-center justify-center rounded text-zinc-800 hover:text-rose-500 hover:bg-rose-500/10 transition-all opacity-0 group-hover:opacity-100 ml-1">
                    <i class="fas fa-trash-alt text-[9px]"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    index++;
    calculateTotals();
}

function syncRowData(select) {
    const row   = select.closest('.item-row');
    const price = select.options[select.selectedIndex].getAttribute('data-price');
    if (price) row.querySelector('.input-price').value = price;
    row.querySelector('.description-field').value = select.options[select.selectedIndex].text;
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0, taxTotal = 0, qtySum = 0, rows = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty       = parseFloat(row.querySelector('.input-qty').value) || 0;
        const price     = parseFloat(row.querySelector('.input-price').value) || 0;
        const taxId     = row.querySelector('select[name*="[tax_category_id]"]').value;
        const rowSub    = qty * price;
        const rowTax    = rowSub * ((taxRates[taxId] || 0) / 100);
        row.querySelector('.row-total').innerText = `$${rowSub.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        subtotal += rowSub; taxTotal += rowTax; qtySum += qty; rows++;
    });
    const fmt = v => v.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('subtotal_display').innerText    = `$${fmt(subtotal)}`;
    document.getElementById('tax_display').innerText         = `$${fmt(taxTotal)}`;
    document.getElementById('grand_total_display').innerText = `$${fmt(subtotal + taxTotal)}`;
    document.getElementById('items_qty_sum').innerText       = fmt(qtySum);
    document.getElementById('row_count').innerText           = `Líneas registradas: ${rows}`;
}

window.addEventListener('keydown', e => { if (e.key === 'F2') { e.preventDefault(); addItem(); } });
window.onload = () => addItem();
</script>

<style>
    .scroll-custom::-webkit-scrollbar { width: 4px; }
    .scroll-custom::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
    .scroll-custom::-webkit-scrollbar-thumb:hover { background: #eab308; }
    input.no-spinner::-webkit-outer-spin-button,
    input.no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number].no-spinner { -moz-appearance: textfield; }
    .select-clean option { font-weight: bold; background-color: #09090b; }
</style>
</x-app-layout>
