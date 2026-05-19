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
    {{-- Header del Layout con Progreso al 100% --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="bg-emerald-500/10 p-2.5 rounded-xl border border-emerald-500/20">
                    <i class="fas fa-check-double text-emerald-500 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight">
                        Proceso <span class="text-emerald-500">Finalizado</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] mt-1 font-bold">Configuración Global Exitosa</p>
                </div>
            </div>

            {{-- Barra de Progreso al 100% --}}
            <div class="w-full md:w-48">
                <div class="flex justify-between mb-1.5">
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-tighter">Completado</span>
                    <span class="text-[10px] font-bold text-emerald-500">100%</span>
                </div>
                <div class="w-full bg-white/5 rounded-full h-1.5 border border-white/5 p-[1px]">
                    <div class="bg-emerald-500 h-full rounded-full shadow-[0_0_15px_rgba(16,185,129,0.5)] transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 lg:py-20 flex items-center justify-center">
        <div class="max-w-3xl w-full px-4">

            {{-- Tarjeta de Éxito Premium --}}
            <div class="bg-[#111827] border border-white/5 shadow-2xl rounded-[3rem] overflow-hidden relative">

                {{-- Efecto de fondo decorativo --}}
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>

                <div class="p-8 md:p-16 text-center relative z-10">

                    {{-- Icono Animado --}}
                    <div class="mb-8 relative inline-block">
                        <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center border border-emerald-500/20 shadow-2xl shadow-emerald-500/20 animate-bounce">
                            <i class="fas fa-rocket text-4xl text-emerald-500"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center border-4 border-[#111827]">
                            <i class="fas fa-star text-black text-[10px]"></i>
                        </div>
                    </div>

                    <h3 class="text-3xl md:text-4xl font-black text-white mb-4 tracking-tighter">
                        ¡Todo listo para despegar!
                    </h3>

                    <p class="text-gray-400 text-lg leading-relaxed mb-10 max-w-md mx-auto ">
                        La empresa <span class="text-white font-bold">{{ $company?->legal_name }}</span> ha sido orquestada correctamente y ya puede operar en <span class="text-yellow-500 font-black tracking-tighter">TIZZILA</span>.
                    </p>

                    {{-- Acciones Finales --}}
                    <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                        <a href="{{ route('dashboard') }}"
                           class="w-full md:w-auto flex items-center justify-center gap-3 bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-widest px-10 py-4 rounded-2xl transition-all transform hover:scale-105 shadow-2xl shadow-emerald-500/30">
                            Ir al Dashboard
                            <i class="fas fa-chart-line text-sm"></i>
                        </a>

                        <a href="{{ route('configuration.show') }}"
                           class="w-full md:w-auto flex items-center justify-center gap-3 bg-white/5 hover:bg-white/10 text-gray-300 font-bold text-xs uppercase tracking-widest px-10 py-4 rounded-2xl transition-all border border-white/10">
                            Ver Perfil Fiscal
                            <i class="fas fa-cog text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- Banner inferior decorativo --}}
                <div class="bg-emerald-500/5 py-4 border-t border-white/5 text-center">
                    <p class="text-[10px] text-emerald-500/60 uppercase tracking-[0.4em] font-black">
                        Sistema de Orquestación Inteligente Activo
                    </p>
                </div>
            </div>

            {{-- Mensaje de soporte --}}
            <p class="mt-8 text-center text-gray-600 text-xs">
                ¿Necesitas ayuda adicional? <a href="#" class="text-yellow-500 hover:underline">Contacta con soporte técnico</a>
            </p>
        </div>
    </div>
</x-app-layout>
