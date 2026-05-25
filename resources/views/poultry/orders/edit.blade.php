{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    {{-- HEADER INTEGRADO --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-yellow-500/30 blur-lg rounded-2xl opacity-70 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative bg-[#0d121f] p-4 rounded-2xl border border-yellow-500/30 shadow-2xl">
                        <i class="fas fa-file-signature text-yellow-500 text-2xl"></i>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded-md bg-yellow-500/10 border border-yellow-500/20 text-[9px] font-black text-yellow-500 uppercase tracking-widest">Protocolo de Modificación</span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black text-white tracking-tighter leading-none uppercase">
                        Modificar Pedido <span class="text-yellow-500">#{{ $order->id }}</span>
                    </h2>
                </div>
            </div>
            
            <div class="hidden md:flex flex-col items-end">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-600">Fecha Registro</p>
                <p class="text-xs font-mono text-gray-400">{{ $order->created_at->format('d/m/Y - H:i') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 md:py-8" x-data="{ 
        loading: false,
        qty: {{ $order->quantity }},
        originalQty: {{ $order->quantity }}
    }">
        <div class="max-w-5xl mx-auto">

            <form method="POST" action="{{ route('poultry.orders.update', $order) }}" @submit="loading = true" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- CONTENEDOR PRINCIPAL --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-[2.5rem] shadow-3xl overflow-hidden relative">
                    
                    {{-- GLOW DE FONDO --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-500/5 blur-[100px] rounded-full pointer-events-none"></div>

                    {{-- 1. SECCIÓN SUPERIOR: DATOS MAESTROS --}}
                    <div class="p-6 md:p-10 border-b border-white/5 bg-white/[0.01]">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                            
                            {{-- PROVEEDOR --}}
                            <div class="space-y-3">
                                <label class="flex items-center gap-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                    <i class="fas fa-truck-container text-yellow-500"></i>
                                    Proveedor Autorizado
                                </label>
                                <div class="relative group">
                                    <select name="provider_id"
                                            class="w-full bg-black/40 border-white/10 rounded-2xl text-white font-bold py-4 pl-6 focus:border-yellow-500 focus:ring-0 transition-all appearance-none cursor-pointer">
                                        @foreach($providers as $provider)
                                            <option value="{{ $provider->id }}" {{ $order->provider_id == $provider->id ? 'selected' : '' }}>
                                                {{ $provider->business_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-600 group-hover:text-yellow-500 transition-colors">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- FECHA DE DESPACHO --}}
                            <div class="flex flex-col md:items-end">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">
                                    Fecha de Salida
                                </label>
                                <div class="inline-flex items-center gap-4 bg-black/30 p-2 rounded-2xl border border-white/5">
                                    <input type="date" 
                                           name="dispatch_date" 
                                           value="{{ $order->dispatch_date->format('Y-m-d') }}"
                                           class="bg-transparent border-none text-white text-2xl md:text-3xl font-black text-left md:text-right focus:ring-0 p-2 [color-scheme:dark] cursor-pointer hover:text-yellow-500 transition-colors tracking-tighter">
                                    <div class="bg-yellow-500/10 p-3 rounded-xl border border-yellow-500/20 hidden sm:block">
                                        <i class="fas fa-calendar-day text-yellow-500"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. SECCIÓN CENTRAL: CONFIGURACIÓN --}}
                    <div class="p-6 md:p-10">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="h-1 w-6 bg-yellow-500 rounded-full"></div>
                            <h3 class="text-white text-[11px] font-black uppercase tracking-widest">Parámetros de Carga</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            {{-- LÍNEA GENÉTICA --}}
                            <div class="bg-black/20 p-6 rounded-3xl border border-white/5 space-y-3">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest block">Línea Genética</label>
                                <select name="poultry_type_id"
                                        class="w-full bg-[#111827] border-white/10 rounded-xl text-white text-sm font-black focus:border-yellow-500 transition-all">
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ $order->poultry_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->icon }} {{ strtoupper($type->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- CANTIDAD --}}
                            <div class="bg-black/20 p-6 rounded-3xl border border-white/5 space-y-3">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest block">Aves (Unidades)</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" 
                                           name="quantity"
                                           x-model="qty"
                                           class="w-full bg-[#111827] border-white/10 rounded-xl text-white text-2xl font-black focus:border-yellow-500 text-center tabular-nums">
                                    <div class="flex flex-col">
                                        <span x-show="qty > originalQty" class="text-[7px] font-black text-emerald-500 uppercase tracking-tighter animate-bounce">+ Subió</span>
                                        <span x-show="qty < originalQty" class="text-[7px] font-black text-rose-500 uppercase tracking-tighter animate-bounce">- Bajó</span>
                                    </div>
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="bg-black/20 p-6 rounded-3xl border border-white/5 space-y-3">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest block">Fase de la Orden</label>
                                <select name="status"
                                        class="w-full bg-[#111827] border-white/10 rounded-xl text-xs font-black uppercase tracking-widest text-yellow-500 focus:border-yellow-500 transition-all">
                                    @foreach(['planned' => 'Planeado', 'dispatched' => 'Despachado', 'paid' => 'Liquidado', 'cancelled' => 'Anulado'] as $value => $label)
                                        <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                                            {{ strtoupper($label) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- NOTAS --}}
                        <div class="mt-6">
                            <div class="bg-black/20 p-6 rounded-3xl border border-white/5 focus-within:border-yellow-500/30 transition-all">
                                <label class="flex items-center gap-2 text-[9px] font-black text-gray-500 uppercase tracking-widest mb-3">
                                    <i class="fas fa-sticky-note text-yellow-500/50"></i>
                                    Observaciones Logísticas
                                </label>
                                <textarea name="notes" rows="2"
                                          placeholder="Sin observaciones..."
                                          class="w-full bg-transparent border-none text-gray-300 text-sm font-bold focus:ring-0 p-0 resize-none placeholder:text-gray-800 tracking-tight leading-relaxed">{{ $order->notes }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- 3. PIE DE ACCIONES --}}
                    <div class="p-6 md:p-10 bg-black/40 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">

                        <div class="flex items-center gap-4">
                            <a href="{{ route('poultry.orders.index') }}"
                               class="flex items-center gap-2 text-[10px] font-black text-gray-600 hover:text-white uppercase tracking-widest transition-all group">
                                <i class="fas fa-times text-[8px] group-hover:rotate-90 transition-transform"></i>
                                Descartar Cambios
                            </a>

                            @if($order->status !== 'cancelled' && $order->status !== 'paid')
                            {{-- Botón Anular --}}
                            <button type="button" @click="$dispatch('open-cancel-modal')"
                                    class="flex items-center gap-2 text-[10px] font-black text-red-500/70 hover:text-red-400 uppercase tracking-widest transition-all group border border-red-500/20 hover:border-red-500/40 px-4 py-2 rounded-xl">
                                <i class="fas fa-ban text-[9px]"></i>
                                Anular Orden
                            </button>
                            @endif

                            @if($order->status === 'cancelled')
                            <div class="flex items-center gap-2 text-[10px] font-black text-red-400 uppercase tracking-widest border border-red-500/20 px-4 py-2 rounded-xl bg-red-500/5">
                                <i class="fas fa-ban text-[9px]"></i>
                                Orden Anulada
                            </div>
                            @endif
                        </div>

                        <button type="submit"
                                :disabled="loading"
                                class="w-full md:w-auto bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 px-10 rounded-2xl transition-all shadow-lg shadow-yellow-500/10 active:scale-95 flex items-center justify-center gap-3">
                            <span x-show="!loading" class="text-[10px] uppercase tracking-widest">Guardar Modificaciones</span>
                            <span x-show="loading" class="text-[10px] uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-sync animate-spin"></i> Sincronizando...
                            </span>
                        </button>
                    </div>

                    {{-- Mostrar razón de anulación si ya fue anulada --}}
                    @if($order->status === 'cancelled' && $order->cancellation_reason)
                    <div class="px-6 md:px-10 py-4 bg-red-500/5 border-t border-red-500/20">
                        <p class="text-[9px] font-black uppercase tracking-widest text-red-400 mb-1">Motivo de Anulación</p>
                        <p class="text-sm text-red-300">{{ $order->cancellation_reason }}</p>
                        @if($order->cancelled_at)
                        <p class="text-[9px] text-zinc-600 mt-1">{{ $order->cancelled_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- INFO SISTEMA --}}
                <div class="flex justify-center items-center gap-6 opacity-30 text-white">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-check text-[10px]"></i>
                        <span class="text-[8px] font-black uppercase tracking-widest">Operación Encriptada</span>
                    </div>
                    <div class="w-1 h-1 rounded-full bg-gray-600"></div>
                    <div class="text-[8px] font-black uppercase tracking-widest">Tizzila Cloud v2.0</div>
                </div>
            </form>

        </div>
    </div>

    {{-- ══ MODAL ANULAR ORDEN ══ --}}
    @if($order->status !== 'cancelled' && $order->status !== 'paid')
    <div x-data="{ open: false }"
         @open-cancel-modal.window="open = true"
         x-show="open"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         style="display:none">

        <div @click.outside="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-[#0d121f] border border-red-500/30 rounded-2xl p-8 w-full max-w-md shadow-2xl">

            <div class="flex items-center gap-3 mb-6">
                <div class="h-10 w-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                    <i class="fas fa-ban text-red-400"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-red-400">Anular Pedido</p>
                    <p class="text-lg font-black text-white leading-none">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} · {{ $order->dispatch_date->format('d/m/Y') }}</p>
                </div>
            </div>

            <p class="text-xs text-zinc-400 mb-5">Esta acción marcará el pedido como <span class="text-red-400 font-black">ANULADO</span>. Indica el motivo — el proveedor pudo haber quitado aves, cambio de condiciones u otro evento.</p>

            <form method="POST" action="{{ route('poultry.orders.cancel', $order) }}">
                @csrf
                <div class="mb-5">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-2">Motivo de Anulación <span class="text-red-400">*</span></label>
                    <textarea name="cancellation_reason" rows="4" required
                        placeholder="Ej: El proveedor redujo la disponibilidad de aves por enfermedad en la granja. Se reprogramará para la próxima semana..."
                        class="w-full px-4 py-3 rounded-xl bg-black/40 border border-red-500/20 text-sm text-white outline-none focus:border-red-500/50 resize-none placeholder-zinc-600"></textarea>
                    @error('cancellation_reason')
                    <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="open = false"
                            class="flex-1 py-3 rounded-xl border border-white/10 text-[10px] font-black uppercase tracking-widest text-zinc-400 hover:text-white hover:border-white/20 transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 rounded-xl bg-red-500 hover:bg-red-400 text-[10px] font-black uppercase tracking-widest text-white transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-ban text-[9px]"></i> Confirmar Anulación
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</x-app-layout>