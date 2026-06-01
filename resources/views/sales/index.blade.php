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

        {{-- Filtros --}}
        <form method="GET" class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 flex items-center gap-3 flex-wrap">
            <select name="mes" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-[10px] font-black text-white outline-none focus:border-yellow-500/50">
                <option value="">Todos los meses</option>
                @foreach(['01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'] as $num => $nombre)
                <option value="{{ $num }}" {{ request('mes') == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
            <select name="ano" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-[10px] font-black text-white outline-none focus:border-yellow-500/50">
                <option value="">Todos los años</option>
                @foreach([2026, 2025, 2024] as $y)
                <option value="{{ $y }}" {{ request('ano') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <select name="tipo" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-[10px] font-black text-white outline-none focus:border-yellow-500/50">
                <option value="">Todos los productos</option>
                <option value="pollito" {{ request('tipo') == 'pollito' ? 'selected' : '' }}>Pollito</option>
                <option value="pollita" {{ request('tipo') == 'pollita' ? 'selected' : '' }}>Pollita</option>
            </select>
            <select name="status" class="bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-[10px] font-black text-white outline-none focus:border-yellow-500/50">
                <option value="">Todos</option>
                <option value="facturado" {{ request('status') == 'facturado' ? 'selected' : '' }}>Facturados</option>
                <option value="sin_factura" {{ request('status') == 'sin_factura' ? 'selected' : '' }}>Sin Factura</option>
            </select>
            <button type="submit" class="h-9 px-5 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="{{ route('sales.index') }}" class="h-9 px-4 bg-white/5 border border-white/10 text-zinc-400 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center">
                Limpiar
            </a>
        </form>

        {{-- KPIs --}}
        @if($resumen)
        <div class="grid grid-cols-5 gap-3">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 text-center">
                <p class="text-[8px] font-black uppercase tracking-widest text-zinc-500 mb-1">Registros</p>
                <p class="text-2xl font-black text-white">{{ number_format($resumen->total_registros, 0, ',', '.') }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 text-center">
                <p class="text-[8px] font-black uppercase tracking-widest text-zinc-500 mb-1">Total Aves</p>
                <p class="text-xl font-black text-white">{{ number_format($resumen->total_aves, 0, ',', '.') }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 text-center">
                <p class="text-[8px] font-black uppercase tracking-widest text-zinc-500 mb-1">Total Venta</p>
                <p class="text-lg font-black text-emerald-400">$ {{ number_format($resumen->total_venta, 0, ',', '.') }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 text-center">
                <p class="text-[8px] font-black uppercase tracking-widest text-zinc-500 mb-1">Total Compra</p>
                <p class="text-lg font-black text-red-400">$ {{ number_format($resumen->total_compra, 0, ',', '.') }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 text-center">
                <p class="text-[8px] font-black uppercase tracking-widest text-zinc-500 mb-1">Utilidad</p>
                <p class="text-lg font-black text-yellow-400">$ {{ number_format($resumen->total_utilidad, 0, ',', '.') }}</p>
            </div>
        </div>
        @endif

        {{-- Tabla --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/5 bg-white/[0.02]">
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Fecha</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Factura</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Cliente</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Zona</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-left">Producto</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Cant.</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">P.Venta</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Total</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-right">Utilidad</th>
                            <th class="px-3 py-3 text-[8px] font-black uppercase tracking-widest text-zinc-500 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($records as $r)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-3 py-2.5">
                                <span class="text-[9px] text-zinc-400">{{ $r->sale_date->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="text-[9px] font-black font-mono {{ $r->invoice_number ? 'text-yellow-400' : 'text-zinc-600' }}">
                                    {{ $r->invoice_number ?? 'SIN' }}
                                </span>
                            </td>
                            <td class="px-3 py-2.5 max-w-[180px]">
                                <p class="text-[10px] text-white truncate">{{ $r->nombre_cliente }}</p>
                                <p class="text-[8px] text-zinc-500 font-mono">{{ $r->nit_cliente }}</p>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="text-[9px] text-zinc-400">{{ $r->zona }}</span>
                            </td>
                            <td class="px-3 py-2.5">
                                <p class="text-[9px] text-white">{{ strtoupper($r->tipo_producto) }} · {{ $r->linea }}</p>
                                @if($r->observacion && $r->observacion !== 'NINGUNA')
                                <p class="text-[8px] text-zinc-500">{{ $r->observacion }}</p>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-right">
                                <span class="text-[9px] text-zinc-300 font-mono">{{ number_format($r->cantidad, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-right">
                                <span class="text-[9px] text-zinc-400 font-mono">$ {{ number_format($r->precio_venta, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-right">
                                <span class="text-[10px] font-black text-emerald-400 font-mono">$ {{ number_format($r->total_venta, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-right">
                                <span class="text-[9px] font-black text-yellow-400 font-mono">$ {{ number_format($r->utilidad, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                @if($r->invoice_status === 'facturado')
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-black">OFICIAL</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[8px] font-black">INFORMAL</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-3 py-16 text-center">
                                <p class="text-zinc-600 text-[10px] uppercase tracking-widest font-black">Sin registros de ventas</p>
                                <a href="{{ route('sales.import.form') }}" class="mt-3 inline-flex items-center gap-2 text-yellow-500 text-[9px] font-black uppercase tracking-widest hover:text-yellow-400">
                                    <i class="fas fa-file-import"></i> Importar primer CSV
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $records->withQueryString()->links() }}
    </div>
</x-app-layout>
