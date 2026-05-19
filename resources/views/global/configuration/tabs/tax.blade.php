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
    {{-- Navegación por Pestañas Internas Estilizada --}}
    <div class="border-b border-white/5 mb-6">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center gap-2" role="tablist">
            <li role="presentation">
                <button class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-yellow-500 text-yellow-500 rounded-t-lg active group transition-all"
                        id="tax-profile-tab" type="button" role="tab">
                    <i class="fas fa-id-badge text-xs"></i>
                    Perfil Activo
                </button>
            </li>
            <li role="presentation">
                <button class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-300 hover:border-white/20 rounded-t-lg transition-all"
                        id="resolutions-tab" type="button" role="tab">
                    <i class="fas fa-file-signature text-xs"></i>
                    Resoluciones
                </button>
            </li>
            <li role="presentation">
                <button class="inline-flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-300 hover:border-white/20 rounded-t-lg transition-all"
                        id="history-tab" type="button" role="tab">
                    <i class="fas fa-history text-xs"></i>
                    Historial
                </button>
            </li>
        </ul>
    </div>

    {{-- Encabezado de la Sección --}}
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-3">
            <div class="bg-emerald-500/10 p-2.5 rounded-xl border border-emerald-500/20">
                <i class="fas fa-file-invoice-dollar text-emerald-500"></i>
            </div>
            <div>
                <h5 class="text-white font-bold mb-0">Información Tributaria</h5>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Configuración legal y parámetros de facturación</p>
            </div>
        </div>

        @if($company->activeTaxProfile)
            <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-bold text-emerald-500 uppercase tracking-tighter">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Sincronizado
            </span>
        @endif
    </div>

    {{-- Contenido del Tab Activo --}}
    @if($company->activeTaxProfile)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in fade-in slide-in-from-bottom-2 duration-500">

            {{-- Régimen Fiscal --}}
            <div class="bg-[#070a13] border border-white/5 p-5 rounded-2xl relative overflow-hidden group transition-all hover:border-yellow-500/20">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Régimen Fiscal</p>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/5 rounded-lg border border-white/5">
                        <i class="fas fa-gavel text-gray-400 text-xs"></i>
                    </div>
                    <h4 class="text-white font-bold tracking-tight">
                        {{ $company->activeTaxProfile->tax_regime }}
                    </h4>
                </div>
                <div class="absolute -right-2 -bottom-2 opacity-5 transition-transform group-hover:scale-110">
                    <i class="fas fa-gavel text-6xl text-white"></i>
                </div>
            </div>

            {{-- Prefijo de Facturación --}}
            <div class="bg-[#070a13] border border-white/5 p-5 rounded-2xl relative overflow-hidden group transition-all hover:border-yellow-500/20">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Prefijo de Facturación</p>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-yellow-500/10 rounded-lg text-yellow-500 border border-yellow-500/10">
                        <i class="fas fa-hashtag text-xs"></i>
                    </div>
                    <h4 class="text-white font-extrabold text-2xl tracking-tighter">
                        {{ $company->activeTaxProfile->prefix }}
                    </h4>
                </div>
            </div>

            {{-- Responsabilidades Fiscales (Ancho completo) --}}
            <div class="md:col-span-2 bg-[#070a13] border border-white/5 p-5 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Responsabilidades y Obligaciones</p>
                    <i class="fas fa-info-circle text-gray-700 text-xs" title="Códigos según RUT"></i>
                </div>
                <div class="flex flex-wrap gap-2">
                    @forelse($company->activeTaxProfile->responsibility_codes ?? [] as $code)
                        <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-[11px] font-semibold flex items-center gap-2 hover:bg-yellow-500/10 hover:border-yellow-500/30 transition-all cursor-default">
                            <div class="w-1 h-1 rounded-full bg-yellow-500"></div>
                            {{ $code }}
                        </span>
                    @empty
                        <span class="text-gray-600  text-xs">No se encontraron códigos registrados.</span>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        {{-- Estado Vacío Profesional --}}
        <div class="flex flex-col items-center justify-center py-16 bg-[#070a13] rounded-3xl border border-dashed border-white/10">
            <div class="w-16 h-16 bg-yellow-500/5 rounded-full flex items-center justify-center mb-4 border border-yellow-500/10">
                <i class="fas fa-file-invoice text-yellow-500/50 text-2xl"></i>
            </div>
            <h5 class="text-white font-bold">Perfil Tributario Pendiente</h5>
            <p class="text-gray-500 text-sm mt-1 mb-6 text-center max-w-xs">Debe configurar sus datos fiscales para habilitar la facturación electrónica.</p>
            <button class="px-6 py-2.5 bg-yellow-500 text-black font-bold rounded-xl hover:bg-yellow-400 transition transform hover:scale-105 shadow-xl shadow-yellow-500/20 text-sm">
                Configurar Perfil
            </button>
        </div>
    @endif

    {{-- Nota al pie --}}
    <div class="mt-4 flex items-start gap-3 p-4 rounded-xl bg-yellow-500/5 border border-yellow-500/10">
        <i class="fas fa-shield-halved text-yellow-500/40 mt-0.5"></i>
        <p class="text-[11px] text-gray-500 leading-relaxed ">
            Esta información es vinculante para la orquestación de documentos electrónicos. Los cambios aquí realizados afectan la validez legal de sus transacciones.
        </p>
    </div>
</div>
