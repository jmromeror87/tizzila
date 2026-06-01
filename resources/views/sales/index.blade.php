<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Módulo de Ventas</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Registro de <span class="text-yellow-500">Ventas</span>
                </h2>
            </div>
            <a href="{{ route('sales.import.form') }}"
               class="h-10 px-6 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                <i class="fas fa-file-import"></i> Importar CSV
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- KPIs + Filtros integrados --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">

            @if($resumen)
            @php $margen = $resumen->total_venta > 0 ? round(($resumen->total_utilidad / $resumen->total_venta) * 100, 1) : 0; @endphp
            <div class="grid grid-cols-2 lg:grid-cols-5 divide-x divide-white/5">
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Aves</p>
                    <h2 class="text-2xl font-black text-white">{{ number_format($resumen->total_aves, 0, ',', '.') }} <span class="text-yellow-500 text-lg font-bold">Aves</span></h2>
                    <div class="mt-2 h-0.5 w-full bg-white/5 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Venta</p>
                    <h2 class="text-2xl font-black text-emerald-400">${{ number_format($resumen->total_venta, 0, ',', '.') }}</h2>
                    <div class="mt-2 h-0.5 w-full bg-emerald-500/20 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Compra</p>
                    <h2 class="text-2xl font-black text-red-400">${{ number_format($resumen->total_compra, 0, ',', '.') }}</h2>
                    <div class="mt-2 h-0.5 w-full bg-red-500/20 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Utilidad <span class="text-yellow-500">{{ $margen }}%</span></p>
                    <h2 class="text-2xl font-black text-yellow-400">${{ number_format($resumen->total_utilidad, 0, ',', '.') }}</h2>
                    <div class="mt-2 h-0.5 w-full bg-yellow-500/20 rounded-full"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Registros</p>
                    <h2 class="text-2xl font-black text-white">{{ number_format($resumen->total_registros, 0, ',', '.') }} <span class="text-zinc-500 text-lg font-bold">Docs</span></h2>
                    <div class="mt-2 h-0.5 w-full bg-white/5 rounded-full"></div>
                </div>
            </div>
            @endif

            {{-- Filtros --}}
            <div class="border-t border-white/5 px-5 py-4 bg-black/20">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Mes</label>
                        <select name="mes" class="px-3 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white text-xs font-bold outline-none focus:border-yellow-500/50 transition-all">
                            <option value="">Todos</option>
                            @foreach(['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'] as $num => $nombre)
                            <option value="{{ $num }}" {{ request('mes') == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Año</label>
                        <select name="ano" class="px-3 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white text-xs font-bold outline-none focus:border-yellow-500/50 transition-all">
                            <option value="">Todos</option>
                            @foreach([2026, 2025, 2024] as $y)
                            <option value="{{ $y }}" {{ request('ano') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Producto</label>
                        <select name="tipo" class="px-3 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white text-xs font-bold outline-none focus:border-yellow-500/50 transition-all">
                            <option value="">Todos</option>
                            <option value="pollito" {{ request('tipo') == 'pollito' ? 'selected' : '' }}>Pollito</option>
                            <option value="pollita" {{ request('tipo') == 'pollita' ? 'selected' : '' }}>Pollita</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Estado</label>
                        <select name="status" class="px-3 py-2.5 rounded-xl bg-black/40 border border-white/10 text-white text-xs font-bold outline-none focus:border-yellow-500/50 transition-all">
                            <option value="">Todos</option>
                            <option value="facturado" {{ request('status') == 'facturado' ? 'selected' : '' }}>Oficial</option>
                            <option value="sin_factura" {{ request('status') == 'sin_factura' ? 'selected' : '' }}>Informal</option>
                        </select>
                    </div>
                    <div class="flex gap-2 items-end">
                        <button type="submit" class="h-10 px-5 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('sales.index') }}" class="h-10 px-4 bg-white/5 border border-white/10 text-zinc-400 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/5 bg-white/[0.02]">
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Fecha</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Factura</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Cliente</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Zona</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Producto</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Cant.</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">P.Compra</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">P.Venta</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Total</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Utilidad</th>
                            <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($records as $r)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-4 py-3">
                                <span class="text-[9px] text-zinc-400">{{ $r->sale_date->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-[9px] font-black font-mono {{ $r->invoice_number ? 'text-yellow-400' : 'text-zinc-600' }}">
                                    {{ $r->invoice_number ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-[200px]">
                                <p class="text-[10px] font-bold text-white truncate">{{ $r->nombre_cliente }}</p>
                                <p class="text-[8px] text-zinc-500 font-mono">{{ $r->nit_cliente }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-[9px] text-zinc-400">{{ $r->zona }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-[9px] font-bold text-white">{{ strtoupper($r->tipo_producto) }}
                                    <span class="text-zinc-500 font-normal">· {{ $r->linea }}</span>
                                </p>
                                @if($r->observacion && $r->observacion !== 'NINGUNA')
                                <p class="text-[8px] text-zinc-500">{{ $r->observacion }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-[10px] font-bold text-zinc-200 font-mono">{{ number_format($r->cantidad, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-[9px] text-zinc-500 font-mono">$ {{ number_format($r->precio_compra, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-[9px] text-zinc-300 font-mono">$ {{ number_format($r->precio_venta, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-[11px] font-black text-emerald-400 font-mono">$ {{ number_format($r->total_venta, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-[10px] font-black text-yellow-400 font-mono">$ {{ number_format($r->utilidad, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($r->invoice_status === 'facturado')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-black uppercase tracking-widest">Oficial</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[8px] font-black uppercase tracking-widest">Informal</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-4 py-20 text-center">
                                <i class="fas fa-chart-line text-4xl text-zinc-700 mb-4 block"></i>
                                <p class="text-zinc-500 text-[11px] uppercase tracking-widest font-black mb-3">Sin registros de ventas</p>
                                <a href="{{ route('sales.import.form') }}"
                                   class="inline-flex items-center gap-2 h-9 px-5 bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-yellow-500/20 transition-all">
                                    <i class="fas fa-file-import"></i> Importar primer CSV
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
            <div class="border-t border-white/5 px-4 py-3 bg-black/10">
                {{ $records->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
