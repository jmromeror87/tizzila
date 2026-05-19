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
                    Configuración <span class="text-yellow-500">Contable</span>
                </h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] font-bold mt-1">
                    Mapeo de cuentas del plan contable — motor de asientos automáticos
                </p>
            </div>
            <a href="{{ route('accounting.dashboard') }}"
               class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white flex items-center gap-2 transition-colors">
                <i class="fas fa-arrow-left text-[9px]"></i> Contabilidad
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-6">

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-5 py-3 rounded-xl flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- AVISO INFORMATIVO --}}
        <div class="bg-yellow-500/5 border border-yellow-500/10 rounded-2xl px-5 py-4 flex gap-4 items-start">
            <i class="fas fa-circle-info text-yellow-500 mt-0.5 shrink-0"></i>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-yellow-500">¿Para qué sirve esto?</p>
                <p class="text-xs text-yellow-500/60 mt-0.5 leading-relaxed">
                    Cada clave del sistema se mapea a una cuenta del PUC. El motor contable usa estas asignaciones para
                    generar asientos automáticamente al registrar ventas, pagos, gastos e impuestos.
                </p>
            </div>
        </div>

        {{-- MATRIZ DE CONFIGURACIÓN --}}
        @php
            $groups = [
                'Caja & Bancos'        => ['cash', 'bank'],
                'Ingresos'             => ['income_sales', 'income_services'],
                'Cuentas por Cobrar'   => ['accounts_receivable', 'customers'],
                'Cuentas por Pagar'    => ['accounts_payable', 'suppliers'],
                'Gastos Operativos'    => ['expense_default', 'expense_services', 'expense_rent', 'expense_payroll', 'expense_maintenance'],
                'Impuestos'            => ['iva_creditable', 'iva_generated', 'retefuente', 'reteica'],
                'Inventario & Costos'  => ['inventory', 'cost_of_sales'],
                'Patrimonio'           => ['equity', 'retained_earnings'],
                'Sistema'              => ['rounding'],
            ];

            $groupIcons = [
                'Caja & Bancos'        => 'fa-vault',
                'Ingresos'             => 'fa-arrow-trend-up',
                'Cuentas por Cobrar'   => 'fa-hand-holding-dollar',
                'Cuentas por Pagar'    => 'fa-credit-card',
                'Gastos Operativos'    => 'fa-receipt',
                'Impuestos'            => 'fa-scale-balanced',
                'Inventario & Costos'  => 'fa-boxes-stacked',
                'Patrimonio'           => 'fa-building-columns',
                'Sistema'              => 'fa-gear',
            ];

            $configured = $settings->keyBy('key_name');
        @endphp

        @foreach($groups as $groupName => $keys)
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">

            {{-- Group Header --}}
            <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex items-center gap-3">
                <div class="h-8 w-8 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center">
                    <i class="fas {{ $groupIcons[$groupName] ?? 'fa-tag' }} text-yellow-500 text-xs"></i>
                </div>
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">{{ $groupName }}</h3>
                @php $configuredCount = collect($keys)->filter(fn($k) => $configured->has($k))->count(); @endphp
                <span class="ml-auto text-[9px] font-black px-2 py-0.5 rounded-lg
                    {{ $configuredCount === count($keys) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-zinc-500/10 text-zinc-500 border border-zinc-500/20' }}">
                    {{ $configuredCount }}/{{ count($keys) }} configuradas
                </span>
            </div>

            {{-- Keys List --}}
            <div class="divide-y divide-white/[0.03]">
                @foreach($keys as $key)
                    @php
                        $label = \App\Models\Accounting\AccountingSetting::KEYS[$key] ?? $key;
                        $current = $configured->get($key);
                    @endphp
                    <div class="px-6 py-4 flex flex-col md:flex-row md:items-center gap-4">

                        {{-- Key info --}}
                        <div class="flex items-center gap-3 md:w-72 shrink-0">
                            <div class="h-6 w-6 rounded-lg {{ $current ? 'bg-emerald-500/10 border-emerald-500/20' : 'bg-zinc-800 border-white/5' }} border flex items-center justify-center shrink-0">
                                <i class="fas {{ $current ? 'fa-check text-emerald-400' : 'fa-xmark text-zinc-600' }} text-[9px]"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-white">{{ $label }}</p>
                                <p class="text-[9px] font-mono text-zinc-600">{{ $key }}</p>
                            </div>
                        </div>

                        {{-- Current account --}}
                        <div class="flex-1">
                            @if($current && $current->account)
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] font-mono text-emerald-500/70 bg-emerald-500/5 border border-emerald-500/10 px-2 py-0.5 rounded">
                                        {{ $current->account->code }}
                                    </span>
                                    <span class="text-xs font-bold text-white">{{ $current->account->name }}</span>
                                    <span class="text-[9px] text-zinc-600 uppercase">{{ $current->account->type }}</span>
                                </div>
                            @else
                                <span class="text-[9px] text-zinc-600 italic font-bold uppercase tracking-widest">— Sin asignar —</span>
                            @endif
                        </div>

                        {{-- Action: assign/change --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <form method="POST" action="{{ route('accounting.settings.store') }}" class="flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="key_name" value="{{ $key }}">
                                <select name="account_id"
                                    class="px-3 py-2 rounded-xl bg-black/50 border border-white/10 text-xs text-white outline-none focus:border-yellow-500/50 min-w-[220px]">
                                    <option value="">— Seleccionar cuenta —</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ $current && $current->account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->code }} — {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="px-4 py-2 bg-yellow-500 hover:bg-yellow-400 text-black text-[9px] font-black uppercase tracking-widest rounded-xl transition-all">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>

                            @if($current)
                                <form method="POST" action="{{ route('accounting.settings.destroy', $current) }}"
                                    onsubmit="return confirm('¿Quitar asignación de {{ $label }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="h-9 w-9 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-all flex items-center justify-center">
                                        <i class="fas fa-trash text-[9px]"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- RESUMEN DE ESTADO --}}
        @php
            $totalKeys = count(\App\Models\Accounting\AccountingSetting::KEYS);
            $totalConfigured = $settings->count();
            $totalPct = $totalKeys > 0 ? round(($totalConfigured / $totalKeys) * 100) : 0;
        @endphp
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl px-6 py-5 flex flex-col md:flex-row md:items-center gap-4 justify-between">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-1">Estado General</p>
                <p class="text-sm font-black text-white">
                    <span class="{{ $totalConfigured === $totalKeys ? 'text-emerald-400' : 'text-yellow-500' }}">{{ $totalConfigured }}</span>
                    <span class="text-zinc-600"> / {{ $totalKeys }} cuentas configuradas</span>
                </p>
            </div>
            <div class="md:w-64">
                <div class="flex justify-between text-[9px] font-black uppercase mb-1">
                    <span class="text-zinc-600">Completado</span>
                    <span class="{{ $totalPct === 100 ? 'text-emerald-400' : 'text-yellow-500' }}">{{ $totalPct }}%</span>
                </div>
                <div class="h-2 bg-black/50 rounded-full overflow-hidden">
                    <div class="h-full {{ $totalPct === 100 ? 'bg-emerald-500' : 'bg-yellow-500' }} rounded-full transition-all"
                         style="width: {{ $totalPct }}%"></div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
