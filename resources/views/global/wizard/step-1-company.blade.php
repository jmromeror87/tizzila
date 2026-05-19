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
    {{-- Header con Progreso --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div class="absolute inset-0 bg-yellow-500/20 blur-xl rounded-full animate-pulse"></div>
                    <div class="relative bg-[#161b2a] p-4 rounded-2xl border border-yellow-500/30 shadow-2xl">
                        <i class="fas fa-rocket text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white uppercase  tracking-tighter leading-none">
                        Configuración <span class="text-yellow-500">Inicial</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] mt-2 font-black flex items-center gap-2">
                        <span class="text-yellow-500/50">Fase 01</span> 
                        <i class="fas fa-chevron-right text-[8px]"></i> 
                        Identidad Corporativa
                    </p>
                </div>
            </div>

            <div class="w-full md:w-64 bg-[#0f172a] p-4 rounded-2xl border border-white/5">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Progreso</span>
                    <span class="text-xs font-mono font-black text-yellow-500">25%</span>
                </div>
                <div class="relative w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="absolute inset-y-0 left-0 bg-yellow-500 rounded-full shadow-[0_0_15px_rgba(234,179,8,0.5)] transition-all duration-1000" style="width: 25%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 lg:py-20 bg-[#030712] min-h-screen relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 relative z-10">

            <div class="bg-[#0f172a]/60 backdrop-blur-xl border border-white/5 shadow-2xl rounded-[3rem] overflow-hidden">
                
                <form method="POST" action="{{ route('setup.company.store') }}" enctype="multipart/form-data" class="p-8 md:p-12 space-y-12">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                        
                        {{-- COLUMNA IZQUIERDA: UPLOADER DE LOGO --}}
                        <div class="lg:col-span-4 flex flex-col items-center justify-start space-y-6">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] self-start px-2">Identificador Visual</label>
                            
                            <div class="relative group w-full aspect-square max-w-[280px]">
                                {{-- Contenedor de Drop/Preview --}}
                                <div id="drop-zone" class="relative h-full w-full rounded-[2.5rem] border-2 border-dashed border-white/10 bg-[#070a13]/50 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-yellow-500/40 group-hover:bg-[#070a13]">
                                    
                                    {{-- Preview Image --}}
                                    <img id="logo-preview" src="{{ $company?->logo_url ?? '#' }}" 
                                         class="{{ $company?->logo_url ? '' : 'hidden' }} absolute inset-0 w-full h-full object-contain p-8 z-10">

                                    {{-- Placeholder --}}
                                    <div id="placeholder-content" class="{{ $company?->logo_url ? 'hidden' : '' }} flex flex-col items-center text-center p-6">
                                        <div class="h-16 w-16 mb-4 rounded-2xl bg-white/5 flex items-center justify-center text-gray-600 group-hover:text-yellow-500 transition-colors">
                                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                        </div>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-relaxed">
                                            Arrastra el logo <br><span class="text-gray-700">o haz clic para buscar</span>
                                        </p>
                                    </div>

                                    {{-- Input real oculto --}}
                                    <input type="file" name="logo" id="logo-input" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*">
                                </div>

                                {{-- Botón Flotante de Edición --}}
                                <div class="absolute -bottom-3 -right-3 h-12 w-12 bg-yellow-500 rounded-2xl flex items-center justify-center text-black shadow-xl border-4 border-[#0f172a] z-30 pointer-events-none group-hover:scale-110 transition-transform">
                                    <i class="fas fa-camera text-sm"></i>
                                </div>
                            </div>
                            
                            <p class="text-[9px] text-gray-600 font-bold uppercase tracking-tighter text-center">
                                Recomendado: PNG o SVG <br> Fondo transparente (500x500px)
                            </p>
                        </div>

                        {{-- COLUMNA DERECHA: CAMPOS DE TEXTO --}}
                        <div class="lg:col-span-8 space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Razón Social --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1">Razón Social</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-yellow-500">
                                            <i class="fas fa-certificate text-xs"></i>
                                        </div>
                                        <input type="text" name="legal_name" value="{{ $company?->legal_name }}" class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all">
                                    </div>
                                </div>

                                {{-- Nombre Comercial --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1">Nombre Comercial</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-yellow-500">
                                            <i class="fas fa-tag text-xs"></i>
                                        </div>
                                        <input type="text" name="trade_name" value="{{ $company?->trade_name }}" class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all">
                                    </div>
                                </div>

                                {{-- E-mail --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1">Correo Corporativo</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-yellow-500">
                                            <i class="fas fa-at text-xs"></i>
                                        </div>
                                        <input type="email" name="email" value="{{ $company?->email }}" class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all">
                                    </div>
                                </div>

                                {{-- Teléfono --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1">Teléfono</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-yellow-500">
                                            <i class="fas fa-phone-alt text-xs"></i>
                                        </div>
                                        <input type="text" name="phone" value="{{ $company?->phone }}" class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-yellow-500/50 focus:ring-4 focus:ring-yellow-500/5 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer con Botón --}}
                    <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-yellow-500/10 flex items-center justify-center text-yellow-500 border border-yellow-500/20">
                                <i class="fas fa-shield-alt text-xs"></i>
                            </div>
                            <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold">Datos encriptados bajo protocolos <br>de seguridad industrial.</p>
                        </div>

                        <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-4 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-xs uppercase tracking-[0.2em] px-12 py-5 rounded-2xl transition-all shadow-xl active:scale-95 group">
                            Continuar al Paso 2
                            <i class="fas fa-arrow-right text-[10px] transition-transform group-hover:translate-x-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script de Preview --}}
    <script>
        const logoInput = document.getElementById('logo-input');
        const logoPreview = document.getElementById('logo-preview');
        const placeholder = document.getElementById('placeholder-content');
        const dropZone = document.getElementById('drop-zone');

        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoPreview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    dropZone.style.borderColor = 'rgba(234, 179, 8, 0.4)';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>