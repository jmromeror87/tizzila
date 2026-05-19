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
    {{-- Tizzilla Master Edit - Unified High-Fidelity Style --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap');
        
        :root {
            --tz-yellow: #FFCC00;
            --tz-bg: #08080A;
            --tz-card: rgba(18, 18, 22, 0.7);
        }

        /* RESET: Adiós a los botoncitos feos del input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        .tizzilla-dark { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--tz-bg);
            background-image: radial-gradient(circle at 50% -20%, #1a1a1e 0%, var(--tz-bg) 80%);
        }

        .tizzilla-font-heavy { font-weight: 900; letter-spacing: -0.02em; }

        .tizzilla-card {
            background: var(--tz-card);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.7);
            position: relative;
            overflow: hidden;
        }

        /* Línea de acento superior */
        .tizzilla-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--tz-yellow), transparent);
        }

        .tizzilla-input {
            background: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #fff !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tizzilla-input:focus {
            border-color: var(--tz-yellow) !important;
            background: rgba(255, 204, 0, 0.03) !important;
            box-shadow: 0 0 0 4px rgba(255, 204, 0, 0.1) !important;
            outline: none;
        }

        .btn-tizzilla-primary {
            background: var(--tz-yellow);
            color: #000;
            transition: all 0.3s ease;
        }

        .btn-tizzilla-primary:hover {
            filter: brightness(1.1);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 204, 0, 0.2);
        }

        .neon-text { 
            text-shadow: 0 0 15px rgba(255, 204, 0, 0.4); 
        }

        select.tizzilla-input option {
            background: #121216;
            color: white;
        }
    </style>

    <div class="tizzilla-dark min-h-screen py-12 px-4 sm:px-6 lg:px-8 text-white">
        
        {{-- Header Unificado --}}
        <div class="max-w-4xl mx-auto mb-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="h-20 w-20 bg-yellow-400 rounded-3xl flex items-center justify-center text-black shadow-[0_20px_40px_rgba(255,204,0,0.25)] -rotate-2">
                        <i class="fas fa-pen-fancy text-4xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            <p class="text-zinc-500 text-[10px] tizzilla-font-heavy uppercase tracking-[0.4em]">Master Editor ID: #{{ $tax->id }}</p>
                        </div>
                        <h2 class="text-5xl tizzilla-font-heavy text-white uppercase tracking-tighter leading-none">
                            Ajustar <span class="text-yellow-400 neon-text">Tributo</span>
                        </h2>
                    </div>
                </div>
                
                <div class="hidden md:block text-right">
                    <span class="text-[10px] text-zinc-600 font-mono uppercase tracking-widest">Core_V2.0_Sync</span>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <form action="{{ route('taxes.update', $tax->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="tizzilla-card rounded-[3rem]">
                    
                    {{-- Marca de agua --}}
                    <div class="absolute -top-10 -right-10 opacity-[0.02] pointer-events-none rotate-12">
                        <i class="fas fa-database text-[240px] text-white"></i>
                    </div>

                    <div class="p-8 md:p-14 relative z-10">
                        <div class="space-y-12">
                            
                            {{-- SECCIÓN 01: IDENTIFICACIÓN --}}
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                                <div class="md:col-span-4 space-y-4">
                                    <label class="flex items-center gap-3 text-[11px] tizzilla-font-heavy uppercase text-zinc-500 tracking-widest">
                                        <span class="w-6 h-[1px] bg-zinc-800"></span>
                                        Código DIAN
                                    </label>
                                    <input type="text" name="code" value="{{ old('code', $tax->code) }}" required 
                                        class="tizzilla-input w-full rounded-2xl p-5 text-2xl font-mono font-black text-center"
                                        placeholder="00">
                                </div>

                                <div class="md:col-span-8 space-y-4">
                                    <label class="flex items-center gap-3 text-[11px] tizzilla-font-heavy uppercase text-zinc-500 tracking-widest">
                                        <span class="w-6 h-[1px] bg-zinc-800"></span>
                                        Nombre Comercial
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $tax->name) }}" required 
                                        class="tizzilla-input w-full rounded-2xl p-5 text-xl tizzilla-font-heavy uppercase"
                                        placeholder="EJ: IVA VENTAS">
                                </div>
                            </div>

                            {{-- SECCIÓN 02: CONFIGURACIÓN TÉCNICA --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-end">
                                <div class="space-y-4">
                                    <label class="text-[11px] tizzilla-font-heavy uppercase text-zinc-500 tracking-widest">
                                        Tasa Porcentual
                                    </label>
                                    <div class="relative group">
                                        <input type="number" step="0.01" name="percentage" value="{{ old('percentage', $tax->percentage) }}" required
                                            class="tizzilla-input w-full rounded-2xl p-5 text-3xl font-mono font-black text-yellow-400">
                                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-white/10 text-2xl font-black group-hover:text-yellow-400/20 transition-colors">%</span>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="text-[11px] tizzilla-font-heavy uppercase text-zinc-500 tracking-widest">
                                        Clasificación
                                    </label>
                                    <div class="relative">
                                        <select name="type" class="tizzilla-input w-full rounded-2xl p-5 tizzilla-font-heavy uppercase text-sm appearance-none cursor-pointer">
                                            <option value="iva" {{ $tax->type == 'iva' ? 'selected' : '' }}>IVA (Bienes y Serv)</option>
                                            <option value="inc" {{ $tax->type == 'inc' ? 'selected' : '' }}>INC (Consumo)</option>
                                            <option value="retefuente" {{ $tax->type == 'retefuente' ? 'selected' : '' }}>Retefuente</option>
                                        </select>
                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="text-[11px] tizzilla-font-heavy uppercase text-zinc-500 tracking-widest">
                                        Estado Maestro
                                    </label>
                                    <div class="flex items-center justify-between p-[18px] bg-black/30 rounded-2xl border border-white/5 h-[70px]">
                                        <span class="text-[10px] tizzilla-font-heavy uppercase {{ $tax->is_active ? 'text-emerald-500' : 'text-zinc-600' }}">
                                            {{ $tax->is_active ? 'Operativo' : 'Desactivado' }}
                                        </span>
                                        <label class="relative inline-flex items-center cursor-pointer scale-110">
                                            <input type="checkbox" name="is_active" value="1" {{ $tax->is_active ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-zinc-800 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- ACCIONES --}}
                            <div class="pt-8 flex flex-col md:flex-row gap-5">
                                <button type="submit" class="flex-[2] btn-tizzilla-primary py-7 rounded-2xl tizzilla-font-heavy uppercase tracking-[0.2em] flex items-center justify-center gap-4 text-sm group">
                                    <i class="fas fa-sync-alt group-hover:rotate-180 transition-transform duration-500"></i>
                                    Confirmar Cambios
                                </button>
                                
                                <a href="{{ route('taxes.index') }}" class="flex-1 bg-zinc-900/50 hover:bg-red-500/10 text-zinc-500 hover:text-red-500 py-7 rounded-2xl tizzilla-font-heavy uppercase text-[10px] tracking-[0.3em] transition-all flex items-center justify-center gap-2 border border-white/5">
                                    <i class="fas fa-times"></i> Descartar
                                </a>
                            </div>

                        </div>
                    </div>

                    {{-- Data Footer Industrial --}}
                    <div class="bg-black/60 p-8 flex flex-col md:flex-row justify-between items-center border-t border-white/5 gap-4">
                        <div class="flex items-center gap-8">
                            <div class="flex flex-col">
                                <span class="text-[8px] tizzilla-font-heavy text-zinc-600 uppercase tracking-widest">Alta de Registro</span>
                                <span class="text-xs font-bold text-zinc-400 font-mono">{{ $tax->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="h-8 w-[1px] bg-zinc-800 hidden md:block"></div>
                            <div class="flex flex-col">
                                <span class="text-[8px] tizzilla-font-heavy text-zinc-600 uppercase tracking-widest">Última Sincronización</span>
                                <span class="text-xs font-bold text-yellow-500/80 font-mono">{{ $tax->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="opacity-30">
                            <img src="https://www.dian.gov.co/imagenes/logo-dian.png" class="h-5 grayscale brightness-200" alt="DIAN">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
