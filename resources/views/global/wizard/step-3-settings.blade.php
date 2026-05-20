{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-sliders text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Parámetros <span class="text-yellow-500">Núcleo</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1 flex items-center gap-2">
                        <span class="text-yellow-500/60">Fase 03</span>
                        <i class="fas fa-chevron-right text-[7px]"></i>
                        Configuración de Entorno
                    </p>
                </div>
            </div>
            <div class="w-56 bg-black/40 border border-white/5 rounded-xl p-3">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Calibración</span>
                    <span class="text-[10px] font-black text-yellow-500 font-mono">75%</span>
                </div>
                <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 rounded-full" style="width: 75%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto space-y-5">
            <form method="POST" action="{{ route('global.company_settings.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                            <i class="fas fa-microchip text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white uppercase tracking-tight">Preferencias Globales</h3>
                            <p class="text-[9px] text-zinc-500 uppercase tracking-widest">Defina los estándares financieros y de tiempo para el sistema.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Moneda Base</label>
                                <span class="text-[8px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg uppercase">Financiero</span>
                            </div>
                            <div class="relative">
                                <i class="fas fa-coins absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <select name="default_currency" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all appearance-none">
                                    <option value="COP" @selected(($settings?->default_currency ?? 'COP') === 'COP')>COP — Peso Colombiano</option>
                                    <option value="USD" @selected(($settings?->default_currency) === 'USD')>USD — Dólar Americano</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Timezone Operativo</label>
                            <div class="relative">
                                <i class="fas fa-globe-americas absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <select name="timezone" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all appearance-none">
                                    <option value="America/Bogota" @selected(($settings?->timezone ?? 'America/Bogota') === 'America/Bogota')>America/Bogotá (GMT-5)</option>
                                    <option value="America/New_York" @selected(($settings?->timezone) === 'America/New_York')>America/New York (GMT-4)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Localización de Interfaz</label>
                            <div class="relative">
                                <i class="fas fa-language absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <select name="language" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all appearance-none">
                                    <option value="es" @selected(($settings?->language ?? 'es') === 'es')>Castellano (Predeterminado)</option>
                                    <option value="en" @selected(($settings?->language) === 'en')>English (Beta)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Ciclo Contable</label>
                            <div class="relative">
                                <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <select name="fiscal_year_start" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all appearance-none">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" @selected(($settings?->fiscal_year_start ?? 1) == $m)>
                                            Inicia en: {{ \Carbon\Carbon::create()->month($m)->locale('es')->monthName }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('setup.address') }}"
                       class="flex items-center gap-2 px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-zinc-400 hover:text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-all">
                        <i class="fas fa-chevron-left text-[8px]"></i> Geolocalización
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-8 py-3 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                        Finalizar Ajustes <i class="fas fa-chevron-right text-[8px]"></i>
                    </button>
                </div>
            </form>

            <div class="flex justify-center items-center gap-3">
                <div class="h-1 w-4 rounded-full bg-yellow-500/30"></div>
                <div class="h-1 w-4 rounded-full bg-yellow-500/30"></div>
                <div class="h-1 w-10 rounded-full bg-yellow-500"></div>
                <div class="h-1 w-4 rounded-full bg-white/10"></div>
            </div>
        </div>
    </div>
</x-app-layout>
