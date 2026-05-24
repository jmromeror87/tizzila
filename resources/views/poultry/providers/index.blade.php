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
                    Directorio de <span class="text-yellow-500">Terceros</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Maestro logístico · {{ $providers->total() }} registros
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="NIT o razón social..."
                        class="pl-9 pr-4 py-2.5 rounded-xl bg-[#0d121f] border border-white/10 text-xs text-white placeholder-zinc-700 outline-none focus:border-yellow-500/50 w-56">
                </form>
                <a href="{{ route('poultry.providers.create') }}"
                   class="h-10 px-5 bg-yellow-500 hover:bg-yellow-400 text-black font-black rounded-xl transition-all shadow-[0_8px_20px_rgba(234,179,8,0.2)] flex items-center gap-2 text-[10px] uppercase tracking-widest">
                    <i class="fas fa-plus"></i> Nuevo
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-5 py-3 rounded-2xl flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-black px-5 py-3 rounded-2xl flex items-center gap-3">
                <i class="fas fa-triangle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                    <i class="fas fa-users text-yellow-500 mr-2"></i>Terceros Registrados
                </h3>
                @if($search)
                    <a href="{{ route('poultry.providers.index') }}" class="text-[9px] font-black uppercase tracking-widest text-zinc-500 hover:text-yellow-500 transition-colors flex items-center gap-1.5">
                        <i class="fas fa-times text-[8px]"></i> Limpiar filtro
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/[0.04] text-[9px] font-black uppercase tracking-widest text-zinc-600">
                            <th class="px-6 py-3">Tercero</th>
                            <th class="px-4 py-3 text-center">Identificación</th>
                            <th class="px-4 py-3 text-center">Tipo</th>
                            <th class="px-4 py-3 text-center">Plazo</th>
                            <th class="px-4 py-3 text-center">Pedidos</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right w-28">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($providers as $provider)
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-zinc-900 border border-white/5 group-hover:border-yellow-500/20 flex items-center justify-center shrink-0 transition-all">
                                            <span class="text-xs font-black text-yellow-500">{{ strtoupper(substr($provider->business_name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-white uppercase tracking-tight group-hover:text-yellow-500 transition-colors">{{ $provider->business_name }}</p>
                                            @if($provider->trade_name)
                                                <p class="text-[9px] text-zinc-600 font-bold mt-0.5">{{ $provider->trade_name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="font-mono text-xs text-zinc-300 bg-black/40 px-2 py-1 rounded-lg border border-white/5">
                                        <span class="text-yellow-500 text-[9px]">{{ $provider->tax_id_type }}</span>
                                        {{ $provider->tax_id }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $typeMap = ['poultry'=>['Aves','text-yellow-400 bg-yellow-500/10 border-yellow-500/20'],'expense'=>['Gastos','text-blue-400 bg-blue-500/10 border-blue-500/20'],'general'=>['General','text-zinc-400 bg-white/5 border-white/10']];
                                        [$tl, $tc] = $typeMap[$provider->provider_type ?? 'general'] ?? ['General','text-zinc-400 bg-white/5 border-white/10'];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase border {{ $tc }}">{{ $tl }}</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-xs font-black text-white">{{ $provider->payment_terms_days ?? 0 }}</span>
                                    <span class="text-[9px] text-zinc-600 ml-0.5">días</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-xs font-black text-white">{{ $provider->poultry_order_schedules_count }}</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($provider->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border border-emerald-500/20 bg-emerald-500/5 text-emerald-400 text-[9px] font-black uppercase tracking-widest">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border border-white/10 bg-white/5 text-zinc-500 text-[9px] font-black uppercase tracking-widest">
                                            <i class="fas fa-lock text-[8px]"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('poultry.providers.show', $provider) }}"
                                           class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 hover:bg-yellow-500/10 hover:border-yellow-500/20 hover:text-yellow-400 text-zinc-500 flex items-center justify-center transition-all">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </a>
                                        <a href="{{ route('poultry.providers.edit', $provider) }}"
                                           class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 hover:text-white text-zinc-500 flex items-center justify-center transition-all">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </a>
                                        <form action="{{ route('poultry.providers.destroy', $provider) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar a {{ $provider->business_name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="h-8 w-8 rounded-lg bg-red-500/5 border border-red-500/10 hover:bg-red-500/20 hover:border-red-500/30 hover:text-red-400 text-red-500/40 flex items-center justify-center transition-all">
                                                <i class="fas fa-trash-alt text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 opacity-20">
                                        <i class="fas fa-users text-4xl"></i>
                                        <p class="text-[10px] font-black uppercase tracking-[0.4em]">Sin terceros registrados</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-white/5 flex justify-between items-center">
                <span class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest">
                    Total: {{ $providers->total() }} terceros
                </span>
                <div>{{ $providers->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
