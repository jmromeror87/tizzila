<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('invoices.import.form') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Importar Facturas · Vista Previa</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Revisa <span class="text-yellow-500">{{ count($invoices) }} facturas</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        <form method="POST" action="{{ route('invoices.import.store') }}">
            @csrf

            {{-- Acciones --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleAll(true)"
                            class="h-8 px-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-widest hover:bg-emerald-500/20 transition-all">
                        <i class="fas fa-check-square mr-1"></i> Marcar todos
                    </button>
                    <button type="button" onclick="toggleAll(false)"
                            class="h-8 px-4 rounded-xl bg-white/5 border border-white/10 text-zinc-400 text-[9px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                        <i class="fas fa-square mr-1"></i> Desmarcar todos
                    </button>
                    <span class="text-[9px] text-zinc-500">
                        Total: <span class="text-white font-black">$ {{ number_format(collect($invoices)->sum('total'), 0, ',', '.') }}</span>
                    </span>
                </div>
                <button type="submit"
                        class="h-10 px-8 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-file-import"></i> Importar Seleccionadas
                </button>
            </div>

            {{-- Pasar todas las filas como hidden --}}
            @foreach($rows as $i => $row)
                <input type="hidden" name="rows[{{ $i }}][number]"          value="{{ $row['number'] }}">
                <input type="hidden" name="rows[{{ $i }}][issue_date]"      value="{{ $row['issue_date'] }}">
                <input type="hidden" name="rows[{{ $i }}][nit_cliente]"     value="{{ $row['nit_cliente'] }}">
                <input type="hidden" name="rows[{{ $i }}][description]"     value="{{ $row['description'] }}">
                <input type="hidden" name="rows[{{ $i }}][cantidad]"        value="{{ $row['cantidad'] }}">
                <input type="hidden" name="rows[{{ $i }}][precio_unitario]" value="{{ $row['precio_unitario'] }}">
                <input type="hidden" name="rows[{{ $i }}][total_linea]"     value="{{ $row['total_linea'] }}">
                <input type="hidden" name="rows[{{ $i }}][total_factura]"   value="{{ $row['total_factura'] }}">
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
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">NIT</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Descripción</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Cantidad</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Precio Unit.</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Total Línea</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Total Factura</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            @foreach($invoices as $fi => $invoice)
                            @foreach($invoice['items'] as $ii => $item)
                            <tr class="hover:bg-white/[0.02] transition-colors {{ $ii === 0 ? 'border-t border-white/10' : '' }}">
                                @if($ii === 0)
                                <td class="px-3 py-2.5 text-center" rowspan="{{ count($invoice['items']) }}">
                                    <input type="checkbox" data-invoice="{{ $fi }}" value="1" checked
                                           class="invoice-check accent-yellow-500 w-4 h-4"
                                           onchange="toggleInvoiceRows(this, {{ $fi }})">
                                </td>
                                <td class="px-3 py-2.5" rowspan="{{ count($invoice['items']) }}">
                                    <span class="text-[9px] font-black text-yellow-400 font-mono">{{ $invoice['number'] }}</span>
                                </td>
                                <td class="px-3 py-2.5" rowspan="{{ count($invoice['items']) }}">
                                    <span class="text-[9px] text-zinc-400">{{ $invoice['issue_date'] }}</span>
                                </td>
                                <td class="px-3 py-2.5" rowspan="{{ count($invoice['items']) }}">
                                    <span class="text-[9px] text-zinc-300 font-mono">{{ $invoice['nit_cliente'] }}</span>
                                </td>
                                @endif
                                <td class="px-3 py-2.5">
                                    <span class="text-[10px] text-white">{{ $item['description'] }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <span class="text-[9px] text-zinc-300 font-mono">{{ number_format($item['cantidad'], 0, ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <span class="text-[9px] text-zinc-300 font-mono">$ {{ number_format($item['precio_unitario'], 0, ',', '.') }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <span class="text-[9px] text-emerald-400 font-mono">$ {{ number_format($item['total_linea'], 0, ',', '.') }}</span>
                                </td>
                                @if($ii === 0)
                                <td class="px-3 py-2.5 text-right" rowspan="{{ count($invoice['items']) }}">
                                    <span class="text-[10px] font-black text-yellow-400 font-mono">$ {{ number_format($invoice['total'], 0, ',', '.') }}</span>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-white/10 bg-black/20">
                                <td colspan="8" class="px-3 py-3 text-[9px] font-black uppercase tracking-widest text-zinc-500">
                                    {{ count($invoices) }} facturas · {{ count($rows) }} ítems
                                </td>
                                <td class="px-3 py-3 text-right text-sm font-black text-white font-mono">
                                    $ {{ number_format(collect($invoices)->sum('total'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="h-12 px-10 bg-yellow-500 hover:bg-yellow-400 text-black rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg shadow-yellow-500/20">
                    <i class="fas fa-file-import text-sm"></i> Confirmar Importación
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleAll(state) {
            document.querySelectorAll('.invoice-check').forEach(cb => cb.checked = state);
            document.querySelectorAll('input[name*="[import]"]').forEach(h => h.value = state ? '1' : '0');
        }
        function toggleInvoiceRows(cb, fi) {
            // Marcar/desmarcar los hidden inputs de esa factura
            document.querySelectorAll(`input[name^="rows["]`).forEach(inp => {
                const match = inp.name.match(/rows\[(\d+)\]/);
                // No podemos filtrar por factura fácilmente sin índice, se maneja en el servidor
            });
        }
    </script>
</x-app-layout>
