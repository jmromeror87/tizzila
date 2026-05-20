{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Tipos de <span class="text-yellow-500">Aves</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Módulo de Configuración · {{ $types->count() }} Registros
                </p>
            </div>
            <a href="{{ route('poultry.types.create') }}"
               class="h-9 px-5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest flex items-center gap-2 transition-all active:scale-95 w-fit">
                <i class="fas fa-plus text-xs"></i> Nuevo Tipo
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-5 py-3 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-sm"></i>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false"><i class="fas fa-times text-[10px]"></i></button>
            </div>
        @endif

        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Especie</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-center">Crédito</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-center">Estatus</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($types as $type)
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-black/40 border border-white/5 flex items-center justify-center text-xl group-hover:border-yellow-500/30 transition-colors">
                                            {{ $type->icon ?: '🥚' }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-white uppercase group-hover:text-yellow-400 transition-colors">
                                                {{ $type->name }}
                                            </p>
                                            <p class="text-[9px] text-gray-600 font-bold uppercase tracking-widest mt-0.5">
                                                {{ $type->code }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-black text-white">{{ $type->payment_days }}</span>
                                    <span class="text-[9px] text-gray-600 font-bold uppercase block">días plazo</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($type->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-zinc-500/10 text-zinc-500 border border-zinc-500/20">
                                            <i class="fas fa-ban text-[8px]"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('poultry.types.edit', $type) }}"
                                       class="h-8 w-8 inline-flex items-center justify-center bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:text-yellow-500 hover:border-yellow-500/30 transition-all">
                                        <i class="fas fa-pen text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <i class="fas fa-feather text-4xl text-gray-800 block mb-3"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">Sin tipos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
