{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-rocket text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Configuración <span class="text-yellow-500">Inicial</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1 flex items-center gap-2">
                        <span class="text-yellow-500/60">Fase 01</span>
                        <i class="fas fa-chevron-right text-[7px]"></i>
                        Identidad Corporativa
                    </p>
                </div>
            </div>
            <div class="w-56 bg-black/40 border border-white/5 rounded-xl p-3">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Progreso</span>
                    <span class="text-[10px] font-black text-yellow-500 font-mono">25%</span>
                </div>
                <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 rounded-full" style="width: 25%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <form method="POST" action="{{ route('setup.company.store') }}" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                        {{-- LOGO --}}
                        <div class="lg:col-span-4 flex flex-col items-center space-y-4">
                            <label class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] self-start">Identificador Visual</label>
                            <div class="relative group w-full aspect-square max-w-[240px]">
                                <div id="drop-zone" class="relative h-full w-full rounded-2xl border-2 border-dashed border-white/10 bg-black/30 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-yellow-500/30">
                                    <img id="logo-preview" src="{{ $company?->logo_url ?? '#' }}"
                                         class="{{ $company?->logo_url ? '' : 'hidden' }} absolute inset-0 w-full h-full object-contain p-6 z-10">
                                    <div id="placeholder-content" class="{{ $company?->logo_url ? 'hidden' : '' }} flex flex-col items-center text-center p-4">
                                        <div class="h-14 w-14 mb-3 rounded-xl bg-white/5 flex items-center justify-center text-zinc-600 group-hover:text-yellow-500 transition-colors">
                                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                                        </div>
                                        <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest leading-relaxed">
                                            Arrastra el logo<br><span class="text-zinc-700">o haz clic para buscar</span>
                                        </p>
                                    </div>
                                    <input type="file" name="logo" id="logo-input" class="absolute inset-0 opacity-0 cursor-pointer z-20" accept="image/*">
                                </div>
                                <div class="absolute -bottom-2 -right-2 h-10 w-10 bg-yellow-500 rounded-xl flex items-center justify-center text-black shadow-lg border-4 border-[#0d121f] z-30 pointer-events-none">
                                    <i class="fas fa-camera text-xs"></i>
                                </div>
                            </div>
                            <p class="text-[9px] text-zinc-600 font-bold uppercase tracking-tighter text-center">
                                Recomendado: PNG o SVG<br>Fondo transparente (500x500px)
                            </p>
                        </div>

                        {{-- CAMPOS --}}
                        <div class="lg:col-span-8 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Razón Social</label>
                                    <div class="relative">
                                        <i class="fas fa-certificate absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                        <input type="text" name="legal_name" value="{{ $company?->legal_name }}"
                                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Nombre Comercial</label>
                                    <div class="relative">
                                        <i class="fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                        <input type="text" name="trade_name" value="{{ $company?->trade_name }}"
                                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Correo Corporativo</label>
                                    <div class="relative">
                                        <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                        <input type="email" name="email" value="{{ $company?->email }}"
                                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Teléfono</label>
                                    <div class="relative">
                                        <i class="fas fa-phone-alt absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                        <input type="text" name="phone" value="{{ $company?->phone }}"
                                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-sm font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER FORM --}}
                    <div class="pt-6 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-5">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                                <i class="fas fa-shield-alt text-xs"></i>
                            </div>
                            <p class="text-[9px] text-zinc-500 uppercase tracking-widest font-bold">Datos encriptados bajo protocolos<br>de seguridad industrial.</p>
                        </div>
                        <button type="submit"
                            class="w-full md:w-auto bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest px-8 py-3.5 rounded-xl transition-all flex items-center justify-center gap-3 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                            Continuar al Paso 2 <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                    dropZone.style.borderColor = 'rgba(234,179,8,0.4)';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
