{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<div class="space-y-6">
    {{-- Encabezado de la Sección --}}
    <div class="flex items-center gap-3 mb-2">
        <div class="bg-blue-500/10 p-2.5 rounded-xl border border-blue-500/20">
            <i class="fas fa-sliders-h text-blue-400"></i>
        </div>
        <div>
            <h5 class="text-white font-bold mb-0">Parámetros de Operación</h5>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Configuración regional y logística</p>
        </div>
    </div>

    {{-- Grid de Ajustes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Moneda Principal -->
        <div class="group bg-[#070a13] border border-white/5 p-4 rounded-2xl hover:border-yellow-500/20 transition-all">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                        <i class="fas fa-coins text-emerald-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Moneda Base</p>
                        <p class="text-white font-bold">{{ $company->settings->default_currency }}</p>
                    </div>
                </div>
                <i class="fas fa-check-circle text-[10px] text-emerald-500/50"></i>
            </div>
        </div>

        <!-- Zona Horaria -->
        <div class="group bg-[#070a13] border border-white/5 p-4 rounded-2xl hover:border-yellow-500/20 transition-all">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <i class="fas fa-clock text-blue-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Zona Horaria</p>
                        <p class="text-white font-bold">{{ $company->settings->timezone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Idioma del Sistema -->
        <div class="group bg-[#070a13] border border-white/5 p-4 rounded-2xl hover:border-yellow-500/20 transition-all">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                        <i class="fas fa-language text-purple-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Idioma Local</p>
                        <p class="text-white font-bold">{{ strtoupper($company->settings->language) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Año Fiscal -->
        <div class="group bg-[#070a13] border border-white/5 p-4 rounded-2xl hover:border-yellow-500/20 transition-all">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-yellow-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Inicio Año Fiscal</p>
                        <p class="text-white font-bold">{{ $company->settings->fiscal_year_start }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Estado de la Configuración --}}
    <div class="bg-blue-500/5 border border-blue-500/10 rounded-2xl p-4 flex items-center gap-4">
        <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
        <p class="text-xs text-gray-400 ">
            La configuración de <span class="text-white font-medium">Operación</span> determina el cálculo de fechas, reportes financieros y formatos de moneda en toda la plataforma.
        </p>
    </div>
</div>
