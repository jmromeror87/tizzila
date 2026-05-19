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
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="bg-yellow-500/10 p-2 rounded-lg">
                <i class="fas fa-cog text-yellow-500 text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight">
                    Configuración <span class="text-yellow-500">Global</span>
                </h2>
                <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Gestión de parámetros del sistema</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ activeTab: 'company' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                {{-- Columna izquierda (Navegación Lateral Estilizada) --}}
                <div class="md:col-span-4 lg:col-span-3">
                    <div class="bg-[#111827] border border-white/5 shadow-2xl rounded-2xl overflow-hidden sticky top-24">
                        <div class="p-4">
                            <h6 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4 px-3">
                                Menú de Ajustes
                            </h6>

                            <nav class="flex flex-col gap-2">
                                <button
                                    @click="activeTab = 'company'"
                                    :class="activeTab === 'company' ? 'bg-yellow-500 text-black shadow-lg shadow-yellow-500/20' : 'text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/5'"
                                    class="flex items-center gap-3 py-3 px-4 rounded-xl transition-all duration-200 text-left">
                                    <i class="fas fa-building w-4 text-center"></i>
                                    <span class="font-bold text-sm">Empresa</span>
                                </button>

                                <button
                                    @click="activeTab = 'address'"
                                    :class="activeTab === 'address' ? 'bg-yellow-500 text-black shadow-lg shadow-yellow-500/20' : 'text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/5'"
                                    class="flex items-center gap-3 py-3 px-4 rounded-xl transition-all duration-200 text-left">
                                    <i class="fas fa-map-marker-alt w-4 text-center"></i>
                                    <span class="font-bold text-sm">Dirección</span>
                                </button>

                                <button
                                    @click="activeTab = 'settings'"
                                    :class="activeTab === 'settings' ? 'bg-yellow-500 text-black shadow-lg shadow-yellow-500/20' : 'text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/5'"
                                    class="flex items-center gap-3 py-3 px-4 rounded-xl transition-all duration-200 text-left">
                                    <i class="fas fa-sliders-h w-4 text-center"></i>
                                    <span class="font-bold text-sm">Operación</span>
                                </button>

                                <button
                                    @click="activeTab = 'tax'"
                                    :class="activeTab === 'tax' ? 'bg-yellow-500 text-black shadow-lg shadow-yellow-500/20' : 'text-gray-400 hover:text-yellow-500 hover:bg-yellow-500/5'"
                                    class="flex items-center gap-3 py-3 px-4 rounded-xl transition-all duration-200 text-left">
                                    <i class="fas fa-receipt w-4 text-center"></i>
                                    <span class="font-bold text-sm">Tributario</span>
                                </button>
                            </nav>
                        </div>
                    </div>

                    {{-- Ayuda o Estado --}}
                    <div class="mt-4 p-4 rounded-2xl bg-yellow-500/5 border border-yellow-500/10">
                        <p class="text-[10px] text-yellow-500/70 font-bold uppercase tracking-widest mb-1">Nota de seguridad</p>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Los cambios en esta sección afectan la facturación y reportes legales.
                        </p>
                    </div>
                </div>

                {{-- Columna derecha (Contenido de los Tabs) --}}
                <div class="md:col-span-8 lg:col-span-9">

                    {{-- Panel Empresa --}}
                    <div x-show="activeTab === 'company'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="bg-[#111827] border border-white/5 shadow-2xl rounded-2xl p-6 md:p-10">
                            <div class="mb-8">
                                <h4 class="text-xl text-white font-bold mb-1">Información de la Entidad</h4>
                                <p class="text-sm text-gray-500">Datos básicos y legales de la organización.</p>
                            </div>
                            @include('global.configuration.tabs.company')
                        </div>
                    </div>

                    {{-- Panel Dirección --}}
                    <div x-show="activeTab === 'address'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="bg-[#111827] border border-white/5 shadow-2xl rounded-2xl p-6 md:p-10">
                            <div class="mb-8">
                                <h4 class="text-xl text-white font-bold mb-1">Ubicación y Contacto</h4>
                                <p class="text-sm text-gray-500">Sede principal y canales de comunicación.</p>
                            </div>
                            @include('global.configuration.tabs.address')
                        </div>
                    </div>

                    {{-- Panel Operación --}}
                    <div x-show="activeTab === 'settings'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="bg-[#111827] border border-white/5 shadow-2xl rounded-2xl p-6 md:p-10">
                            <div class="mb-8">
                                <h4 class="text-xl text-white font-bold mb-1">Parámetros Operativos</h4>
                                <p class="text-sm text-gray-500">Configuración de flujos de trabajo y orquestación.</p>
                            </div>
                            @include('global.configuration.tabs.settings')
                        </div>
                    </div>

                    {{-- Panel Tributario --}}
                    <div x-show="activeTab === 'tax'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="bg-[#111827] border border-white/5 shadow-2xl rounded-2xl p-6 md:p-10">
                            <div class="mb-8">
                                <h4 class="text-xl text-white font-bold mb-1">Configuración Fiscal</h4>
                                <p class="text-sm text-gray-500">Impuestos, resoluciones y normativa tributaria.</p>
                            </div>
                            @include('global.configuration.tabs.tax')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
