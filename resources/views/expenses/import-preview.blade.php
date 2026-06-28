<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('expenses.import.form') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Importar Gastos · Vista Previa</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Revisa <span class="text-yellow-500">{{ count($rows) }} registros</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        <form method="POST" action="{{ route('expenses.import.store') }}">
            @csrf

            {{-- Acciones rápidas --}}
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
                </div>
                <button type="submit"
                        class="h-10 px-8 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-file-import"></i> Importar Seleccionados
                </button>
            </div>

            {{-- Tabla --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.02]">
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-center w-8">✓</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Fecha</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Tercero · NIT</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Detalle</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Categoría</th>
                                <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Pago</th>
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
                                    <input type="hidden" name="rows[{{ $i }}][document_number]" value="{{ $row['document_number'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][nit_tercero]"     value="{{ $row['nit_tercero'] ?? '' }}">
                                    <input type="hidden" name="rows[{{ $i }}][tercero]"         value="{{ $row['tercero'] ?? '' }}">
                                    <input type="hidden" name="rows[{{ $i }}][description]"     value="{{ $row['description'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][total]"           value="{{ $row['total'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][expense_date]"    value="{{ $row['expense_date'] }}">
                                    <span class="text-[9px] text-zinc-400">{{ $row['expense_date'] }}</span>
                                </td>
                                <td class="px-3 py-2.5 max-w-[220px]">
                                    @if(!empty($row['tercero']))
                                    <p class="text-[10px] font-bold text-white truncate">{{ $row['tercero'] }}</p>
                                    <p class="text-[8px] text-orange-400 font-mono">{{ $row['nit_tercero'] ?? '' }}</p>
                                    @else
                                    <span class="text-[10px] text-white truncate block">{{ $row['description'] }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 max-w-[180px]">
                                    <span class="text-[9px] text-zinc-400 truncate block">{{ $row['description'] }}</span>
                                </td>
                                <td class="px-3 py-2.5">
                                    <select name="rows[{{ $i }}][category_id]"
                                            class="bg-black/40 border border-white/10 rounded-lg px-2 py-1 text-[9px] font-black text-white outline-none focus:border-yellow-500/50">
                                        @foreach($categories as $catId => $catName)
                                        <option value="{{ $catId }}" {{ $catId == $row['category_id'] ? 'selected' : '' }}>
                                            {{ $catName }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-3 py-2.5">
                                    @php $isTransfer = str_contains(strtolower($row['payment_method']), 'trans'); @endphp
                                    <select name="rows[{{ $i }}][payment_method]"
                                            class="bg-black/40 border border-white/10 rounded-lg px-2 py-1 text-[9px] font-black text-white outline-none focus:border-yellow-500/50">
                                        <option value="transfer" {{ $isTransfer ? 'selected' : '' }}>Transferencia</option>
                                        <option value="cash"     {{ !$isTransfer ? 'selected' : '' }}>Efectivo</option>
                                        <option value="card">Tarjeta</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    <span class="text-[10px] font-black text-red-400 font-mono">
                                        $ {{ number_format($row['total'], 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-white/10 bg-black/20">
                                <td colspan="7" class="px-3 py-3 text-[9px] font-black uppercase tracking-widest text-zinc-500">Total</td>
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
