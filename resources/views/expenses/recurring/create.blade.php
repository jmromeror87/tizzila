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
        <div class="flex items-center gap-4 px-4 py-2">
            <div class="relative">
                <div class="absolute -inset-1 bg-[#f3c444]/20 blur-xl rounded-full"></div>
                <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-[#f3c444] border border-[#f3c444]/50">
                    <i class="fas fa-plus-circle text-xl"></i>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                    Nuevo Gasto <span class="text-[#f3c444]">Recurrente</span>
                </h1>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1 ">Configuración de Automatización</p>
            </div>
        </div>
    </x-slot>

    <div class="p-8 max-w-5xl mx-auto">
        
        {{-- Botón de Regreso --}}
        <div class="mb-8">
            <a href="{{ route('recurring-expenses.index') }}" 
               class="inline-flex items-center gap-2 text-zinc-500 hover:text-[#f3c444] text-[10px] font-black uppercase tracking-widest transition-colors group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Volver al Historial
            </a>
        </div>

        {{-- Formulario --}}
        <form method="POST" action="{{ route('recurring-expenses.store') }}" class="relative">
            @include('expenses.recurring.form')
        </form>
        
    </div>
</x-app-layout>