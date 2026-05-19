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
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black text-white tracking-tighter uppercase">
                    Plan Único de <span class="text-yellow-500">Cuentas</span>
                </h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] font-bold mt-1">
                    PUC Colombia — Gestión dinámica del catálogo contable
                </p>
            </div>
            <button onclick="document.getElementById('modal-create').classList.remove('hidden')"
                class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                <i class="fas fa-plus"></i> Nueva Cuenta
            </button>
        </div>
    </x-slot>

    <div class="py-4 space-y-5" x-data="pucApp()">

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-5 py-3 rounded-xl flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->has('delete'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold px-5 py-3 rounded-xl flex items-center gap-3">
                <i class="fas fa-triangle-exclamation"></i> {{ $errors->first('delete') }}
            </div>
        @endif

        {{-- FILTROS + BÚSQUEDA --}}
        <form method="GET" action="{{ route('accounting.chart.index') }}"
              class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por código o nombre..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#0d121f] border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 placeholder-zinc-700">
            </div>
            <select name="type"
                class="px-4 py-2.5 rounded-xl bg-[#0d121f] border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 min-w-[160px]">
                <option value="">Todos los tipos</option>
                @foreach($types as $val => $label)
                    <option value="{{ $val }}" {{ $filterType === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            @if($search || $filterType)
                <a href="{{ route('accounting.chart.index') }}"
                   class="px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                    <i class="fas fa-xmark"></i> Limpiar
                </a>
            @endif
        </form>

        {{-- STATS --}}
        @php
            $typeColors = [
                'asset'     => ['text-blue-400',    'bg-blue-500/10',    'border-blue-500/20',    'Activo'],
                'liability' => ['text-red-400',     'bg-red-500/10',     'border-red-500/20',     'Pasivo'],
                'equity'    => ['text-purple-400',  'bg-purple-500/10',  'border-purple-500/20',  'Patrimonio'],
                'income'    => ['text-emerald-400', 'bg-emerald-500/10', 'border-emerald-500/20', 'Ingresos'],
                'expense'   => ['text-orange-400',  'bg-orange-500/10',  'border-orange-500/20',  'Gastos'],
                'cost'      => ['text-yellow-400',  'bg-yellow-500/10',  'border-yellow-500/20',  'Costos'],
            ];
        @endphp
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
            @foreach($typeColors as $t => [$tc, $bg, $border, $tlabel])
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl px-4 py-3 text-center hover:border-white/10 transition-all cursor-pointer"
                     onclick="window.location='{{ route('accounting.chart.index') }}?type={{ $t }}'">
                    <p class="text-xl font-black {{ $tc }}">{{ $countByType[$t] ?? 0 }}</p>
                    <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mt-0.5">{{ $tlabel }}</p>
                </div>
            @endforeach
        </div>

        {{-- TABLA PUC --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                    <i class="fas fa-sitemap text-yellow-500 mr-2"></i>Catálogo de Cuentas
                </h3>
                <span class="text-[10px] font-black text-zinc-500">{{ $accounts->count() }} cuentas</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/[0.04] text-[9px] font-black uppercase tracking-widest text-zinc-600">
                            <th class="px-4 py-3 text-left w-32">Código</th>
                            <th class="px-4 py-3 text-left">Nombre</th>
                            <th class="px-4 py-3 text-center w-28">Tipo</th>
                            <th class="px-4 py-3 text-center w-16">Nivel</th>
                            <th class="px-4 py-3 text-center w-20">Imputa</th>
                            <th class="px-4 py-3 text-center w-20">Tercero</th>
                            <th class="px-4 py-3 text-center w-20">Estado</th>
                            <th class="px-4 py-3 text-right w-24">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($accounts as $account)
                            @php
                                [$tc, $bg, $border, $tlabel] = $typeColors[$account->type] ?? ['text-zinc-400','bg-zinc-500/10','border-zinc-500/20','—'];
                                $indent = ($account->level - 1) * 16;
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                {{-- Código --}}
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs font-black {{ $tc }}">{{ $account->code }}</span>
                                </td>
                                {{-- Nombre con sangría --}}
                                <td class="px-4 py-3" style="padding-left: {{ 16 + $indent }}px">
                                    <div class="flex items-center gap-2">
                                        @if($account->level === 1)
                                            <i class="fas fa-layer-group text-[9px] {{ $tc }}"></i>
                                        @elseif($account->level === 2)
                                            <i class="fas fa-chevron-right text-[8px] text-zinc-700"></i>
                                        @else
                                            <i class="fas fa-minus text-[7px] text-zinc-800"></i>
                                        @endif
                                        <span class="text-sm {{ $account->level === 1 ? 'font-black text-white uppercase' : ($account->level === 2 ? 'font-bold text-zinc-200' : 'font-medium text-zinc-400') }}">
                                            {{ $account->name }}
                                        </span>
                                    </div>
                                </td>
                                {{-- Tipo --}}
                                <td class="px-4 py-3 text-center">
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded-lg {{ $bg }} {{ $border }} border {{ $tc }}">
                                        {{ $tlabel }}
                                    </span>
                                </td>
                                {{-- Nivel --}}
                                <td class="px-4 py-3 text-center">
                                    <span class="text-[10px] font-black text-zinc-600">{{ $account->level }}</span>
                                </td>
                                {{-- Is Posting --}}
                                <td class="px-4 py-3 text-center">
                                    @if($account->is_posting)
                                        <span class="text-[9px] font-black text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-lg">SÍ</span>
                                    @else
                                        <span class="text-[9px] font-black text-zinc-600 bg-white/5 border border-white/5 px-2 py-0.5 rounded-lg">NO</span>
                                    @endif
                                </td>
                                {{-- Tercero --}}
                                <td class="px-4 py-3 text-center">
                                    @if($account->requires_third_party)
                                        <i class="fas fa-check text-emerald-400 text-[9px]"></i>
                                    @else
                                        <i class="fas fa-minus text-zinc-700 text-[9px]"></i>
                                    @endif
                                </td>
                                {{-- Estado --}}
                                <td class="px-4 py-3 text-center">
                                    @if($account->is_active)
                                        <span class="text-[9px] font-black text-emerald-400">Activa</span>
                                    @else
                                        <span class="text-[9px] font-black text-red-400">Inactiva</span>
                                    @endif
                                </td>
                                {{-- Acciones --}}
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button"
                                            onclick="openEdit({{ $account->id }}, {{ $account->toJson() }})"
                                            class="h-7 w-7 rounded-lg bg-white/5 border border-white/10 hover:bg-yellow-500/10 hover:border-yellow-500/20 text-zinc-400 hover:text-yellow-400 flex items-center justify-center transition-all">
                                            <i class="fas fa-pen text-[9px]"></i>
                                        </button>
                                        <form method="POST" action="{{ route('accounting.chart.destroy', $account) }}"
                                              onsubmit="return confirm('¿Eliminar cuenta {{ $account->code }} - {{ $account->name }}?\nSolo es posible si no tiene subcuentas ni movimientos.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="h-7 w-7 rounded-lg bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-all">
                                                <i class="fas fa-trash text-[9px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 opacity-20">
                                        <i class="fas fa-sitemap text-4xl"></i>
                                        <p class="text-[10px] font-black uppercase tracking-[0.3em]">Sin cuentas</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- MODAL CREAR CUENTA --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div id="modal-create"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="bg-[#0d121f] border border-white/10 rounded-3xl w-full max-w-xl shadow-2xl">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-widest text-white">
                    <i class="fas fa-plus-circle text-yellow-500 mr-2"></i>Nueva Cuenta Contable
                </h3>
                <button onclick="document.getElementById('modal-create').classList.add('hidden')"
                    class="h-8 w-8 rounded-xl bg-white/5 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-xmark text-sm"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('accounting.chart.store') }}" class="p-6 space-y-4">
                @csrf
                @include('accounting.chart._form', ['account' => null, 'allParents' => $allParents, 'types' => $types])
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 py-3 bg-yellow-500 hover:bg-yellow-400 text-black text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        <i class="fas fa-save mr-2"></i>Crear Cuenta
                    </button>
                    <button type="button"
                        onclick="document.getElementById('modal-create').classList.add('hidden')"
                        class="px-5 py-3 bg-white/5 hover:bg-white/10 text-zinc-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- MODAL EDITAR CUENTA --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div id="modal-edit"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="bg-[#0d121f] border border-white/10 rounded-3xl w-full max-w-xl shadow-2xl">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-widest text-white">
                    <i class="fas fa-pen text-yellow-500 mr-2"></i>Editar Cuenta
                    <span id="edit-title" class="text-yellow-500 ml-2 font-mono"></span>
                </h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="h-8 w-8 rounded-xl bg-white/5 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-xmark text-sm"></i>
                </button>
            </div>
            <form id="form-edit" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                @include('accounting.chart._form', ['account' => null, 'allParents' => $allParents, 'types' => $types, 'edit' => true])
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 py-3 bg-yellow-500 hover:bg-yellow-400 text-black text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        <i class="fas fa-save mr-2"></i>Guardar Cambios
                    </button>
                    <button type="button"
                        onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="px-5 py-3 bg-white/5 hover:bg-white/10 text-zinc-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEdit(id, data) {
        const form = document.getElementById('form-edit');
        form.action = '/accounting/chart/' + id;
        document.getElementById('edit-title').textContent = data.code;

        // Rellenar campos
        form.querySelector('[name="code"]').value       = data.code        ?? '';
        form.querySelector('[name="name"]').value       = data.name        ?? '';
        form.querySelector('[name="type"]').value       = data.type        ?? '';
        form.querySelector('[name="normal_balance"]').value = data.normal_balance ?? '';
        form.querySelector('[name="parent_id"]').value  = data.parent_id   ?? '';
        form.querySelector('[name="is_posting"]').checked          = !!data.is_posting;
        form.querySelector('[name="requires_third_party"]').checked = !!data.requires_third_party;
        form.querySelector('[name="requires_cost_center"]').checked = !!data.requires_cost_center;
        form.querySelector('[name="is_active"]').checked            = !!data.is_active;

        document.getElementById('modal-edit').classList.remove('hidden');
    }

    // Cerrar modales con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('modal-create').classList.add('hidden');
            document.getElementById('modal-edit').classList.add('hidden');
        }
    });

    function pucApp() { return {}; }
    </script>
</x-app-layout>
