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
    <div class="py-12 bg-[#050507] min-h-screen text-zinc-400 font-sans selection:bg-yellow-500/30">
        <div class="max-w-3xl mx-auto px-6">

            {{-- NAVEGACIÓN Y TÍTULO --}}
            <div class="mb-10">
                <a href="{{ route('poultry.types.index') }}" 
                   class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-yellow-500 transition-colors mb-6 group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Volver al Panel
                </a>

                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-[2rem] bg-zinc-900 border border-white/5 flex items-center justify-center text-5xl shadow-2xl">
                        {{ $type->icon ?? '🐣' }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-yellow-500/50">Editor de Parámetros</span>
                        </div>
                        <h1 class="text-5xl font-black text-white uppercase tracking-tighter  leading-none">
                            Editar <span class="text-yellow-500">{{ $type->name }}</span>
                        </h1>
                    </div>
                </div>
            </div>

            {{-- CONTENEDOR DEL FORMULARIO --}}
            <div class="relative group">
                <div class="absolute -top-10 -right-10 w-64 h-64 bg-yellow-500/5 rounded-full blur-[80px] pointer-events-none"></div>
                
                <div class="relative bg-[#0b0b10] border border-white/10 p-10 md:p-14 rounded-[3.5rem] shadow-3xl backdrop-blur-xl overflow-hidden">
                    
                    {{-- Marca de agua técnica --}}
                    <div class="absolute top-8 right-10 text-[10px] font-mono text-white/[0.02] select-none uppercase tracking-[1em] rotate-90 origin-right">
                        Configuration_Mode_Active
                    </div>

                    {{-- Errores de Validación --}}
                    @if ($errors->any())
                        <div class="mb-10 bg-red-500/5 border border-red-500/20 p-6 rounded-[2rem]">
                            <div class="flex items-center gap-3 mb-3 text-red-500">
                                <i class="fas fa-exclamation-triangle text-xs"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest text-red-500">Errores detectados</span>
                            </div>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-xs font-bold text-zinc-500">— {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('poultry.types.update', $type) }}">
                        @include('poultry.types.form', ['type' => $type])
                    </form>
                </div>
            </div>

            {{-- FOOTER DE SEGURIDAD --}}
            <div class="mt-10 flex items-center justify-center gap-8 opacity-20 group-hover:opacity-50 transition-opacity">
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt text-[10px]"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest text-zinc-500 text-[8px]">Encripción SSL</span>
                </div>
                <div class="h-1 w-1 rounded-full bg-zinc-800"></div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-database text-[10px]"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest text-zinc-500 text-[8px]">Data Real-time</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>