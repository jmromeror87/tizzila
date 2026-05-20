{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-map-marked-alt text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Nodo de <span class="text-yellow-500">Operaciones</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1 flex items-center gap-2">
                        <span class="text-yellow-500/60">Fase 02</span>
                        <i class="fas fa-chevron-right text-[7px]"></i>
                        Geolocalización Logística
                    </p>
                </div>
            </div>
            <div class="w-56 bg-black/40 border border-white/5 rounded-xl p-3">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Sincronización</span>
                    <span class="text-[10px] font-black text-yellow-500 font-mono">50%</span>
                </div>
                <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 rounded-full" style="width: 50%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto space-y-5">
            <form method="POST" action="{{ route('global.company_addresses.store') }}" class="space-y-5">
                @csrf

                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                            <i class="fas fa-location-arrow text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white uppercase tracking-tight">Dirección de Matriz</h3>
                            <p class="text-[9px] text-zinc-500 uppercase tracking-widest">Defina el punto origen para cálculos logísticos y facturación.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Departamento / Estado</label>
                            <div class="relative">
                                <i class="fas fa-map absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <select id="state_id" name="state_id" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all appearance-none">
                                    <option value="">Seleccionar Región</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}" @selected(optional($company->mainAddress?->city?->state)->id === $state->id)>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Ciudad / Municipio</label>
                            <div class="relative">
                                <i class="fas fa-city absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <select id="city_id" name="city_id" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all appearance-none">
                                    @if ($company?->mainAddress?->city)
                                        <option value="{{ $company->mainAddress->city->id }}" selected>{{ $company->mainAddress->city->name }}</option>
                                    @else
                                        <option value="">Esperando región...</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Nomenclatura Completa</label>
                            <div class="relative">
                                <i class="fas fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <input type="text" name="address" value="{{ $company?->mainAddress?->address }}"
                                    placeholder="Ej: AVENIDA INDUSTRIAL # 45 - 12, PISO 3" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all placeholder:text-zinc-700">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('setup.company') }}"
                       class="flex items-center gap-2 px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-zinc-400 hover:text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-all">
                        <i class="fas fa-chevron-left text-[8px]"></i> Perfil de Empresa
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-8 py-3 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                        Siguiente Fase <i class="fas fa-chevron-right text-[8px]"></i>
                    </button>
                </div>
            </form>

            <div class="flex justify-center items-center gap-3">
                <div class="h-1 w-4 rounded-full bg-yellow-500/30"></div>
                <div class="h-1 w-10 rounded-full bg-yellow-500"></div>
                <div class="h-1 w-4 rounded-full bg-white/10"></div>
                <div class="h-1 w-4 rounded-full bg-white/10"></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('state_id').addEventListener('change', function () {
            const stateId = this.value;
            const citySelect = document.getElementById('city_id');
            citySelect.innerHTML = '<option>Sincronizando ciudades...</option>';
            fetch(`/api/cities?state_id=${stateId}`)
                .then(res => res.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Seleccionar Ciudad</option>';
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                })
                .catch(() => {
                    citySelect.innerHTML = '<option value="">Error de conexión</option>';
                });
        });
    </script>
</x-app-layout>
