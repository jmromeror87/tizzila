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
    {{-- Header con Fase Final --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div class="absolute inset-0 bg-emerald-500/20 blur-xl rounded-full animate-pulse"></div>
                    <div class="relative bg-[#161b2a] p-4 rounded-2xl border border-emerald-500/30 shadow-2xl">
                        <i class="fas fa-file-invoice-dollar text-emerald-500 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white uppercase  tracking-tighter leading-none">
                        Perfil <span class="text-emerald-500">Tributario</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] mt-2 font-black flex items-center gap-2">
                        <span class="text-emerald-500/50">Fase Final</span> 
                        <i class="fas fa-chevron-right text-[8px]"></i> 
                        Cumplimiento Fiscal DIAN
                    </p>
                </div>
            </div>

            {{-- Barra de Progreso Final (100% al completar) --}}
            <div class="w-full md:w-64 bg-[#0f172a] p-4 rounded-2xl border border-white/5">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Finalizando</span>
                    <span class="text-xs font-mono font-black text-emerald-500">95%</span>
                </div>
                <div class="relative w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.5)] transition-all duration-1000" style="width: 95%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 lg:py-20 bg-[#030712] min-h-screen relative overflow-hidden">
        {{-- Efectos de Fondo --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-6 relative z-10">
            <form method="POST" action="{{ route('global.company_tax_profiles.store') }}" class="space-y-8">
                @csrf

                <div class="bg-[#0f172a]/60 backdrop-blur-xl border border-white/5 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.6)] rounded-[3rem] overflow-hidden">
                    
                    <div class="p-8 md:p-12">
                        <div class="flex items-center gap-6 mb-12">
                            <div class="h-14 w-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                                <i class="fas fa-gavel text-emerald-500 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-white uppercase  tracking-tight">Estructura Legal y Fiscal</h3>
                                <p class="text-gray-500 text-sm ">Configure los parámetros requeridos para la emisión legal de documentos.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-10">

                            {{-- Régimen Fiscal --}}
                            <div class="space-y-3 group">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1 block transition-colors group-focus-within:text-emerald-500">Régimen Fiscal</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-emerald-500 transition-colors">
                                        <i class="fas fa-landmark text-xs"></i>
                                    </div>
                                    <select name="tax_regime" required
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/5 transition-all appearance-none cursor-pointer">
                                        <option value="" class="bg-[#0f172a]">Seleccione Régimen</option>
                                        <option value="Responsable de IVA" @selected(($profile?->tax_regime) === 'Responsable de IVA') class="bg-[#0f172a]">Responsable de IVA</option>
                                        <option value="No responsable de IVA" @selected(($profile?->tax_regime) === 'No responsable de IVA') class="bg-[#0f172a]">No responsable de IVA</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-700">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Responsabilidades DIAN --}}
                            <div class="space-y-3 group">
                                <label class="flex justify-between px-1">
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Responsabilidades DIAN</span>
                                    <span class="text-[8px] text-emerald-500/70 font-bold uppercase tracking-widest">Separe con comas</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-emerald-500 transition-colors">
                                        <i class="fas fa-fingerprint text-xs"></i>
                                    </div>
                                    <input type="text" name="responsibility_codes"
                                        value="{{ $profile?->responsibility_codes ? implode(',', $profile->responsibility_codes) : '' }}"
                                        placeholder="O-13, O-23, R-99-PN"
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/5 transition-all">
                                </div>
                            </div>

                            {{-- Resolución --}}
                            <div class="space-y-3 group">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1 block transition-colors group-focus-within:text-emerald-500">Nº de Resolución</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-emerald-500 transition-colors">
                                        <i class="fas fa-file-signature text-xs"></i>
                                    </div>
                                    <input type="text" name="dian_resolution" value="{{ $profile?->dian_resolution }}"
                                        placeholder="Ej: 18760000001"
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/5 transition-all">
                                </div>
                            </div>

                            {{-- Fecha --}}
                            <div class="space-y-3 group">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1 block transition-colors group-focus-within:text-emerald-500">Fecha de Resolución</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-600 group-focus-within:text-emerald-500 transition-colors">
                                        <i class="fas fa-calendar-alt text-xs"></i>
                                    </div>
                                    <input type="date" name="resolution_date" value="{{ $profile?->resolution_date }}"
                                        class="w-full bg-[#070a13]/50 border-white/10 text-white text-sm rounded-2xl py-4 pl-12 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/5 transition-all">
                                </div>
                            </div>

                            {{-- Panel de Numeración --}}
                            <div class="md:col-span-2 bg-black/30 rounded-[2rem] border border-white/5 p-8 relative overflow-hidden group">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 blur-3xl rounded-full"></div>
                                
                                <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    Control de Secuenciales de Facturación
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-bold text-gray-600 uppercase tracking-widest px-1">Prefijo</label>
                                        <input type="text" name="prefix" value="{{ $profile?->prefix }}" placeholder="SETT"
                                            class="w-full bg-[#030712] border-white/5 text-emerald-500 font-mono text-sm rounded-xl py-3 px-4 focus:border-emerald-500/30 transition-all">
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-bold text-gray-600 uppercase tracking-widest px-1">Desde (Inicio)</label>
                                        <input type="number" name="from_number" value="{{ $profile?->from_number }}" placeholder="1"
                                            class="w-full bg-[#030712] border-white/5 text-white font-mono text-sm rounded-xl py-3 px-4 focus:border-emerald-500/30 transition-all">
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-bold text-gray-600 uppercase tracking-widest px-1">Hasta (Límite)</label>
                                        <input type="number" name="to_number" value="{{ $profile?->to_number }}" placeholder="5000"
                                            class="w-full bg-[#030712] border-white/5 text-white font-mono text-sm rounded-xl py-3 px-4 focus:border-emerald-500/30 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navegación Final --}}
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <a href="{{ route('setup.settings') }}"
                       class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-5 rounded-2xl bg-white/5 text-gray-400 font-black text-[10px] uppercase tracking-[0.2em] hover:bg-white/10 hover:text-white transition-all border border-white/5 group">
                        <i class="fas fa-chevron-left text-[8px] transition-transform group-hover:-translate-x-1"></i> 
                        Parámetros Core
                    </a>

                    <button type="submit"
                            class="w-full md:w-auto flex items-center justify-center gap-4 px-14 py-5 rounded-2xl bg-emerald-500 text-black font-black text-[10px] uppercase tracking-[0.2em] hover:bg-emerald-400 transition-all shadow-[0_20px_40px_-10px_rgba(16,185,129,0.4)] active:scale-95 group">
                        Desplegar Sistema <i class="fas fa-rocket text-[10px] transition-transform group-hover:-translate-y-1 group-hover:translate-x-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>