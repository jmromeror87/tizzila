{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

@csrf
@if(isset($type))
    @method('PUT')
@endif

<div class="space-y-5">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Identificador de Sistema</label>
            <input type="text"
                   name="code"
                   value="{{ old('code', $type->code ?? '') }}"
                   placeholder="EJ: BB-COBB"
                   class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors placeholder:text-gray-700 uppercase font-mono"
                   required>
        </div>

        <div>
            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Nombre Comercial</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $type->name ?? '') }}"
                   placeholder="Nombre de la especie"
                   class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors placeholder:text-gray-700 uppercase"
                   required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div x-data="{ emoji: '{{ old('icon', $type->icon ?? '🐣') }}' }">
            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Ícono / Emoji</label>
            <div class="relative flex items-center">
                <span class="absolute left-4 text-lg" x-text="emoji"></span>
                <input type="text"
                       name="icon"
                       x-model="emoji"
                       class="w-full bg-black/30 border border-white/10 rounded-xl pl-12 pr-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors"
                       placeholder="🐣">
            </div>
        </div>

        <div>
            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Días de Crédito</label>
            <div class="relative">
                <input type="number"
                       name="payment_days"
                       value="{{ old('payment_days', $type->payment_days ?? 15) }}"
                       class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-black text-white focus:outline-none focus:border-yellow-500/50 transition-colors"
                       min="0"
                       required>
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-gray-600 uppercase">Días</span>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between bg-black/20 border border-white/5 rounded-xl px-4 py-3">
        <div>
            <p class="text-xs font-black text-white uppercase">Estatus Operativo</p>
            <p class="text-[9px] text-gray-600 font-bold uppercase mt-0.5">¿Disponible para nuevos pedidos?</p>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox"
                   name="is_active"
                   value="1"
                   class="sr-only peer"
                   {{ old('is_active', $type->is_active ?? true) ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-zinc-800 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-zinc-400 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500 peer-checked:after:bg-black"></div>
        </label>
    </div>

    <button type="submit"
            class="w-full h-10 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2">
        <i class="fas fa-save text-xs"></i>
        {{ isset($type) ? 'Guardar Cambios' : 'Crear Tipo de Ave' }}
    </button>

</div>
