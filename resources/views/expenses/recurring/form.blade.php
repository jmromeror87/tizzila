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

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-[#0a0a0c] border border-white/5 rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl">

    <div class="absolute top-0 right-0 w-40 h-40 bg-[#f3c444]/5 blur-[60px] rounded-full -mr-10 -mt-10"></div>

    {{-- 🏷️ NOMBRE --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2">
            <i class="fas fa-signature text-[#f3c444]"></i> Nombre del Gasto
        </label>
        <input type="text" name="name"
            value="{{ old('name', $recurringExpense->name ?? '') }}"
            placeholder="Ej: Arriendo Local"
            class="w-full bg-black border border-white/10 text-white rounded-2xl px-5 py-4 text-sm font-bold focus:border-[#f3c444] focus:ring-0 transition-all uppercase placeholder:text-zinc-800">
    </div>

    {{-- 📂 CATEGORÍA -- ✅ FIX: modelos completos --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2">
            <i class="fas fa-tag text-[#f3c444]"></i> Categoría Contable
        </label>
        <select name="expense_category_id"
            class="w-full bg-black border border-white/10 text-white rounded-2xl px-5 py-4 text-sm font-bold focus:border-[#f3c444] focus:ring-0 transition-all uppercase appearance-none cursor-pointer">
            <option value="">Seleccione categoría...</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ old('expense_category_id', $recurringExpense->expense_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}{{ $cat->puc_code ? ' (' . $cat->puc_code . ')' : '' }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- 🏢 PROVEEDOR -- ✅ FIX: modelos completos --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2">
            <i class="fas fa-building text-[#f3c444]"></i> Proveedor
            <span class="text-zinc-700">(Opcional)</span>
        </label>
        <select name="provider_id"
            class="w-full bg-black border border-white/10 text-white rounded-2xl px-5 py-4 text-sm font-bold focus:border-[#f3c444] focus:ring-0 transition-all uppercase appearance-none cursor-pointer">
            <option value="">Sin proveedor</option>
            @foreach($providers as $provider)
                <option value="{{ $provider->id }}"
                    {{ old('provider_id', $recurringExpense->provider_id ?? '') == $provider->id ? 'selected' : '' }}>
                    {{ $provider->business_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- 💰 MONTO --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2">
            <i class="fas fa-coins text-[#f3c444]"></i> Monto Estimado
        </label>
        <div class="relative">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-zinc-600 font-black">$</span>
            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" name="amount" step="0.01"
                value="{{ old('amount', $recurringExpense->amount ?? '') }}"
                class="w-full bg-black border border-white/10 text-white rounded-2xl pl-10 pr-5 py-4 text-lg font-black focus:border-[#f3c444] focus:ring-0 transition-all">
        </div>
    </div>

    {{-- 🔄 FRECUENCIA --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2">
            <i class="fas fa-sync-alt text-[#f3c444]"></i> Ciclo de Repetición
        </label>
        <select name="frequency"
            class="w-full bg-black border border-white/10 text-white rounded-2xl px-5 py-4 text-sm font-bold focus:border-[#f3c444] focus:ring-0 transition-all uppercase appearance-none cursor-pointer">
            @foreach(['daily' => 'Diario', 'weekly' => 'Semanal', 'biweekly' => 'Quincenal', 'monthly' => 'Mensual'] as $val => $label)
                <option value="{{ $val }}"
                    {{ old('frequency', $recurringExpense->frequency ?? '') == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- 📅 FECHA INICIO --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2">
            <i class="fas fa-calendar-alt text-[#f3c444]"></i> Fecha de Inicio
        </label>
        <input type="date" name="start_date"
            value="{{ old('start_date', isset($recurringExpense) ? $recurringExpense->start_date?->format('Y-m-d') : '') }}"
            class="w-full bg-black border border-white/10 text-white rounded-2xl px-5 py-4 text-sm font-black focus:border-[#f3c444] focus:ring-0 transition-all">
    </div>

    {{-- 📅 FECHA FIN (opcional) --}}
    <div class="space-y-2">
        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2">
            <i class="fas fa-calendar-xmark text-zinc-600"></i> Fecha de Fin
            <span class="text-zinc-700">(Opcional)</span>
        </label>
        <input type="date" name="end_date"
            value="{{ old('end_date', isset($recurringExpense) ? $recurringExpense->end_date?->format('Y-m-d') : '') }}"
            class="w-full bg-black border border-white/10 text-white rounded-2xl px-5 py-4 text-sm font-black focus:border-[#f3c444] focus:ring-0 transition-all">
    </div>

    {{-- ✅ ESTADO --}}
    <div class="flex items-center pt-8">
        <label class="relative flex items-center gap-4 cursor-pointer group">
            <input type="checkbox" name="is_active" value="1"
                class="sr-only peer"
                {{ !isset($recurringExpense) || $recurringExpense->is_active ? 'checked' : '' }}>
            <div class="w-14 h-7 bg-white/5 border border-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-black after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-zinc-600 after:border-transparent after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:bg-[#f3c444] peer-checked:bg-[#f3c444]/10 peer-checked:border-[#f3c444]/30"></div>
            <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest group-hover:text-white transition-colors">
                Habilitar Recurrencia
            </span>
        </label>
    </div>

</div>

{{-- 🔘 BOTÓN --}}
<div class="mt-10 flex justify-end">
    <button type="submit"
        class="px-10 py-5 bg-[#f3c444] text-black text-[11px] font-black uppercase tracking-[0.2em] rounded-[1.5rem] shadow-xl shadow-[#f3c444]/10 hover:scale-[1.03] active:scale-95 transition-all flex items-center gap-3">
        <i class="fas fa-save text-xs"></i> Guardar Programación
    </button>
</div>