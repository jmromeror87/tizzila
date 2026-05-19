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
    <div class="py-12 bg-[#050507] min-h-screen text-zinc-400 font-sans selection:bg-yellow-500/30">
        <div class="max-w-6xl mx-auto px-6">

            {{-- HEADER AERODINÁMICO --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-12 gap-8">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="h-6 w-1 bg-yellow-500 rounded-full shadow-[0_0_15px_rgba(234,179,8,0.5)]"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-500">Módulo de Configuración</span>
                    </div>
                    <h1 class="text-6xl font-black text-white uppercase tracking-tighter leading-none ">
                        Tipos de <span class="text-yellow-500 drop-shadow-[0_0_20px_rgba(234,179,8,0.3)]">Aves</span>
                    </h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right mr-4">
                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Base de Datos</p>
                        <p class="text-sm font-mono text-white">{{ $types->count() }} Registros</p>
                    </div>
                    <a href="{{ route('poultry.types.create') }}"
                       class="group relative bg-yellow-500 hover:bg-yellow-400 text-black font-black px-10 py-5 rounded-[2rem] uppercase text-xs tracking-[0.2em] transition-all shadow-[0_15px_40px_rgba(234,179,8,0.2)] active:scale-95 flex items-center gap-4 overflow-hidden border-b-4 border-yellow-700">
                        <i class="fas fa-feather relative z-10 group-hover:rotate-12 transition-transform duration-500"></i>
                        <span class="relative z-10">Nuevo Registro</span>
                    </a>
                </div>
            </div>

            {{-- ALERTAS REFINADAS --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-10 bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-md text-emerald-400 p-6 rounded-[2rem] flex items-center justify-between shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em]">Sistema Actualizado</p>
                            <p class="text-sm font-bold text-white/80">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-emerald-500/50 hover:text-emerald-500 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            @endif

            {{-- TABLA TITÁNICA --}}
            <div class="relative">
                <div class="absolute -inset-0.5 bg-gradient-to-b from-white/10 to-transparent rounded-[3rem] opacity-20"></div>
                
                <div class="relative bg-[#0b0b10] border border-white/10 rounded-[3rem] overflow-hidden shadow-3xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-0">
                            <thead>
                                <tr class="bg-white/[0.03] text-[10px] uppercase tracking-[0.3em] text-zinc-500 font-black">
                                    <th class="px-10 py-8 border-b border-white/5">Visual / Especie</th>
                                    <th class="px-10 py-8 border-b border-white/5 text-center">Crédito</th>
                                    <th class="px-10 py-8 border-b border-white/5 text-center">Estatus</th>
                                    <th class="px-10 py-8 border-b border-white/5 text-right">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-white/[0.03]">
                                @forelse($types as $type)
                                    <tr class="group/row transition-all duration-500 hover:bg-white/[0.01]">
                                        <td class="px-10 py-7">
                                            <div class="flex items-center gap-6">
                                                {{-- ICONO TIZZILLA STYLE --}}
                                                <div class="relative shrink-0">
                                                    <div class="absolute inset-0 bg-yellow-500/10 blur-xl rounded-full opacity-0 group-hover/row:opacity-100 transition-opacity"></div>
                                                    <div class="relative w-16 h-16 rounded-[1.8rem] bg-gradient-to-br from-zinc-800 to-black border border-white/10 flex items-center justify-center text-4xl group-hover/row:border-yellow-500/40 group-hover/row:-translate-y-1 transition-all duration-500 shadow-2xl">
                                                        <span class="drop-shadow-[0_5px_10px_rgba(0,0,0,0.5)]">
                                                            {{ $type->icon ?: '🥚' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <p class="text-white font-black uppercase tracking-tighter text-xl group-hover/row:text-yellow-500 transition-colors">
                                                            {{ $type->name }}
                                                        </p>
                                                        <span class="text-[9px] font-mono text-zinc-700 bg-white/5 px-2 py-0.5 rounded border border-white/5">
                                                            {{ $type->code }}
                                                        </span>
                                                    </div>
                                                    <p class="text-[9px] text-zinc-600 font-black uppercase tracking-[0.2em] mt-1.5 flex items-center gap-2">
                                                        <i class="fas fa-fingerprint text-yellow-500/30"></i>
                                                        Identificador de Especie
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-10 py-7">
                                            <div class="flex flex-col items-center">
                                                <div class="flex items-center gap-2 text-white group-hover/row:text-yellow-500 transition-colors">
                                                    <i class="fas fa-calendar-day text-[10px] opacity-40"></i>
                                                    <span class="text-2xl font-black tracking-tighter tabular-nums leading-none">
                                                        {{ $type->payment_days }}
                                                    </span>
                                                </div>
                                                <span class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mt-1">Días Plazo</span>
                                            </div>
                                        </td>

                                        <td class="px-10 py-7 text-center">
                                            @if($type->is_active)
                                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 text-emerald-500">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                    </span>
                                                    <span class="text-[10px] font-black uppercase tracking-widest">Activo</span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-zinc-800 text-zinc-500 border border-white/5 opacity-40">
                                                    <i class="fas fa-ban text-[10px]"></i>
                                                    <span class="text-[10px] font-black uppercase tracking-widest">Inactivo</span>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-10 py-7 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('poultry.types.edit', $type) }}"
                                                   class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-zinc-900 border border-white/5 text-zinc-600 hover:bg-yellow-500 hover:text-black hover:border-yellow-500 hover:-translate-y-1 transition-all duration-300 group/btn">
                                                    <i class="fas fa-sliders-h text-sm group-hover/btn:rotate-90 transition-transform"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-10 py-32 text-center">
                                            <i class="fas fa-feather text-4xl text-zinc-800 mb-4 animate-bounce"></i>
                                            <p class="text-[11px] font-black uppercase tracking-[0.5em] text-zinc-700">Sin categorías</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- FOOTER DE SISTEMA --}}
            <div class="mt-12 flex flex-col md:flex-row justify-between items-center gap-6 px-4">
                <div class="flex items-center gap-6">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-zinc-700 uppercase tracking-widest leading-tight">Gestión Maestro</span>
                        <span class="text-xs font-bold text-zinc-500 ">Logistics v2.4</span>
                    </div>
                </div>
                
                <div class="bg-zinc-900/50 border border-white/5 px-6 py-3 rounded-2xl flex items-center gap-4 shadow-inner">
                    <i class="fas fa-database text-yellow-500/20 text-sm"></i>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest">
                        Total Especies: <span class="text-lg text-white ml-2 tabular-nums">{{ $types->count() }}</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>