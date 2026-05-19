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
    {{-- Card de Visualización de Dirección --}}
    <div class="relative overflow-hidden bg-[#070a13] border border-white/5 rounded-2xl p-6 transition-all duration-300 hover:border-yellow-500/20">

        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-500/10 p-3 rounded-xl">
                    <i class="fas fa-map-marked-alt text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <h5 class="text-white font-bold text-lg leading-none">Dirección Principal</h5>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-2">Sede Legal / Operativa</p>
                </div>
            </div>

            {{-- Badge de Estado --}}
            @if($company->mainAddress)
                <span class="px-2 py-1 rounded-md bg-emerald-500/10 text-emerald-500 text-[10px] font-bold uppercase tracking-tighter border border-emerald-500/20">
                    Registrada
                </span>
            @else
                <span class="px-2 py-1 rounded-md bg-red-500/10 text-red-500 text-[10px] font-bold uppercase tracking-tighter border border-red-500/20">
                    Pendiente
                </span>
            @endif
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Detalle de la Dirección --}}
            <div class="space-y-4">
                @if($company->mainAddress)
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Nomenclatura</p>
                        <p class="text-white font-medium text-lg ">
                            {{ $company->mainAddress->address }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-white/5">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Ubicación Geográfica</p>
                        <div class="flex items-center gap-2 text-gray-300">
                            <i class="fas fa-city text-xs text-yellow-500/50"></i>
                            <span class="font-semibold">{{ $company->mainAddress->city->name }}</span>
                            <span class="text-gray-600 mx-1">•</span>
                            <span class="text-gray-400">{{ $company->mainAddress->city->state->name }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 bg-black/20 rounded-xl border border-dashed border-white/10">
                        <i class="fas fa-location-dot text-gray-700 text-3xl mb-3"></i>
                        <p class="text-gray-500 text-sm ">No hay una dirección registrada actualmente.</p>
                        <button class="mt-4 text-xs font-bold text-yellow-500 hover:text-yellow-400 transition uppercase tracking-widest">
                            + Agregar Dirección
                        </button>
                    </div>
                @endif
            </div>

            {{-- Mapa Visual Decorativo / Placeholder --}}
            <div class="relative h-full min-h-[150px] bg-[#0d121f] rounded-xl border border-white/5 overflow-hidden group">
                <div class="absolute inset-0 opacity-30 grayscale group-hover:grayscale-0 transition-all duration-700"
                     style="background-image: url('https://www.google.com/maps/vt/pb=!1m4!1m3!1i12!2i1024!3i1536!2m3!1e0!2sm!3i420120488!3m8!2ses!3sUS!5e1105!12m4!1e68!2m2!1sset!2sRoadmap!4e0!5m1!1f2'); background-size: cover;">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#070a13] to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></div>
                        <span class="text-[10px] text-white font-bold uppercase">Localización Activa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Botón de Acción --}}
    @if($company->mainAddress)
        <div class="flex justify-end">
            <button class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-gray-300 rounded-lg text-sm font-bold transition border border-white/10">
                <i class="fas fa-edit text-yellow-500"></i>
                Actualizar Dirección
            </button>
        </div>
    @endif
</div>
