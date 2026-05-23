{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('taxes.index') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Nuevo <span class="text-yellow-500">Impuesto</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Configuración de Tributos DIAN
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-2xl">

            @if ($errors->any())
                <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-5 py-3 rounded-2xl">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-2">Errores de Validación</p>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs font-bold">· {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
                <form action="{{ route('taxes.store') }}" method="POST">
                    @csrf

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Código DIAN</label>
                                <input type="text" name="code" value="{{ old('code') }}" required
                                       placeholder="EJ: IV19"
                                       class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-black text-white focus:outline-none focus:border-yellow-500/50 transition-colors placeholder:text-gray-700 uppercase font-mono">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Nombre del Impuesto</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       placeholder="EJ: IVA Compras 19%"
                                       class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors placeholder:text-gray-700 uppercase">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Tasa Porcentual</label>
                                <div class="relative">
                                    <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" step="0.01" name="percentage" value="{{ old('percentage', '0.00') }}" required
                                           class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-black text-white focus:outline-none focus:border-yellow-500/50 transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-gray-600">%</span>
                                </div>
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Tipo de Tributo</label>
                                <select name="type" class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer uppercase">
                                    <option value="iva" {{ old('type') == 'iva' ? 'selected' : '' }}>IVA (Bienes y Servicios)</option>
                                    <option value="inc" {{ old('type') == 'inc' ? 'selected' : '' }}>INC (Consumo)</option>
                                    <option value="retefuente" {{ old('type') == 'retefuente' ? 'selected' : '' }}>Retención en la Fuente</option>
                                    <option value="reteiva" {{ old('type') == 'reteiva' ? 'selected' : '' }}>Reteiva</option>
                                    <option value="reteica" {{ old('type') == 'reteica' ? 'selected' : '' }}>Reteica</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between bg-black/20 border border-white/5 rounded-xl px-4 py-3">
                            <div>
                                <p class="text-xs font-black text-white uppercase">Estado</p>
                                <p class="text-[9px] text-gray-600 font-bold uppercase mt-0.5">¿Activo para facturación?</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-zinc-800 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-zinc-400 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500 peer-checked:after:bg-black"></div>
                            </label>
                        </div>

                        <button type="submit"
                                class="w-full h-10 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="fas fa-save text-xs"></i> Crear Impuesto
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
