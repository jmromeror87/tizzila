{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

@csrf

<div class="space-y-5">
    {{-- NOMBRE --}}
    <div>
        <label class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 mb-2 block">Nombre del Operador</label>
        <div class="relative">
            <i class="fas fa-user-circle absolute left-4 top-1/2 -translate-y-1/2 text-yellow-500/50 text-sm pointer-events-none"></i>
            <input name="full_name" required
                   value="{{ old('full_name', $driver->full_name ?? '') }}"
                   placeholder="EJ. JUAN PÉREZ"
                   class="w-full bg-black/30 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-sm font-bold text-white uppercase tracking-wider focus:outline-none focus:border-yellow-500/50 transition-colors">
        </div>
    </div>

    {{-- CONTACTO --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 mb-2 block">Teléfono</label>
            <div class="relative">
                <i class="fas fa-phone-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-sm pointer-events-none"></i>
                <input name="phone" required
                       value="{{ old('phone', $driver->phone ?? '') }}"
                       placeholder="313..."
                       class="w-full bg-black/30 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-sm font-mono text-white focus:outline-none focus:border-yellow-500/50 transition-colors">
            </div>
        </div>
        <div>
            <label class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 mb-2 block">WhatsApp</label>
            <div class="relative">
                <i class="fab fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-sm pointer-events-none"></i>
                <input name="whatsapp"
                       value="{{ old('whatsapp', $driver->whatsapp ?? '') }}"
                       placeholder="+57..."
                       class="w-full bg-black/30 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-sm font-mono text-white focus:outline-none focus:border-yellow-500/50 transition-colors">
            </div>
        </div>
    </div>

    {{-- VEHÍCULO --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 mb-2 block">Tipo de Unidad</label>
            <div class="relative">
                <i class="fas fa-truck absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-sm pointer-events-none"></i>
                <input name="truck_type"
                       value="{{ old('truck_type', $driver->truck_type ?? '') }}"
                       placeholder="EJ. CAMIÓN SENCILLO"
                       class="w-full bg-black/30 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-sm font-bold text-white uppercase tracking-wide focus:outline-none focus:border-yellow-500/50 transition-colors">
            </div>
        </div>
        <div>
            <label class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 mb-2 block">Placa Vehicular</label>
            <div class="relative">
                <i class="fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-sm pointer-events-none"></i>
                <input name="truck_plate" required
                       value="{{ old('truck_plate', $driver->truck_plate ?? '') }}"
                       placeholder="XXX-000"
                       class="w-full bg-black/30 border border-white/10 rounded-xl pl-11 pr-4 py-3 text-sm font-black text-yellow-500 uppercase tracking-[0.3em] focus:outline-none focus:border-yellow-500/50 transition-colors">
            </div>
        </div>
    </div>

    {{-- ESTADO (solo en edición) --}}
    @isset($driver)
        <div class="pt-2 border-t border-white/5">
            <label class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 mb-2 block">Estado</label>
            <select name="status"
                    class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-sm font-black text-white uppercase tracking-wide focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer">
                <option value="active"   @selected($driver->status === 'active')>Activo / Disponible</option>
                <option value="inactive" @selected($driver->status === 'inactive')>Fuera de Servicio</option>
            </select>
        </div>
    @endisset

    {{-- GUARDAR --}}
    <div class="pt-2">
        <button type="submit"
                class="w-full bg-yellow-500 hover:bg-yellow-400 text-black py-3.5 rounded-xl font-black uppercase tracking-widest text-[11px] transition-all active:scale-95 flex items-center justify-center gap-2">
            {{ isset($driver) ? 'Guardar Cambios' : 'Registrar Conductor' }}
            <i class="fas fa-arrow-right text-xs"></i>
        </button>
    </div>
</div>
