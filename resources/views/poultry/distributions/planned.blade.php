{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('poultry.orders.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">
                        Distribución Programada · Pedido #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        ¿A quién le <span class="text-yellow-500">vendo</span>?
                    </h2>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">{{ $order->dispatch_date->translatedFormat('d F Y') }}</p>
                <p class="text-[9px] font-black text-yellow-400 uppercase mt-0.5">{{ $order->poultryType?->name ?? $order->poultry_type }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
             class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-5 py-3 rounded-2xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- TABLERO PRINCIPAL --}}
        <div class="grid grid-cols-3 gap-4">

            {{-- Total programado --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 text-center">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Pedido a PRONAVICOLA</p>
                <p class="text-4xl font-black text-white tracking-tighter">{{ number_format($order->quantity) }}</p>
                <p class="text-[9px] text-zinc-600 mt-1">aves programadas</p>
            </div>

            {{-- Asignadas --}}
            <div class="bg-[#0d121f] border border-emerald-500/20 rounded-2xl p-5 text-center">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Asignadas a Clientes</p>
                <p class="text-4xl font-black text-emerald-400 tracking-tighter" id="total-assigned">{{ number_format($totalAssigned) }}</p>
                <p class="text-[9px] text-zinc-600 mt-1">{{ $order->distributions->count() }} clientes</p>
            </div>

            {{-- Disponibles --}}
            <div class="bg-[#0d121f] border border-{{ $remaining > 0 ? 'yellow-500/30' : 'white/5' }} rounded-2xl p-5 text-center">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Por Asignar</p>
                <p class="text-4xl font-black {{ $remaining > 0 ? 'text-yellow-400' : 'text-zinc-600' }} tracking-tighter">
                    {{ number_format($remaining) }}
                </p>
                @if($remaining < 0)
                <p class="text-[9px] text-red-400 font-black mt-1 uppercase">⚠ Excede el pedido</p>
                @elseif($remaining == 0)
                <p class="text-[9px] text-emerald-400 font-black mt-1 uppercase">✓ Todo asignado</p>
                @else
                <p class="text-[9px] text-zinc-600 mt-1">aves sin asignar</p>
                @endif
            </div>
        </div>

        {{-- BARRA DE PROGRESO --}}
        @php $pct = $order->quantity > 0 ? min(100, round(($totalAssigned / $order->quantity) * 100)) : 0; @endphp
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
            <div class="flex justify-between text-[9px] font-black mb-2">
                <span class="text-zinc-500 uppercase tracking-widest">Progreso de distribución</span>
                <span class="{{ $pct >= 100 ? 'text-emerald-400' : 'text-yellow-400' }}">{{ $pct }}%</span>
            </div>
            <div class="w-full h-3 bg-white/5 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $pct >= 100 ? 'bg-emerald-500' : 'bg-yellow-500' }}"
                     style="width: {{ $pct }}%"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- COLUMNA IZQUIERDA: Clientes asignados --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Clientes fijos auto-carga --}}
                @if($fixedClients->count() > 0)
                <div class="bg-yellow-500/5 border border-yellow-500/20 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-yellow-500/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-yellow-400">{{ $fixedClients->count() }} Clientes Fijos sin asignar</p>
                                <p class="text-[9px] text-zinc-500">{{ number_format($fixedClients->sum('fixed_weekly_quantity')) }} aves comprometidas</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('poultry.distribution.auto-fixed', $order) }}">
                            @csrf
                            <button type="submit"
                                class="h-9 px-5 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                                <i class="fas fa-magic text-[9px]"></i> Cargar Todos
                            </button>
                        </form>
                    </div>
                    <div class="divide-y divide-yellow-500/10">
                        @foreach($fixedClients as $fc)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-black text-white">{{ $fc->name }}</p>
                                <p class="text-[9px] text-zinc-500">Fijo: {{ number_format($fc->fixed_weekly_quantity) }} aves/sem</p>
                            </div>
                            <form method="POST" action="{{ route('poultry.distribution.store', $order) }}" class="flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $fc->id }}">
                                <input type="hidden" name="quantity" value="{{ $fc->fixed_weekly_quantity }}">
                                <button type="submit"
                                    class="h-7 px-3 bg-yellow-500/10 border border-yellow-500/20 hover:bg-yellow-500/20 text-yellow-400 rounded-lg text-[9px] font-black uppercase transition-all">
                                    + Agregar
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Lista de distribución actual --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Distribución Programada</h3>
                        @if($order->distributions->count() > 0)
                        <span class="text-[9px] font-black text-zinc-500">{{ $order->distributions->count() }} clientes</span>
                        @endif
                    </div>

                    @if($order->distributions->isEmpty())
                    <div class="py-14 flex flex-col items-center gap-3 text-zinc-700">
                        <i class="fas fa-users text-4xl opacity-20"></i>
                        <p class="text-[9px] font-black uppercase tracking-widest">Sin clientes asignados</p>
                        <p class="text-[9px] text-zinc-700">Carga los fijos o agrega manualmente</p>
                    </div>
                    @else
                    <div class="divide-y divide-white/[0.04]">
                        @foreach($order->distributions as $dist)
                        {{-- Fila resumen (siempre visible) --}}
                        <div x-data="{ open: false }" class="group">
                            <div class="px-5 py-3 flex items-center gap-3 hover:bg-white/[0.02] transition-colors cursor-pointer"
                                 @click="open = !open">
                                {{-- Expand icon --}}
                                <i class="fas fa-chevron-right text-[9px] text-zinc-600 group-hover:text-zinc-400 transition-all"
                                   :class="open ? 'rotate-90' : ''"></i>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="text-xs font-black text-white truncate">{{ $dist->customer->name }}</p>
                                        @if($dist->customer->is_fixed_client)
                                        <span class="text-[8px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-1.5 py-0.5 rounded">FIJO</span>
                                        @endif
                                        @if($dist->beak_condition)
                                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded
                                            {{ $dist->beak_condition === 'sin_pico' ? 'text-orange-400 bg-orange-500/10 border border-orange-500/20' : 'text-sky-400 bg-sky-500/10 border border-sky-500/20' }}">
                                            {{ $dist->beak_condition === 'sin_pico' ? '✂ SIN PICO' : '○ CON PICO' }}
                                        </span>
                                        @endif
                                        @if($dist->sale_price)
                                        <span class="text-[8px] font-black text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.5 rounded">
                                            $ {{ number_format($dist->sale_price, 0, ',', '.') }}
                                        </span>
                                        @endif
                                    </div>
                                    @if($dist->observations)
                                    <p class="text-[9px] text-zinc-500 mt-0.5 truncate"><i class="fas fa-comment-medical text-[8px] mr-1"></i>{{ $dist->observations }}</p>
                                    @endif
                                </div>

                                <span class="text-sm font-black text-white shrink-0">{{ number_format($dist->quantity) }}</span>

                                {{-- Eliminar --}}
                                <form method="POST" action="{{ route('poultry.distribution.destroy', [$order, $dist]) }}"
                                      @click.stop>
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="h-7 w-7 rounded-lg hover:bg-red-500/10 text-zinc-700 hover:text-red-400 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                        <i class="fas fa-times text-[10px]"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- Panel edición expandible --}}
                            <div x-show="open" x-transition class="px-5 pb-4 bg-black/30">
                                <form method="POST" action="{{ route('poultry.distribution.update', [$order, $dist]) }}"
                                      class="space-y-3 pt-3 border-t border-white/5">
                                    @csrf @method('PUT')

                                    {{-- Fila 1: cantidad + precio pollita --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">Cantidad Aves</label>
                                            <input type="number" name="quantity" value="{{ $dist->quantity }}" min="1"
                                                   class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-sm font-black text-white text-center outline-none focus:border-yellow-500/50">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">Precio Pollita</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-xs">$</span>
                                                <input type="number" name="sale_price" value="{{ $dist->sale_price }}"
                                                       step="0.01" min="0" placeholder="0.00"
                                                       class="w-full pl-6 pr-3 py-2 rounded-lg bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                            </div>
                                        </div>
                                    </div>

                                    @php $isPollita = in_array($order->poultryType?->code ?? $order->poultry_type, ['lsl','lohmann_brown','pollita_levantada','h_n','hn']); @endphp
                                    @if($isPollita)
                                    {{-- Fila 2: vacuna + despique (solo pollitas) --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">
                                                <i class="fas fa-syringe text-[8px] mr-1 text-purple-400"></i>Precio Vacuna
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-xs">$</span>
                                                <input type="number" name="vaccine_price" value="{{ $dist->vaccine_price }}"
                                                       step="0.01" min="0" placeholder="0.00"
                                                       class="w-full pl-6 pr-3 py-2 rounded-lg bg-black/40 border border-purple-500/20 text-sm text-white outline-none focus:border-purple-500/50">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">
                                                <i class="fas fa-cut text-[8px] mr-1 text-orange-400"></i>Precio Despique
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-xs">$</span>
                                                <input type="number" name="despique_price" value="{{ $dist->despique_price }}"
                                                       step="0.01" min="0" placeholder="0.00"
                                                       class="w-full pl-6 pr-3 py-2 rounded-lg bg-black/40 border border-orange-500/20 text-sm text-white outline-none focus:border-orange-500/50">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Fila 3: condición del pico --}}
                                    <div>
                                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">Condición del Pico</label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-all
                                                {{ $dist->beak_condition === 'con_pico' ? 'border-sky-500/50 bg-sky-500/10' : 'border-white/10 bg-black/40' }}">
                                                <input type="radio" name="beak_condition" value="con_pico" class="accent-sky-400"
                                                       {{ $dist->beak_condition === 'con_pico' ? 'checked' : '' }}>
                                                <span class="text-[10px] font-black text-white">○ Con Pico</span>
                                            </label>
                                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-all
                                                {{ $dist->beak_condition === 'sin_pico' ? 'border-orange-500/50 bg-orange-500/10' : 'border-white/10 bg-black/40' }}">
                                                <input type="radio" name="beak_condition" value="sin_pico" class="accent-orange-400"
                                                       {{ $dist->beak_condition === 'sin_pico' ? 'checked' : '' }}>
                                                <span class="text-[10px] font-black text-white">✂ Sin Pico</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Observaciones --}}
                                    <div class="grid grid-cols-4 gap-3">
                                        <div class="col-span-3">
                                            <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">
                                                <i class="fas fa-comment-medical text-[8px] mr-1 text-emerald-400"></i>Observaciones Veterinarias
                                            </label>
                                            <input type="text" name="observations" value="{{ $dist->observations }}"
                                                   placeholder="Vacunas requeridas, condiciones especiales..."
                                                   class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="submit"
                                                    class="w-full h-9 bg-yellow-500 hover:bg-yellow-400 text-black rounded-lg text-[9px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                                                <i class="fas fa-save text-[9px]"></i> Guardar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- ══ RESUMEN TIPO FACTURA (solo clientes con precios) ══ --}}
                    @php
                        // Solo incluir distribuciones con al menos un precio cargado
                        $distsConPrecio = $order->distributions->filter(fn($d) =>
                            $d->sale_price || $d->vaccine_price || $d->despique_price
                        );

                        $totAves    = 0;
                        $totPollita = 0;
                        $totVacuna  = 0;
                        $totDesp    = 0;
                        $totGeneral = 0;
                        foreach ($distsConPrecio as $d) {
                            $sp = $d->quantity * ($d->sale_price     ?? 0);
                            $sv = $d->quantity * ($d->vaccine_price  ?? 0);
                            $sd = $d->quantity * ($d->despique_price ?? 0);
                            $totAves    += $d->quantity;
                            $totPollita += $sp;
                            $totVacuna  += $sv;
                            $totDesp    += $sd;
                            $totGeneral += $sp + $sv + $sd;
                        }
                    @endphp

                    @if($distsConPrecio->count() > 0)
                    <div class="border-t border-white/10 bg-black/30">

                        {{-- Encabezado --}}
                        <div class="px-5 py-2.5 border-b border-white/5 bg-white/[0.02] flex items-center gap-2">
                            <i class="fas fa-file-invoice-dollar text-yellow-500/50 text-[10px]"></i>
                            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Resumen de Precios · {{ $distsConPrecio->count() }} {{ $distsConPrecio->count() === 1 ? 'cliente' : 'clientes' }}</p>
                        </div>

                        {{-- Fila por cliente con precio --}}
                        @foreach($distsConPrecio as $d)
                        @php
                            $sp = $d->quantity * ($d->sale_price     ?? 0);
                            $sv = $d->quantity * ($d->vaccine_price  ?? 0);
                            $sd = $d->quantity * ($d->despique_price ?? 0);
                            $st = $sp + $sv + $sd;
                        @endphp
                        <div class="px-5 py-3 border-b border-white/[0.04] hover:bg-white/[0.02] transition-colors">
                            {{-- Nombre + condición pico --}}
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <p class="text-xs font-black text-white">{{ $d->customer->name }}</p>
                                    @if($d->beak_condition)
                                    <span class="text-[8px] font-black px-1.5 py-0.5 rounded
                                        {{ $d->beak_condition === 'sin_pico' ? 'text-orange-400 bg-orange-500/10 border border-orange-500/20' : 'text-sky-400 bg-sky-500/10 border border-sky-500/20' }}">
                                        {{ $d->beak_condition === 'sin_pico' ? '✂ SIN PICO' : '○ CON PICO' }}
                                    </span>
                                    @endif
                                </div>
                                <span class="text-[9px] font-black text-zinc-500">{{ number_format($d->quantity) }} aves</span>
                            </div>
                            {{-- Desglose de servicios --}}
                            <div class="space-y-1 pl-1">
                                @if($d->sale_price)
                                <div class="flex justify-between items-center">
                                    <p class="text-[9px] text-zinc-500">Pollita · {{ number_format($d->quantity) }} × $ {{ number_format($d->sale_price, 0, ',', '.') }}</p>
                                    <p class="text-[10px] font-black text-white font-mono">$ {{ number_format($sp, 0, ',', '.') }}</p>
                                </div>
                                @endif
                                @if($d->vaccine_price)
                                <div class="flex justify-between items-center">
                                    <p class="text-[9px] text-purple-400/70"><i class="fas fa-syringe text-[8px] mr-1"></i>Vacuna · {{ number_format($d->quantity) }} × $ {{ number_format($d->vaccine_price, 0, ',', '.') }}</p>
                                    <p class="text-[10px] font-black text-purple-300 font-mono">$ {{ number_format($sv, 0, ',', '.') }}</p>
                                </div>
                                @endif
                                @if($d->despique_price)
                                <div class="flex justify-between items-center">
                                    <p class="text-[9px] text-orange-400/70"><i class="fas fa-cut text-[8px] mr-1"></i>Despique · {{ number_format($d->quantity) }} × $ {{ number_format($d->despique_price, 0, ',', '.') }}</p>
                                    <p class="text-[10px] font-black text-orange-300 font-mono">$ {{ number_format($sd, 0, ',', '.') }}</p>
                                </div>
                                @endif
                            </div>
                            {{-- Subtotal cliente --}}
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-white/5">
                                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Subtotal</p>
                                <p class="text-sm font-black text-emerald-400 font-mono">$ {{ number_format($st, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach

                        {{-- Gran total --}}
                        <div class="px-5 py-4 flex items-center justify-between bg-emerald-500/5 border-t border-emerald-500/20">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Valor Total del Pedido</p>
                                <div class="flex gap-3 mt-1 flex-wrap">
                                    @if($totPollita > 0)
                                    <p class="text-[9px] text-zinc-600">Pollita: <span class="text-white font-black">$ {{ number_format($totPollita, 0, ',', '.') }}</span></p>
                                    @endif
                                    @if($totVacuna > 0)
                                    <p class="text-[9px] text-zinc-600">Vacuna: <span class="text-purple-300 font-black">$ {{ number_format($totVacuna, 0, ',', '.') }}</span></p>
                                    @endif
                                    @if($totDesp > 0)
                                    <p class="text-[9px] text-zinc-600">Despique: <span class="text-orange-300 font-black">$ {{ number_format($totDesp, 0, ',', '.') }}</span></p>
                                    @endif
                                </div>
                            </div>
                            <p class="text-2xl font-black text-emerald-400 tracking-tighter font-mono">$ {{ number_format($totGeneral, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            {{-- COLUMNA DERECHA: Agregar cliente --}}
            <div class="space-y-4">
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-white/5">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Agregar Cliente</h3>
                    </div>
                    <div class="p-5">
                        <form method="POST" action="{{ route('poultry.distribution.store', $order) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Cliente</label>
                                <select name="customer_id" required onchange="autoFillQty(this)"
                                    class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                    <option value="">Seleccionar...</option>
                                    @foreach($allClients as $client)
                                    <option value="{{ $client->id }}"
                                        data-qty="{{ $client->fixed_weekly_quantity }}"
                                        data-fixed="{{ $client->is_fixed_client ? 1 : 0 }}">
                                        {{ $client->name }}{{ $client->is_fixed_client ? ' ★' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Cantidad de Aves</label>
                                <input type="number" name="quantity" id="qty_input" min="1"
                                    placeholder="0"
                                    class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                <p class="text-[9px] text-zinc-600 mt-1">Disponible: <span class="text-yellow-400 font-black">{{ number_format(max(0, $remaining)) }}</span></p>
                            </div>

                            {{-- Precio pollita --}}
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Precio Pollita</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-xs">$</span>
                                    <input type="number" name="sale_price" step="0.01" min="0"
                                           placeholder="0.00"
                                           class="w-full pl-6 pr-3 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                </div>
                            </div>

                            @php $isPollitaForm = in_array($order->poultryType?->code ?? $order->poultry_type, ['lsl','lohmann_brown','pollita_levantada','h_n','hn']); @endphp
                            @if($isPollitaForm)
                            {{-- Vacuna y Despique (solo pollitas) --}}
                            <div class="bg-purple-500/5 border border-purple-500/20 rounded-xl p-3 space-y-3">
                                <p class="text-[9px] font-black text-purple-400 uppercase tracking-widest">
                                    <i class="fas fa-stethoscope text-[8px] mr-1"></i>Servicios Veterinarios
                                </p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">
                                            <i class="fas fa-syringe text-[8px] mr-1 text-purple-400"></i>Precio Vacuna
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-xs">$</span>
                                            <input type="number" name="vaccine_price" step="0.01" min="0"
                                                   placeholder="0.00"
                                                   class="w-full pl-6 pr-3 py-2 rounded-lg bg-black/40 border border-purple-500/20 text-sm text-white outline-none focus:border-purple-500/50">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">
                                            <i class="fas fa-cut text-[8px] mr-1 text-orange-400"></i>Precio Despique
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-xs">$</span>
                                            <input type="number" name="despique_price" step="0.01" min="0"
                                                   placeholder="0.00"
                                                   class="w-full pl-6 pr-3 py-2 rounded-lg bg-black/40 border border-orange-500/20 text-sm text-white outline-none focus:border-orange-500/50">
                                        </div>
                                    </div>
                                </div>

                                {{-- Condición pico --}}
                                <div>
                                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1">Condición del Pico</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-white/10 bg-black/40 cursor-pointer has-[:checked]:border-sky-500/50 has-[:checked]:bg-sky-500/5 transition-all">
                                            <input type="radio" name="beak_condition" value="con_pico" class="accent-sky-400">
                                            <span class="text-[10px] font-black text-white">○ Con Pico</span>
                                        </label>
                                        <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-white/10 bg-black/40 cursor-pointer has-[:checked]:border-orange-500/50 has-[:checked]:bg-orange-500/5 transition-all">
                                            <input type="radio" name="beak_condition" value="sin_pico" class="accent-orange-400">
                                            <span class="text-[10px] font-black text-white">✂ Sin Pico</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Observaciones --}}
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
                                    <i class="fas fa-comment-medical text-[9px] mr-1 text-emerald-400"></i>Observaciones Veterinarias
                                </label>
                                <textarea name="observations" rows="2"
                                    placeholder="Vacunas requeridas, condiciones especiales..."
                                    class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 resize-none"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-yellow-500 hover:bg-yellow-400 text-black py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-plus text-xs"></i> Agregar a Distribución
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Info del pedido --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 space-y-3">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-500 mb-3">Resumen del Pedido</p>
                    @foreach([
                        ['Proveedor', $order->provider?->business_name ?? 'PRONAVICOLA'],
                        ['Tipo', $order->poultryType?->name ?? $order->poultry_type],
                        ['Fecha despacho', $order->dispatch_date->format('d/m/Y')],
                        ['Estado', ucfirst($order->status)],
                    ] as [$label, $value])
                    <div class="flex justify-between items-center py-1 border-b border-white/5">
                        <p class="text-[9px] text-zinc-600 font-black uppercase tracking-widest">{{ $label }}</p>
                        <p class="text-[9px] font-black text-white">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function autoFillQty(select) {
            const opt = select.options[select.selectedIndex];
            const isFixed = opt.dataset.fixed === '1';
            const qty = parseInt(opt.dataset.qty) || 0;
            if (isFixed && qty > 0) {
                document.getElementById('qty_input').value = qty;
            }
        }
    </script>
</x-app-layout>
