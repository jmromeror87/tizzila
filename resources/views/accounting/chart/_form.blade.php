{{-- Partial: formulario de cuenta contable (crear y editar) --}}
@php $isEdit = isset($edit) && $edit; @endphp

<div class="grid grid-cols-2 gap-4">
    {{-- Código --}}
    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
            Código PUC <span class="text-red-400">*</span>
        </label>
        <input type="text" name="code"
            value="{{ old('code', $account->code ?? '') }}"
            placeholder="Ej: 110505"
            class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm font-mono text-white outline-none focus:border-yellow-500/50 placeholder-zinc-700"
            required>
    </div>

    {{-- Nombre --}}
    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
            Nombre <span class="text-red-400">*</span>
        </label>
        <input type="text" name="name"
            value="{{ old('name', $account->name ?? '') }}"
            placeholder="Ej: Caja General"
            class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 placeholder-zinc-700"
            required>
    </div>

    {{-- Tipo --}}
    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
            Tipo <span class="text-red-400">*</span>
        </label>
        <select name="type"
            class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50"
            required>
            <option value="">— Seleccionar —</option>
            @foreach($types as $val => $label)
                <option value="{{ $val }}" {{ old('type', $account->type ?? '') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Saldo Normal --}}
    <div>
        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
            Saldo Normal <span class="text-red-400">*</span>
        </label>
        <select name="normal_balance"
            class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50"
            required>
            <option value="">— Seleccionar —</option>
            <option value="debit"  {{ old('normal_balance', $account->normal_balance ?? '') === 'debit'  ? 'selected' : '' }}>Débito</option>
            <option value="credit" {{ old('normal_balance', $account->normal_balance ?? '') === 'credit' ? 'selected' : '' }}>Crédito</option>
        </select>
    </div>
</div>

{{-- Cuenta Padre --}}
<div>
    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
        Cuenta Padre (opcional)
    </label>
    <select name="parent_id"
        class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
        <option value="">— Sin cuenta padre (raíz) —</option>
        @foreach($allParents as $parent)
            @if(!isset($account) || $parent->id !== ($account->id ?? null))
                <option value="{{ $parent->id }}"
                    {{ old('parent_id', $account->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                    {{ str_repeat('   ', $parent->level - 1) }}{{ $parent->code }} — {{ $parent->name }}
                </option>
            @endif
        @endforeach
    </select>
</div>

{{-- Flags --}}
<div class="grid grid-cols-2 gap-3">
    <label class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] border border-white/5 cursor-pointer hover:border-white/10 transition-all">
        <input type="checkbox" name="is_posting" value="1"
            {{ old('is_posting', $account->is_posting ?? false) ? 'checked' : '' }}
            class="h-4 w-4 rounded accent-yellow-500">
        <div>
            <p class="text-xs font-black text-white">Cuenta de Imputación</p>
            <p class="text-[9px] text-zinc-600">Permite registrar movimientos</p>
        </div>
    </label>

    <label class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] border border-white/5 cursor-pointer hover:border-white/10 transition-all">
        <input type="checkbox" name="requires_third_party" value="1"
            {{ old('requires_third_party', $account->requires_third_party ?? false) ? 'checked' : '' }}
            class="h-4 w-4 rounded accent-yellow-500">
        <div>
            <p class="text-xs font-black text-white">Requiere Tercero</p>
            <p class="text-[9px] text-zinc-600">Clientes, proveedores, etc.</p>
        </div>
    </label>

    <label class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] border border-white/5 cursor-pointer hover:border-white/10 transition-all">
        <input type="checkbox" name="requires_cost_center" value="1"
            {{ old('requires_cost_center', $account->requires_cost_center ?? false) ? 'checked' : '' }}
            class="h-4 w-4 rounded accent-yellow-500">
        <div>
            <p class="text-xs font-black text-white">Centro de Costos</p>
            <p class="text-[9px] text-zinc-600">Requiere clasificación por área</p>
        </div>
    </label>

    <label class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] border border-white/5 cursor-pointer hover:border-white/10 transition-all">
        <input type="checkbox" name="is_active" value="1"
            {{ old('is_active', $account->is_active ?? true) ? 'checked' : '' }}
            class="h-4 w-4 rounded accent-yellow-500">
        <div>
            <p class="text-xs font-black text-white">Cuenta Activa</p>
            <p class="text-[9px] text-zinc-600">Visible en registros y PUC</p>
        </div>
    </label>
</div>
