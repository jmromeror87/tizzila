<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('invoices.import.form') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Facturación · Vista Previa</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    <span class="text-yellow-500">{{ count($invoices) }} facturas</span> listas para importar
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- KPI --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="grid grid-cols-2 lg:grid-cols-3 divide-x divide-white/5">
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Facturas</p>
                    <h2 class="text-2xl font-black text-white">{{ count($invoices) }} <span class="text-yellow-500 text-lg font-bold">Docs</span></h2>
                    <div class="mt-2 h-0.5 w-full bg-white/5 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total a Facturar</p>
                    <h2 class="text-2xl font-black text-emerald-400">${{ number_format($totalVenta, 0, ',', '.') }}</h2>
                    <div class="mt-2 h-0.5 w-full bg-emerald-500/20 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Genera automáticamente</p>
                    <div class="flex gap-3 mt-1">
                        <span class="px-2 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[8px] font-black"><i class="fas fa-book mr-1"></i>Contabilidad</span>
                        <span class="px-2 py-1 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-400 text-[8px] font-black"><i class="fas fa-wallet mr-1"></i>Cartera</span>
                    </div>
                    <div class="mt-2 h-0.5 w-full bg-white/5 rounded-full"></div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('invoices.import.store') }}">
            @csrf

            {{-- Acciones --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 flex items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleAll(true)"
                            class="h-8 px-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-widest hover:bg-emerald-500/20 transition-all">
                        <i class="fas fa-check-square mr-1"></i> Marcar todos
                    </button>
                    <button type="button" onclick="toggleAll(false)"
                            class="h-8 px-4 rounded-xl bg-white/5 border border-white/10 text-zinc-400 text-[9px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                        <i class="fas fa-square mr-1"></i> Desmarcar todos
                    </button>
                </div>
                <button type="submit"
                        class="h-10 px-8 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-file-import"></i> Importar y Contabilizar
                </button>
            </div>

            {{-- Hidden rows --}}
            @php
                // Mapa invoice_number → índices de rows (para syncHidden)
                $invoiceRowMap = [];
                foreach($rows as $i => $row) {
                    $invoiceRowMap[$row['invoice_number']][] = $i;
                }
                // Mapa fi (invoice index) → índices de rows
                $fiRowMap = [];
                foreach($invoices as $fi => $inv) {
                    $fiRowMap[$fi] = $invoiceRowMap[$inv['invoice_number']] ?? [];
                }
            @endphp
            @foreach($rows as $i => $row)
                <input type="hidden" name="rows[{{ $i }}][invoice_number]" value="{{ $row['invoice_number'] }}">
                <input type="hidden" name="rows[{{ $i }}][issue_date]"     value="{{ $row['issue_date'] }}">
                <input type="hidden" name="rows[{{ $i }}][nit_cliente]"    value="{{ $row['nit_cliente'] }}">
                <input type="hidden" name="rows[{{ $i }}][nombre_cliente]" value="{{ $row['nombre_cliente'] }}">
                <input type="hidden" name="rows[{{ $i }}][tipo_producto]"  value="{{ $row['tipo_producto'] }}">
                <input type="hidden" name="rows[{{ $i }}][linea]"          value="{{ $row['linea'] }}">
                <input type="hidden" name="rows[{{ $i }}][observacion]"    value="{{ $row['observacion'] }}">
                <input type="hidden" name="rows[{{ $i }}][cantidad]"       value="{{ $row['cantidad'] }}">
                <input type="hidden" name="rows[{{ $i }}][precio_compra]"  value="{{ $row['precio_compra'] }}">
                <input type="hidden" name="rows[{{ $i }}][precio_venta]"   value="{{ $row['precio_venta'] }}">
                <input type="hidden" name="rows[{{ $i }}][total_linea]"    value="{{ $row['total_linea'] }}">
                <input type="hidden" name="rows[{{ $i }}][facturado]"      value="{{ $row['facturado'] }}">
                <input type="hidden" name="rows[{{ $i }}][import]" id="import-row-{{ $i }}" value="1">
            @endforeach

            {{-- Tabla por factura --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.02]">
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-center w-8">✓</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">N° Factura</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Fecha</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Cliente</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Producto</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Cant.</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">P.Venta</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            @foreach($invoices as $fi => $invoice)
                            @foreach($invoice['items'] as $ii => $item)
                            <tr class="hover:bg-white/[0.02] transition-colors {{ $ii === 0 ? 'border-t border-white/10' : '' }}">
                                @if($ii === 0)
                                <td class="px-3 py-2.5 text-center" rowspan="{{ count($invoice['items']) }}">
                                    <input type="checkbox" data-fi="{{ $fi }}" value="1" checked
                                           class="invoice-check accent-yellow-500 w-4 h-4"
                                           onchange="syncHidden(this, {{ $fi }})">
                                </td>
                                <td class="px-3 py-2.5" rowspan="{{ count($invoice['items']) }}">
                                    <span class="text-[9px] font-black text-yellow-400 font-mono">{{ $invoice['invoice_number'] }}</span>
                                </td>
                                <td class="px-3 py-2.5" rowspan="{{ count($invoice['items']) }}">
                                    <span class="text-[9px] text-zinc-400">{{ $invoice['issue_date'] }}</span>
                                </td>
                                <td class="px-3 py-2.5 max-w-[180px]" rowspan="{{ count($invoice['items']) }}">
                                    <p class="text-[10px] font-bold text-white truncate">{{ $invoice['nombre_cliente'] }}</p>
                                    <p class="text-[8px] text-zinc-500 font-mono">{{ $invoice['nit_cliente'] }}</p>
                                </td>
                                @endif
                                <td class="px-3 py-2.5">
                                    <p class="text-[9px] text-white">{{ $item['tipo_producto'] }} · {{ $item['linea'] }}</p>
                                    @if($item['observacion'] && $item['observacion'] !== 'NINGUNA')
                                    <p class="text-[8px] text-zinc-500">{{ $item['observacion'] }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <span class="text-[9px] text-zinc-300 font-mono">{{ number_format($item['cantidad'], 0, ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <span class="text-[9px] text-zinc-400 font-mono">$ {{ number_format($item['precio_venta'], 0, ',', '.') }}</span>
                                </td>
                                @if($ii === 0)
                                <td class="px-3 py-2.5 text-right" rowspan="{{ count($invoice['items']) }}">
                                    <span class="text-[11px] font-black text-emerald-400 font-mono">$ {{ number_format($invoice['total'], 0, ',', '.') }}</span>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-white/10 bg-black/20">
                                <td colspan="7" class="px-3 py-3 text-[9px] font-black uppercase tracking-widest text-zinc-500">
                                    {{ count($invoices) }} facturas
                                </td>
                                <td class="px-3 py-3 text-right text-sm font-black text-white font-mono">
                                    $ {{ number_format($totalVenta, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="submit"
                        class="h-12 px-10 bg-yellow-500 hover:bg-yellow-400 text-black rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg shadow-yellow-500/20">
                    <i class="fas fa-file-import text-sm"></i> Confirmar — Importar y Contabilizar
                </button>
            </div>
        </form>
    </div>

    <script>
        const fiRowMap = @json($fiRowMap);

        function toggleAll(state) {
            document.querySelectorAll('.invoice-check').forEach(cb => cb.checked = state);
            document.querySelectorAll('input[name*="[import]"]').forEach(h => h.value = state ? '1' : '0');
        }
        function syncHidden(cb, fi) {
            const val = cb.checked ? '1' : '0';
            (fiRowMap[fi] || []).forEach(i => {
                const el = document.getElementById('import-row-' + i);
                if (el) el.value = val;
            });
        }
    </script>
</x-app-layout>
