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
                    Revisa <span class="text-yellow-500">{{ count($rows) }} facturas</span>
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
                        Total: <span class="text-white font-black">$ {{ number_format(collect($rows)->sum('total'), 0, ',', '.') }}</span>
                    </span>
                </div>
                <button type="submit"
                        class="h-10 px-8 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-file-import"></i> Importar Seleccionadas
                </button>
            </div>

            {{-- Tabla --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.02]">
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-center w-8">✓</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">N° Factura</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Fecha</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">NIT Cliente</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Descripción</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            @foreach($rows as $i => $row)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-3 py-2.5 text-center">
                                    <input type="checkbox" name="rows[{{ $i }}][import]" value="1" checked
                                           class="accent-yellow-500 w-4 h-4">
                                </td>
                                <td class="px-3 py-2.5">
                                    <input type="hidden" name="rows[{{ $i }}][number]"      value="{{ $row['number'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][issue_date]"  value="{{ $row['issue_date'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][nit_cliente]" value="{{ $row['nit_cliente'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][description]" value="{{ $row['description'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][total]"       value="{{ $row['total'] }}">
                                    <span class="text-[9px] font-black text-yellow-400 font-mono">{{ $row['number'] }}</span>
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="text-[9px] text-zinc-400">{{ $row['issue_date'] }}</span>
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="text-[9px] text-zinc-300 font-mono">{{ $row['nit_cliente'] }}</span>
                                </td>
                                <td class="px-3 py-2.5 max-w-xs">
                                    <span class="text-[10px] text-white truncate block">{{ $row['description'] }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <span class="text-[10px] font-black text-emerald-400 font-mono">
                                        $ {{ number_format($row['total'], 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-white/10 bg-black/20">
                                <td colspan="5" class="px-3 py-3 text-[9px] font-black uppercase tracking-widest text-zinc-500">Total</td>
                                <td class="px-3 py-3 text-right text-sm font-black text-white font-mono">
                                    $ {{ number_format(collect($rows)->sum('total'), 0, ',', '.') }}
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
            document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = state);
        }
    </script>
</x-app-layout>
