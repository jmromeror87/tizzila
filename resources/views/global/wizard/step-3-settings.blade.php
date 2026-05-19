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
    {{-- Header con Proyección de Progreso --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div class="absolute inset-0 bg-yellow-500/20 blur-xl rounded-full animate-pulse"></div>
                    <div class="relative bg-[#161b2a] p-4 rounded-2xl border border-yellow-500/30 shadow-2xl">
                        <i class="fas fa-sliders text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white uppercase  tracking-tighter leading-none">
                        Parámetros <span class="text-yellow-500">Núcleo</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] mt-2 font-black flex items-center gap-2">
                        <span class="text-yellow-500/50">Fase 03</span> 
                        <i class="fas fa-chevron-right text-[8px]"></i> 
                        Configuración de Entorno
                    </p>
                </div>
            </div>

            {{-- Barra de Progreso (75% - Paso 3 de 4) --}}
            <div class="w-full md:w-64 bg-[#0f172a] p-4 rounded-2xl border border-white/5 shadow-inner">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Calibración</span>
                    <span class="text-xs font-mono font-black text-yellow-500">75%</span>
                </div>
                <div class="relative w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-yellow-600 to-yellow-400 rounded-full shadow-[0_0_15px_rgba(234,179,8,0.5)] transition-all duration-1000" style="width: 75%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 lg:py-20 bg-[#030712] min-h-screen relative overflow-hidden">
        {{-- Luces de Ambiente --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-yellow-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <form method="POST" action="{{ route('global.company_settings.update') }}" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Tarjeta Principal --}}
                <div class="bg-[#0f172a]/60 backdrop-blur-xl border border-white/5 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.6)] rounded-[3rem] overflow-hidden">
                    
                    <div class="p-8 md:p-12">
                        <div class="flex items-center gap-6 mb-12">
                            <div class="h-14 w-14 rounded-2xl bg-yellow-500/10 flex items-center justify-center border border-yellow-500/20">
                                <i class="fas fa-microchip text-yellow-500 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-white uppercase  tracking-tight">Preferencias Globales</h3>
                                <p class="text-gray-500 text-sm ">Defina los estándares financieros y de tiempo para el sistema.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-10">

                            {{-- Moneda con Badge Visual --}}
                            <div class="space-y-3 group">
                                <label class="flex justify-between px-1">
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Moneda Base</span>
                                    <span class="text-[9px] bg-yellow-500/10 text-yellow-500 px-2 py-0.5 rounded-md border border-yellow-500/20 font-bold uppercase">Financiero</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-yellow-500 transition-colors">
                                        <i class="fas fa-coins text-xs"></i>
                                    </div>
                                    <select name="default_currency" required
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all appearance-none cursor-pointer font-medium">
                                        <option value="COP" @selected(($settings?->default_currency ?? 'COP') === 'COP') class="bg-[#0f172a]">COP — Peso Colombiano</option>
                                        <option value="USD" @selected(($settings?->default_currency) === 'USD') class="bg-[#0f172a]">USD — Dólar Americano</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-700">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Zona Horaria con Reloj Sutil --}}
                            <div class="space-y-3 group">
                                <label class="flex justify-between px-1">
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Timezone Operativo</span>
                                    <i class="fas fa-clock text-[10px] text-gray-700 animate-pulse"></i>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-yellow-500 transition-colors">
                                        <i class="fas fa-globe-americas text-xs"></i>
                                    </div>
                                    <select name="timezone" required
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all appearance-none cursor-pointer font-medium">
                                        <option value="America/Bogota" @selected(($settings?->timezone ?? 'America/Bogota') === 'America/Bogota') class="bg-[#0f172a]">America/Bogotá (GMT-5)</option>
                                        <option value="America/New_York" @selected(($settings?->timezone) === 'America/New_York') class="bg-[#0f172a]">America/New York (GMT-4)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-700">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Idioma --}}
                            <div class="space-y-3 group">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1 block">Localización de Interfaz</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-yellow-500 transition-colors">
                                        <i class="fas fa-language text-xs"></i>
                                    </div>
                                    <select name="language" required
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all appearance-none cursor-pointer font-medium">
                                        <option value="es" @selected(($settings?->language ?? 'es') === 'es') class="bg-[#0f172a]">Castellano (Predeterminado)</option>
                                        <option value="en" @selected(($settings?->language) === 'en') class="bg-[#0f172a]">English (Beta)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-700">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Año Fiscal con Separador --}}
                            <div class="space-y-3 group">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1 block">Ciclo Contable</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-yellow-500 transition-colors">
                                        <i class="fas fa-calendar-check text-xs"></i>
                                    </div>
                                    <select name="fiscal_year_start" required
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all appearance-none cursor-pointer font-medium">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" @selected(($settings?->fiscal_year_start ?? 1) == $m) class="bg-[#0f172a]">
                                                Inicia en: {{ \Carbon\Carbon::create()->month($m)->locale('es')->monthName }}
                                            </option>
                                        @endfor
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-700">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navegación Estilizada --}}
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <a href="{{ route('setup.address') }}"
                       class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-5 rounded-2xl bg-white/5 text-gray-400 font-black text-[10px] uppercase tracking-[0.2em] hover:bg-white/10 hover:text-white transition-all border border-white/5 group">
                        <i class="fas fa-chevron-left text-[8px] transition-transform group-hover:-translate-x-1"></i> 
                        Geolocalización
                    </a>

                    <button type="submit"
                            class="w-full md:w-auto flex items-center justify-center gap-4 px-12 py-5 rounded-2xl bg-yellow-500 text-black font-black text-[10px] uppercase tracking-[0.2em] hover:bg-yellow-400 transition-all shadow-[0_20px_40px_-10px_rgba(234,179,8,0.3)] active:scale-95 group">
                        Finalizar Ajustes <i class="fas fa-chevron-right text-[8px] transition-transform group-hover:translate-x-1"></i>
                    </button>
                </div>
            </form>

            {{-- Indicadores de Pasos --}}
            <div class="mt-16 flex justify-center items-center gap-4">
                <div class="h-1 w-4 rounded-full bg-yellow-500/20"></div>
                <div class="h-1 w-4 rounded-full bg-yellow-500/20"></div>
                <div class="h-1 w-12 rounded-full bg-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.6)]"></div>
                <div class="h-1 w-4 rounded-full bg-white/10"></div>
            </div>
        </div>
    </div>
</x-app-layout>