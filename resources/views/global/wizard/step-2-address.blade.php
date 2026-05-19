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
    {{-- Header con Gauge de Progreso Evolucionado --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div class="absolute inset-0 bg-yellow-500/20 blur-xl rounded-full animate-pulse"></div>
                    <div class="relative bg-[#161b2a] p-4 rounded-2xl border border-yellow-500/30 shadow-2xl">
                        <i class="fas fa-map-marked-alt text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white uppercase  tracking-tighter leading-none">
                        Nodo de <span class="text-yellow-500">Operaciones</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] mt-2 font-black flex items-center gap-2">
                        <span class="text-yellow-500/50">Fase 02</span> 
                        <i class="fas fa-chevron-right text-[8px]"></i> 
                        Geolocalización Logística
                    </p>
                </div>
            </div>

            {{-- Gauge de Progreso (50% - Paso 2 de 4) --}}
            <div class="w-full md:w-64 bg-[#0f172a] p-4 rounded-2xl border border-white/5 shadow-inner">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Sincronización</span>
                    <span class="text-xs font-mono font-black text-yellow-500">50%</span>
                </div>
                <div class="relative w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-yellow-600 to-yellow-400 rounded-full shadow-[0_0_15px_rgba(234,179,8,0.5)] transition-all duration-1000" style="width: 50%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 lg:py-20 bg-[#030712] min-h-screen relative overflow-hidden">
        {{-- Elementos Ambientales --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-blue-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <form method="POST" action="{{ route('global.company_addresses.store') }}" class="space-y-8">
                @csrf

                {{-- Card de Ubicación --}}
                <div class="bg-[#0f172a]/60 backdrop-blur-xl border border-white/5 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.6)] rounded-[3rem] overflow-hidden">
                    
                    <div class="p-8 md:p-12">
                        <div class="flex items-center gap-6 mb-12">
                            <div class="h-14 w-14 rounded-2xl bg-yellow-500/10 flex items-center justify-center border border-yellow-500/20">
                                <i class="fas fa-location-arrow text-yellow-500 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-white uppercase  tracking-tight">Dirección de Matriz</h3>
                                <p class="text-gray-500 text-sm ">Defina el punto origen para cálculos logísticos y facturación.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">

                            {{-- Departamento --}}
                            <div class="space-y-3 group">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1 block transition-colors group-focus-within:text-yellow-500">Departamento / Estado</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 transition-colors group-focus-within:text-yellow-500">
                                        <i class="fas fa-map text-xs"></i>
                                    </div>
                                    <select id="state_id" name="state_id" required
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all appearance-none cursor-pointer">
                                        <option value="" class="bg-[#0f172a]">Seleccionar Región</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}" @selected(optional($company->mainAddress?->city?->state)->id === $state->id) class="bg-[#0f172a]">
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-700">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Ciudad --}}
                            <div class="space-y-3 group">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1 block transition-colors group-focus-within:text-yellow-500">Ciudad / Municipio</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 transition-colors group-focus-within:text-yellow-500">
                                        <i class="fas fa-city text-xs"></i>
                                    </div>
                                    <select id="city_id" name="city_id" required
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all appearance-none cursor-pointer">
                                        @if ($company?->mainAddress?->city)
                                            <option value="{{ $company->mainAddress->city->id }}" selected class="bg-[#0f172a]">
                                                {{ $company->mainAddress->city->name }}
                                            </option>
                                        @else
                                            <option value="" class="bg-[#0f172a]">Esperando región...</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-700">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Dirección Detallada --}}
                            <div class="md:col-span-2 space-y-3 group">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1 block transition-colors group-focus-within:text-yellow-500">Nomenclatura Completa</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 transition-colors group-focus-within:text-yellow-500">
                                        <i class="fas fa-location-dot text-xs"></i>
                                    </div>
                                    <input type="text" name="address" value="{{ $company?->mainAddress?->address }}"
                                           class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all placeholder:text-gray-700"
                                           placeholder="Ej: AVENIDA INDUSTRIAL # 45 - 12, PISO 3" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navegación Táctica --}}
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <a href="{{ route('setup.company') }}"
                       class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-5 rounded-2xl bg-white/5 text-gray-400 font-black text-[10px] uppercase tracking-[0.2em] hover:bg-white/10 hover:text-white transition-all border border-white/5 group">
                        <i class="fas fa-chevron-left text-[8px] transition-transform group-hover:-translate-x-1"></i> 
                        Perfil de Empresa
                    </a>

                    <button type="submit"
                            class="w-full md:w-auto flex items-center justify-center gap-4 px-12 py-5 rounded-2xl bg-yellow-500 text-black font-black text-[10px] uppercase tracking-[0.2em] hover:bg-yellow-400 transition-all shadow-[0_20px_40px_-10px_rgba(234,179,8,0.3)] active:scale-95 group">
                        Siguiente Fase <i class="fas fa-chevron-right text-[8px] transition-transform group-hover:translate-x-1"></i>
                    </button>
                </div>
            </form>

            {{-- Indicadores de Pasos --}}
            <div class="mt-16 flex justify-center items-center gap-4">
                <div class="h-1 w-4 rounded-full bg-yellow-500/20 transition-all"></div>
                <div class="h-1 w-12 rounded-full bg-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.6)]"></div>
                <div class="h-1 w-4 rounded-full bg-white/10"></div>
                <div class="h-1 w-4 rounded-full bg-white/10"></div>
            </div>
        </div>
    </div>

    {{-- Script de carga dinámico mejorado --}}
    <script>
        document.getElementById('state_id').addEventListener('change', function () {
            const stateId = this.value;
            const citySelect = document.getElementById('city_id');

            // Feedback visual de carga
            citySelect.innerHTML = '<option class="bg-[#0f172a]">Sincronizando ciudades...</option>';
            citySelect.parentElement.classList.add('animate-pulse');

            fetch(`/api/cities?state_id=${stateId}`)
                .then(res => res.json())
                .then(data => {
                    citySelect.parentElement.classList.remove('animate-pulse');
                    citySelect.innerHTML = '<option value="" class="bg-[#0f172a]">Seleccionar Ciudad</option>';
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        option.className = 'bg-[#0f172a]';
                        citySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    citySelect.parentElement.classList.remove('animate-pulse');
                    citySelect.innerHTML = '<option value="" class="bg-[#0f172a]">Error de conexión</option>';
                });
        });
    </script>
</x-app-layout>