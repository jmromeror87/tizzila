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
        <div class="max-w-3xl mx-auto px-6">

            {{-- NAVEGACIÓN --}}
            <div class="mb-10">
                <a href="{{ route('poultry.types.index') }}" 
                   class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-yellow-500 transition-colors mb-6 group">
                    <i class="fas fa-chevron-left group-hover:-translate-x-1 transition-transform text-[8px]"></i>
                    Cancelar Operación
                </a>

                <div class="flex items-end gap-4">
                    <div class="h-12 w-[2px] bg-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.5)]"></div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-yellow-500/50 mb-1">Registro de Activos</p>
                        <h1 class="text-5xl font-black text-white uppercase tracking-tighter  leading-none">
                            Nuevo <span class="text-yellow-500">Tipo de Ave</span>
                        </h1>
                    </div>
                </div>
            </div>

            {{-- CONTENEDOR DEL FORMULARIO --}}
            <div class="relative">
                {{-- Glow lateral decorativo --}}
                <div class="absolute -left-20 top-1/2 -translate-y-1/2 w-40 h-80 bg-yellow-500/5 rounded-full blur-[100px] pointer-events-none"></div>
                
                <div class="relative bg-[#0b0b10] border border-white/10 p-10 md:p-14 rounded-[3.5rem] shadow-3xl backdrop-blur-xl overflow-hidden">
                    
                    {{-- Indicador de Paso Único --}}
                    <div class="absolute top-0 right-0 px-8 py-4 bg-white/[0.02] border-b border-l border-white/5 rounded-bl-[2rem]">
                        <span class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em]">Step_01 / Global_Config</span>
                    </div>

                    {{-- Errores de Validación --}}
                    @if ($errors->any())
                        <div class="mb-10 bg-red-500/5 border-l-2 border-red-500 p-6 rounded-r-2xl animate-pulse">
                            <div class="flex items-center gap-3 mb-2 text-red-500">
                                <i class="fas fa-terminal text-xs"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">Error_Log: Validación Fallida</span>
                            </div>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-[11px] font-bold text-zinc-500 ">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('poultry.types.store') }}">
                        @include('poultry.types.form')
                    </form>
                </div>
            </div>

            {{-- INSTRUCCIONES RÁPIDAS (TIPO DASHBOARD) --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 px-4">
                <div class="flex gap-4 p-4 rounded-2xl border border-white/[0.03] bg-white/[0.01]">
                    <i class="fas fa-info-circle text-yellow-500/30 mt-1"></i>
                    <p class="text-[10px] font-bold leading-relaxed text-zinc-600 uppercase tracking-tight">
                        Asegúrate de que el <span class="text-zinc-400">Código Interno</span> sea único para evitar conflictos en la base de datos de suministros.
                    </p>
                </div>
                <div class="flex gap-4 p-4 rounded-2xl border border-white/[0.03] bg-white/[0.01]">
                    <i class="fas fa-clock text-yellow-500/30 mt-1"></i>
                    <p class="text-[10px] font-bold leading-relaxed text-zinc-600 uppercase tracking-tight">
                        Los <span class="text-zinc-400">Días de Pago</span> definen la prioridad de liquidación en el módulo de finanzas automáticas.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>