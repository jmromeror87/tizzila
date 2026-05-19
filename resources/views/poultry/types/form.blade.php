{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

@csrf

@if(isset($type))
    @method('PUT')
@endif

<div class="space-y-10 group/form">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- CODE --}}
        <div class="relative">
            <label class="flex items-center gap-2 text-[10px] font-black uppercase text-zinc-500 mb-3 tracking-[0.2em]">
                <i class="fas fa-fingerprint text-yellow-500/50"></i>
                Identificador de Sistema
            </label>
            <input type="text"
                   name="code"
                   value="{{ old('code', $type->code ?? '') }}"
                   placeholder="EJ: BB-COBB"
                   class="w-full bg-black/40 border border-white/5 rounded-2xl text-white px-5 py-4 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/5 transition-all font-mono uppercase placeholder:text-zinc-800"
                   required>
        </div>

        {{-- NAME --}}
        <div class="relative">
            <label class="flex items-center gap-2 text-[10px] font-black uppercase text-zinc-500 mb-3 tracking-[0.2em]">
                <i class="fas fa-tag text-yellow-500/50"></i>
                Etiqueta Comercial
            </label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $type->name ?? '') }}"
                   placeholder="Nombre de la especie"
                   class="w-full bg-black/40 border border-white/5 rounded-2xl text-white px-5 py-4 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/5 transition-all font-bold placeholder:text-zinc-800"
                   required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- ICON --}}
        <div class="relative" x-data="{ emoji: '{{ old('icon', $type->icon ?? '🐣') }}' }">
            <label class="flex items-center gap-2 text-[10px] font-black uppercase text-zinc-500 mb-3 tracking-[0.2em]">
                <i class="fas fa-icons text-yellow-500/50"></i>
                Representación Visual
            </label>
            <div class="relative flex items-center">
                <span class="absolute left-5 text-2xl" x-text="emoji"></span>
                <input type="text"
                       name="icon"
                       x-model="emoji"
                       class="w-full bg-black/40 border border-white/5 rounded-2xl text-white pl-16 pr-5 py-4 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/5 transition-all font-bold"
                       placeholder="Emoji">
            </div>
        </div>

        {{-- PAYMENT DAYS --}}
        <div class="relative">
            <label class="flex items-center gap-2 text-[10px] font-black uppercase text-zinc-500 mb-3 tracking-[0.2em]">
                <i class="fas fa-calendar-alt text-yellow-500/50"></i>
                Ciclo de Liquidación (Días)
            </label>
            <div class="relative">
                <input type="number"
                       name="payment_days"
                       value="{{ old('payment_days', $type->payment_days ?? 15) }}"
                       class="w-full bg-black/40 border border-white/5 rounded-2xl text-white px-5 py-4 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/5 transition-all font-black  text-xl"
                       min="0"
                       required>
                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-zinc-600 uppercase">Días Plazo</span>
            </div>
        </div>
    </div>

    {{-- STATUS TOGGLE (ESTILO TIZZILLA) --}}
    <div class="flex items-center justify-between p-6 bg-white/[0.02] border border-white/5 rounded-[2rem]">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-zinc-900 flex items-center justify-center border border-white/5 text-yellow-500">
                <i class="fas fa-toggle-on text-lg"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-white uppercase tracking-widest">Estatus Operativo</p>
                <p class="text-[9px] font-bold text-zinc-600 uppercase ">¿Disponible para nuevos pedidos?</p>
            </div>
        </div>

        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" 
                   name="is_active" 
                   value="1" 
                   class="sr-only peer"
                   {{ old('is_active', $type->is_active ?? true) ? 'checked' : '' }}>
            <div class="w-14 h-7 bg-zinc-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-zinc-400 after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500 peer-checked:after:bg-black"></div>
        </label>
    </div>

    {{-- BOTÓN DE ACCIÓN --}}
    <div class="pt-4">
        <button type="submit"
            class="group relative w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black py-5 rounded-[2rem] uppercase text-xs tracking-[0.3em] transition-all shadow-[0_20px_40px_rgba(234,179,8,0.2)] active:scale-[0.98] overflow-hidden">
            <span class="relative z-10 flex items-center justify-center gap-3">
                <i class="fas fa-save group-hover:rotate-12 transition-transform"></i>
                {{ isset($type) ? 'Ejecutar Actualización' : 'Confirmar Nuevo Tipo' }}
            </span>
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
        </button>
        

    </div>

</div>
