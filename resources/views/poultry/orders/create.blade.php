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
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Nuevo <span class="text-yellow-500">Pedido</span>
                </h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-4 h-[1px] bg-yellow-500/50"></span>
                    <p class="text-[9px] text-gray-500 uppercase tracking-[0.3em] font-bold">Módulo de Suministros Avícolas</p>
                </div>
            </div>
            <div class="hidden md:block text-right">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-600">ID Operativo</p>
                <p class="text-xs font-mono text-yellow-500/50">#TRX-{{ now()->format('Ymd') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 md:py-8"
        x-data="{
            items: [{ type: '', quantity: 0 }],
            dispatchDate: '{{ now()->format('Y-m-d') }}',
            overrideReason: '',
            showOverrideModal: false,
            pendingSubmit: false,

            addItem() { this.items.push({ type: '', quantity: 0 }) },
            removeItem(index) { if(this.items.length > 1) this.items.splice(index, 1) },
            totalQty() {
                return this.items.reduce((acc, item) => acc + parseInt(item.quantity || 0), 0);
            },
            isValidDay() {
                if (!this.dispatchDate) return true;
                let d = this.dispatchDate.split('-');
                let day = new Date(d[0], d[1]-1, d[2]).getDay();
                return day === 1 || day === 4;
            },
            dayName() {
                if (!this.dispatchDate) return '';
                const names = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
                let d = this.dispatchDate.split('-');
                return names[new Date(d[0], d[1]-1, d[2]).getDay()];
            },
            handleSubmit(e) {
                if (!this.isValidDay() && !this.overrideReason.trim()) {
                    e.preventDefault();
                    this.showOverrideModal = true;
                }
            },
            confirmOverride() {
                if (!this.overrideReason.trim()) return;
                this.showOverrideModal = false;
                this.$nextTick(() => this.$el.querySelector('form').submit());
            }
        }">

        <form method="POST" action="{{ route('poultry.orders.store') }}" class="max-w-4xl mx-auto">
            @csrf

            <div class="bg-[#0d121f] border border-white/5 shadow-2xl rounded-[2.5rem] overflow-hidden">
                
                {{-- SECCIÓN 1: LOGÍSTICA DE ORIGEN --}}
                <div class="p-6 md:p-10 border-b border-white/[0.03] bg-white/[0.01]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

                        {{-- SELECCIÓN DE PROVEEDOR --}}
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                <i class="fas fa-truck-loading text-yellow-500"></i>
                                Proveedor de Lote
                            </label>
                            <div class="relative group">
                                <select name="provider_id" 
                                    class="w-full bg-black/40 border border-white/10 rounded-2xl text-white text-sm font-bold focus:border-yellow-500 focus:ring-0 pl-6 h-14 transition-all appearance-none cursor-pointer" 
                                    required>
                                    <option value="" disabled selected>Seleccionar fuente...</option>
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->business_name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-600">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        {{-- FECHA DE DESPACHO --}}
                        <div class="flex flex-col md:items-end space-y-3">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Fecha de Salida</label>
                            <div class="flex flex-col md:items-end">
                                <input type="date" name="dispatch_date" x-model="dispatchDate" required
                                    class="bg-transparent border-none text-white text-3xl md:text-4xl font-black text-left md:text-right focus:ring-0 p-0 [color-scheme:dark] cursor-pointer hover:text-yellow-500 transition-colors tracking-tighter">
                                
                                <div class="mt-2 flex flex-col items-end gap-2">
                                    {{-- Día válido --}}
                                    <span x-show="isValidDay()" class="text-[9px] px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 font-black uppercase tracking-widest border border-emerald-500/20">
                                        <i class="fas fa-check-circle mr-1"></i> <span x-text="dayName()"></span> — Día Válido
                                    </span>

                                    {{-- Día no estándar sin justificación --}}
                                    <span x-show="!isValidDay() && !overrideReason" class="text-[9px] px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 font-black uppercase tracking-widest border border-amber-500/20 animate-pulse cursor-pointer"
                                        @click="showOverrideModal = true">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> <span x-text="dayName()"></span> — Requiere justificación
                                    </span>

                                    {{-- Día no estándar con justificación --}}
                                    <span x-show="!isValidDay() && overrideReason" class="text-[9px] px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 font-black uppercase tracking-widest border border-blue-500/20 cursor-pointer"
                                        @click="showOverrideModal = true"
                                        :title="overrideReason">
                                        <i class="fas fa-shield-alt mr-1"></i> <span x-text="dayName()"></span> — Justificado ✓
                                    </span>
                                </div>

                                {{-- Input oculto con la justificación --}}
                                <input type="hidden" name="date_override_reason" :value="overrideReason">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: MANIFIESTO DE CARGA --}}
                <div class="p-6 md:p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-yellow-500 rounded-full"></div>
                            <h3 class="text-white text-[11px] font-black uppercase tracking-[0.2em]">Detalle de Productos</h3>
                        </div>

                        <button type="button" @click="addItem()" 
                            class="bg-white/5 hover:bg-yellow-500 text-white hover:text-black px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all active:scale-95 border border-white/5">
                            + Añadir Línea
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex flex-col md:flex-row gap-4 p-5 rounded-2xl bg-black/30 border border-white/5 transition-all group hover:border-white/10">
                                
                                {{-- Tipo de Ave --}}
                                <div class="flex-1 relative">
                                    <select :name="'items['+index+'][type]'" x-model="item.type" required
                                        class="w-full bg-transparent border-none text-white text-sm font-black focus:ring-0 appearance-none cursor-pointer">
                                        <option value="" disabled>Seleccionar Producto...</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->icon }} {{ strtoupper($type->name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute left-0 -top-4 text-[8px] font-black text-gray-600 uppercase">Línea Genética</div>
                                </div>

                                {{-- Cantidad --}}
                                <div class="w-full md:w-44 flex items-center bg-[#070a13] rounded-xl px-4 border border-white/10 focus-within:border-yellow-500/50 transition-all relative">
                                    <div class="absolute -top-4 left-0 text-[8px] font-black text-gray-600 uppercase">Cantidad</div>
                                    <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" placeholder="0" min="1" required
                                        class="w-full bg-transparent border-none text-white text-center font-black text-lg focus:ring-0">
                                    <span class="text-[9px] font-black text-gray-600 uppercase ml-2">U.</span>
                                </div>

                                {{-- Acción --}}
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                    class="h-12 w-full md:w-12 flex items-center justify-center rounded-xl bg-rose-500/5 text-gray-600 hover:text-rose-500 hover:bg-rose-500/10 transition-all">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- TOTALIZADOR --}}
                    <div class="mt-8 flex items-center justify-between bg-yellow-500/5 border border-yellow-500/10 p-6 rounded-2xl">
                        <div>
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Volumen Total de Carga</p>
                            <p class="text-[9px] font-bold text-yellow-500/50 uppercase mt-1">Sujeto a capacidad del camión</p>
                        </div>
                        <div class="text-right">
                            <span class="text-4xl font-black text-yellow-500 tracking-tighter" x-text="totalQty().toLocaleString()"></span>
                            <span class="text-[10px] font-black text-yellow-500/50 uppercase ml-1">Aves</span>
                        </div>
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="p-6 md:p-10 bg-white/[0.02] border-t border-white/[0.05] flex flex-col md:flex-row items-center justify-between gap-6">
                    <a href="{{ route('poultry.orders.index') }}" 
                        class="text-[10px] font-black text-gray-600 hover:text-white uppercase tracking-widest transition-colors flex items-center gap-2">
                        <i class="fas fa-chevron-left text-[8px]"></i> Cancelar y Volver
                    </a>

                    <button type="submit" @click="handleSubmit($event)"
                        class="w-full md:w-auto bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 px-12 rounded-2xl uppercase text-[11px] tracking-widest transition-all hover:scale-[1.02] shadow-xl shadow-yellow-500/10 flex items-center justify-center gap-3">
                        Confirmar Programación
                        <i class="fas fa-paper-plane text-[10px]"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- MODAL DE JUSTIFICACIÓN --}}
    <div x-show="showOverrideModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @keydown.escape.window="showOverrideModal = false">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="showOverrideModal = false"></div>

        {{-- Panel --}}
        <div class="relative w-full max-w-md bg-[#0d121f] border border-white/10 rounded-3xl p-8 shadow-2xl"
            @click.stop>

            {{-- Ícono --}}
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center shrink-0">
                    <i class="fas fa-calendar-exclamation text-amber-400 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-white font-black text-lg tracking-tight">Despacho Extraordinario</h3>
                    <p class="text-white/40 text-xs mt-0.5">
                        <span x-text="dayName()"></span> no es día estándar de despacho
                    </p>
                </div>
            </div>

            <p class="text-white/50 text-sm mb-6 leading-relaxed">
                Los despachos regulares son los <strong class="text-white/70">lunes y jueves</strong>.
                Para programar en otra fecha indique la razón — quedará registrada en el pedido.
            </p>

            {{-- Opciones rápidas --}}
            <div class="grid grid-cols-2 gap-2 mb-4">
                @foreach(['Día festivo', 'Eventualidad operativa', 'Solicitud del cliente', 'Pedido urgente'] as $opcion)
                <button type="button"
                    @click="overrideReason = '{{ $opcion }}'"
                    :class="overrideReason === '{{ $opcion }}' ? 'border-amber-400 text-amber-400 bg-amber-500/10' : 'border-white/10 text-white/50 hover:border-white/30 hover:text-white/80'"
                    class="text-[11px] font-bold px-3 py-2 rounded-xl border transition-all text-left">
                    {{ $opcion }}
                </button>
                @endforeach
            </div>

            {{-- Campo libre --}}
            <input type="text" x-model="overrideReason" placeholder="O escribe la justificación..."
                class="w-full bg-black/40 border border-white/10 focus:border-amber-500 rounded-2xl text-white text-sm font-bold px-4 py-3 focus:ring-0 transition-colors placeholder-white/20 mb-6">

            {{-- Acciones --}}
            <div class="flex gap-3">
                <button type="button" @click="showOverrideModal = false"
                    class="flex-1 py-3 rounded-2xl border border-white/10 text-white/50 hover:text-white hover:border-white/30 text-[11px] font-black uppercase tracking-widest transition-all">
                    Cancelar
                </button>
                <button type="button" @click="confirmOverride()"
                    :disabled="!overrideReason.trim()"
                    :class="!overrideReason.trim() ? 'opacity-30 cursor-not-allowed' : 'hover:bg-amber-400'"
                    class="flex-1 py-3 rounded-2xl bg-amber-500 text-black text-[11px] font-black uppercase tracking-widest transition-all">
                    Confirmar y Programar
                </button>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.5;
            cursor: pointer;
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
</x-app-layout>