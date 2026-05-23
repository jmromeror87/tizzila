{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <i class="fas fa-file-invoice-dollar text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Perfil <span class="text-yellow-500">Tributario</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1 flex items-center gap-2">
                        <span class="text-emerald-400/60">Fase Final</span>
                        <i class="fas fa-chevron-right text-[7px]"></i>
                        Cumplimiento Fiscal DIAN
                    </p>
                </div>
            </div>
            <div class="w-56 bg-black/40 border border-white/5 rounded-xl p-3">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Finalizando</span>
                    <span class="text-[10px] font-black text-emerald-400 font-mono">95%</span>
                </div>
                <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: 95%"></div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto space-y-5">
            <form method="POST" action="{{ route('global.company_tax_profiles.store') }}" class="space-y-5">
                @csrf

                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                            <i class="fas fa-gavel text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white uppercase tracking-tight">Estructura Legal y Fiscal</h3>
                            <p class="text-[9px] text-zinc-500 uppercase tracking-widest">Configure los parámetros requeridos para la emisión legal de documentos.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Régimen Fiscal</label>
                            <div class="relative">
                                <i class="fas fa-landmark absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <select name="tax_regime" required
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all appearance-none">
                                    <option value="">Seleccione Régimen</option>
                                    <option value="Responsable de IVA" @selected(($profile?->tax_regime) === 'Responsable de IVA')>Responsable de IVA</option>
                                    <option value="No responsable de IVA" @selected(($profile?->tax_regime) === 'No responsable de IVA')>No responsable de IVA</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Responsabilidades DIAN</label>
                                <span class="text-[8px] font-black text-emerald-400/70 uppercase tracking-widest">Separe con comas</span>
                            </div>
                            <div class="relative">
                                <i class="fas fa-fingerprint absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <input type="text" name="responsibility_codes"
                                    value="{{ $profile?->responsibility_codes ? implode(',', $profile->responsibility_codes) : '' }}"
                                    placeholder="O-13, O-23, R-99-PN"
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all placeholder:text-zinc-700">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Nº de Resolución</label>
                            <div class="relative">
                                <i class="fas fa-file-signature absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <input type="text" name="dian_resolution" value="{{ $profile?->dian_resolution }}"
                                    placeholder="Ej: 18760000001"
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all placeholder:text-zinc-700">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Fecha de Resolución</label>
                            <div class="relative">
                                <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                                <input type="date" name="resolution_date" value="{{ $profile?->resolution_date }}"
                                    class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-10 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                            </div>
                        </div>

                        {{-- Secuenciales --}}
                        <div class="md:col-span-2 bg-black/30 border border-white/5 rounded-xl p-5">
                            <h4 class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.3em] mb-4 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                Control de Secuenciales de Facturación
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1.5">Prefijo</label>
                                    <input type="text" name="prefix" value="{{ $profile?->prefix }}" placeholder="DS"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl text-emerald-400 font-mono text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all placeholder:text-zinc-700">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1.5">Desde (Inicio)</label>
                                    <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" name="from_number" value="{{ $profile?->from_number }}" placeholder="1"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white font-mono text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all placeholder:text-zinc-700">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1.5">Hasta (Límite)</label>
                                    <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" name="to_number" value="{{ $profile?->to_number }}" placeholder="5000"
                                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white font-mono text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all placeholder:text-zinc-700">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('setup.settings') }}"
                       class="flex items-center gap-2 px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-zinc-400 hover:text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-all">
                        <i class="fas fa-chevron-left text-[8px]"></i> Parámetros Core
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-8 py-3 bg-emerald-500 hover:bg-emerald-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all shadow-[0_8px_20px_rgba(16,185,129,0.2)]">
                        Desplegar Sistema <i class="fas fa-rocket text-xs"></i>
                    </button>
                </div>
            </form>

            <div class="flex justify-center items-center gap-3">
                <div class="h-1 w-4 rounded-full bg-yellow-500/30"></div>
                <div class="h-1 w-4 rounded-full bg-yellow-500/30"></div>
                <div class="h-1 w-4 rounded-full bg-yellow-500/30"></div>
                <div class="h-1 w-10 rounded-full bg-emerald-500"></div>
            </div>
        </div>
    </div>
</x-app-layout>
