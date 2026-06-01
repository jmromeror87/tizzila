<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('sales.import.form') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Ventas · Vista Previa</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    <span class="text-yellow-500">{{ count($rows) }} registros</span> listos
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- Resumen KPIs --}}
        @php $margen = $totalVenta > 0 ? round(($totalUtilidad / $totalVenta) * 100, 1) : 0; @endphp
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="grid grid-cols-2 lg:grid-cols-5 divide-x divide-white/5">
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Aves</p>
                    <h2 class="text-2xl font-black text-white">{{ number_format(collect($rows)->sum('cantidad'), 0, ',', '.') }} <span class="text-yellow-500 text-lg font-bold">Aves</span></h2>
                    <div class="mt-2 h-0.5 w-full bg-white/5 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Venta</p>
                    <h2 class="text-2xl font-black text-emerald-400">${{ number_format($totalVenta, 0, ',', '.') }}</h2>
                    <div class="mt-2 h-0.5 w-full bg-emerald-500/20 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Compra</p>
                    <h2 class="text-2xl font-black text-red-400">${{ number_format($totalCompra, 0, ',', '.') }}</h2>
                    <div class="mt-2 h-0.5 w-full bg-red-500/20 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Utilidad <span class="text-yellow-500">{{ $margen }}%</span></p>
                    <h2 class="text-2xl font-black text-yellow-400">${{ number_format($totalUtilidad, 0, ',', '.') }}</h2>
                    <div class="mt-2 h-0.5 w-full bg-yellow-500/20 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Facturas</p>
                    <h2 class="text-2xl font-black text-white">
                        <span class="text-emerald-400">{{ $facturadas }}</span>
                        <span class="text-zinc-600 text-lg"> / </span>
                        <span class="text-orange-400">{{ $sinFactura }}</span>
                    </h2>
                    <div class="mt-2 h-0.5 w-full bg-white/5 rounded-full"></div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('sales.import.store') }}">
            @csrf

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
                    <i class="fas fa-file-import"></i> Importar Seleccionados
                </button>
            </div>

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.02]">
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-center w-6">✓</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Fecha</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Factura</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Cliente</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Zona</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Producto</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Cant.</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">P.Compra</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">P.Venta</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Total</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Utilidad</th>
                                <th class="px-2 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            @foreach($rows as $i => $row)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-2 py-2 text-center">
                                    <input type="checkbox" name="rows[{{ $i }}][import]" value="1" checked class="accent-yellow-500 w-3.5 h-3.5">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="hidden" name="rows[{{ $i }}][sale_date]"      value="{{ $row['sale_date'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][invoice_number]" value="{{ $row['invoice_number'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][nit_cliente]"    value="{{ $row['nit_cliente'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][nombre_cliente]" value="{{ $row['nombre_cliente'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][zona]"           value="{{ $row['zona'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][tipo_producto]"  value="{{ $row['tipo_producto'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][linea]"          value="{{ $row['linea'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][observacion]"    value="{{ $row['observacion'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][cantidad]"       value="{{ $row['cantidad'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][precio_compra]"  value="{{ $row['precio_compra'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][precio_venta]"   value="{{ $row['precio_venta'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][total_venta]"    value="{{ $row['total_venta'] }}">
                                    <input type="hidden" name="rows[{{ $i }}][facturado]"      value="{{ $row['facturado'] }}">
                                    <span class="text-[9px] text-zinc-400">{{ $row['sale_date'] }}</span>
                                </td>
                                <td class="px-2 py-2">
                                    <span class="text-[9px] font-black font-mono {{ $row['invoice_number'] && $row['invoice_number'] !== 'SIN' ? 'text-yellow-400' : 'text-zinc-600' }}">
                                        {{ $row['invoice_number'] ?: 'SIN' }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 max-w-[160px]">
                                    <p class="text-[9px] text-white truncate">{{ $row['nombre_cliente'] }}</p>
                                    <p class="text-[8px] text-zinc-500 font-mono">{{ $row['nit_cliente'] }}</p>
                                </td>
                                <td class="px-2 py-2">
                                    <span class="text-[9px] text-zinc-400">{{ $row['zona'] }}</span>
                                </td>
                                <td class="px-2 py-2">
                                    <p class="text-[9px] text-white">{{ strtoupper($row['tipo_producto']) }}</p>
                                    <p class="text-[8px] text-zinc-500">{{ $row['linea'] }} @if($row['observacion'] && $row['observacion'] !== 'NINGUNA') · {{ $row['observacion'] }} @endif</p>
                                </td>
                                <td class="px-2 py-2 text-right">
                                    <span class="text-[9px] text-zinc-300 font-mono">{{ number_format($row['cantidad'], 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-2 text-right">
                                    <span class="text-[9px] text-zinc-400 font-mono">$ {{ number_format($row['precio_compra'], 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-2 text-right">
                                    <span class="text-[9px] text-zinc-300 font-mono">$ {{ number_format($row['precio_venta'], 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-2 text-right">
                                    <span class="text-[9px] font-black text-emerald-400 font-mono">$ {{ number_format($row['total_venta'], 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-2 text-right">
                                    <span class="text-[9px] font-black text-yellow-400 font-mono">$ {{ number_format($row['utilidad'], 0, ',', '.') }}</span>
                                </td>
                                <td class="px-2 py-2 text-center">
                                    @if($row['facturado'] === 'SI')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-black">OFICIAL</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[8px] font-black">INFORMAL</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end mt-4">
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
